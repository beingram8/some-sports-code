import MoreVertIcon from "@material-ui/icons/MoreVert";
import { RWebShare } from "react-web-share";
import { useSelector } from "react-redux";
import { useHistory } from "react-router-dom";
import React, { useState } from "react";
import _ from "lodash";
import { Setting } from "../../Utils/Setting";
import Chat from "../../Assets/Images/chat.png";
import Like from "../../Assets/Images/like.png";
import Share from "../../Assets/Images/share.png";
import euro from "../../Assets/Images/fan_coins.png";
import likeOutLine from "../../Assets/Images/likeOutLine.png";
import { postOption } from "../../staticData";
import CAlert from "../CAlert/index";
import {
  getWords,
  addAnalyticsEvent,
  refreshUserData,
} from "../../commonFunctions";
import { getApiData, getAPIProgressData } from "../../Utils/APIHelper";

function CTeasingRoomPost(props) {
  const {
    data,
    defaultTab,
    openReportModal,
    dialogopen,
    onRefreshData,
    dialogAction,
    defaultItem,
  } = props;
  const history = useHistory();
  const isRoomLike = data?.is_like;
  const [deletePostData, setDeletePostData] = useState({});
  const [isearnedcoin, setIsEarnedcoin] = useState(false);
  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [callFunc, setCallFunction] = useState(false);
  const [showCancelOptionValue, showCancelOption] = useState(false);
  const { userdata } = useSelector((state) => state.auth);

  // Post Like Process //
  async function roomLikeProcess() {
    const roomId = data?.id;
    try {
      let endPoint = `${Setting.endpoints.teasing_post_like}?id=${roomId}`;
      const eventData = {
        user_name: userdata?.username,
        first_name: userdata?.firstname,
        last_name: userdata?.lastname,
        email: userdata?.email,
        user_Pic: userdata?.user_image,
        like_post_id: roomId,
      };
      addAnalyticsEvent("Teasing_Room_Like_Post_Event", eventData);
      const response = await getAPIProgressData(endPoint, "POST", null, true);
      if (response && response.status && response.status === true) {
        onRefreshData();

        if (response?.data?.is_animation === true) {
          setTimeout(() => {
            setIsEarnedcoin(!isearnedcoin);
            setTimeout(() => {
              setIsEarnedcoin(isearnedcoin);
              refreshUserData();
            }, 1000);
          }, 500);
        }
      } else {
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  // Delete Post Process //
  async function postDeleteProcess() {
    const deleteItemId = deletePostData?.id;
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: userToken,
    };
    try {
      let endPoint = `${Setting.endpoints.teasing_delete_post}?id=${deleteItemId}`;
      const eventData = {
        user_name: userdata?.username,
        first_name: userdata?.firstname,
        last_name: userdata?.lastname,
        email: userdata?.email,
        user_Pic: userdata?.user_image,
        delete_post_id: deleteItemId,
      };
      addAnalyticsEvent("Teasing_Room_Delete_Post_Event", eventData);
      const response = await getApiData(endPoint, "GET", null, header);
      if (response && response.status && response.status === true) {
        onRefreshData();
      } else {
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("OOPS"), "Something went wrong");
    }
  }

  // Delete Post Confirmation Method //
  function deletepost() {
    showCancelOption(true);
    showAlert(true, getWords("OOPS"), getWords("DELETE_POST"), true);
  }

  const showAlert = (open, title, message, callFunction) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
    setCallFunction(callFunction);
  };

  function renderAlert() {
    return (
      <CAlert
        showCancel={showCancelOptionValue}
        open={alertOpen}
        onClose={() => {
          setAlertOpen(false);
          showCancelOption(false);
          setDeletePostData({});
        }}
        onOkay={() => {
          setAlertOpen(false);
          if (callFunc) {
            postDeleteProcess();
          }
          setDeletePostData({});
          showCancelOption(false);
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  function onMenuSelect(item) {
    dialogAction();
    if (item?.id === 1) {
      openReportModal();
    } else if (item?.id === 2) {
      history.push({
        pathname: "/add-post",
        state: {
          data: data,
        },
      });
    } else if (item?.id === 3) {
      setDeletePostData(data);
      deletepost();
    }
  }

  return (
    <div className="teasingroot">
      <div className="post_header">
        <div style={{ display: "flex", alignItems: "center" }}>
          <img className="user_img" src={data?.user_photo} alt="user_Image" />
          <span className="user_name">{data?.username}</span>
        </div>
        <div className="postheader_right">
          <div className="postheader_right" style={{ marginRight: 5 }}>
            <span className="post_time">{data?.created_at}</span>
          </div>
          <div className="postheader_right">
            <MoreVertIcon
              className="menuoption"
              onClick={() => {
                dialogAction();
              }}
            />
            {data?.id === defaultItem?.id ? (
              <div
                className="dialogstyle"
                style={{
                  visibility: dialogopen ? "visible" : "hidden",
                  boxShadow:
                    "0px 3px 3px -2px rgb(0 0 0 / 20%), 0px 3px 4px 0px rgb(0 0 0 / 14%), 0px 1px 8px 0px rgb(0 0 0 / 12%)",
                  top: defaultTab?.id === 1 ? "none" : "0px",
                }}
              >
                {!_.isEmpty(postOption) && _.isArray(postOption)
                  ? postOption?.map((obj, index) => {
                      return defaultTab?.id === 1 && obj?.id === 1 ? (
                        <div
                          key={index}
                          onClick={() => {
                            onMenuSelect(obj);
                          }}
                          style={{
                            padding: 10,
                            cursor: "pointer",
                          }}
                        >
                          <span>{obj.option}</span>
                        </div>
                      ) : defaultTab?.id === 2 && obj?.id !== 1 ? (
                        <div
                          key={index}
                          onClick={() => {
                            onMenuSelect(obj);
                          }}
                          style={{
                            borderBottom:
                              obj?.id === 2 ? "1px solid #484848" : "0px",
                            padding: 10,
                            cursor: "pointer",
                          }}
                        >
                          <span>{obj.option}</span>
                        </div>
                      ) : null;
                    })
                  : null}
              </div>
            ) : null}
          </div>
        </div>
      </div>
      <div className="user_div">
        {data?.is_video === 1 ? (
          <video className="post_img" controls src={data?.post_media} />
        ) : (
          <img className="post_img" src={data?.post_media} alt="post" />
        )}
      </div>
      <div className="caption">
        <span className="post_caption">{data?.caption}</span>
      </div>
      <div className="likes_comments">
        <div className="chatmain">
          <div
            style={{ cursor: "pointer", position: "relative" }}
            onClick={() => {
              roomLikeProcess();
            }}
          >
            <div
              id="coinanime"
              style={{
                position: "absolute",
                top: -30,
                display: isearnedcoin ? "unset" : "none",
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
            <img
              src={isRoomLike === "1" ? Like : likeOutLine}
              alt="chat"
              className="chat"
            />
          </div>
          <span className="post_details">{data?.total_likes}</span>
        </div>
        <div className="chatmain">
          <img
            src={Chat}
            alt="chat"
            className="chat"
            onClick={() => {
              history.push({
                pathname: "/teasing-comment",
                state: {
                  data: data,
                },
              });
            }}
          />
          <span className="post_details">{data?.total_comments}</span>
        </div>
        <div className="chatmain">
          <RWebShare
            data={{
              url: `https://fanratingweb.com/teasing-room-post-detail?${data?.token}`,
              title: "fanratingweb.com",
            }}
            onClick={() => {
              const shareEventData = {
                user_name: userdata?.username,
                first_name: userdata?.firstname,
                last_name: userdata?.lastname,
                email: userdata?.email,
                user_Pic: userdata?.user_image,
                shared_post_id: data?.id,
              };
              addAnalyticsEvent(
                "Teasing_Room_Post_Share_Event",
                shareEventData
              );
            }}
          >
            <img
              loading="lazy"
              src={Share}
              className="chat"
              style={{ cursor: "pointer" }}
              alt={"ShareIcon"}
            />
          </RWebShare>
        </div>
      </div>
      {renderAlert()}
    </div>
  );
}

CTeasingRoomPost.propTypes = {};

CTeasingRoomPost.defaultProps = {};

export default CTeasingRoomPost;
