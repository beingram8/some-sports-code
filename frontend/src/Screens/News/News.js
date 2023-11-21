import CircularProgress from "@material-ui/core/CircularProgress";
import React, { useState, useEffect } from "react";
import FlatList from "flatlist-react";
import renderHTML from "react-render-html";
import SendIcon from "@material-ui/icons/Send";
import IconButton from "@material-ui/core/IconButton";
import { useDispatch, useSelector } from "react-redux";
import OutlinedInput from "@material-ui/core/OutlinedInput";
import InputAdornment from "@material-ui/core/InputAdornment";
import _ from "lodash";
import "./styles.scss";
import {
  getWords,
  addAnalyticsEvent,
  isUserLogin,
  sendFCMTokenToServer,
  checkSurveyQuizIsEnable,
  refreshUserData,
} from "../../commonFunctions";
import "../../Styles/common.scss";
import { Setting } from "../../Utils/Setting";
import like from "../../Assets/Images/like.png";
import chat from "../../Assets/Images/chat.png";
import Header from "../../Components/Header/index";
import CAlert from "../../Components/CAlert/index";
import CNoData from "../../Components/CNoData/index";
import LoginModal from "../../Modals/LoginModal/index";
import SignUpModal from "../../Modals/SignUpModal/index";
import SuccessModal from "../../Modals/SuccessModal/index";
import authActions from "../../Redux/reducers/auth/actions";
import CNewsLoader from "../../Loaders/CNewsLoader/index.js";
import likeOutLine from "../../Assets/Images/likeOutLine.png";
import NotificationPopup from "../../Components/NotificationPopup";
import { getApiData, getAPIProgressData } from "../../Utils/APIHelper";
import ForgotPasswordModal from "../../Modals/ForgotPasswordModal/index";
import { RWebShare } from "react-web-share";
import share from "../../Assets/Images/share.png";
import { useHistory } from "react-router-dom";
import $ from "jquery";
import loadingindicator from "../../Assets/Lottie/LoadingIndicator3.json";
import Lottie from "react-lottie";
import useMediaQuery from "@material-ui/core/useMediaQuery";
import euro from "../../Assets/Images/fan_coins.png";
import AMPAutoAd from "../../Components/Ads/AMPAutoAd";
// import { MentionsInput, Mention } from "react-mentions";
// import defaultStyle from "./defaultStyle";
// import defaultMentionStyle from "./defaultMentionStyle";

const { setUserData } = authActions;

function News(props) {
  const history = useHistory();

  const dispatch = useDispatch();
  const { userdata, selectedNews } = useSelector((state) => state.auth);

  // let newsID = selectedNews?.id;

  // if (props?.location?.state?.newsId > 0) {
  //   newsID = props?.location?.state?.newsId;
  // }

  const propsData = props?.location?.state;
  const match425 = useMediaQuery("(min-width:426px)");
  const [loader, setLoader] = useState(true);
  const [newsData, setNewsData] = useState({});
  const [commentList, setCommentList] = useState({});
  const [commenttext, setcommenttext] = useState("");
  // const [subcomment, setSubcomment] = useState("");
  const [loginModal, setLoginModal] = useState(false);
  const [signUpModal, setSignUpModal] = useState(false);
  const [successModal, setSuccessModal] = useState(false);
  const [forgorPwdModal, setForgotPwdModal] = useState(false);
  const [iscommentlast, setIsCommentLast] = useState(true);
  const [isearnedcoin, setIsEarnedcoin] = useState(false);
  const [isearnedcoincomment, setIsEarnedcoinComment] = useState(false);

  // const [defaultItem, setdefaultItem] = useState({});
  // const [likeCount, setLikeCount] = useState(false);
  const [cLoad, setCLoad] = useState(false);
  const [listLoad, setListLoad] = useState(false);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  const checkIsUserLogin = _.isObject(userdata) && !_.isEmpty(userdata);
  let commentRef = React.createRef();

  const guestUser = {
    user_name: "Guest User",
  };

  const checkUserLogin = isUserLogin();
  const eventData = checkUserLogin ? true : guestUser;

  useEffect(() => {
    getNewsDetails();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [userdata]);

  useEffect(() => {
    document.title = Setting.page_name.NEWS;
  }, []);

  // show alert
  const showAlert = (open, title, message) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
  };

  // display alert
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

  // gety news details api call
  async function getNewsDetails() {
    const header = checkUserLogin
      ? {
        authorization: `Bearer ${userdata?.access_token}`,
      }
      : null;
    try {
      // remove news_id=
      const newsID12 = _.includes(window.location.search, "news_id=")
        ? window.location.search.replace("?news_id=", "")
        : "";
      // remove slug=
      const newsID13 = _.includes(newsID12, "slug=")
        ? newsID12.replace("slug=", "")
        : "";
      // split id and slug
      const newsID14 = _.includes(newsID12, "slug=") ? newsID13.split("&") : "";

      const NEWS_ID = !_.isUndefined(newsID14[0])
        ? newsID14[0]
        : propsData?.newsId;
      const SLUG = !_.isUndefined(newsID14[1]) ? newsID14[1] : propsData?.slug;

      let endPoint = `${Setting.endpoints.detail_for_guest}?news_id=${NEWS_ID}&slug=${SLUG}`;

      if (checkUserLogin) {
        endPoint = `${Setting.endpoints.news_detail}?news_id=${NEWS_ID}&slug=${SLUG}`;
      }

      const response = await getApiData(endPoint, "GET", {}, header);
      addAnalyticsEvent("News_Details_Event", eventData);
      if (response && response.status && response.status === true) {
        setNewsData(response.data);

        getCommentList();

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

  // get comment list api call
  async function getCommentList(bool = true) {
    setListLoad(true);
    const cPage =
      commentList &&
        commentList.pagination &&
        commentList.pagination.currentPage
        ? _.toNumber(commentList.pagination.currentPage)
        : 0;

    let PageNo = 0;
    if (bool === true) {
      PageNo = 1;
    } else {
      PageNo = cPage + 1;
    }

    // remove news_id=
    const newsID12 = _.includes(window.location.search, "news_id=")
      ? window.location.search.replace("?news_id=", "")
      : "";
    // remove slug=
    const newsID13 = _.includes(newsID12, "slug=")
      ? newsID12.replace("slug=", "")
      : "";
    // split id and slug
    const newsID14 = _.includes(newsID12, "slug=") ? newsID13.split("&") : "";

    const NEWS_ID = newsID14[0];
    // const SLUG = newsID14[1];

    const header = {
      Authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = `${Setting.endpoints.comment_list}?news_id=${NEWS_ID}&page=${PageNo}`;
      const response = await getApiData(endPoint, "GET", null, header);
      addAnalyticsEvent("News_List_Event", eventData);
      if (response && response.status && response.status === true) {
        const obj = bool ? {} : _.cloneDeep(commentList);

        const cmntListData =
          response && response.data && response.data.rows
            ? response.data.rows
            : [];
        const paginationDatas =
          response && response.data && response.data.pagination
            ? response.data.pagination
            : {};

        if (_.isArray(cmntListData)) {
          if (_.isArray(obj.data) && obj.data.length > 0) {
            obj.data = _.flattenDeep([...obj.data, cmntListData]);
          } else {
            obj.data = cmntListData;
          }
          obj.pagination = paginationDatas;
        }

        setCommentList(obj);
        setIsCommentLast(false);
        setLoader(false);
        setCLoad(false);
        setListLoad(false);
      } else {
        setLoader(false);
        setListLoad(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setLoader(false);
      setListLoad(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  // add comment api call
  async function addCommentProcess() {
    setCLoad(true);
    try {
      let endPoint = `${Setting.endpoints.news_comment}`;
      const commentdata = {
        "NewsComment[comment_text]": commenttext,
        "NewsComment[news_id]": newsData?.id,
      };

      const response = await getAPIProgressData(
        endPoint,
        "POST",
        commentdata,
        true
      );
      console.log("comment : ", response);
      if (response?.status) {
        const eventData = {
          user: userdata,
          news: newsData,
          commentText: commenttext,
        };
        setcommenttext("");
        addAnalyticsEvent("News_Comment_Event", eventData);
        getCommentList();
        if (
          _.isObject(response?.data) &&
          !_.isEmpty(response?.data) &&
          _.has(response?.data, "is_animation") &&
          response?.data?.is_animation
        ) {
          let coinanime2 = document.getElementById("coinanime2");
          coinanime2.style.animation = "boxActivated 1.5s";
          coinanime2.style.webkitAnimation = "boxActivated 1.5s";
          setIsEarnedcoinComment(!isearnedcoincomment);
          setTimeout(() => {
            setIsEarnedcoinComment(isearnedcoincomment);
            refreshUserData();
          }, 1000);
        }
      } else {
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  // like api call
  async function likeNew() {
    try {
      let endPoint = `${Setting.endpoints.news_like}`;
      const likedata = {
        id: newsData?.id,
      };
      const response = await getAPIProgressData(
        endPoint,
        "POST",
        likedata,
        true
      );
      console.log("like :  ", response);
      if (response && response.status && response.status === true) {
        const eventData = {
          user: userdata,
          news: newsData,
        };
        if (
          _.isObject(response?.data) &&
          !_.isEmpty(response?.data) &&
          _.has(response?.data, "is_animation") &&
          response?.data?.is_animation
        ) {
          let coinanime = document.getElementById("coinanime");
          coinanime.style.animation = "boxActivated 1.5s";
          coinanime.style.webkitAnimation = "boxActivated 1.5s";
          setIsEarnedcoin(!isearnedcoin);
          setTimeout(() => {
            setIsEarnedcoin(isearnedcoin);
            refreshUserData();
          }, 1000);
        }

        addAnalyticsEvent("News_Like_Event", eventData);
        getNewsDetails();
      } else {
      }
    } catch (err) {
      console.log("Catch Part", err);
    }
  }

  // display login modal
  function renderLoginModal() {
    return (
      <LoginModal
        loginModal={loginModal}
        onSignupClick={() => {
          setLoginModal(false);
          setSignUpModal(true);
        }}
        handleClose={(uData, str) => {
          if (_.isObject(uData) && !_.isEmpty(uData)) {
            dispatch(setUserData(uData));
            setTimeout(() => {
              sendFCMTokenToServer();
              checkSurveyQuizIsEnable();
              if (!_.isEmpty(str) && _.isString(str)) {
                addAnalyticsEvent(str, true);
              } else {
                addAnalyticsEvent("Login_Event", true);
              }
            }, 2000);
          }
          setLoginModal(false);
        }}
        onForgotPasswordClick={() => {
          setLoginModal(false);
          setForgotPwdModal(true);
        }}
      />
    );
  }

  // display signup modal
  function renderSignUpModal() {
    return (
      <SignUpModal
        signUpModal={signUpModal}
        onSignInClick={() => {
          setSignUpModal(false);
          setLoginModal(true);
        }}
        handleClose={(uData) => {
          if (_.isObject(uData) && !_.isEmpty(uData)) {
            dispatch(setUserData(uData));
            setTimeout(() => {
              sendFCMTokenToServer();
              checkSurveyQuizIsEnable();
            }, 2000);
          }
          setSignUpModal(false);
        }}
      />
    );
  }

  // display forgot password modal
  function renderForgotPWDModal() {
    return (
      <ForgotPasswordModal
        forgorPwdModal={forgorPwdModal}
        handleClose={() => {
          setForgotPwdModal(false);
        }}
        onSavePassword={() => {
          setForgotPwdModal(false);
          setSuccessModal(true);
        }}
      />
    );
  }

  // display success modal
  function renderSuccessModal() {
    return (
      <SuccessModal
        successModal={successModal}
        handleClose={() => {
          setSuccessModal(false);
        }}
        frmQuiz={false}
        score={10}
        earnedTokens={120}
      />
    );
  }

  // fetch other data
  function fetchOtherData() {
    if (listLoad) {
      return null;
    }

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
      getCommentList(false);
    }
  }

  $(document).ready(function () {
    $("#newscommentid").on("scroll", chk_scroll);
  });

  function chk_scroll(e) {
    var elem = $(e.currentTarget);
    if (
      parseInt(elem[0].scrollHeight) - parseInt(elem.scrollTop()) ===
      parseInt(elem.outerHeight())
    ) {
      setIsCommentLast(true);
    }
  }

  // keyboard button click events
  function handleKeyEnter1(e) {
    e.which = e.which || e.keyCode;

    // If the key press is Enter
    // eslint-disable-next-line eqeqeq
    if (e.which == 13) {
      switch (e.target.id) {
        case "comment":
          commentRef.current.blur();
          // addCommentProcess();
          if (!checkIsUserLogin) {
            setLoginModal(true);
          } else {
            addCommentProcess();
          }
          break;

        default:
          break;
      }
    }
  }

  const renderSubComment = () => {
    const users = [
      {
        id: "walter",
        display: "Walter White",
      },
      {
        id: "jesse",
        display: "Jesse Pinkman",
      },
      {
        id: "gus",
        display: 'Gustavo "Gus" Fring',
      },
      {
        id: "saul",
        display: "Saul Goodman",
      },
      {
        id: "hank",
        display: "Hank Schrader",
      },
      {
        id: "skyler",
        display: "Skyler White",
      },
      {
        id: "mike",
        display: "Mike Ehrmantraut",
      },
      {
        id: "lydia",
        display: "Lydìã Rôdarté-Qüayle",
      },
    ];
    return (
      <div
        style={{
          display: "flex",
          flexDirection: "row",
          border: "1px solid #ed0f18",
          borderRadius: 5,
          width: "100%",
        }}
      >
        {/* <MentionsInput
          className="comments-textarea"
          value={subcomment}
          style={defaultStyle}
          placeholder={getWords("ADD_COMMENT")}
          onChange={(value) => {
            console.log("onchange mention input", value);
            setSubcomment(value.target.value);
          }}
          singleLine
        >
          <Mention
            trigger="@"
            data={users}
            style={defaultMentionStyle}
            renderSuggestion={(
              suggestion,
              search,
              highlightedDisplay,
              index,
              focused
            ) => (
              <div className={`user ${focused ? "focused" : ""}`}>
                {highlightedDisplay}
              </div>
            )}
          />
        </MentionsInput> */}

        <IconButton
          style={{ position: "relative" }}
          onClick={
            cLoad
              ? null
              : () => {
                if (!checkIsUserLogin) {
                  setLoginModal(true);
                } else {
                  addCommentProcess();
                }
              }
          }
        >
          <div
            id="coinanime2"
            style={{
              position: "absolute",
              top: -30,
              display: isearnedcoincomment ? "unset" : "none",
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
          {cLoad ? (
            <CircularProgress
              style={{
                width: 15,
                height: 15,
                color: "#ed0f1b",
              }}
            />
          ) : (
            <SendIcon style={{ color: "#ed0f1b", cursor: "pointer" }} />
          )}
        </IconButton>
      </div>
    );
  };

  // display comment list
  function renderCommentList() {
    if (_.isArray(commentList?.data) && !_.isEmpty(commentList?.data)) {
      return (
        <FlatList
          list={commentList?.data}
          renderItem={(item, index) => {
            return (
              <div key={index} className="commentscontainer">
                <div className="commentorimage">
                  <img
                    loading="lazy"
                    src={item.user_photo}
                    className="commentorimage"
                    alt={"UserProfile"}
                  />
                </div>

                <div
                  style={{
                    display: "flex",
                    flexDirection: "column",
                    alignItems: "center",
                  }}
                >
                  <div
                    style={{
                      display: "flex",
                    }}
                  >
                    <div className="teamiconStyleNews">
                      <img
                        loading="lazy"
                        className="teamiconStyleNews"
                        src={item?.team_icon}
                        style={{
                          display: "flex",
                          alignSelf: "flex-start",
                        }}
                        alt={"TeamIcon"}
                      />
                    </div>
                    <div
                      style={{
                        display: "flex",
                        flexDirection: "column",
                        marginTop: -5,
                      }}
                    >
                      <span className="commentorname">
                        {match425
                          ? item.username
                          : item.username.length <= 12
                            ? item.username
                            : item.username.slice(0, 12).concat("...")}
                        &nbsp;
                        <span className="commentorcomment">
                          {item.comment_text}
                        </span>
                      </span>
                      <div
                        style={{
                          display: "flex",
                          flexDirection: "row",
                        }}
                      >
                        <span className="commentorcommentTime">
                          {item.created_at}
                        </span>

                        {/* like comment */}
                        {/* <div
                          style={{
                            cursor: "pointer",
                          }}
                          onClick={() => {
                            console.log("like comment ====>>>>> ", item);
                            setLikeCount(!likeCount);
                            setdefaultItem(item);
                          }}
                        >
                          <span className="commentorcommentTime">
                            {getWords("LIKE")}
                          </span>
                        </div> */}

                        {/* reply comment */}
                        {/* <div
                          style={{
                            cursor: "pointer",
                          }}
                          onClick={() => {
                            console.log("reply comment ====>>>>> ", item);
                            setdefaultItem(item);
                            // setSubcomment(`${item.username} `);
                          }}
                        >
                          <span className="commentorcommentTime">
                            {getWords("reply")}
                          </span>
                        </div> */}

                        {/* {likeCount && item.id === defaultItem.id ? (
                          <div
                            style={{
                              marginLeft: 20,
                            }}
                          >
                            <span>{likeCount ? 1 : 0}</span>
                            <img
                              loading="lazy"
                              src={like}
                              className="likesubcommenticons"
                              style={{ cursor: "pointer", marginLeft: 5 }}
                              alt={"LikeIcon"}
                            />
                          </div>
                        ) : null} */}
                      </div>
                      {/* {item.id === defaultItem.id ? renderSubComment() : null} */}
                    </div>
                  </div>
                </div>
              </div>
            );
          }}
          hasMoreItems={commentList?.pagination?.isMore && iscommentlast}
          loadMoreItems={() => fetchOtherData()}
        />
      );
    }
  }

  if (loader) {
    return <CNewsLoader web={(window.innerWidth >= 600).toString()} />;
  }

  return (
    <div className="MainContainer">
      <Header
        isSubScreen={true}
        onGoback={true}
        onBack={() => {
          if (_.includes(window.location.search, "&shared")) {
            history.push("/rate");
          } else {
            history.goBack();
          }
        }}
      />
      {!_.isEmpty(newsData) ? (
        <div
          className="CommonContainer"
          style={{ height: "calc(100% - 65px)" }}
        >
          <div className="newsMaindiv" id="newscommentid">
            <div className="newssubmaindiv">
              <div>
                <AMPAutoAd />
              </div>
              <img
                loading="lazy"
                className="image"
                src={newsData?.main_img}
                alt={"NewsImage"}
              />

              <div className="newsinfo">
                {!_.isEmpty(newsData?.created_at) ? (
                  <span className="posteddate">
                    {getWords("POSTED_ON")} {newsData?.created_at}
                  </span>
                ) : null}
                <span className="articaltitle">{newsData?.title}</span>
                <span className="newsarticle">
                  {renderHTML(_.toString(newsData?.body))}
                </span>
                <div id="down" className="iconscontainer">
                  <IconButton
                    onClick={() => {
                      // if (!checkIsUserLogin) {
                      //   setLoginModal(true);
                      // } else {
                      //   setShowCommentBool(true);
                      // }
                      // setShowCommentBool(true);
                    }}
                    style={{ padding: 5, marginRight: 10 }}
                  >
                    <a
                      href
                      // onClick={() => scrolltoshowcomments()}
                      style={{ display: "flex" }}
                    >
                      <img
                        loading="lazy"
                        src={chat}
                        className="likesharecommenticons"
                        style={{ cursor: "pointer" }}
                        alt={"CommentIcon"}
                      />
                    </a>
                  </IconButton>
                  <IconButton
                    onClick={() => {
                      if (!checkIsUserLogin) {
                        setLoginModal(true);
                      } else {
                        likeNew();
                      }
                    }}
                    style={{
                      padding: 5,
                      marginRight: 10,
                      position: "relative",
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
                      loading="lazy"
                      src={newsData?.is_like ? like : likeOutLine}
                      className="likesharecommenticons"
                      style={{ cursor: "pointer" }}
                      alt={"LikeIcon"}
                    />
                  </IconButton>
                  {!checkIsUserLogin ? (
                    <IconButton
                      onClick={() => {
                        setLoginModal(true);
                      }}
                      style={{ padding: 5 }}
                    >
                      <img
                        loading="lazy"
                        src={share}
                        className="likesharecommenticons"
                        style={{ cursor: "pointer" }}
                        alt={"ShareIcon"}
                      />
                    </IconButton>
                  ) : (
                    <RWebShare
                      data={{
                        text: `${selectedNews?.small_description}`,
                        url: `${window.location}&shared`,
                        title: "fanratingweb.com",
                      }}
                      onClick={() => console.log("shared successfully!")}
                    >
                      <IconButton style={{ padding: 5 }}>
                        <img
                          loading="lazy"
                          src={share}
                          className="likesharecommenticons"
                          style={{ cursor: "pointer" }}
                          alt={"ShareIcon"}
                        />
                      </IconButton>
                    </RWebShare>
                  )}
                </div>

                <div style={{ margin: "0px 0px 20px 0px" }}>
                  <span className="liketext">
                    {/* {newsData?.total_likes && Number(newsData?.total_likes)
                      ? `${newsData.total_likes} ${getWords("LIKES")}`
                      : getWords("Be_First_to_like")} */}
                  </span>
                </div>

                <div style={{ marginBottom: 20 }}>
                  <OutlinedInput
                    className="commentinputbox"
                    placeholder={getWords("ADD_COMMENT")}
                    id="comment"
                    type={"text"}
                    ref={commentRef}
                    onChange={
                      cLoad
                        ? null
                        : (val) => {
                          setcommenttext(val.target.value);
                        }
                    }
                    onKeyPress={(e) => {
                      handleKeyEnter1(e);
                    }}
                    value={commenttext}
                    endAdornment={
                      <InputAdornment position="end">
                        <IconButton
                          style={{ position: "relative" }}
                          onClick={
                            cLoad
                              ? null
                              : () => {
                                if (!checkIsUserLogin) {
                                  setLoginModal(true);
                                } else {
                                  addCommentProcess();
                                }
                              }
                          }
                        >
                          <div
                            id="coinanime2"
                            style={{
                              position: "absolute",
                              top: -30,
                              display: isearnedcoincomment ? "unset" : "none",
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
                          {cLoad ? (
                            <CircularProgress
                              style={{
                                width: 15,
                                height: 15,
                                color: "#ed0f1b",
                              }}
                            />
                          ) : (
                            <SendIcon
                              style={{ color: "#ed0f1b", cursor: "pointer" }}
                            />
                          )}
                        </IconButton>
                      </InputAdornment>
                    }
                  />
                </div>
                {renderCommentList()}
                <div
                  style={{
                    width: "100%",
                    display: "flex",
                    justifyContent: "center",
                  }}
                >
                  {commentList?.pagination?.isMore && iscommentlast ? (
                    <div
                      style={{
                        position: "fixed",
                        bottom: 0,
                        backgroundColor: "#fff",
                        width: "100%",
                        display: "flex",
                        justifyContent: "center",
                      }}
                    >
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
              </div>
            </div>
          </div>
        </div>
      ) : (
        <CNoData
          message={getWords("SORRY_NO_DATA_FOUND")}
          hasfooter={true}
          hasheader={true}
        />
      )}
      {renderLoginModal()}
      {renderSignUpModal()}
      {renderForgotPWDModal()}
      {renderSuccessModal()}
      {renderAlert()}
      <NotificationPopup />
    </div>
  );
}

export default News;
