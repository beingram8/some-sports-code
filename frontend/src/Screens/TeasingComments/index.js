import CircularProgress from "@material-ui/core/CircularProgress";
import React, { useState, useEffect } from "react";
import SendIcon from "@material-ui/icons/Send";
import { useSelector } from "react-redux";
import FlatList from "flatlist-react";
import Lottie from "react-lottie";
import $ from "jquery";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import { Setting } from "../../Utils/Setting";
import CAlert from "../../Components/CAlert/index";
import Header from "../../Components/Header/index";
import euro from "../../Assets/Images/fan_coins.png";
import CRequestLoader from "../../Loaders/CRequestLoader/index";
import {
  getWords,
  addAnalyticsEvent,
  refreshUserData,
} from "../../commonFunctions";
import { getApiData, getAPIProgressData } from "../../Utils/APIHelper";
import loadingindicator from "../../Assets/Lottie/LoadingIndicator3.json";

const TeasingComment = (props) => {
  const postdata = props?.location?.state?.data;
  const post_id = postdata?.id;
  const [isLastComment, setIsLastComment] = useState(false);
  const [commentList, setCommentList] = useState({});
  const [cLoad, setCLoad] = useState(false);
  const [comment, setComment] = useState("");
  const { userdata } = useSelector((state) => state.auth);
  const [loader, setLoader] = useState(true);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [isearnedcoin, setIsEarnedcoin] = useState(false);
  let commentRef = React.createRef();

  useEffect(() => {
    document.title = Setting.page_name.TEASING_COMMENT;
  }, []);

  useEffect(() => {
    getCommentdata();
    // eslint-disable-next-line react-hooks/exhaustive-deps
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
          setCLoad(false);
        }}
        onOkay={() => {
          setCLoad(false);
          setAlertOpen(false);
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  async function getCommentdata(bool = true) {
    const cPage =
      commentList &&
        commentList.pagination &&
        commentList.pagination.currentPage
        ? _.toNumber(commentList.pagination.currentPage)
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
      let endPoint = `${Setting.endpoints.teasing_comment_list}?id=${post_id}&page=${PageNo}`;
      const response = await getApiData(endPoint, "GET", null, header);
      addAnalyticsEvent("Teasing_Room_Post_Comment_List_Event", true);

      if (response?.status) {
        setLoader(false);
        const obj = bool ? {} : _.cloneDeep(commentList);

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

        setCommentList(obj);
        setIsLastComment(false);
        setCLoad(false);
      } else {
        showAlert(true, getWords("OOPS"), response?.message);
        setLoader(false);
        console.log("ERROR", response.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
      setLoader(false);
    }
  }

  async function addCommentProcess() {
    setCLoad(true);
    try {
      let endPoint = `${Setting.endpoints.teasing_add_comment}`;
      const commentdata = {
        comment: comment,
        id: post_id,
      };
      const response = await getAPIProgressData(
        endPoint,
        "POST",
        commentdata,
        true
      );
      if (response?.status) {
        const eventData = {
          user_name: userdata?.username,
          first_name: userdata?.firstname,
          last_name: userdata?.lastname,
          email: userdata?.email,
          user_Pic: userdata?.user_image,
          post_id: post_id,
          commentText: comment,
        };
        getCommentdata(true);
        setComment("");
        addAnalyticsEvent("Add_New_Comment_In_Teasing_Room_Event", eventData);

        if (response?.data?.is_animation === true) {
          setTimeout(() => {
            let coinanime = document.getElementById("coinanime");
            coinanime.style.animation = "boxActivatedh 1.5s";
            coinanime.style.webkitAnimation = "boxActivated 1.5s";

            setIsEarnedcoin(!isearnedcoin);
            setTimeout(() => {
              setIsEarnedcoin(isearnedcoin);
              refreshUserData();
            }, 1000);
          }, 500);
        }
      } else {
        setLoader(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      setLoader(false);
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  function handleKeyEnter1(e) {
    e.which = e.which || e.keyCode;

    // If the key press is Enter
    // eslint-disable-next-line eqeqeq
    if (e.which == 13) {
      switch (e.target.id) {
        case "comment":
          commentRef.current.blur();
          addCommentProcess();
          break;

        default:
          break;
      }
    }
  }

  function fetchOtherData() {
    const totalPages =
      commentList && commentList.pagination && commentList.pagination.totalPage
        ? _.toNumber(commentList.pagination.totalPage)
        : 0;

    const currentPage =
      commentList &&
        commentList.pagination &&
        commentList.pagination.currentPage
        ? _.toNumber(commentList.pagination.currentPage)
        : 0;

    if (commentList.pagination.isMore === true && currentPage < totalPages) {
      getCommentdata(false);
    }
  }

  $(document).ready(function () {
    $("#commentListID").on("scroll", chk_scroll);
  });

  function chk_scroll(e) {
    var elem = $(e.currentTarget);
    if (
      parseInt(elem[0].scrollHeight) - parseInt(elem.scrollTop()) ===
      parseInt(elem.outerHeight())
    ) {
      setIsLastComment(true);
    }
  }

  const scrollToTop = () => {
    window.scroll(0, 0);
  };

  function renderIsMoreLoader() {
    const isMore = commentList?.pagination?.isMore && isLastComment;
    return (
      <div className="notifDiv1">
        {isMore ? (
          <Lottie
            options={{
              loop: true,
              autoplay: true,
              animationData: loadingindicator,
            }}
            height={window.innerWidth >= 600 ? 70 : 50}
            width={"100%"}
          />
        ) : null}
      </div>
    );
  }

  function renderCommentList() {
    const isMore = commentList?.pagination?.isMore && isLastComment;
    if (_.isArray(commentList.data) && !_.isEmpty(commentList.data)) {
      return (
        <FlatList
          list={commentList.data}
          hasMoreItems={isMore}
          loadMoreItems={() => fetchOtherData()}
          renderItem={(item, index) => {
            return (
              <div key={index} className="divide_div">
                <div className="post_detail">
                  <img
                    src={item.user_photo}
                    alt="user_photo"
                    className="user_photo"
                  />
                  <img
                    src={item.team_photo}
                    alt="team_photo"
                    className="commentteamlogo"
                  />
                  <div className="post_detail">
                    <div style={{ marginLeft: 15 }}>
                      <div>
                        <span className="commentorname teasingcommentname">
                          {item.username}
                        </span>
                        <span className="commentorcomment">{item.comment}</span>
                      </div>
                      <div>
                        <span className="post_time">{item.created_at}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            );
          }}
        />
      );
    } else {
      return (
        <div className="comment_empty">
          <span>{getWords("NO_COMMENT")}</span>
        </div>
      );
    }
  }

  if (loader) {
    return (
      <div className="MainContainer">
        <Header isSubScreen={true} />
        <div className="CommonContainer teasingcommentheight">
          <div className="teasingcommentmargin">
            <span className="Headingmain">
              {getWords("COMMENT_SCREEN_TITLE")}
            </span>
          </div>
        </div>
        <CRequestLoader
          openModal={loader}
          handleClose={() => {
            setLoader(false);
          }}
        />
      </div>
    );
  }

  return (
    <div className="MainContainer">
      <Header isSubScreen={true} />
      <div className="CommonContainer screentitlemain">
        <div className="screentitle">
          <span className="Headingmain">
            {getWords("COMMENT_SCREEN_TITLE")}
          </span>
        </div>

        <div className="post-info-data">
          <div>
            <img
              src={postdata.user_photo}
              alt="user_photo"
              className="user_img"
            />
          </div>
          <div className="main_box">
            <div>
              <span className="commentorname teasingcommentpaddingstyle">
                {postdata.username}
              </span>
            </div>
            <div className="captionmainstyle">
              <span className="commentorcomment">{postdata.caption}</span>
            </div>
          </div>
          <div className="post_timedetail">
            <span className="post_time">{postdata.created_at}</span>
          </div>
        </div>

        <div id="commentListID" className="pagination_div">
          <div className="notifymaindiv">
            <div className="notifymaindivsub">
              <div className="notificationscontainer">
                {renderCommentList()}
              </div>
              {renderIsMoreLoader()}
            </div>
          </div>
        </div>
      </div>

      <div className="CommonContainer commentinput">
        <div className="comment_inputmain">
          <div className="comment_input">
            <div>
              <img
                src={userdata.user_image}
                alt="profile"
                className="user_profile"
              />
            </div>
            <input
              type={"text"}
              placeholder="Add comment..."
              id="comment"
              autoComplete="false"
              className="post_input"
              ref={commentRef}
              onKeyPress={(e) => {
                handleKeyEnter1(e);
              }}
              onChange={(val) => {
                setComment(val.target.value);
              }}
              value={comment}
            />
            {cLoad ? (
              <CircularProgress className="comment_loader" />
            ) : (
              <div className="teasingcommentposition">
                <SendIcon
                  className="send_arrow"
                  onClick={
                    cLoad || loader
                      ? null
                      : () => {
                        if (!_.isEmpty(comment)) {
                          addCommentProcess();
                          scrollToTop();
                        }
                      }
                  }
                />
                <div
                  id="coinanime"
                  className="commentanimation"
                  style={{
                    visibility: isearnedcoin ? "visible" : "hidden",
                  }}
                >
                  <img
                    loading="lazy"
                    src={euro}
                    alt="coin"
                    height={22}
                    width={22}
                    className="animatecoin"
                  />
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
      {renderAlert()}
    </div>
  );
};

export default TeasingComment;
