/* eslint-disable react-hooks/exhaustive-deps */
import { useHistory, useLocation } from "react-router-dom";
import CardContent from "@material-ui/core/CardContent";
import { isMacOs, isSafari } from "react-device-detect";
import { useDispatch, useSelector } from "react-redux";
import { makeStyles } from "@material-ui/core/styles";
import React, { useState, useEffect } from "react";
import Card from "@material-ui/core/Card";
import firebase from "firebase/app";
import "firebase/messaging";
import _ from "lodash";
import "./styles.scss";
import {
  getWords,
  isUserLogin,
  getTeamListData,
  addAnalyticsEvent,
  getNotificationBadge,
  sendFCMTokenToServer,
  checkSurveyQuizIsEnable,
  getLeagueListData,
} from "../../commonFunctions";
import "../../Styles/common.scss";
import Header from "../../Components/Header";
import { Setting } from "../../Utils/Setting";
import CButton from "../../Components/CButton";
import { RateFilterTab } from "../../staticData";
import BottomTab from "../../Components/BottomTab";
import { getApiData } from "../../Utils/APIHelper";
import CAlert from "../../Components/CAlert/index";
import CNoData from "../../Components/CNoData/index";
import AMPAutoAd from "../../Components/Ads/AMPAutoAd";
import LoginModal from "../../Modals/LoginModal/index";
import SurveyModal from "../../Modals/SurveyModal/index";
import SignUpModal from "../../Modals/SignUpModal/index";
import CRateLoader from "../../Loaders/CRateLoader/index";
import SuccessModal from "../../Modals/SuccessModal/index";
import authActions from "../../Redux/reducers/auth/actions";
import InstallAppTutorial from "../../Modals/InstallAppTutorial";
import NotificationPopup from "../../Components/NotificationPopup";
import notificationTone from "../../Assets/Tone/notificationTone.mp3";
import ForgotPasswordModal from "../../Modals/ForgotPasswordModal/index";
import { askForPermissionToReceiveNotifications } from "../../push-notification";

const {
  setUserData,
  setNotiData,
  setSelectedTab,
  setUserReferenceCode,
  displayNotificationPopUp,
} = authActions;

const useStyles = makeStyles({
  root: {
    width:
      window.innerWidth >= 600 ? 450 : window.innerWidth >= 350 ? 350 : 300,
    paddingBottom: 8,
  },
  bullet: {
    display: "inline-block",
    margin: "0 2px",
    transform: "scale(0.8)",
  },
  title: {
    fontSize: 14,
  },
  pos: {
    marginBottom: 12,
  },
});

const RateNew = () => {
  const classes = useStyles();
  const history = useHistory();
  const location = useLocation();
  const dispatch = useDispatch();

  const { userdata } = useSelector((state) => state.auth);
  const [loader, setLoader] = useState(false);

  const [loginModal, setLoginModal] = useState(false);
  const [signUpModal, setSignUpModal] = useState(false);
  const [successModal, setSuccessModal] = useState(false);
  const [forgorPwdModal, setForgotPwdModal] = useState(false);
  const [defaultTab, setDefaultTab] = useState(RateFilterTab[0]);

  const [matchList, setMatchList] = useState({});
  const [surveyModal, setSurveyModal] = useState(false);
  const [buyMatchData, setBuyMatchData] = useState({});

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [photoUpdated, setPhotoUpdated] = useState(false);

  const checkIsUserLogin = isUserLogin();

  const [currentTimeStemp, setCurrentTimeStemp] = useState(
    Math.floor(new Date() / 1000)
  );

  useEffect(() => {
    recivedForegroundNotifications();
  }, []);

  useEffect(() => {
    document.title = Setting.page_name.RATE;
  }, []);

  useEffect(() => {
    dispatch(setSelectedTab(1));
    isGetReferEarnToken();
    askForPermissionToReceiveNotifications();
    // getTeamListData();
    getLeagueListData();
  }, []);

  useEffect(() => {
    if (checkIsUserLogin) {
      getMatchList("user");
      setTimeout(() => {
        checkSurveyQuizIsEnable();
      }, 2000);
    } else {
      getMatchList("guest");
    }
  }, [userdata, defaultTab]);

  useEffect(() => {
    setTimeout(() => {
      setCurrentTimeStemp(Math.floor(new Date() / 1000));
    }, 1000);
  }, [currentTimeStemp]);

  useEffect(() => {
    if (photoUpdated) {
      showAlert(true, getWords("SUCCESS"), getWords("PROFILE_UPDATED"), true);
    }
  }, [photoUpdated]);

  // check refer and earn tokens
  function isGetReferEarnToken() {
    const referenceCode =
      location && location.search && !_.isEmpty(location.search)
        ? _.toString(location.search).substring(1)
        : "";

    if (_.isString(referenceCode) && !_.isEmpty(referenceCode)) {
      dispatch(setUserReferenceCode(referenceCode));
    }
  }

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
        }}
        onOkay={() => {
          setAlertOpen(false);
          setSurveyModal(false);
          setBuyMatchData({});
          setPhotoUpdated(false);
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  // get match list api call
  async function getMatchList(str) {
    setLoader(true);
    try {
      let endPoint = "";
      let response = null;

      if (str === "user") {
        const userToken = `Bearer ${userdata?.access_token}`;
        const header = {
          Authorization: userToken,
        };
        if (defaultTab?.id === 2) {
          endPoint = `${Setting.endpoints.matches_for_user}?for=past`;
        } else {
          endPoint = Setting.endpoints.matches_for_user;
        }

        response = await getApiData(endPoint, "GET", null, header);
        addAnalyticsEvent("Rate_Event", true);
      } else {
        endPoint = Setting.endpoints.matches_for_guest;
        response = await getApiData(endPoint, "GET", null);
        addAnalyticsEvent("Rate_Event", {
          user_name: "Guest User",
        });
      }

      if (response && response.status && response.status === true) {
        if (response && response.data) {
          setMatchList(response.data);
          setLoader(false);
        } else {
          setLoader(false);
        }
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

  // foreground notification
  function recivedForegroundNotifications() {
    if (firebase.messaging.isSupported()) {
      const pushTone = new Audio(notificationTone);
      const messaging = firebase.messaging();

      messaging.onMessage((payload) => {
        const notiData = payload?.data;
        dispatch(setNotiData(notiData));
        pushTone.play();
        dispatch(displayNotificationPopUp(true));
        getNotificationBadge();
        setTimeout(() => {
          pushTone.pause();
          dispatch(displayNotificationPopUp(false));
        }, 4000);
      });
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
        handleClose={(uData) => {
          if (_.isObject(uData) && !_.isEmpty(uData)) {
            dispatch(setUserData(uData));
            setTimeout(() => {
              sendFCMTokenToServer();
              checkSurveyQuizIsEnable();
            }, 2000);
            addAnalyticsEvent("Login_Event", true);
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
          setSignUpModal(false);
          if (_.isObject(uData) && !_.isEmpty(uData)) {
            dispatch(setUserData(uData));
            setTimeout(() => {
              sendFCMTokenToServer();
              checkSurveyQuizIsEnable();
            }, 2000);
          }
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

  // display tabs
  const renderTabs = () => {
    return (
      <div className="tabstyleNew">
        {RateFilterTab.map((obj, index) => {
          const checkUserLogin = isUserLogin();
          return (
            <div
              key={index}
              className="tabbuttonstyleNew"
              style={{
                borderBottom: `2px solid ${obj.id === defaultTab.id ? "#ED0F1B" : "hsl(0deg 0% 96%)"
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
                if (!checkUserLogin && index === 1) {
                  setLoginModal(true);
                } else {
                  setDefaultTab(obj);
                }
              }}
            >
              <span className="tabbuttontextNew">{getWords(obj.title)}</span>
            </div>
          );
        })}
      </div>
    );
  };

  // display fav team
  const renderFavTeam = () => {
    const isVoteEnable = matchList?.first_match?.is_vote_enabled;
    const isAlreadyVoted = matchList?.first_match?.is_already_voted;
    const isLock = matchList?.first_match?.lock;

    const btnStyle = {
      width: window.innerWidth >= 600 ? 100 : 80,
      bottom: 30,
      position: "relative",
      backgroundColor: "#ed0f1b",
    };

    const disableGreyBtn = {
      width: window.innerWidth >= 600 ? 100 : 80,
      bottom: 30,
      position: "relative",
      backgroundColor: "#AEAEAE",
    };

    const viewBtnStyle = {
      width:
        window.innerWidth >= 600 ? 100 : window.innerWidth >= 300 ? 80 : 50,
      bottom: 20,
      position: "relative",
    };

    return !_.isEmpty(matchList) &&
      _.isObject(matchList.first_match) &&
      !_.isEmpty(matchList.first_match) ? (
      <div className="favTeamContainer">
        <Card elevation={5} className={classes.root}>
          <CardContent>
            <div className="dateTimeDiv">
              <span className="favteamTextNew">
                {matchList?.first_match?.match_date}
              </span>
              {window.innerWidth >= 600 ? (
                <span
                  className="favteamTextNew"
                  style={{
                    marginLeft: -55,
                  }}
                >
                  {matchList?.first_match?.league_name}
                </span>
              ) : null}
              <span className="favteamTextNew">
                {matchList?.first_match?.match_time}
              </span>
            </div>

            <div className="dividerStyleNew" />
            {window.innerWidth >= 600 ? null : (
              <div className="RNDiv1">
                <span className="favteamTextNew">
                  {matchList?.first_match?.league_name}
                </span>
              </div>
            )}
            <div
              className="dateTimeDiv"
              style={{
                marginTop: 10,
              }}
            >
              <div className="columnStyle">
                <div
                  style={{
                    width:
                      window.innerWidth >= 600
                        ? 420
                        : window.innerWidth >= 350
                          ? 325
                          : 270,
                    // backgroundColor: "rosy/brown",
                    display: "flex",
                    flexDirection: "row",
                    justifyContent: "space-between",
                    alignItems: "center",
                  }}
                >
                  <img
                    loading="lazy"
                    className="favTeamFlag"
                    src={matchList?.first_match?.logo_of_home}
                    alt={"favTeam"}
                  />

                  <span className="scoreStyleNew">
                    {!_.isNull(matchList?.first_match?.goal_of_home_team)
                      ? matchList?.first_match?.goal_of_home_team
                      : 0}{" "}
                    :{" "}
                    {!_.isNull(matchList?.first_match?.goal_of_away_team)
                      ? matchList?.first_match?.goal_of_away_team
                      : 0}
                  </span>

                  <img
                    loading="lazy"
                    className="favTeamFlag"
                    src={matchList?.first_match?.logo_of_away}
                    alt={"FavTeamFlag"}
                  />
                </div>

                <div
                  style={{
                    width:
                      window.innerWidth >= 600
                        ? 420
                        : window.innerWidth >= 350
                          ? 325
                          : 270,
                    display: "flex",
                    flexDirection: "row",
                    justifyContent: "space-between",
                    alignItems: "center",
                  }}
                >
                  <span className="teamNameStyleNew">
                    {matchList?.first_match?.name_of_home}
                  </span>

                  <div
                    style={{
                      display: "flex",
                      flexDirection: "column",
                      width: window.innerWidth >= 450 ? "none" : 220,
                    }}
                  >
                    {matchList?.first_match?.match_url === false ? null : (
                      <CButton
                        handleBtnClick={() => {
                          const MatchURL = "https://livesport365.net/all-sport?match_type=1"
                          //  matchList?.first_match?.match_url;
                          if (checkIsUserLogin === false) {
                            setLoginModal(true);
                          } else {
                            window.open(MatchURL);
                          }
                        }}
                        outlined
                        buttonText={getWords("WATCH_ON")}
                        buttonStyle={{
                          width: window.innerWidth >= 450 ? "none" : 140,
                          padding: "0px 5px",
                          border: "2px solid #ed0f1b",
                          borderLeft: "3px solid #ed0f1b",
                          borderRight: "3px solid #ed0f1b",
                        }}
                        bungeeText
                        btntextfontSize={window.innerWidth > 400 ? 15 : 11}
                      />
                    )}

                    <CButton
                      handleBtnClick={() => {
                        const MatchURL = "https://www.footballticketnet.com/?gclid=Cj0KCQiAzeSdBhC4ARIsACj36uFFdU-nL8X9p8kVP8Z6vPz1L5WJ_VEebUanOOS5kdmGzNhfHONdBTEaAkeMEALw_wcB"
                        // matchList?.first_match?.match_ground_url;
                        if (
                          matchList?.first_match?.match_ground_url === false
                        ) {
                          return true;
                        } else {
                          if (checkIsUserLogin === false) {
                            setLoginModal(true);
                          } else {
                            window.open(MatchURL);
                          }
                        }
                      }}
                      buttonText={
                        matchList?.first_match?.match_ground_url === false
                          ? `${matchList?.first_match?.match_ground}`
                          : getWords("GO_TO_SAN_SIRO_STADIUM")
                      }
                      buttonStyle={{
                        width: window.innerWidth >= 450 ? "none" : 140,
                        padding: "0px 5px",
                        top: -3,
                        margin: "10px 3px 0px",
                        cursor:
                          matchList?.first_match?.match_ground_url === false
                            ? "unset"
                            : "pointer",
                      }}
                      bungeeText
                      btntextfontSize={window.innerWidth >= 450 ? 15 : 14}
                    />
                  </div>

                  <span className="teamNameStyleNew">
                    {matchList?.first_match?.name_of_away}
                  </span>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        {isVoteEnable ? (
          isAlreadyVoted ? (
            <CButton // View Button
              buttonStyle={viewBtnStyle}
              outlined={true}
              bungeeText={true}
              buttonText={getWords("VIEW")}
              handleBtnClick={() => {
                buttonOnClickEvent("VIEW", matchList?.first_match);
              }}
            />
          ) : isLock ? (
            <CButton // Unlock Button
              buttonStyle={btnStyle}
              bungeeText={true}
              buttonText={getWords("UNLOCK")}
              handleBtnClick={() => {
                buttonOnClickEvent("UNLOCK", matchList?.first_match);
              }}
            />
          ) : (
            <CButton // Vote Button
              buttonStyle={btnStyle}
              bungeeText={true}
              buttonText={getWords("VOTE")}
              handleBtnClick={() => {
                buttonOnClickEvent("VOTE", matchList?.first_match);
              }}
            />
          )
        ) : (
          <CButton // Disbale Vote Button
            buttonStyle={disableGreyBtn}
            bungeeText={true}
            buttonText={getWords("VOTE")}
            handleBtnClick={() => {
              // return null;
              if (checkIsUserLogin === false) {
                setLoginModal(true);
              } else {
                showAlert(
                  true,
                  getWords("UPCOMING_MATCH"),
                  getWords("VOTE_ENABLED")
                );
              }
            }}
          />
        )}
      </div>
    ) : null;
  };

  // button click events
  function buttonOnClickEvent(type, item) {
    const isAlreadyVoted = item?.is_already_voted;

    if (checkIsUserLogin === false) {
      setLoginModal(true);
    } else {
      if (type === "UNLOCK") {
        setSurveyModal(true);
        setBuyMatchData(item);
      } else if (type === "VOTE" || type === "VIEW") {
        history.push({
          pathname: "/team-details",
          state: {
            id: 1,
            listData: item,
            isPastMatch: isAlreadyVoted,
            fromTabTwo: false,
          },
        });
      }
    }
  }

  // display unlock, view and vote buttons
  function renderButtons(item) {
    const isVoteEnable = item?.is_vote_enabled;
    const isAlreadyVoted = item?.is_already_voted;
    const isLock = item?.lock;

    const btnStyle = {
      width: window.innerWidth >= 600 ? 100 : 80,
      bottom: 5,
      padding: window.innerWidth >= 600 ? 10 : 5,
      backgroundColor: "#ed0f1b",
    };

    const disableGreyBtn = {
      width: window.innerWidth >= 600 ? 100 : 80,
      bottom: 5,
      padding: window.innerWidth >= 600 ? 10 : 5,
      backgroundColor: "#AEAEAE",
    };

    const viewBtnStyle = {
      width:
        window.innerWidth >= 600 ? 100 : window.innerWidth >= 300 ? 80 : 50,
      bottom: window.innerWidth >= 600 ? 20 : 5,
      padding: window.innerWidth >= 600 ? 10 : 5,
    };

    if (isVoteEnable) {
      if (isAlreadyVoted) {
        return (
          <CButton // View Button
            buttonStyle={viewBtnStyle}
            outlined={true}
            bungeeText={true}
            buttonText={getWords("VIEW")}
            handleBtnClick={() => {
              buttonOnClickEvent("VIEW", item);
            }}
          />
        );
      } else {
        if (isLock) {
          return (
            <CButton // Unlock Button
              buttonStyle={btnStyle}
              bungeeText={true}
              buttonText={getWords("UNLOCK")}
              handleBtnClick={() => {
                buttonOnClickEvent("UNLOCK", item);
              }}
            />
          );
        } else {
          return (
            <CButton // Vote Button
              buttonStyle={btnStyle}
              bungeeText={true}
              buttonText={getWords("VOTE")}
              handleBtnClick={() => {
                buttonOnClickEvent("VOTE", item);
              }}
            />
          );
        }
      }
    } else {
      return (
        <CButton // Disbale Vote Button
          buttonStyle={disableGreyBtn}
          bungeeText={true}
          buttonText={getWords("VOTE")}
          handleBtnClick={() => {
            // return null;
            if (checkIsUserLogin === false) {
              setLoginModal(true);
            } else {
              showAlert(
                true,
                getWords("UPCOMING_MATCH"),
                getWords("VOTE_ENABLED")
              );
            }
          }}
        />
      );
    }
  }

  // display other teams
  const renderOtherTeams = (item, index) => {
    const matchStartAt = item?.match_timestamp;
    const remainingSeconds = matchStartAt - currentTimeStemp;

    const isDisplayBlinkingDot =
      remainingSeconds <= 0 && item?.is_vote_enabled === false;
    return (
      <div key={index} style={{ paddignTop: "20px" }}>
        <hr className="RNhrStyle" />
        <div className="RNDiv2">
          <span
            style={{
              fontFamily: "seguibl",
              fontSize: 16,
              fontWeight: 600,
              color: "#484848",
              marginLeft: window.innerWidth >= 450 ? 0 : 7,
            }}
          >{`${item?.match_datetime}`}</span>
        </div>
        <div className="otherTeamDtlsContainer">
          <div className="otherTeamDtlsContainer1">
            <div className={"otherTeamDtls"}>
              <img
                loading="lazy"
                className="otherTeamFlag"
                src={item?.logo_of_home}
                alt={"otherTeamImg"}
              />
              <span className="otherTeamName">
                {item?.name_of_home.length > 11 &&
                  window.innerWidth <= 450 &&
                  window.innerWidth >= 250
                  ? `${item?.name_of_home.slice(0, 7)}...`
                  : item?.name_of_home}
              </span>
            </div>
            <div className={"otherTeamDtls"}>
              <img
                loading="lazy"
                className="otherTeamFlag"
                src={item?.logo_of_away}
                alt={"otherTeamFlagImg"}
              />
              <span className="otherTeamName">
                {item?.name_of_away.length > 11 &&
                  window.innerWidth <= 450 &&
                  window.innerWidth >= 250
                  ? `${item?.name_of_away.slice(0, 7)}...`
                  : item?.name_of_away}
              </span>
            </div>
          </div>
          <div>
            {isDisplayBlinkingDot ? (
              <div
                style={{
                  display: "flex",
                  justifyContent: "flex-end",
                }}
                className="animatequize"
              >
                <div
                  style={{
                    height: 10,
                    width: 10,
                    backgroundColor: "#ED0F18",
                    borderRadius: 20,
                  }}
                />
              </div>
            ) : null}
            <span className="otherTeamScoreNew">
              {!_.isNull(item?.goal_of_home_team) ? item?.goal_of_home_team : 0}{" "}
              :{" "}
              {!_.isNull(item?.goal_of_away_team) ? item?.goal_of_away_team : 0}
            </span>
          </div>
          <div>{renderButtons(item)}</div>
        </div>
      </div>
    );
  };

  // display concluse data
  const renderConcluseData = (item, index) => {
    const btnStyle = {
      width: window.innerWidth >= 600 ? 100 : 80,
      bottom: 5,
      padding: window.innerWidth >= 600 ? 10 : 5,
    };

    return (
      <div key={index} style={{ paddignTop: "20px" }}>
        <span
          style={{
            fontFamily: "seguibl",
            fontSize: 16,
            fontWeight: 600,
            color: "#484848",
          }}
        >{`${item?.match_datetime}`}</span>
        <div className="otherTeamDtlsContainer">
          <div className="otherTeamDtlsContainer1">
            <div className={"otherTeamDtls"}>
              <img
                loading="lazy"
                className="otherTeamFlag"
                src={item?.logo_of_home}
                alt={"otherTeamImg"}
              />
              <span className="otherTeamName">
                {item?.name_of_home.length > 11 &&
                  window.innerWidth <= 450 &&
                  window.innerWidth >= 250
                  ? `${item?.name_of_home.slice(0, 7)}...`
                  : item?.name_of_home}
              </span>
            </div>
            <div className={"otherTeamDtls"}>
              <img
                loading="lazy"
                className="otherTeamFlag"
                src={item?.logo_of_away}
                alt={"otherTeamFlagImg"}
              />
              <span className="otherTeamName">
                {item?.name_of_away.length > 11 &&
                  window.innerWidth <= 450 &&
                  window.innerWidth >= 250
                  ? `${item?.name_of_away.slice(0, 7)}...`
                  : item?.name_of_away}
              </span>
            </div>
          </div>
          <div>
            <span className="otherTeamScoreNew">
              {!_.isNull(item?.goal_of_home_team) ? item?.goal_of_home_team : 0}{" "}
              :{" "}
              {!_.isNull(item?.goal_of_away_team) ? item?.goal_of_away_team : 0}
            </span>
          </div>
          <CButton
            buttonStyle={btnStyle}
            outlined={true}
            bungeeText={true}
            buttonText={getWords("VIEW")}
            handleBtnClick={() => {
              if (checkIsUserLogin === false) {
                setLoginModal(true);
              } else {
                history.push({
                  pathname: "/team-details",
                  state: {
                    id: 1,
                    listData: item,
                    isPastMatch: item?.is_already_voted,
                    fromTabTwo: true,
                  },
                });
              }
            }}
          />
        </div>
        <hr
          style={{
            width: "100%",
            height: 1,
            backgroundColor: "#E8E8E8",
            border: "0px",
            margin: "20px 0px",
          }}
        />
      </div>
    );
  };

  // display no data found
  const renderNoDataFound = () => {
    return (
      <CNoData
        message={getWords("SORRY_NO_DATA_FOUND")}
        hasheader={true}
        hasfooter={true}
        otherStyle={{
          display: "flex",
          alignItem: "center",
          justifyContent: "center",
          width: "100%",
          height: "100%",
        }}
      />
    );
  };

  // display other tab data
  function renderOtherTabData() {

    const concluseData = matchList?.match_list;
    if (_.isArray(concluseData) && !_.isEmpty(concluseData)) {
      return (
        <div
          style={{
            overflow: "auto",
            marginTop: "20px",
            padding: "0px 10px",
          }}
        >
          {concluseData.map((item, index) => {
            return renderConcluseData(item, index);
          })}
        </div>
      );
    }

    return renderNoDataFound();
  }

  // display first tab data
  function renderFirstTabData() {
    console.log(">>>>>>>>>>>>>>>>>>>>>>")
    console.log(matchList)
    if (_.isObject(matchList) && !_.isEmpty(matchList)) {
      if (
        _.isArray(matchList?.match_list)
        // &&
        // !_.isEmpty(matchList?.match_list)
      ) {
        return (
          <div
            style={{ overflow: "auto", marginTop: "20px", padding: "0px 10px" }}
          >
            {renderFavTeam()}
            {!_.isEmpty(matchList) &&
              _.isObject(matchList.first_match) &&
              !_.isEmpty(matchList.first_match) ? (
              <hr
                style={{
                  width: "100%",
                  height: 1,
                  backgroundColor: "#E8E8E8",
                  border: "0px",
                  margin: "20px 0px",
                }}
              />
            ) : null}

            <span className="voteOtherMatchesTitle">
              {getWords("VOTE_other_matches")}
            </span>

            <div
              style={{
                overflow: "scroll",
                paddingLeft: 5,
                paddingRight: 5,
              }}
            >
              {matchList?.match_list?.map((item, index) => {
                return renderOtherTeams(item, index);
              })}
            </div>
          </div>
        );
      }
    }
    return renderNoDataFound();
  }

  // display survey modal
  function renderSurveyModal() {
    return (
      <SurveyModal
        from={"RateScreen"}
        surveyModal={surveyModal}
        handleClose={() => {
          setSurveyModal(false);
          setBuyMatchData({});
        }}
        onSuccess={() => {
          setSurveyModal(false);
          setBuyMatchData({});
          getMatchList("user");
        }}
        errorData={(msg) => {
          if (msg === "WAR") {
            showAlert(
              true,
              getWords("WARNING"),
              getWords("Something_went_wrong")
            );
          } else {
            showAlert(true, getWords("OOPS"), msg);
          }
        }}
        buyMatchData={buyMatchData}
      />
    );
  }

  if (loader) {
    return <CRateLoader web={(window.innerWidth >= 600).toString()} />;
  }

  return (
    <div className="mainRAteNewContainer MainContainer">
      <InstallAppTutorial />
      <Header
        profileUpdated={(bool, message) => {
          setPhotoUpdated(bool);
        }}
      />
      <div className="CommonContainer" style={{ height: "calc(100% - 130px)" }}>
        <div className="rateNewmaindiv">
          <span
            style={{
              paddingTop: 15,
            }}
            className="rateNewtext"
          >
            {getWords("RATE")}
          </span>
          <div>
            <AMPAutoAd />
          </div>
          {renderTabs()}
          {defaultTab.id === 1 ? renderFirstTabData() : renderOtherTabData()}
        </div>
      </div>
      <BottomTab />
      {renderLoginModal()}
      {renderSignUpModal()}
      {renderForgotPWDModal()}
      {renderSuccessModal()}
      {renderAlert()}
      {renderSurveyModal()}
      <NotificationPopup />
    </div>
  );
};

export default RateNew;
