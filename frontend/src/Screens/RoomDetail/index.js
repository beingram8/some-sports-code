import React, { useState, useEffect } from "react";
import { useLocation } from "react-router-dom";
import { useSelector } from "react-redux";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import { Setting } from "../../Utils/Setting";
import { getApiData } from "../../Utils/APIHelper";
import Header from "../../Components/Header/index";
import CRequestLoader from "../../Loaders/CRequestLoader/index";
import { addAnalyticsEvent, getWords } from "../../commonFunctions";

const Roomdetail = () => {
  const location = useLocation();
  const [loader, setLoader] = useState(true);
  const [postdetail, setPostDetail] = useState({});

  const { userdata } = useSelector((state) => state.auth);

  const postIdToken =
    location && location.search && !_.isEmpty(location.search)
      ? _.toString(location.search).substring(1)
      : "";

  useEffect(() => {
    getPostDetailData();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  async function getPostDetailData() {
    const userInfo =
      _.isObject(userdata) && !_.isEmpty(userdata)
        ? true
        : { user_name: "Guest User" };
    try {
      let endPoint = `${Setting.endpoints.teasing_post_detail}?token=${postIdToken}`;
      const response = await getApiData(endPoint, "GET", null);
      addAnalyticsEvent("Shared_Teasing_Room_Post_Event", userInfo);
      if (response?.status) {
        setLoader(false);
        setPostDetail(response);
      } else {
        setLoader(false);
      }
    } catch (err) {
      setLoader(false);
      console.log("Catch Part", err);
    }
  }

  if (loader) {
    return (
      <div className="MainContainer">
        <Header startup />
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
      <Header startup />
      <div style={{ height: "calc(100% - 65px)" }}>
        <div className="mainContactUsSubCon">
          <div
            className="CommonContainer"
            style={{
              overflow: "auto",
              display: "flex",
              alignItems: "center",
              flexDirection: "column",
            }}
          >
            <div className="roomDetailsTitle">
              <span className="Headingmain">
                {getWords("TEASING_ROOM_TITLE")}
              </span>
            </div>

            <div className="post_header">
              <div style={{ display: "flex", alignItems: "center" }}>
                <img
                  className="user_img"
                  src={postdetail?.data?.user_photo}
                  alt="user_Image"
                />
                <span className="user_name">{postdetail?.data?.username}</span>
              </div>
              <div>
                <span className="post_time">
                  {postdetail?.data?.created_at}
                </span>
              </div>
            </div>

            <div className="user_div">
              {postdetail?.data?.is_video === 1 ? (
                <video
                  className="post_img"
                  style={{ height: 380 }}
                  controls
                  src={postdetail?.data?.media}
                  onPlay={() => {
                    console.log("video played");
                  }}
                />
              ) : (
                <img
                  className="post_img"
                  style={{ height: 380 }}
                  src={postdetail?.data?.media}
                  alt="post"
                />
              )}
            </div>

            <div
              className="caption"
              style={{ borderBottom: "1px solid #ebebeb" }}
            >
              <span className="post_caption">{postdetail?.data?.caption}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Roomdetail;
