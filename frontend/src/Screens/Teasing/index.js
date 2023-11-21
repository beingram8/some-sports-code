import { isMacOs, isSafari } from "react-device-detect";
import React, { useState, useEffect } from "react";
import { useSelector } from "react-redux";
import FlatList from "flatlist-react";
import Lottie from "react-lottie";
import $ from "jquery";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import { Setting } from "../../Utils/Setting";
import Header from "../../Components/Header/index";
import CAlert from "../../Components/CAlert/index";
import Protected from "../../Components/Protected";
import ReportModal from "../../Modals/ReportModal";
import { TesingFilterTab } from "../../staticData";
import CRequestLoader from "../../Loaders/CRequestLoader/index";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";
import { getApiData, getAPIProgressData } from "../../Utils/APIHelper";
import CTeasingRoomPost from "../../Components/CTeasingRoomPost/index";
import loadingindicator from "../../Assets/Lottie/LoadingIndicator3.json";
import trumpet from "../../Assets/Images/tifaRed.png";

const Teasing = () => {
  const [postData, setPostData] = useState([]);
  const [isLastPost, setIsLastPost] = useState(false);
  const { userdata } = useSelector((state) => state.auth);
  const [loader, setLoader] = useState(true);
  const [defaultTab, setDefaultTab] = useState(TesingFilterTab[0]);
  const [reportitem, setReportItem] = useState({});
  const [reportmodal, setReportModal] = useState(false);
  const [report, setReport] = useState("");
  const [defaultItem, setDEfaultItem] = useState({});
  const [dialogopen, setDialogOpen] = useState(false);
  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  useEffect(() => {
    getPostListData();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [defaultTab]);

  useEffect(() => {
    document.title = Setting.page_name.TEASING_ROOM;
  }, []);

  const showAlert = (open, title, message) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
  };

  function renderAlert() {
    return (
      <CAlert
        open={alertOpen}
        onClose={() => {
          setAlertOpen(false);
        }}
        onOkay={() => {
          setAlertOpen(false);
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  // Get Post List Data //
  async function getPostListData(bool = true) {
    const cPage =
      postData && postData.pagination && postData.pagination.currentPage
        ? _.toNumber(postData.pagination.currentPage)
        : 0;

    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: userToken,
    };

    let PageNo = 0;
    if (bool === true) {
      PageNo = 1;
    } else {
      PageNo = cPage + 1;
    }

    try {
      let endPoint = `${Setting.endpoints.teasing_post_list}?page=${PageNo}`;
      let eventKeyValue = "Teasing_Room_Post_List_Event";

      if (defaultTab.id === 2) {
        endPoint = `${Setting.endpoints.teasing_post_list}?page=${PageNo}&my_post=1`;
        eventKeyValue = "Teasing_Room_User_Own_Post_List_Event";
      }

      const response = await getApiData(endPoint, "GET", null, header);
      addAnalyticsEvent(eventKeyValue, true);
      if (response?.status) {
        setLoader(false);
        const obj = bool ? {} : _.cloneDeep(postData);
        const postListData =
          response && response.data && response.data.rows
            ? response.data.rows
            : [];
        const paginationDatas =
          response && response.data && response.data.pagination
            ? response.data.pagination
            : {};

        if (_.isArray(postListData)) {
          if (_.isArray(obj.data) && obj.data.length > 0) {
            obj.data = _.flattenDeep([...obj.data, postListData]);
          } else {
            obj.data = postListData;
          }
          obj.pagination = paginationDatas;
        }
        setPostData(obj);
        setIsLastPost(false);
      } else {
        showAlert(true, getWords("OOPS"), response?.message);
        setLoader(false);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setLoader(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  // Post Report Process //
  async function postReport() {
    const roomId = reportitem?.id;
    try {
      let endPoint = `${Setting.endpoints.teasing_report_post}?id=${roomId}`;

      const data = {
        "TeasingRoomReported[reason]": report,
        "TeasingRoomReported[teasing_id]": roomId,
      };
      const response = await getAPIProgressData(endPoint, "POST", data, true);
      const eventData = {
        user_name: userdata?.username,
        first_name: userdata?.firstname,
        last_name: userdata?.lastname,
        email: userdata?.email,
        user_Pic: userdata?.user_image,
        report_post_id: roomId,
        report_post_reason: report,
      };
      addAnalyticsEvent("Teasing_Room_Post_Report_Event", eventData);
      if (response && response.status && response.status === true) {
        showAlert(true, getWords("SUCCESS"), response?.message);
      } else {
        console.log("Status false");
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  // Get More List Data (Pagination Process) //
  function fetchOtherData() {
    const totalPages =
      postData && postData.pagination && postData.pagination.totalPage
        ? _.toNumber(postData.pagination.totalPage)
        : 0;

    const currentPage =
      postData && postData.pagination && postData.pagination.currentPage
        ? _.toNumber(postData.pagination.currentPage)
        : 0;

    if (postData.pagination.isMore === true && currentPage < totalPages) {
      getPostListData(false);
    }
  }

  // Start Check Scroll react at on End //
  $(document).ready(function () {
    $("#postListID").on("scroll", chk_scroll);
  });

  function chk_scroll(e) {
    var elem = $(e.currentTarget);
    if (
      parseInt(elem[0].scrollHeight) - parseInt(elem.scrollTop()) ===
      parseInt(elem.outerHeight())
    ) {
      setIsLastPost(true);
    }
  }
  // End Check Scroll react at on End //

  // check for more data
  function renderIsMoreLoader() {
    const isMore = postData?.pagination?.isMore && isLastPost;
    return (
      <div className="notifDiv1">
        {isMore ? (
          <div className="notifDiv3">
            <Lottie
              options={{
                loop: true,
                autoplay: true,
                animationData: loadingindicator,
              }}
              height={window.innerWidth >= 600 ? 70 : 50}
              width={"100%"}
            />
          </div>
        ) : null}
      </div>
    );
  }

  // close report modal
  function handleClose() {
    setReportModal(false);
    setReport("");
  }

  function renderListItem(item, index) {
    return (
      <CTeasingRoomPost
        key={index}
        data={item}
        defaultTab={defaultTab}
        openReportModal={() => {
          setReportModal(true);
          setReportItem(item);
        }}
        onRefreshData={() => {
          getPostListData(true);
        }}
        defaultItem={defaultItem}
        dialogopen={dialogopen}
        dialogAction={() => {
          setDEfaultItem(item);
          setDialogOpen(!dialogopen);
        }}
      />
    );
  }

  // display teasing post list
  function renderPostList() {
    const isMore = postData?.pagination?.isMore && isLastPost;
    if (_.isArray(postData.data) && !_.isEmpty(postData.data)) {
      return (
        <FlatList
          list={postData?.data}
          renderItem={renderListItem}
          hasMoreItems={isMore}
          loadMoreItems={() => fetchOtherData()}
        />
      );
    } else {
      return (
        <div className="comment_empty">
          <img
            src={trumpet}
            alt={"post"}
            style={{
              width: 100,
              height: 100,
              marginBottom: 30,
            }}
          />
          <span>{getWords("FIRST_POST")}</span>
        </div>
      );
    }
  }

  // display tabs
  const renderTabs = () => {
    return (
      <div className="tabstyleNew tabmain">
        {TesingFilterTab.map((obj, index) => {
          return (
            <div
              key={index}
              className="tabbuttonstyleNew"
              style={{
                borderBottom: `2px solid ${obj.id === defaultTab.id ? "#ED0F1B" : "rgb(202 198 198)"
                  }`,
                color: `${obj.id === defaultTab.id ? "#222" : "#555"}`,
                paddingBottom:
                  isMacOs && isSafari && defaultTab.id === 1
                    ? 15
                    : isMacOs && isSafari && defaultTab.id === 2
                      ? 0
                      : 0,
              }}
              onClick={() => {
                setLoader(true);
                setDialogOpen(false);
                setDefaultTab(obj);
              }}
            >
              <span className="tabbuttontextNew tabview">{getWords(obj.title)}</span>
            </div>
          );
        })}
      </div>
    );
  };

  // report validation
  function reportvalidation() {
    if (_.isEmpty(report)) {
      showAlert(true, getWords("OOPS"), getWords("ENTER_REPORT"));
    } else {
      setReport("");
      postReport();
      setReportModal(false);
    }
  }

  // display report modal
  function renderreportmodal() {
    return (
      <ReportModal
        openDialog={reportmodal}
        handleClose={() => handleClose()}
        inputData={(e) => setReport(e.target.value)}
        inputValue={report}
        handleBtn={() => reportvalidation()}
      />
    );
  }

  if (loader) {
    return (
      <Protected>
        <div className="MainContainer">
          <Header isSubScreen={true} addIcon={true} />
          <div className="CommonContainer notifDiv">
            <div className="addpostmain">
              <span className="Headingmain">
                {getWords("TEASING_ROOM_TITLE")}
              </span>
            </div>
            <div>{renderTabs()}</div>
          </div>
        </div>
        <CRequestLoader
          openModal={loader}
          handleClose={() => {
            setLoader(false);
          }}
        />
      </Protected>
    );
  }

  return (
    <Protected>
      <div className="MainContainer">
        <Header isSubScreen={true} addIcon={true} />
        <div className="CommonContainer notifDiv">
          <div className="addpostmain">
            <span className="Headingmain">
              {getWords("TEASING_ROOM_TITLE")}
            </span>
          </div>
          <div>{renderTabs()}</div>
          <div className="postscroll" id="postListID">
            <div className="notifymaindiv">
              <div className="notifymaindivsub">
                <div className="notificationscontainer">{renderPostList()}</div>
                {renderIsMoreLoader()}
              </div>
            </div>
          </div>
        </div>
      </div>

      {renderAlert()}
      {renderreportmodal()}
    </Protected>
  );
};

export default Teasing;
