import React, { useEffect, useState } from "react";
import PropTypes from "prop-types";
import _ from "lodash";
import { useDispatch, useSelector } from "react-redux";
import { useHistory } from "react-router-dom";
import NotificationsIcon from "@material-ui/icons/Notifications";
import { makeStyles } from "@material-ui/core/styles";
import PopupState, { bindTrigger, bindMenu } from "material-ui-popup-state";
import Menu from "@material-ui/core/Menu";
import MenuItem from '@material-ui/core/MenuItem';
import Guarantee from "../../Assets/Images/guarantee.png";
import LoginModal from "../../Modals/LoginModal/index";
import QuizModal from "../../Modals/QuizModal/index";
import SurveyModal from "../../Modals/SurveyModal/index";
import SuccessModal from "../../Modals/SuccessModal/index";
import EditProfilePic from "../../Modals/EditProfilePic/index";
import SignUpModal from "../../Modals/SignUpModal/index";
import ForgotPasswordModal from "../../Modals/ForgotPasswordModal/index";
import APPICON from "../../Assets/Images/IMG_1136.webp";

import EN from 'country-flag-icons/react/3x2/GB';
import IT from 'country-flag-icons/react/3x2/IT';
import FR from 'country-flag-icons/react/3x2/FR';
import SP from 'country-flag-icons/react/3x2/ES';
import GE from 'country-flag-icons/react/3x2/DE';
import CH from 'country-flag-icons/react/3x2/CN';
import AR from 'country-flag-icons/react/3x2/AE';


import {
  getWords,
  isUserLogin,
  isUser18Plus,
  logoutProcess,
  addAnalyticsEvent,
  sendFCMTokenToServer,
  checkSurveyQuizIsEnable,
  refreshUserData,
} from "../../commonFunctions";
import MenuIcon from "../../Assets/Images/menu_new.png";
import Camera from "../../Assets/Images/camera.png";
import QuizIcon from "../../Assets/Images/quiz.png";
import SurveyIcon from "../../Assets/Images/survey.png";
import KeyboardBackspaceIcon from "@material-ui/icons/KeyboardBackspace";
import ArrowForwardIosIcon from "@material-ui/icons/ArrowForwardIos";
import authActions from "../../Redux/reducers/auth/actions";
import Switch from "@material-ui/core/Switch";
import { DrawerData } from "../../staticData";
import Drawer from "@material-ui/core/Drawer";
import CAlert from "../../Components/CAlert/index";
import CButton from "../CButton/index";
import "./styles.scss";
import "../../Styles/common.scss";
import {
  LANG_US,
  LANG_IT,
  LANG_SP,
  LANG_GE,
  LANG_CH,
  LANG_AR,
  LANG_FR,
  Setting,
} from "../../Utils/Setting";
import { getApiData, getAPIProgressData } from "../../Utils/APIHelper";
import CRequestLoader from "../../Loaders/CRequestLoader/index";
import { withStyles } from "@material-ui/core/styles";
import { isAndroid, isIOS } from "react-device-detect";
import CProgressbar from "../CProgressbar/index";
import TransferComplete from "../../Modals/TransferComplete";
import LevelIcon from "../../Assets/Images/levelIcon.png";
import Ranking from "../../Assets/Images/ranking.png";
import euro from "../../Assets/Images/fan_coins.png";
import AddIcon from "../../Assets/Images/add.png";

const { setUserData, setIsDisplayInstallPWAPopup } = authActions;

const AntSwitch = withStyles((theme) => ({
  root: {
    width: 40,
    height: 20,
    padding: 0,
    display: "flex",
  },
  switchBase: {
    padding: 2,
    cursor: "pointer !important",
    "&$checked": {
      transform: "translateX(20px)",
      color: theme.palette.common.white,
      "& + $track": {
        opacity: 1,
        backgroundColor: "#ED0F18 !important",
      },
    },
  },
  thumb: {
    width: 16,
    height: 16,
    boxShadow: "none",
  },
  track: {
    borderRadius: 40 / 2,
    opacity: 1,
    backgroundColor: "#ddd !important",
    cursor: "pointer",
  },
  checked: {},
}))(Switch);

const useStyles = makeStyles((theme) => ({
  drawerPaper: {
    width: window.innerWidth > 640 ? 330 : 270,
    top: "65px",
    height: "calc(100% - 65px)",
    marginLeft:
      window.innerWidth >= 1279 ? "14%" : window.innerWidth >= 639 ? "7%" : 0,
    display: "flex",
  },
  drawerContainer: {
    overflow: "auto",
  },
  menuItem: {
    minHeight: 0,
    paddingTop: 0,
    paddingBottom: 0,
  },
  iconsStyle: {
    width: window.innerWidth >= 550 ? "15px" : "10px",
    height: window.innerWidth >= 550 ? "15px" : "10px",
  },
  profileImg: {
    width: "100%",
    height: "100%",
    color: "#828282",
  },
  menuContainer: {
    marginTop: 50,
    width: 370,
  },
  menuItemContainer: {
    backgroundColor: "#FFFFFF",
  },
  cameraIcon: {
    width: window.innerWidth >= 350 ? 14 : 10,
    height: window.innerWidth >= 350 ? 14 : 10,
  },
  cameraDiv: {
    position: "absolute",
    bottom: window.innerWidth >= 350 ? -12 : -10,
    left: window.innerWidth >= 350 ? 22 : 14,
    border: "1px solid #ED0F1B",
    borderRadius: 50,
    padding: 4,
    height: window.innerWidth >= 350 ? 18 : 12,
    width: window.innerWidth >= 350 ? 18 : 12,
    backgroundColor: "#FFFFFF",
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
    cursor: "pointer",
  },
  menuMainDiv: {
    width: window.innerWidth >= 600 ? "400px" : "100%",
    display: "flex",
    alignItems: "center",
    marginBottom: 10,
    position: "relative",
  },
  nameDivStyle: {
    display: "flex",
    alignItems: "center",
    justifyContent: "flex-start",
  },
  nameStyle: {
    fontSize: window.innerWidth >= 350 ? 20 : 16,
    fontWeight: 600,
  },
  emailStyle: {
    fontSize: window.innerWidth >= 350 ? 16 : 12,
    fontWeight: 400,
  },
  guaranteeIcon: {
    width: window.innerWidth >= 350 ? 30 : 20,
    height: window.innerWidth >= 350 ? 30 : 20,
    marginLeft: 20,
  },
  marginLeftStyle: {
    marginLeft: 10,
  },
  userDetailsDiv: {
    display: "flex",
    justifyContent: "space-between",
    alignItems: "center",
    width: "100%",
    margin: "20px 0px",
  },
  iconStyle: {
    width: 20,
    height: 20,
  },
  dividerStyle: {
    height: 1,
    width: "100%",
    backgroundColor: "#E8E8E8",
    marginTop: 15,
    marginBottom: 15,
  },
  editProfileBtn: {
    backgroundColor: "#ED0F1B",
    border: "1px solid #ED0F1B",
    padding: 10,
    cursor: "pointer",
    width: "100%",
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
    marginRight: 4,
    borderRadius: "5px",
    // boxShadow:
    //   "0px 3px 3px -2px rgb(0 0 0 / 20%), 0px 3px 4px 0px rgb(0 0 0 / 14%), 0px 1px 8px 0px rgb(0 0 0 / 12%)",
  },
  signOutBtn: {
    backgroundColor: "#FFFFFF",
    border: "1px solid #ED0F1B",
    padding: 10,
    width: "100%",
    display: "flex",
    alignItems: "center",
    cursor: "pointer",
    justifyContent: "center",
    marginLeft: 4,
    borderRadius: "5px",
    // boxShadow: '0px 0px 2px 0.5px rgba(237, 15, 24, 1)',
    boxShadow:
      "0px 3px 3px -2px rgb(0 0 0 / 20%), 0px 3px 4px 0px rgb(0 0 0 / 14%), 0px 1px 8px 0px rgb(0 0 0 / 12%)",
  },
  whiteFont: {
    color: "#FFFFFF",
    fontWeight: "normal",
    fontFamily: "segoeui",
  },
  themeColor: {
    color: "#ED0F1B",
    fontWeight: "normal",
  },
  badgeStyle: {
    backgroundColor: "#484848",
    width: "24px",
    height: "24px",
    color: "#FFFFFF",
  },
}));

function Header(props) {
  const {
    isSubScreen,
    onGoback,
    onBack,
    removeBackArrow,
    startup,
    profileUpdated,
    addIcon,
  } = props;
  const history = useHistory();
  const classes = useStyles();
  const dispatch = useDispatch();
  const [signUpModal, setSignUpModal] = useState(false);
  const [forgorPwdModal, setForgotPwdModal] = useState(false);
  const [loginModal, setLoginModal] = useState(false);
  const [successModal, setSuccessModal] = useState(false);
  const [fromQuiz, setFrmQuiz] = useState(false);
  const [quizModal, setQuizModal] = useState(false);
  const [surveyModal, setSurveyModal] = useState(false);
  const [open, setOpen] = useState(false);
  const [editprofilepic, setEditProfilePic] = useState(false);
  const [eighteenplus, setEighteenPlus] = useState(isUser18Plus());
  const { userdata, badgeCount, logoutLoad, serveyQuizData } = useSelector(
    (state) => state.auth
  );
  const { language } = userdata;
  const isQuizEnable = serveyQuizData?.is_quiz_available;
  const isSurveyEnable = serveyQuizData?.is_survey_available;

  const [quizDetails, setQuizDetails] = useState({});
  const [surveyDetails, setSurveyDetails] = useState({});
  const [quizResult, setQuizResult] = useState({});
  const [fromSurvey, setFrmSurvey] = useState(false);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [loader, setLoader] = useState(false);
  const [callFunc, setCallFunction] = useState(false);
  const [displayAnim, setDisplayAnim] = useState(false);
  const [isearnedcoin, setIsEarnedcoin] = useState(false);
  const [isearnedcoinquiz, setIsEarnedcoinQuiz] = useState(false);

  const [displayView, setDisplay] = useState(false);

  const userIMG = userdata?.user_image;
  const userName = `${userdata?.firstname} ${userdata?.lastname}`;
  const userEmail = userdata?.email;
  const userToken = userdata?.token;
  const userPoint = userdata?.point;
  // const level = userdata?.level?.current_level;
  // console.log(language)
  const checkIsUserLogin = isUserLogin();

  const showAlert = (open, title, message, callFunction, display) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
    setCallFunction(callFunction);
    setDisplay(display);
  };

  useEffect(() => {
    setEighteenPlus(isUser18Plus());
    checkSurveyQuizIsEnable();
  }, []);

  function renderAlert() {
    return (
      <CAlert
        showCancel={callFunc}
        open={alertOpen}
        onClose={() => {
          setAlertOpen(false);
        }}
        onOkay={() => {
          setAlertOpen(false);
          // logout
          if (callFunc) {
            logoutProcess();
            setTimeout(() => {
              history.replace("/rate");
              setCallFunction(false);
            }, 1000);
          }
          // display animation for token when profile pic updated
          if (displayView) {
            if (isearnedcoin) {
              setTimeout(() => {
                setDisplayAnim(true);
              }, 1000);
              setTimeout(() => {
                setDisplayAnim(false);
                refreshUserData();
              }, 3000);
            }
          }
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  // quiz details api call
  const getQuizDetails = async () => {
    setLoader(true);
    const header = {
      authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = `${Setting.endpoints.quiz_details}`;
      const response = await getApiData(endPoint, "get", {}, header);
      if (response?.status) {
        setLoader(false);
        if (_.has(response?.data, "is_result") && response?.data?.is_result) {
          setSuccessModal(response?.data?.is_result);
          setFrmQuiz(response?.data?.is_result);
          setQuizResult(response?.data?.child_data);
        } else {
          addAnalyticsEvent("Quiz_Start_Event", true);
          setQuizDetails(response?.data?.child_data);
          setQuizModal(true);
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
  };

  // quiz result api call
  const getQuizResult = async () => {
    setLoader(true);
    const header = {
      authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = `${Setting.endpoints.quiz_result}`;
      const response = await getApiData(endPoint, "GET", {}, header);
      if (response?.status) {
        setLoader(false);
        const rData = response?.data;
        const udata = {
          user_name: userdata?.username,
          first_name: userdata?.firstname,
          last_name: userdata?.lastname,
          email: userdata?.email,
          user_Pic: userdata?.user_image,
          result_is_winner: response?.data?.is_winner,
        };
        addAnalyticsEvent("Quiz_Result_Event", udata);
        setQuizResult(rData);
        if (
          _.isObject(response?.data) &&
          !_.isEmpty(response?.data) &&
          _.has(response?.data, "is_animation") &&
          response?.data?.is_animation
        ) {
          setIsEarnedcoinQuiz(true);
        } else {
          setIsEarnedcoinQuiz(false);
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
  };

  // survey details api call
  const getSurveyDetails = async () => {
    setLoader(true);
    const header = {
      authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = `${Setting.endpoints.survey_details}`;
      const response = await getApiData(endPoint, "GET", {}, header);
      if (response?.status) {
        setLoader(false);
        if (_.has(response?.data, "is_result") && response?.data?.is_result) {
          setSuccessModal(response?.data?.is_result);
          setFrmQuiz(false);
          setFrmSurvey(response?.data?.is_result);
        } else {
          addAnalyticsEvent("Survey_Start_Event", true);
          setSurveyDetails(response?.data?.child_data);
          setSurveyModal(true);
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
  };

  // survey result api call survey_result
  const getSurveyResult = async () => {
    setLoader(true);
    const header = {
      authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = `${Setting.endpoints.survey_result}`;
      const response = await getApiData(endPoint, "GET", {}, header);
      if (response?.status) {
        const udata = {
          user_name: userdata?.username,
          first_name: userdata?.firstname,
          last_name: userdata?.lastname,
          email: userdata?.email,
          user_Pic: userdata?.user_image,
          earn_Token: response?.data?.earn_token,
        };
        addAnalyticsEvent("Survey_Result_Event", udata);
        setFrmQuiz(false);
        setLoader(false);
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
        setLoader(false);
        setIsEarnedcoin(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      setLoader(false);
      setIsEarnedcoin(false);
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  // update profile picture api call
  async function saveProfileProcess(fileData) {
    setLoader(true);

    const imgData = {
      "ImageForm[photo]": fileData,
    };

    try {
      let endPoint = Setting.endpoints.photo;
      const response = await getAPIProgressData(
        endPoint,
        "POST",
        imgData,
        true
      );

      if (response?.status) {
        const updatedUserData = response?.data;
        dispatch(setUserData(updatedUserData));
        setTimeout(() => {
          addAnalyticsEvent("Update_Profile_Picture_Event", true);
        }, 1000);

        setLoader(false);
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
        setTimeout(() => {
          setEditProfilePic(false);
          profileUpdated(true, response?.message);
          showAlert(true, getWords("SUCCESS"), response?.message, false, true);
        }, 500);
      } else {
        setLoader(false);
        setEditProfilePic(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setLoader(false);
      setEditProfilePic(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  // toggle drawer
  const toggleMenuBar = (e) => {
    if (e && e.preventDefault) {
      e.preventDefault();
    }
    setOpen(!open);
  };

  function mainHeader() {
    return (
      <header className="header">
        {startup ? (
          <div className="leftContainer" />
        ) : (
          <div className="leftContainer">
            {isSubScreen ? (
              <div className="headerBackIcon">
                {removeBackArrow ? null : (
                  <KeyboardBackspaceIcon
                    className="headerbackspaceiconstyle"
                    onClick={() => {
                      onGoback ? onBack() : history.goBack();
                    }}
                  />
                )}
              </div>
            ) : (
              <div
                style={{
                  display: "flex",
                  flexDirection: "row",
                }}
              >
                <div
                  style={{
                    display: "flex",
                    alignItems: "center",
                  }}
                  id="boxopen"
                  onClick={(e) => {
                    toggleMenuBar(e);
                    let boxopen = document.getElementById("boxopen");
                    boxopen.style.animation = "rotate .5s";
                    boxopen.style.webkitAnimation = "rotate .5s";
                  }}
                >
                  <img
                    style={{
                      color: "#FFFFFF",
                      width: window.innerWidth >= 550 ? "30px" : "25px",
                      height: window.innerWidth >= 550 ? "20px" : "15px",
                      cursor: "pointer",
                      marginRight: 15,
                    }}
                    src={MenuIcon}
                    alt={"MenuIcon"}
                    loading="lazy"
                  />
                </div>

                {checkIsUserLogin && isQuizEnable ? (
                  <div
                    style={{
                      display: "flex",
                      alignItems: "center",
                      paddingRight: "10px",
                    }}
                    onClick={() => {
                      getQuizDetails();
                    }}
                  >
                    {isAndroid || isIOS ? (
                      <div
                        style={{
                          border: "0.3px solid #FCFCFC",
                          borderRadius: 50,
                          alignItems: "center",
                          justifyContent: "center",
                          display: "flex",
                          padding: 3,
                          cursor: "pointer",
                          boxShadow:
                            "0px 3px 3px -2px rgb(0 0 0 / 20%), 0px 3px 4px 0px rgb(0 0 0 / 14%), 0px 1px 8px 0px rgb(0 0 0 / 12%)",
                        }}
                        className="animatequize"
                      >
                        <div
                          style={{
                            border: "1px solid #FFF",
                            borderRadius: 50,
                            alignItems: "center",
                            justifyContent: "center",
                            display: "flex",
                            padding: 5,
                          }}
                        >
                          <img
                            className={classes.iconsStyle}
                            src={QuizIcon}
                            alt={"QuizIcon"}
                            loading="lazy"
                          />
                        </div>
                      </div>
                    ) : (
                      <div
                        style={{
                          backgroundColor: "#ED0F30",
                          padding: "2px 5px",
                          borderRadius: "4px",
                          boxShadow:
                            "0px 3px 3px -2px rgb(0 0 0 / 20%), 0px 3px 4px 0px rgb(0 0 0 / 14%), 0px 1px 8px 0px rgb(0 0 0 / 12%)",
                        }}
                        className="animatequize"
                      >
                        <span className="eventTextStyle">
                          {getWords("QUIZ")}
                        </span>
                      </div>
                    )}
                  </div>
                ) : null}
                {checkIsUserLogin && isSurveyEnable ? (
                  <div
                    onClick={() => {
                      getSurveyDetails();
                    }}
                    style={{
                      display: "flex",
                      alignItems: "center",
                    }}
                  >
                    {isAndroid || isIOS ? (
                      <div
                        style={{
                          border: "0.3px solid #FCFCFC",
                          borderRadius: 50,
                          alignItems: "center",
                          justifyContent: "center",
                          display: "flex",
                          padding: 3,
                          cursor: "pointer",
                          boxShadow:
                            "0px 3px 3px -2px rgb(0 0 0 / 20%), 0px 3px 4px 0px rgb(0 0 0 / 14%), 0px 1px 8px 0px rgb(0 0 0 / 12%)",
                        }}
                        className="animatequize"
                      >
                        <div
                          style={{
                            border: "1px solid #FFF",
                            borderRadius: 50,
                            alignItems: "center",
                            justifyContent: "center",
                            display: "flex",
                            padding: 5,
                          }}
                        >
                          <img
                            className={classes.iconsStyle}
                            src={SurveyIcon}
                            alt={"surveyIcon"}
                            loading="lazy"
                          />
                        </div>
                      </div>
                    ) : (
                      <div
                        style={{
                          // backgroundColor: "pink",
                          padding: "2px 5px",
                          borderRadius: "4px",
                          boxShadow:
                            "0px 3px 3px -2px rgb(0 0 0 / 20%), 0px 3px 4px 0px rgb(0 0 0 / 14%), 0px 1px 8px 0px rgb(0 0 0 / 12%)",
                        }}
                        className="animatequize"
                      >
                        <span className="eventTextStyle">
                          {getWords("SURVEY")}
                        </span>
                      </div>
                    )}
                  </div>
                ) : null}
              </div>
            )}
          </div>
        )}

        <div
          onClick={() => {
            history.push("/rate");
          }}
          className="titleContainer"
        >
          <span
            style={{
              cursor: "pointer",
            }}
            className="header__title"
          >
            FAN RATING!
          </span>
        </div>

        {isSubScreen || startup ? (
          addIcon ? (
            // redirect to add post screen from teasing room
            <div
              style={{
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                cursor: "pointer",
              }}
              onClick={() => {
                history.push("/add-post");
              }}
            >
              <img className="addIconHeader" src={AddIcon} alt={"add-icon"} />
              <span
                style={{
                  color: "#FFF",
                  marginLeft: 10,
                }}
              >
                {getWords("ADD_POST")}
              </span>
            </div>
          ) : (
            <div className="rightContainer" >
              {/* TODO milan */}
              <div className="text-end">
                <img
                  loading="lazy"
                  src={APPICON}
                  className="app-icon"
                  alt={"app icon"}
                />
              </div>
            </div>
          )
        ) : (
          <div className="rightContainer">
            {checkIsUserLogin ? null : (
              <>
              <div
                style={{ cursor: "pointer", paddingRight: "5px" }}
                onClick={() => {
                  history.push("/login");
                }}
                className={classes.loginDiv}
              >
                <span className="loginTExtHeader">{getWords("LOGIN")}</span>
              </div>
              <div
                style={{ cursor: "pointer" }}
                onClick={() => {
                  history.push("/register");
                }}
                className={classes.loginDiv}
              >
                <span className="loginTExtHeader">{getWords("REGISTER")}</span>
              </div>
              </>
            )}
            {checkIsUserLogin ? (
              // renderLanguageDropdown()
              <div style={{
                position: "relative",
                display: "flex",
                justifyContent: "center",
                alignItems: "center",
                padding: 5,
              }}>
                {/*  */}
                <PopupState variant="popover" popupId="demo-popup-menu-2">
                  {(popupState) => (
                    <React.Fragment>
                      <div {...bindTrigger(popupState)}>
                        {renderIcon()}
                      </div>
                      <Menu {...bindMenu(popupState)} style={{
                        // width: "70px"
                      }}>
                        <MenuItem onClick={async () => {
                          await setLanguage(LANG_US)
                          popupState.close()
                        }}>
                          <EN title="English" style={{
                            width: "30px",
                            height: "30px",
                            marginRight: "10px"
                          }} />
                          English
                        </MenuItem>
                        <MenuItem onClick={async () => {
                          await setLanguage(LANG_IT)
                          popupState.close()
                        }}>
                          <IT title="Italia" style={{
                            width: "30px",
                            height: "30px",
                            marginRight: "10px"
                          }} />
                          Italia
                        </MenuItem>
                        <MenuItem onClick={async () => {
                          await setLanguage(LANG_FR)
                          popupState.close()
                        }}>
                          <FR title="Italia" style={{
                            width: "30px",
                            height: "30px",
                            marginRight: "10px"
                          }} />
                          France
                        </MenuItem>
                        <MenuItem onClick={async () => {
                          await setLanguage(LANG_SP)
                          popupState.close()
                        }}>
                          <SP title="Italia" style={{
                            width: "30px",
                            height: "30px",
                            marginRight: "10px"
                          }} />
                          Spanish
                        </MenuItem>
                        <MenuItem onClick={async () => {
                          await setLanguage(LANG_GE)
                          popupState.close()
                        }}>
                          <GE title="Italia" style={{
                            width: "30px",
                            height: "30px",
                            marginRight: "10px"
                          }} />
                          Germany
                        </MenuItem>
                        <MenuItem onClick={async () => {
                          await setLanguage(LANG_CH)
                          popupState.close()
                        }}>
                          <CH title="Italia" style={{
                            width: "30px",
                            height: "30px",
                            marginRight: "10px"
                          }} />
                          Chinese
                        </MenuItem>
                        <MenuItem onClick={async () => {
                          await setLanguage(LANG_AR)
                          popupState.close()
                        }}>
                          <AR title="Italia" style={{
                            width: "30px",
                            height: "30px",
                            marginRight: "10px"
                          }} />
                          Arabic
                        </MenuItem>
                        {/* <MenuItem onClick={popupState.close}>Logout</MenuItem> */}
                      </Menu>
                    </React.Fragment>
                  )}
                </PopupState>
              </div>
            ) : null}
            {checkIsUserLogin ? (
              <div
                onClick={() => {
                  history.push("/notifications");
                }}
                style={{
                  position: "relative",
                  display: "flex",
                  justifyContent: "center",
                  alignItems: "center",
                  padding: 5,
                }}
              >
                {badgeCount > 0 ? (
                  <div className="badgeCountDiv">
                    <span className="badgeCountStyle">{badgeCount}</span>
                  </div>
                ) : null}
                <NotificationsIcon className="headernotification" />
              </div>
            ) : null}

            {checkIsUserLogin ? renderUserInfoPopUp() : null}
          </div>
        )}
      </header>
    );
  }

  // render side menu
  function renderDrawer() {
    return (
      <div>
        <Drawer
          transitionDuration={500}
          className="drawer"
          anchor="left"
          open={open}
          onClose={() => {
            toggleMenuBar(false);
            let boxopen = document.getElementById("boxopen");
            boxopen.style.animation = "rotatereverse .5s";
            boxopen.style.webkitAnimation = "rotatereverse .5s";
          }}
          classes={{
            paper: classes.drawerPaper,
          }}
        >
          <div className={classes.drawerContainer}>
            {_.isArray(DrawerData) && !_.isEmpty(DrawerData)
              ? DrawerData.map((obj, index) => {
                const checkUserLogin = isUserLogin();

                if (
                  !checkUserLogin &&
                  (obj.id === 2 ||
                    obj.id === 8 ||
                    obj.id === 9 ||
                    obj.id === 3)
                ) {
                  return null;
                }

                return (
                  <div
                    key={index}
                    onClick={() => {
                      if (obj.id === 10) {
                        dispatch(setIsDisplayInstallPWAPopup(true));
                      } else if (obj.id === 11) {
                        window.open(
                          obj.path, "_blank");
                      }
                      else {
                        history.push(obj.path);
                      }
                      toggleMenuBar(false);
                    }}
                    style={{
                      fontSize: window.innerWidth >= 600 ? 18 : 16,
                      color: "#656565",
                      cursor: "pointer",
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "space-between",
                      width: "auto",
                      padding: "12px 0px 12px 20px",
                      borderBottom: "1px solid #eee",
                    }}
                    className="drawericonstextscontainer"
                  >
                    <img
                      className="drawericons"
                      src={obj.id === 2 ? userdata?.team?.logo : obj.img}
                      alt={"TeamIcon"}
                      loading="lazy"
                    />
                    <div
                      style={{
                        display: "flex",
                        width: "100%",
                        alignItems: "center",
                        justifyContent: "flex-start",
                        paddingLeft: "10px",
                      }}
                    >
                      <span className="drawertexts">{getWords(obj.title)}</span>
                    </div>
                    <ArrowForwardIosIcon className="rightarrowicon" />
                  </div>
                );
              })
              : null}
          </div>
        </Drawer>
      </div>
    );
  }

  // render login modal
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

  // render sign up modal
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

  // render forgot password modal
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

  // render success modal
  function renderSuccessModal() {
    return (
      <SuccessModal
        successModal={successModal}
        handleClose={() => {
          setSuccessModal(false);
          if (isearnedcoin || isearnedcoinquiz) {
            setTimeout(() => {
              setDisplayAnim(true);
            }, 1000);
            setTimeout(() => {
              setDisplayAnim(false);
              setIsEarnedcoin(false);
              setIsEarnedcoinQuiz(false);
              refreshUserData();
            }, 3000);
          }
        }}
        frmQuiz={fromQuiz}
        score={5}
        earnedTokens={120}
        quizResult={quizResult}
        fromSurvey={fromSurvey}
      />
    );
  }

  // render quiz modal
  function renderQuizModal() {
    return (
      <QuizModal
        quizDetails={quizDetails}
        quizModal={quizModal}
        handleClose={(value) => {
          setQuizModal(false);
          if (value) {
            setFrmQuiz(value);
            setSuccessModal(value);
          }
        }}
        getQuizResult={() => getQuizResult()}
      />
    );
  }

  // render survey modal
  function renderSurveyModal() {
    return (
      <SurveyModal
        surveyDetails={surveyDetails}
        surveyModal={surveyModal}
        handleClose={(value) => {
          setSurveyModal(false);
          setFrmQuiz(false);
          if (value) {
            setFrmSurvey(value);
            setSuccessModal(value);
          }
        }}
        getSurveyResult={() => getSurveyResult()}
      />
    );
  }

  function rendereditprofilepic() {
    return (
      <EditProfilePic
        openDialog={editprofilepic}
        saveProfileProcess={(fileData) => {
          saveProfileProcess(fileData);
        }}
        handleClose={(value) => setEditProfilePic(false)}
      />
    );
  }
  // TODO ln milan
  function renderUserInfoPopUp() {
    return (
      <PopupState
        variant="popover"
        popupId="demo-popup-menu"
        onClose={(event) => this.handlePopoverClose(event)}
        anchorOrigin={{ vertical: "top", horizontal: "right" }}
        anchorPosition={{ left: 200, top: 20 }}
        transformOrigin={{ vertical: "top", horizontal: "rights" }}
      >
        {(popupState) => {
          return (
            <div>
              <div
                style={{
                  width: userIMG?.includes(".svg") ? "30px" : "35px",
                  height: userIMG?.includes(".svg") ? "30px" : "35px",
                  alignItems: "center",
                  justifyContent: "center",
                  display: "flex",
                  marginLeft: window.innerWidth >= 550 ? 15 : 5,
                  zIndex: 10,
                }}
                {...bindTrigger(popupState)}
              >
                <img
                  style={{
                    width:
                      window.innerWidth >= 550
                        ? userIMG?.includes(".svg")
                          ? "30px"
                          : "35px"
                        : userIMG?.includes(".svg")
                          ? "25px"
                          : "30px",
                    height:
                      window.innerWidth >= 550
                        ? userIMG?.includes(".svg")
                          ? "30px"
                          : "35px"
                        : userIMG?.includes(".svg")
                          ? "25px"
                          : "30px",
                    borderRadius: "30px",
                    cursor: "pointer",
                    padding: userIMG?.includes(".svg") ? 2 : 0,
                    backgroundColor: userIMG?.includes(".svg")
                      ? "#FFF"
                      : `#FFF`,
                  }}
                  src={
                    !_.isEmpty(userIMG)
                      ? userIMG
                      : "https://www.desicomments.com/dc3/08/286960/286960.jpg"
                  }
                  alt={"UserData"}
                  loading="lazy"
                />
              </div>
              <Menu {...bindMenu(popupState)}>
                <div
                  style={{
                    padding: "10px 20px 10px 20px",
                    border: "none",
                    outline: "none",
                  }}
                >
                  <div className={classes.menuItemContainer}>
                    <div className={classes.menuMainDiv}>
                      <div className="menuProfileImg">
                        <img
                          className={classes.profileImg}
                          alt={"userData"}
                          loading="lazy"
                          src={
                            !_.isEmpty(userIMG)
                              ? userIMG
                              : "https://www.desicomments.com/dc3/08/286960/286960.jpg"
                          }
                        />
                        <div
                          className={classes.cameraDiv}
                          onClick={() => {
                            setEditProfilePic(true);
                            popupState.close();
                          }}
                        >
                          <img
                            className={classes.cameraIcon}
                            src={Camera}
                            alt={"CameraIcon"}
                            loading="lazy"
                          />
                        </div>
                      </div>
                      <div className={classes.marginLeftStyle}>
                        <div className={classes.nameDivStyle}>
                          <span className={classes.nameStyle}>
                            {!_.isEmpty(userName) ? userName : "XYZ"}
                          </span>
                          <div>
                            <img
                              className={classes.guaranteeIcon}
                              src={Guarantee}
                              alt={"GuaranteeIcon"}
                              loading="lazy"
                            />
                          </div>
                        </div>

                        <span className={classes.emailStyle}>
                          {!_.isEmpty(userEmail) ? userEmail : "xyz@abcd.com"}
                        </span>
                      </div>
                    </div>
                  </div>

                  <div
                    style={{
                      cursor: "pointer",
                    }}
                    onClick={() => {
                      // redirect to buy token screen
                      history.push("/buy-tokens");
                    }}
                  >
                    <div
                      className={classes.userDetailsDiv}
                      style={{ marginTop: 30 }}
                    >
                      <div
                        style={{
                          display: "flex",
                          alignItems: "center",
                        }}
                      >
                        <img
                          className={classes.iconStyle}
                          src={euro}
                          alt={"coinIcon"}
                          loading="lazy"
                        />
                        <span
                          className={classes.marginLeftStyle}
                          style={{
                            borderBottomWidth: "1px",
                            borderBottomColor: "#ed0f1b",
                            borderBottomStyle: "solid",
                          }}
                        >
                          {getWords("TOKEN")}
                        </span>
                      </div>
                      <div>
                        <span>{userToken < 0 ? "0" : userToken}</span>
                      </div>
                    </div>
                  </div>

                  <div>
                    <div className={classes.userDetailsDiv}>
                      <div
                        style={{
                          display: "flex",
                          alignItems: "center",
                        }}
                      >
                        <img
                          className={classes.iconStyle}
                          src={Ranking}
                          alt={"CoinIcon"}
                          loading="lazy"
                        />
                        <span className={classes.marginLeftStyle}>
                          {getWords("POINTS")}
                        </span>
                      </div>
                      <div>
                        <span>{userPoint}</span>
                      </div>
                    </div>
                  </div>

                  <div>
                    <div className={classes.userDetailsDiv}>
                      <div
                        style={{
                          display: "flex",
                          alignItems: "center",
                          marginBottom: 10,
                        }}
                      >
                        <img
                          style={{
                            width: 23,
                            height: 23,
                          }}
                          src={LevelIcon}
                          alt={"CoinIcon"}
                          loading="lazy"
                        />
                        <span className={classes.marginLeftStyle}>
                          {getWords("LEVEL")}
                        </span>
                      </div>
                    </div>

                    <div>
                      <CButton
                        buttonText={getWords("INCREASE_LEVEL")}
                        handleBtnClick={() => {
                          history.push("/user-level");
                        }}
                      />
                    </div>
                  </div>

                  <CProgressbar
                    progress={
                      !_.isUndefined(userdata?.level?.progress)
                        ? userdata?.level?.progress
                        : 0
                    }
                    currentLevel={
                      !_.isUndefined(userdata?.level?.current_level)
                        ? userdata?.level?.current_level
                        : ""
                    }
                    nextLevel={
                      !_.isUndefined(userdata?.level?.next_level)
                        ? userdata?.level?.next_level
                        : ""
                    }
                  />

                  <div classes={{ root: classes.menuItem }}>
                    <div className={classes.dividerStyle} />
                  </div>

                  {/* <div
                    style={{
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "space-between",
                    }}
                  >
                    <div>
                      <div
                        style={{
                          display: "flex",
                          alignItems: "center",
                          paddingRight: 25,
                        }}
                      >
                        <span
                          style={{
                            fontFamily: "segoeui",
                            fontWeight: "bold",
                            fontSize: 18,
                            paddingRight: 5,
                          }}
                        >
                          {getWords("I_AM_18_YEARS_OLD")}
                        </span>
                      </div>
                      <div style={{ paddingTop: 5, paddingRight: 25 }}>
                        <span
                          style={{
                            fontSize: 12,
                            fontFamily: "segoeui",
                            fontWeight: "bold",
                            color: "#555",
                          }}
                        >
                          {getWords(
                            "REQUIRED_TO_MAKE_IN_APP_PURCHASES_AND_ACCESS_THE_REWARDS_CATALOG"
                          )}
                        </span>
                      </div>
                    </div>
                    <AntSwitch
                      checked={eighteenplus}
                      onChange={() => setEighteenPlus(!eighteenplus)}
                    />
                  </div> */}

                  <div classes={{ root: classes.menuItem }}>
                    <div className={classes.dividerStyle} />
                  </div>

                  <div
                    style={{
                      marginBottom: 5,
                    }}
                  >
                    <div
                      style={{
                        display: "flex",
                        justifyContent: "space-between",
                        alignItems: "center",
                        width: "100%",
                        marginBottom: 0,
                      }}
                    >
                      <CButton
                        outlined={false}
                        buttonStyle={{
                          width: "100%",
                          bottom: 5,
                          marginRight: 5,
                        }}
                        buttonText={getWords("EDIT_PROFILE")}
                        handleBtnClick={() => {
                          history.push("/edit-profile");
                          popupState.close();
                        }}
                      />

                      <CButton
                        btnLoader={logoutLoad}
                        outlined={true}
                        buttonStyle={{
                          width: "100%",
                          marginLeft: 5,
                          height: 19,
                        }}
                        buttonText={getWords("SIGN_OUT")}
                        handleBtnClick={() => {
                          if (logoutLoad) {
                            return;
                          } else {
                            showAlert(
                              true,
                              getWords("WARNING"),
                              getWords("Logout_Message"),
                              true
                            );
                          }
                        }}
                      />
                    </div>
                  </div>
                </div>
              </Menu>
            </div>
          );
        }}
      </PopupState>
    );
  }


  async function setLanguage(language) {
    setLoader(true);
    const header = {
      authorization: `Bearer ${userdata?.access_token}`,
      "Content-Type": "application/json"
    };
    let endPoint = `${Setting.endpoints.set_language}`;
    const data = {
      "lang": language
    }

    const response = await getAPIProgressData(endPoint, "POST", data, header);
    if (response?.status) {
      const updatedUserData = response?.data;
      dispatch(setUserData(updatedUserData));
      setLoader(false);
      return true;
    } else {
      setLoader(false);
      showAlert(true, getWords("OOPS"), response?.message);
      return false;
    }
  }
  function renderIcon() {
    let result;
    switch (language) {
      case LANG_IT:
        result = <IT style={{
          width: "30px",
          height: "30px"

        }} />
        break;
      case LANG_US:
        result = <EN style={{
          width: "30px",
          height: "30px"

        }} />
        break;
      case LANG_SP:
        result = <SP style={{
          width: "30px",
          height: "30px"

        }} />
        break;

      case LANG_GE:
        result = <GE style={{
          width: "30px",
          height: "30px"

        }} />
        break;
      case LANG_CH:
        result = <CH style={{
          width: "30px",
          height: "30px"

        }} />
        break;
      case LANG_AR:
        result = <AR style={{
          width: "30px",
          height: "30px"

        }} />
        break;
      case LANG_FR:
        result = <FR style={{
          width: "30px",
          height: "30px"

        }} />
        break;
      default:
        result = <EN style={{
          width: "30px",
          height: "30px"

        }} />
    }
    return result;
  }

  return (
    <div className="container">
      {mainHeader()}
      {renderLoginModal()}
      {renderSignUpModal()}
      {renderForgotPWDModal()}
      {renderSuccessModal()}
      {renderQuizModal()}
      {renderSurveyModal()}
      {renderDrawer()}
      {rendereditprofilepic()}
      {renderAlert()}
      <TransferComplete animationtype="coinrotation" openModal={displayAnim} />
      <CRequestLoader
        openModal={loader}
        handleClose={() => {
          setLoader(false);
        }}
      />
    </div>
  );
}

Header.propTypes = {
  isSubScreen: PropTypes.bool,
  onGoback: PropTypes.bool,
  onBack: PropTypes.func,
  removeBackArrow: PropTypes.bool,
  startup: PropTypes.bool,
  profileUpdated: PropTypes.func,
  addIcon: PropTypes.bool,
};

Header.defaultProps = {
  isSubScreen: false,
  onGoback: false,
  onBack: () => { },
  removeBackArrow: false,
  startup: false,
  profileUpdated: () => { },
  addIcon: false,
};

export default Header;
