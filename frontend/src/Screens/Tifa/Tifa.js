/* eslint-disable array-callback-return */
/* eslint-disable react-hooks/exhaustive-deps */
import PlayCircleFilledWhiteIcon from "@material-ui/icons/PlayCircleFilledWhite";
import ThumbUpAltRoundedIcon from "@material-ui/icons/ThumbUpAltRounded";
import MessageRoundedIcon from "@material-ui/icons/MessageRounded";
import CircularProgress from "@material-ui/core/CircularProgress";
import ReactTwitchEmbedVideo from "react-twitch-embed-video";
import { useDispatch, useSelector } from "react-redux";
import React, { useState, useEffect } from "react";
import Checkbox from "@material-ui/core/Checkbox";
import { useHistory } from "react-router-dom";
import { Paper } from "@material-ui/core";
import Grid from "@material-ui/core/Grid";
import Modal from "react-modal";
import _ from "lodash";
import "./styles.scss";
import {
  getWords,
  addAnalyticsEvent,
  isUserLogin,
  refreshUserData,
} from "../../commonFunctions";
import "../../Styles/common.scss";
import "../../Styles/common.scss";
import Header from "../../Components/Header";
import { Setting } from "../../Utils/Setting";
import FeedAd from "../../Components/Ads/FeedAd";
import BottomTab from "../../Components/BottomTab";
import CDropDown from "../../Components/CDropDown";
import CNoData from "../../Components/CNoData/index";
import FilterIcon from "../../Assets/Images/filter.png";
import ScrollContainer from "react-indiana-drag-scroll";
import CancelIcon from "../../Assets/Images/cancel.png";
import CTifaLoader from "../../Loaders/CTifaLoader/index";
import DialogBox from "../../Components/DialogBox/index.js";
import useMediaQuery from "@material-ui/core/useMediaQuery";
import authActions from "../../Redux/reducers/auth/actions";
import TransferComplete from "../../Modals/TransferComplete";
import InstallAppTutorial from "../../Modals/InstallAppTutorial";
import NotificationPopup from "../../Components/NotificationPopup";
import { getApiData, getAPIProgressData } from "../../Utils/APIHelper";
import CButton from "../../Components/CButton";
// import ArrowForwardIosIcon from "@material-ui/icons/ArrowForwardIos";

const { setSelectedTab, setSelectedNews } = authActions;
const customStyles = {
  content: {
    top: "50%",
    left: "50%",
    right: "auto",
    bottom: "auto",
    transform: "translate(-50%, -50%)",
    backgroundColor: "#FFF",
  },
  overlay: {
    backgroundColor: "rgba(0, 0, 0, 0.50)",
  },
};

function Tifa() {
  const { userdata, teamList } = useSelector((state) => state.auth);
  const matches1100 = useMediaQuery("(min-width:1100px)");
  const matches570 = useMediaQuery("(min-width:571px)");
  const matches = useMediaQuery("(min-width:640px)");
  const history = useHistory();
  const dispatch = useDispatch();

  const [loader, setLoader] = useState(true);
  const [open, setOpen] = React.useState(false);
  const [hideView, sethideView] = useState(false);
  const [giftItem, setItem] = React.useState({});

  const [streamopen, setStreamOPen] = React.useState(false);
  const [newsList, setNewsList] = useState({});
  const [videoList, setVideoList] = useState({});
  const [teamArray, setTeamArray] = useState([]);
  const [streamList, setStreamList] = useState([]);
  const [isearnedcoin, setIsEarnedcoin] = useState(false);
  const [displayAnim, setDisplayAnim] = useState(false);

  const [comment, setCommentCheck] = useState(false);
  const [like, setLikedCheck] = useState(false);
  const [mostRecent, setMostRecent] = useState(false);

  const [applyBtnLoad, setApplyBtnLoad] = useState(false);
  const [dialogopenTeam, setDialogOpenTeam] = useState(false);
  const [dialogopenOrder, setDialogOpenOrder] = useState(false);

  const guestUser = {
    user: "Guest User",
  };

  const checkUserLogin = isUserLogin();
  const eventData = checkUserLogin ? true : guestUser;

  useEffect(() => {
    document.title = Setting.page_name.TIFA;
  }, []);

  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);

    setTimeout(() => {
      sethideView(false);
    }, 500);
  };

  useEffect(() => {
    dispatch(setSelectedTab(0));
    getStreamList();
  }, []);

  useEffect(() => {
    getNewsList();
  }, [userdata, comment, like, mostRecent, teamArray]);

  async function getStreamList() {
    try {
      let endPoint = Setting.endpoints.stream_list;
      const response = await getApiData(endPoint, "GET", null);
      addAnalyticsEvent("Tifa_Screen_Data_Event", eventData);
      if (response && response.status && response.status === true) {
        getVideoList();
        if (response && response.data) {
          setStreamList(response.data);
        } else {
          setLoader(false);
        }
      } else {
        getVideoList();
      }
    } catch (err) {
      console.log("Catch Part", err);
      getVideoList();
    }
  }

  async function getVideoList() {
    try {
      let endPoint = Setting.endpoints.video_list;
      const response = await getApiData(endPoint, "GET", null);
      if (response && response.status && response.status === true) {
        if (response && response.data) {
          setVideoList(response.data);
        }
        setLoader(false);
      } else {
        setLoader(false);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setLoader(false);
    }
  }

  // get video details for guest
  async function getVideoDetailsGuest(id) {
    try {
      let endPoint = `${Setting.endpoints.video_details}?id=${id}`;
      const response = await getApiData(endPoint, "GET", null);
      if (response && response.status && response.status === true) {
        if (response && response.data) {
          let eventData = {};
          eventData.user = checkUserLogin
            ? userdata
            : { userType: "Guest User" };
          eventData.videoData = response.data;

          addAnalyticsEvent("Video_Details_Data_Event", eventData);
          setItem(response.data);
          setOpen(true);
        } else {
        }
      } else {
      }
    } catch (err) {
      console.log("Catch Part", err);
    }
  }

  // get video details for user
  async function getVideoDetailsUser(id, isWatch) {
    try {
      let endPoint = `${Setting.endpoints.video_details_users
        }?id=${id}&is_watch=${isWatch ? 1 : 0}`;
      const header = {
        authorization: `Bearer ${userdata?.access_token}`,
      };
      const response = await getApiData(endPoint, "GET", {}, header);
      if (response && response.status && response.status === true) {
        if (response && response.data) {
          let eventData = {};
          eventData.user = checkUserLogin
            ? userdata
            : { userType: "Guest User" };
          eventData.videoData = response.data;

          addAnalyticsEvent("Video_Details_Data_Event", eventData);
          setItem(response.data);
          setOpen(true);
          if (
            _.isObject(response?.data) &&
            !_.isEmpty(response?.data) &&
            _.has(response?.data, "is_animation") &&
            response?.data?.is_animation
          ) {
            setIsEarnedcoin(true);
          } else {
            setIsEarnedcoin(false);
          }
        } else {
        }
      } else {
      }
    } catch (err) {
      console.log("Catch Part", err);
    }
  }

  // stream_watched
  async function getStreamWatch(id) {
    try {
      let endPoint = `${Setting.endpoints.stream_watched}?stream_id=${id}&is_watch=1`;
      const header = {
        authorization: `Bearer ${userdata?.access_token}`,
      };
      const response = await getApiData(endPoint, "GET", {}, header);
      if (response && response.status && response.status === true) {
        if (response && response.data) {
          let eventData = {};
          eventData.user = checkUserLogin
            ? userdata
            : { userType: "Guest User" };
          eventData.videoData = response.data;

          addAnalyticsEvent("Stream_Details_Data_Event", eventData);

          if (
            _.isObject(response?.data) &&
            !_.isEmpty(response?.data) &&
            _.has(response?.data, "is_animation") &&
            response?.data?.is_animation
          ) {
            setIsEarnedcoin(true);
          } else {
            setIsEarnedcoin(false);
          }
        } else {
        }
      } else {
      }
    } catch (err) {
      console.log("Catch Part", err);
    }
  }

  // news list api call
  async function getNewsList() {
    setApplyBtnLoad(true);
    setDialogOpenTeam(false);
    setDialogOpenOrder(false);

    const query = {
      most_like: like,
      most_comment: comment,
      most_recent: mostRecent,
    };

    if (_.isArray(teamArray) && !_.isEmpty(teamArray)) {
      teamArray?.map((dd, ii) => {
        query[`Team[${ii}]`] = dd.id;
      });
    }

    try {
      let endPoint = Setting.endpoints.news_list_for_guest;
      if (checkUserLogin) {
        endPoint = Setting.endpoints.news_list_for_user;
      }

      const header = checkUserLogin;

      const response = await getAPIProgressData(
        endPoint,
        "POST",
        query,
        header
      );
      if (response && response.status && response.status === true) {
        setNewsList(response?.data);
        setTimeout(() => {
          setApplyBtnLoad(false);
        }, 500);
      } else {
        setApplyBtnLoad(false);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setApplyBtnLoad(false);
    }
  }

  // show more
  const renderShowMoreContent = (position) => {
    return (
      <div
        style={{
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          paddingBottom: 20,
        }}
      >
        <div
          onClick={() => {
            history.push({
              pathname: "/all-news",
              state: {
                data: newsList.rows,
              },
            });
          }}
          className="showmorecontainer"
          style={{
            backgroundColor: "#ED0F18",
            borderRadius: 5,
            width: window.innerWidth > 450 ? "40%" : "90%",
            alignItems: "center",
            justifyContent: "center",
            display: "flex",
            padding: 10,
            cursor: "pointer",
          }}
        >
          <span
            style={{
              color: "white",
              fontFamily: "Segoui",
              fontSize: 16,
              fontWeight: "bold",
            }}
          >
            {getWords("ALL_NEWS")}
          </span>
        </div>
      </div>
    );
  };

  return loader ? (
    <CTifaLoader web={(window.innerWidth >= 640).toString()} />
  ) : (
    <div className="MainContainer">
      <InstallAppTutorial />
      <Header />
      <div className="CommonContainer" style={{ height: "calc(100% - 130px)" }}>
        {(_.isArray(streamList) && !_.isEmpty(streamList)) ||
          (_.isArray(videoList?.rows) && !_.isEmpty(videoList?.rows)) ||
          (_.isArray(newsList?.rows) && !_.isEmpty(newsList?.rows)) ? (
          <div className="tifaMaindiv">
            <span className="cheers">{getWords("CHEERS")}</span>
            <FeedAd adUnit={Setting.ads_Units.TEST_FEED_AD} />
            {/* stream content */}
            {_.isArray(streamList) && !_.isEmpty(streamList) ? (
              <div className="paddingtopstyle">
                <span className="tifaBoldtext">{getWords("LIVE")}</span>
                <ScrollContainer className="radiostream">
                  {streamList?.map((item, index) => {
                    return (
                      <div key={index} className="tifastorycontainer">
                        <Paper
                          className="tifaPaper"
                          style={{
                            backgroundImage: `url(${item.thumb_img})`,
                            backgroundSize: `cover`,
                            backgroundPosition: `center`,
                            backgroundRepeat: "no-repeat",
                          }}
                          onClick={() => {
                            setItem(item);
                            if (item.is_live === "1") {
                              setStreamOPen(true);
                              setTimeout(() => {
                                // getStreamWatch(item.id, iw);
                                getStreamWatch(item.id);
                              }, 2000);
                            } else {
                              setOpen(true);
                              sethideView(true);
                            }
                          }}
                        >
                          {item.is_live === "1" ? (
                            <div className="greendotstyle" />
                          ) : null}
                        </Paper>
                        <div
                          style={{ textAlign: "center" }}
                          className="tifaalltitlepadding1"
                        >
                          <span className="tifaalltitletext">{item.title}</span>
                        </div>
                      </div>
                    );
                  })}
                </ScrollContainer>
              </div>
            ) : null}

            {/* tv content for desktop / tablet */}
            {_.isArray(videoList.rows) && !_.isEmpty(videoList.rows) ? (
              <div className="paddingtopstyle">
                <div
                  style={{
                    display: "flex",
                    flexDirection: "row",
                    justifyContent: "space-between",
                  }}
                >
                  <span className="tifaBoldtext">
                    {getWords("FAN_RATING_TV")}
                  </span>
                  {/* <div
                    onClick={() => {
                      console.log("redirect to all videos");
                      // history.push("/all-videos");
                      history.push({
                        pathname: "/all-videos",
                        state: {
                          data: videoList.rows,
                        },
                      });
                    }}
                    style={{
                      cursor: "pointer",
                      display: "flex",
                      alignItems: "center",
                    }}
                  >
                    <span className="tifaViewAlltext">
                      {getWords("VIEW_ALL")}
                    </span>
                    <ArrowForwardIosIcon className="rightarrowicontifa" />
                  </div> */}
                </div>
                {matches ? (
                  <Grid
                    container
                    justify="space-between"
                    alignContent="space-between"
                    className="radiostream2"
                  >
                    {videoList?.rows?.slice(0, 6).map((item, index) => {
                      return (
                        <Grid key={index} item>
                          <Paper
                            className="tifaPapersquare1"
                            elevation={5}
                            style={{
                              backgroundImage: `url(${item.thumb_img})`,
                            }}
                            onClick={() => {
                              sethideView(false);
                              if (checkUserLogin) {
                                getVideoDetailsUser(item.id, false);
                              } else {
                                getVideoDetailsGuest(item.id);
                              }
                            }}
                          >
                            <div className="tifaiconbuttonstyle">
                              <PlayCircleFilledWhiteIcon className="tifaplayiconstyle" />
                            </div>
                          </Paper>
                          <div className="tifaalltitlepadding">
                            <span className="tifaalltitletext2">
                              {item.title}
                            </span>
                          </div>
                        </Grid>
                      );
                    })}
                  </Grid>
                ) : (
                  // for mobile
                  <div className="radiostream2div">
                    {videoList?.rows?.map((item, index) => (
                      <Grid key={index} item>
                        <Paper
                          className="tifaPapersquare1"
                          elevation={5}
                          style={{
                            backgroundImage: `url(${item.thumb_img})`,
                          }}
                          onClick={() => {
                            sethideView(false);
                            getVideoDetailsGuest(item.id, false);
                          }}
                        >
                          <div className="tifaiconbuttonstyle">
                            <PlayCircleFilledWhiteIcon className="tifaplayiconstyle" />
                          </div>
                        </Paper>
                        <div className="tifaalltitlepadding">
                          <span className="tifaalltitletext2">
                            {item.title}
                          </span>
                        </div>
                      </Grid>
                    ))}
                  </div>
                )}
              </div>
            ) : null}

            {/* news content */}
            <div className="paddingtopstyle">
              <div
                style={{
                  display: "flex",
                  justifyContent: "space-between",
                }}
              >
                <span className="tifaBoldtext">{getWords("NEWS")}</span>

                <div
                  style={{
                    display: "flex",
                    flexDirection: "row",
                    alignItems: "center",
                    justifyContent: "center",
                  }}
                >
                  {/* order by */}
                  <div
                    style={{
                      marginRight: 20,
                    }}
                    className="orderByMainDiv"
                  >
                    {dialogopenOrder ? (
                      <div
                        className="orderbyDiv1"
                        style={{
                          backgroundColor: "#fff",
                          width: window.innerWidth >= 500 ? 400 : 200,
                          zIndex: 10,
                        }}
                      >
                        <div
                          style={{
                            display: "flex",
                            flexDirection: "column",
                            zIndex: 10,
                            boxShadow:
                              "0px 3px 3px -2px rgb(0 0 0 / 50%),0px 3px 4px 0px rgb(0 0 0 / 50%), 0px 1px 8px 0px rgb(0 0 0 / 50%)",
                          }}
                        >
                          <div
                            onClick={
                              applyBtnLoad
                                ? null
                                : () => {
                                  setDialogOpenOrder(false);
                                }
                            }
                            style={{
                              display: "flex",
                              justifyContent: "flex-end",
                              marginTop: 10,
                              marginRight: 10,
                              cursor: "pointer",
                            }}
                          >
                            <img
                              src={CancelIcon}
                              alt={"cancel-icon"}
                              style={{
                                width: 20,
                                height: 20,
                              }}
                            />
                          </div>

                          <div
                            className="orderbyDiv2"
                            style={{
                              zIndex: 10,
                              flexDirection:
                                window.innerWidth >= 500 ? "row" : "column",
                              alignItems:
                                window.innerWidth >= 500 ? "center" : "none",
                            }}
                          >
                            <div className="flexCenterDiv">
                              <Checkbox
                                checked={comment}
                                onChange={
                                  applyBtnLoad
                                    ? null
                                    : (val) => {
                                      setCommentCheck(val?.target?.checked);
                                    }
                                }
                                className="signupmodalcheckbox"
                              />
                              <span>{getWords("MOST_COMMENTS")}</span>
                            </div>
                            <div className="flexCenterDiv">
                              <Checkbox
                                checked={like}
                                onChange={
                                  applyBtnLoad
                                    ? null
                                    : (val) => {
                                      setLikedCheck(val?.target?.checked);
                                    }
                                }
                                className="signupmodalcheckbox"
                              />
                              <span>{getWords("MOST_LIKES")}</span>
                            </div>
                            <div className="flexCenterDiv">
                              <Checkbox
                                checked={mostRecent}
                                onChange={
                                  applyBtnLoad
                                    ? null
                                    : (val) => {
                                      setMostRecent(val?.target?.checked);
                                    }
                                }
                                className="signupmodalcheckbox"
                              />
                              <span>{getWords("MOST_RECENT")}</span>
                            </div>
                          </div>

                          <div className="flexCenterDiv">
                            <CButton
                              buttonText={"Reset"}
                              buttonStyle={{
                                height: 20,
                                width: "100%",
                                margin: "20px 20px 0px 20px",
                              }}
                              handleBtnClick={() => {
                                console.log("reset filter for order by");
                                setCommentCheck(false);
                                setLikedCheck(false);
                                setMostRecent(false);
                              }}
                            />
                          </div>
                        </div>
                      </div>
                    ) : null}
                    <div
                      className="orderbyDiv3"
                      onClick={
                        applyBtnLoad
                          ? null
                          : () => {
                            setDialogOpenOrder(!dialogopenOrder);
                            setDialogOpenTeam(false);
                          }
                      }
                    >
                      <div className="orderbyDiv4" />
                      <div className="marginLeft5UL">
                        <span className="textFilterTitleUL">
                          {getWords("ORDER_BY")}
                        </span>
                      </div>
                    </div>
                  </div>

                  {/* team tag */}
                  <div
                    className="orderByMainDiv"
                    style={{
                      marginRight: window.innerWidth >= 600 ? 10 : 20,
                    }}
                  >
                    {dialogopenTeam ? (
                      <div
                        className="orderbyDiv1"
                        style={{
                          backgroundColor: "#fff",
                          width: window.innerWidth >= 350 ? 320 : 300,
                        }}
                      >
                        <div
                          className="filterDiv1"
                          style={{
                            zIndex: 10,
                          }}
                        >
                          <div
                            style={{
                              display: "flex",
                              width: "100%",
                              alignItems: "center",
                              justifyItems: "center",
                              marginBottom: 10,
                            }}
                          >
                            <div
                              style={{
                                width: "90%",
                              }}
                            >
                              <span className="paddingULDiv">
                                {getWords("SELECT_TEAM_TAG")}
                              </span>
                            </div>

                            <div
                              onClick={
                                applyBtnLoad
                                  ? null
                                  : () => {
                                    setDialogOpenTeam(false);
                                  }
                              }
                              style={{
                                display: "flex",
                                justifyContent: "flex-end",

                                cursor: "pointer",
                                width: "10%",
                              }}
                            >
                              <img
                                src={CancelIcon}
                                alt={"cancel-icon"}
                                style={{
                                  width: 20,
                                  height: 20,
                                }}
                              />
                            </div>
                          </div>

                          <CDropDown
                            data={teamList}
                            // value={}
                            placeholder={getWords("SELECT_TEAM")}
                            onChange={
                              applyBtnLoad
                                ? null
                                : (value) => {
                                  setTeamArray(value);
                                }
                            }
                            selectedColor={"#ED0F18"}
                            borderColor={"#ED0F18"}
                            cstyle={{
                              marginTop: 10,
                            }}
                          />
                        </div>
                      </div>
                    ) : null}
                    <div
                      className="orderbyDiv3"
                      onClick={
                        applyBtnLoad
                          ? null
                          : () => {
                            setDialogOpenTeam(!dialogopenTeam);
                            setDialogOpenOrder(false);
                          }
                      }
                    >
                      <img
                        loading="lazy"
                        className="FIconSty"
                        src={FilterIcon}
                        alt={"filterIcon"}
                      />
                      <div className="marginLeft5UL">
                        <span className="textFilterTitleUL">
                          {getWords("TEAM_TAG")}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              {applyBtnLoad ? (
                <div className="nodataFoundDivTF">
                  <CircularProgress
                    style={{
                      width: 20,
                      height: 20,
                      color: "#ed0f1b",
                    }}
                  />
                </div>
              ) : _.isArray(newsList.rows) && !_.isEmpty(newsList.rows) ? (
                newsList?.rows?.length % 3 === 0 || !matches1100 ? (
                  <Grid
                    container
                    justify={!matches570 ? "center" : "space-between"}
                    className="news"
                  >
                    {newsList?.rows?.map((item, index) => {
                      return (
                        <Grid key={index} item>
                          <Paper
                            className="papernewsstyle"
                            onClick={() => {
                              dispatch(setSelectedNews(item));
                              history.push({
                                pathname: "/news",
                                search: `?news_id=${item?.id}&slug=${item?.slug}`,
                                state: {
                                  newsId: item?.id,
                                  slug: item?.slug,
                                },
                              });
                            }}
                            elevation={5}
                          >
                            <img
                              src={item?.thumb_img}
                              className="match"
                              alt={"match"}
                              loading="lazy"
                            />
                            <div className="newstextcontainer">
                              <span className="tifaalltitletextnews">
                                {item.title.length > 80
                                  ? `${item.title.slice(0, 80)}...`
                                  : item.title}
                              </span>
                              <div className="newsListDiv7">
                                <div style={{ display: "flex" }}>
                                  <div className="newsListDiv5">
                                    <ThumbUpAltRoundedIcon className="likecommenticon" />
                                    <span className="totalcomments">
                                      {item?.total_likes}
                                    </span>
                                  </div>

                                  <div className="newsListDiv6">
                                    <MessageRoundedIcon className="likecommenticon" />
                                    <span className="totalcomments">
                                      {item?.total_comments}
                                    </span>
                                  </div>
                                </div>
                              </div>
                              <div className="newsListDiv1">

                                <div className="newsListDiv2">
                                  <div className="newsListDiv3" />
                                  <div className="newsListDiv4">

                                    <span className="newstext">
                                      {item?.created_at.slice(0, 10)}
                                    </span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </Paper>
                        </Grid>
                      );
                    })}
                  </Grid>
                ) : (
                  <Grid container className="news1">
                    {newsList?.rows?.map((item, index) => {
                      return (
                        <Grid key={index} item>
                          <Paper
                            className="papernewsstyle1"
                            onClick={() => {
                              dispatch(setSelectedNews(item));
                              history.push({
                                pathname: "/news",
                                search: `?news_id=${item?.id}&slug=${item?.slug}`,
                                state: {
                                  newsId: item?.id,
                                  slug: item?.slug,
                                },
                              });
                            }}
                            style={{
                              marginRight: (index + 1) % 3 === 0 ? 0 : "none",
                            }}
                            elevation={5}
                          >
                            <img
                              loading="lazy"
                              src={item?.thumb_img}
                              className="match"
                              alt={"match"}
                            />
                            <div className="newstextcontainer">
                              <span className="tifaalltitletextnews">
                                {item.title.length > 80
                                  ? `${item.title.slice(0, 80)}...`
                                  : item.title}
                              </span>

                              <div className="newsListDiv1">
                                <div className="newsListDiv2">
                                  <div className="newsListDiv3" />
                                  <div className="newsListDiv4">
                                    <div style={{ display: "flex" }}>
                                      <div className="newsListDiv5">
                                        <ThumbUpAltRoundedIcon className="likecommenticon" />
                                        <span className="totalcomments">
                                          {item?.total_likes}
                                        </span>
                                      </div>

                                      <div className="newsListDiv6">
                                        <MessageRoundedIcon className="likecommenticon" />
                                        <span className="totalcomments">
                                          {item?.total_comments}
                                        </span>
                                      </div>
                                    </div>
                                    <span className="newstext">
                                      {item?.created_at.slice(0, 10)}
                                    </span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </Paper>
                        </Grid>
                      );
                    })}
                  </Grid>
                )
              ) : (
                <div className="nodataFoundDivTF">
                  <CNoData
                    message={getWords("SORRY_NO_DATA_FOUND")}
                    hasfooter={false}
                    hasheader={false}
                  />
                </div>
              )}
              {/* show more content */}
              {/* {renderShowMoreContent("news")} */}
            </div>
          </div>
        ) : (
          <CNoData
            message={getWords("SORRY_NO_DATA_FOUND")}
            hasfooter={true}
            hasheader={true}
          />
        )}
      </div>
      <TransferComplete
        animationtype="coinrotation"
        openModal={displayAnim}
        handleClose={() => {
          setTimeout(() => {
            setDisplayAnim(false);
          }, 1000);
        }}
      />
      <DialogBox
        openDialog={open}
        handleClose={() => {
          handleClose();
          if (isearnedcoin) {
            setTimeout(() => {
              setDisplayAnim(true);
            }, 1000);
            setTimeout(() => {
              setIsEarnedcoin(false);
              setDisplayAnim(false);
              refreshUserData();
            }, 3000);
          }
        }}
        handleClickOpen={() => {
          handleClickOpen();
        }}
        fromTifa={true}
        giftItem={giftItem}
        fromnews={true}
        handleBtn={() => {
          handleClose();
          if (isearnedcoin) {
            setTimeout(() => {
              setDisplayAnim(true);
            }, 1000);
            setTimeout(() => {
              setIsEarnedcoin(false);
              setDisplayAnim(false);
              refreshUserData();
            }, 3000);
          }
        }}
        hideView={hideView}
        onPlayVideo={(data) => {
          if (hideView) {
            return null;
          } else {
            if (!_.isEmpty(data)) {
              if (checkUserLogin) {
                getVideoDetailsUser(data.id, data?.videoPlayed);
              } else {
                getVideoDetailsGuest(data.id);
              }
            }
          }
        }}
      />

      <Modal
        isOpen={streamopen}
        onRequestClose={() => {
          setStreamOPen(false);
          if (isearnedcoin) {
            setTimeout(() => {
              setDisplayAnim(true);
            }, 1000);
            setTimeout(() => {
              setIsEarnedcoin(false);
              setDisplayAnim(false);
              refreshUserData();
            }, 3000);
          }
        }}
        style={customStyles}
        contentLabel="Example Modal"
      >
        <ReactTwitchEmbedVideo
          channel="fanrating"
          width={
            window.innerWidth >= 850
              ? 700
              : window.innerWidth >= 680
                ? 600
                : window.innerWidth >= 680
                  ? 400
                  : 300
          }
          height={500}
        />
      </Modal>
      <BottomTab />
      <NotificationPopup />
    </div>
  );
}

export default Tifa;
