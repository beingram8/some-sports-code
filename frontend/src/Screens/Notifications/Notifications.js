import { useDispatch, useSelector } from "react-redux";
import React, { useState, useEffect } from "react";
import { useHistory } from "react-router-dom";
import FlatList from "flatlist-react";
import Lottie from "react-lottie";
import $ from "jquery";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import Header from "../../Components/Header";
import { Setting } from "../../Utils/Setting";
import FeedAd from "../../Components/Ads/FeedAd";
import Protected from "../../Components/Protected";
import { getApiData } from "../../Utils/APIHelper";
import CAlert from "../../Components/CAlert/index";
import CNoData from "../../Components/CNoData/index";
import authActions from "../../Redux/reducers/auth/actions";
import CNotificationLoader from "../../Loaders/CNotificationLoader";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";
import loadingindicator from "../../Assets/Lottie/LoadingIndicator3.json";
import AppLogo from "../../Assets/Images/IMG_1136.webp";
const { setBadgeCount } = authActions;

function Notifications() {
  const history = useHistory();
  const dispatch = useDispatch();
  const { userdata } = useSelector((state) => state.auth);
  const [notificationList, setNotificationList] = useState({});
  const [loader, setLoader] = useState(true);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [callFunc, setCallFunction] = useState(false);
  const [isLastNotification, setIsLastNotification] = useState(true);

  useEffect(() => {
    dispatch(setBadgeCount(0));
    getNotificationData();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    document.title = Setting.page_name.NOTIFICATION;
  }, []);

  const showAlert = (open, title, message, callFunction) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
    setCallFunction(callFunction);
  };

  function renderAlert() {
    return (
      <CAlert
        open={alertOpen}
        showCancel={callFunc}
        onClose={() => {
          setAlertOpen(false);
          setCallFunction(false);
        }}
        onOkay={() => {
          setAlertOpen(false);
          if (callFunc) {
            removeAllNoti();
          }
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  async function getNotificationData(bool = true) {
    const cPage =
      notificationList &&
        notificationList.pagination &&
        notificationList.pagination.currentPage
        ? _.toNumber(notificationList.pagination.currentPage)
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
      let endPoint = `${Setting.endpoints.notification_list}?page=${PageNo}`;
      const response = await getApiData(endPoint, "GET", null, header);
      if (bool === true) {
        addAnalyticsEvent("Notification_Event", true);
      }
      if (response && response.status && response.status === true) {
        const obj = bool ? {} : _.cloneDeep(notificationList);

        const notiListData =
          response && response.data && response.data.rows
            ? response.data.rows
            : [];
        const paginationDatas =
          response && response.data && response.data.pagination
            ? response.data.pagination
            : {};

        if (_.isArray(notiListData)) {
          if (_.isArray(obj.data) && obj.data.length > 0) {
            obj.data = _.flattenDeep([...obj.data, notiListData]);
          } else {
            obj.data = notiListData;
          }
          obj.pagination = paginationDatas;
        }
        setNotificationList(obj);
        setIsLastNotification(false);
        setLoader(false);
      } else {
        setLoader(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setLoader(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  async function removeAllNoti() {
    setLoader(true);
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: userToken,
    };

    try {
      let endPoint = Setting.endpoints.remove_all_noti;
      const response = await getApiData(endPoint, "GET", null, header);
      addAnalyticsEvent("Remove_All_Notifications_Event", true);
      if (response && response.status && response.status === true) {
        setNotificationList({});
        setLoader(false);
        setCallFunction(false);
      } else {
        setLoader(false);
        setCallFunction(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setLoader(false);
      setCallFunction(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  function fetchOtherData() {
    const totalPages =
      notificationList &&
        notificationList.pagination &&
        notificationList.pagination.totalPage
        ? _.toNumber(notificationList.pagination.totalPage)
        : 0;

    const currentPage =
      notificationList &&
        notificationList.pagination &&
        notificationList.pagination.currentPage
        ? _.toNumber(notificationList.pagination.currentPage)
        : 0;

    if (
      notificationList.pagination.isMore === true &&
      currentPage < totalPages
    ) {
      getNotificationData(false);
    }
  }

  $(document).ready(function () {
    $("#notificationListID").on("scroll", chk_scroll);
  });

  function chk_scroll(e) {
    var elem = $(e.currentTarget);
    if (
      parseInt(elem[0].scrollHeight) - parseInt(elem.scrollTop()) ===
      parseInt(elem.outerHeight())
    ) {
      setIsLastNotification(true);
    }
  }

  function redirectScreen(item) {
    const screenData = item?.data;
    const screenName = screenData?.type;

    if (_.isObject(screenData) && !_.isEmpty(screenData)) {
      if (_.toUpper(screenName) === "NEWS") {
        history.push({
          pathname: "/news",
          search: `?news_id=${screenData?.news_id}&slug=${screenData?.slug}`,
          state: {
            newsId: screenData?.news_id,
            slug: screenData?.slug,
          },
        });
      } else if (
        _.toUpper(screenName) === "STREAMING" ||
        _.toUpper(screenName) === "VIDEO"
      ) {
        history.push("/tifa");
      } else if (_.toUpper(screenName) === "WINNER") {
        history.push("/winner");
      } else if (
        _.toUpper(screenName) === "VOTE_OPEN" ||
        _.toUpper(screenName) === "survey_online"
      ) {
        history.push("/rate");
      }
    } else {
      return null;
    }
  }

  function renderListItem(item, index) {
    const notificationDate = item?.format_date;
    const notificationTime = item?.format_time;

    return (
      <div
        key={index}
        className="arraydatastyle"
        onClick={() => {
          redirectScreen(item);
        }}
      >
        <div className="notifDiv12">
          <img
            loading="lazy"
            // src={item?.image}
            src={AppLogo}
            className="notifImageStyle"
            alt={"notification"}
          />
        </div>

        <div className="notifTitleMessageDiv">
          <span className="itemtitlestyle">{item?.title}</span>
          <span className="messageNotifyStyle">{item?.message}</span>
        </div>

        <div className="notifDateTimeDiv">
          <span className="timeNotifyStyle">{notificationDate}</span>
          <span className="timeNotifyStyle">{notificationTime}</span>
        </div>
      </div>
    );
  }

  function renderListData() {
    const isMore = notificationList?.pagination?.isMore && isLastNotification;
    if (_.isArray(notificationList.data) && !_.isEmpty(notificationList.data)) {
      return (
        <FlatList
          list={notificationList?.data}
          renderItem={renderListItem}
          hasMoreItems={isMore}
          loadMoreItems={() => fetchOtherData()}
        />
      );
    }
  }

  function renderIsMoreLoader() {
    const isMore = notificationList?.pagination?.isMore && isLastNotification;
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

  function renderTitleHeader() {
    return (
      <div>
        <div className="notificationTitleDiv">
          <span className="notificationtitletext">
            {getWords("NOTIFICATION")}
          </span>
          <div
            onClick={() => {
              showAlert(
                true,
                getWords("WARNING"),
                getWords("REMOVE_ALL_NOTIFICATION"),
                true
              );
            }}
          >
            <span className="removeALlNotification">
              {getWords("REMOVE_ALL")}
            </span>
          </div>
        </div>
        <FeedAd adUnit={Setting.ads_Units.TEST_FEED_AD} />
      </div>
    );
  }

  if (loader) {
    return (
      <Protected>
        <Header isSubScreen={true} />
        <CNotificationLoader web={(window.innerWidth >= 600).toString()} />
      </Protected>
    );
  }

  if (
    (_.isObject(notificationList) && _.isEmpty(notificationList)) ||
    (_.isObject(notificationList) &&
      _.has(notificationList, "data") &&
      _.isEmpty(notificationList.data))
  ) {
    return (
      <Protected>
        <div className="MainContainer">
          <Header isSubScreen={true} />
          <CNoData message={getWords("SORRY_NO_DATA_FOUND")} hasheader={true} />
        </div>
      </Protected>
    );
  }

  return (
    <Protected>
      <div className="MainContainer">
        <Header isSubScreen={true} />
        <div className="CommonContainer notifDiv" id="notificationListID">
          {renderTitleHeader()}
          <div className="notifymaindiv">
            <div className="notifymaindivsub">
              <div className="notificationscontainer">{renderListData()}</div>
              {renderIsMoreLoader()}
            </div>
          </div>
        </div>
        {renderAlert()}
      </div>
    </Protected>
  );
}

export default Notifications;
