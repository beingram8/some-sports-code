import React, { useState, useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import useMediaQuery from "@material-ui/core/useMediaQuery";
import { Carousel } from "react-responsive-carousel";
import { Paper } from "@material-ui/core";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import Header from "../../Components/Header";
import euro from "../../Assets/Images/fan_coins.png";
import {
  getWords,
  addAnalyticsEvent,
  isUserLogin,
  refreshUserData,
  sendFCMTokenToServer,
  checkSurveyQuizIsEnable,
} from "../../commonFunctions";
import ArticleAd from "../../Components/Ads/ArticleAd";
import BottomTab from "../../Components/BottomTab";
import DialogBox from "../../Components/DialogBox/index.js";
import CWinnerLoader from "../../Loaders/CWinnerLoader/index.js";
import { Setting } from "../../Utils/Setting";
import { getApiData } from "../../Utils/APIHelper";
import LoginModal from "../../Modals/LoginModal/index";
import SuccessModal from "../../Modals/SuccessModal/index";
import SignUpModal from "../../Modals/SignUpModal/index";
import ForgotPasswordModal from "../../Modals/ForgotPasswordModal/index";
import authActions from "../../Redux/reducers/auth/actions";
import CNoData from "../../Components/CNoData/index";
import CAlert from "../../Components/CAlert/index";
import NotificationPopup from "../../Components/NotificationPopup";
import TransferComplete from "../../Modals/TransferComplete";
import { useHistory } from "react-router-dom";
import InstallAppTutorial from "../../Modals/InstallAppTutorial";

const { setUserData, setSelectedTab } = authActions;

function Winner() {
  const history = useHistory();

  const { userdata } = useSelector((state) => state.auth);
  const matches = useMediaQuery("(min-width:550px)");
  const matches450width = useMediaQuery("(min-width:450px)");
  const [open, setOpen] = React.useState(false);
  const [loader, setLoader] = useState(true);
  const dispatch = useDispatch();
  const buyingtoken = 0;

  const [signUpModal, setSignUpModal] = useState(false);
  const [forgorPwdModal, setForgotPwdModal] = useState(false);
  const [loginModal, setLoginModal] = useState(false);
  const [successModal, setSuccessModal] = useState(false);

  const [winnerList, setWinnerList] = useState({});
  const [winDetails, setWinDetails] = useState({});
  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [autoplayCard, setAutoPlayCard] = useState(false);
  const [callFunc, setCallFunction] = useState(false);
  const [displayAnim, setDisplayAnim] = useState(false);
  const [btnLoader, setBtnLoader] = useState(false);
  const guestUser = {
    user: "Guest User",
  };

  const [isListIsNotEmpty, setListIsNotEmpty] = useState(true);

  const checkUserLogin = isUserLogin();
  const eventData = checkUserLogin ? true : guestUser;

  const handleClose = () => {
    setOpen(false);
    setAutoPlayCard(true);
  };

  useEffect(() => {
    dispatch(setSelectedTab(2));
    getWinnerList();
    setAutoPlayCard(true);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    document.title = Setting.page_name.VINCI;
  }, []);

  const showAlert = (open, title, message, callFunction, displayAnim) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
    setCallFunction(callFunction);
    setDisplayAnim(displayAnim);
  };

  function renderAlert() {
    return (
      <CAlert
        open={alertOpen}
        onClose={() => {
          setAlertOpen(false);
        }}
        showCancel={callFunc}
        onOkay={() => {
          setAlertOpen(false);
          if (callFunc) {
            buyVocher();
            // setDisplayAnim(true);
          }
        }}
        title={alertTitle}
        message={alertMessage}
        handleBuyToken={() => {
          history.push("/buy-tokens");
        }}
        lesstoken={userdata?.token < winDetails?.token || userdata?.token < 0}
      />
    );
  }

  async function getWinnerList() {
    setLoader(true);
    try {
      let endPoint = Setting.endpoints.product_list;
      const response = await getApiData(endPoint, "GET", null);
      addAnalyticsEvent("Winner_Screen_Data_Event", eventData);
      if (response && response.status && response.status === true) {
        if (response && response.data) {
          setWinnerList(response.data);
          setListIsNotEmpty(response?.data?.product_available);
          setLoader(false);
        } else {
          setLoader(false);
        }
      } else {
        setLoader(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      setLoader(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  async function getDetails(item) {
    try {
      let endPoint = `${Setting.endpoints.product_Details}?reward_id=${item.id}`;
      const response = await getApiData(endPoint, "GET", null);
      if (response && response.status && response.status === true) {
        if (response && response.data) {
          setWinDetails(response.data);
          setAutoPlayCard(false);
          setOpen(true);

          let eData = {};
          eData.user = checkUserLogin ? userdata : { userTyep: "Guest User" };
          eData.videoData = response.data;
          addAnalyticsEvent("Winner_Video_Details_Data_Event", eventData);
        }
      } else {
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  async function buyVocher() {
    const rewardId = winDetails?.id;
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: userToken,
    };
    setBtnLoader(true);

    try {
      let endPoint = `${Setting.endpoints.buy_vocher}?reward_id=${rewardId}`;
      const response = await getApiData(endPoint, "GET", null, header);
      if (response && response.status && response.status === true) {
        const eventData = {
          user: userdata,
          voucherData: winDetails,
        };
        setBtnLoader(false);
        refreshUserData();
        setAutoPlayCard(true);
        getWinnerList();
        addAnalyticsEvent("Buy_New_Voucher_Event", eventData);
        setOpen(false);
        setTimeout(() => {
          showAlert(true, getWords("AWARD_REDEEMED"), getWords("CHECK_EMAIL"));
        }, 200);
      } else {
        setOpen(false);
        setBtnLoader(false);
        setAutoPlayCard(true);
        setTimeout(() => {
          showAlert(true, getWords("OOPS"), response?.message);
        }, 200);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setAutoPlayCard(true);
      setBtnLoader(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

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

  const renderTransferComplete = () => {
    return (
      <TransferComplete
        openModal={displayAnim}
        handleClose={() => {
          setTimeout(() => {
            setDisplayAnim(false);
          }, 1000);
        }}
      />
    );
  };

  if (loader) {
    return <CWinnerLoader web={(window.innerWidth >= 640).toString()} />;
  }

  if (isListIsNotEmpty === false) {
    return (
      <div className="MainContainer">
        <Header />
        <CNoData
          message={getWords("SORRY_NO_DATA_FOUND")}
          hasfooter={true}
          hasheader={true}
        />
        <BottomTab />
        <NotificationPopup />
      </div>
    );
  }

  return (
    <div className="MainContainer">
      <InstallAppTutorial />
      <Header />
      <div className="CommonContainer" style={{ height: "calc(100% - 130px)" }}>
        <div className="winnermaindiv">
          <div className="winnersubmaindiv">
            <div className="achievementscontainer">
              <span className="Achivements">{getWords("ACHIEVEMENTS")}</span>
              <span className="tokenTextStyle">
                {checkUserLogin
                  ? `${getWords("YOUR_TOKENS")}: ${
                      userdata?.token < 0 ? "0" : userdata?.token
                    }`
                  : null}
              </span>
            </div>

            <div>
              <ArticleAd adUnit={Setting.ads_Units.TEST_ARTICLE_AD} />
            </div>
            {_.isArray(winnerList?.rows) && !_.isEmpty(winnerList?.rows) ? (
              winnerList?.rows?.map((item, index) => {
                return item.name !== "" && !_.isEmpty(item?.product_list) ? (
                  <div key={index} className="winnerprizecontainer">
                    <div className="HorizontalItem">
                      <span className="Boldtext">{item.name}</span>
                    </div>
                    {index === 0 ? (
                      <Carousel
                        className="Prize"
                        swipeable={true}
                        emulateTouch={true}
                        showArrows={false}
                        autoPlay={autoplayCard}
                        // interval={600000}
                        infiniteLoop
                      >
                        {_.chunk(
                          item.product_list,
                          !matches450width ? 1 : !matches ? 2 : 3
                        ).map((item1, index) => {
                          return item?.product_list?.length % 3 === 0 ||
                            !matches ? (
                            <div
                              key={index}
                              style={{
                                display: "flex",
                                justifyContent: matches450width
                                  ? "space-between"
                                  : "center",
                              }}
                              className="winnercaraousalcontainer"
                            >
                              {item1.map((item, index) => {
                                return (
                                  <div
                                    key={index}
                                    className="winnerpapercontainer"
                                  >
                                    <Paper
                                      elevation={3}
                                      className="winnerpaper"
                                      onClick={() => {
                                        getDetails(item);
                                      }}
                                    >
                                      <img
                                        loading="lazy"
                                        src={item.reward_img_url}
                                        className="prizecontainer"
                                        alt="oops..."
                                      />
                                    </Paper>
                                    <div className="winnerhorizontalitems">
                                      <img
                                        loading="lazy"
                                        src={euro}
                                        className="winnercoinimage"
                                        alt={"euroIcon"}
                                      />
                                      <span className="amount">
                                        {item.buying_token}
                                      </span>
                                    </div>
                                  </div>
                                );
                              })}
                            </div>
                          ) : (
                            <div
                              key={index}
                              style={{
                                display: "flex",
                              }}
                              className="winnercaraousalcontainer"
                            >
                              {item1.map((item, index) => {
                                return (
                                  <div
                                    key={index}
                                    className="winnerpapercontainer1"
                                  >
                                    <Paper
                                      elevation={3}
                                      className="winnerpaper"
                                      onClick={() => {
                                        getDetails(item);
                                      }}
                                    >
                                      <img
                                        loading="lazy"
                                        src={item.reward_img_url}
                                        className="prizecontainer"
                                        alt="oops..."
                                      />
                                    </Paper>
                                    <div className="winnerhorizontalitems">
                                      <img
                                        loading="lazy"
                                        src={euro}
                                        className="winnercoinimage"
                                        alt={"euroIcon"}
                                      />
                                      <span className="amount">
                                        {item.buying_token}
                                      </span>
                                    </div>
                                  </div>
                                );
                              })}
                            </div>
                          );
                        })}
                      </Carousel>
                    ) : matches450width ? (
                      <Carousel
                        className="Prize"
                        swipeable={true}
                        emulateTouch={true}
                        showArrows={false}
                        autoPlay={false}
                        interval={600000}
                        infiniteLoop
                      >
                        {_.chunk(
                          item.product_list,
                          !matches450width ? 1 : !matches ? 2 : 3
                        ).map((item1, index) => {
                          return item?.product_list?.length % 3 === 0 ||
                            !matches ? (
                            <div
                              key={index}
                              style={{
                                display: "flex",
                                justifyContent: matches450width
                                  ? "space-between"
                                  : "center",
                              }}
                              className="winnercaraousalcontainer"
                            >
                              {item1.map((item, index) => {
                                return (
                                  <div
                                    key={index}
                                    className="winnerpapercontainer"
                                  >
                                    <Paper
                                      elevation={3}
                                      className="winnerpaper"
                                      onClick={() => {
                                        getDetails(item);
                                      }}
                                    >
                                      <img
                                        loading="lazy"
                                        src={item.reward_img_url}
                                        className="prizecontainer"
                                        alt="oops..."
                                      />
                                    </Paper>
                                    <div className="winnerhorizontalitems">
                                      <img
                                        loading="lazy"
                                        src={euro}
                                        className="winnercoinimage"
                                        alt={"euroIcon"}
                                      />
                                      <span className="amount">
                                        {item.buying_token}
                                      </span>
                                    </div>
                                  </div>
                                );
                              })}
                            </div>
                          ) : (
                            <div
                              key={index}
                              style={{
                                display: "flex",
                              }}
                              className="winnercaraousalcontainer"
                            >
                              {item1.map((item, index) => {
                                return (
                                  <div
                                    key={index}
                                    className="winnerpapercontainer1"
                                  >
                                    <Paper
                                      elevation={3}
                                      className="winnerpaper"
                                      onClick={() => {
                                        getDetails(item);
                                      }}
                                    >
                                      <img
                                        loading="lazy"
                                        src={item.reward_img_url}
                                        className="prizecontainer"
                                        alt="oops..."
                                      />
                                    </Paper>
                                    <div className="winnerhorizontalitems">
                                      <img
                                        loading="lazy"
                                        src={euro}
                                        className="winnercoinimage"
                                        alt={"euroIcon"}
                                      />
                                      <span className="amount">
                                        {item.buying_token}
                                      </span>
                                    </div>
                                  </div>
                                );
                              })}
                            </div>
                          );
                        })}
                      </Carousel>
                    ) : (
                      <div className="Prize2">
                        {item.product_list.map((item, index) => {
                          return (
                            <div key={index} className="winnerpapercontainer">
                              <Paper
                                elevation={3}
                                className="winnerpaper2"
                                onClick={() => {
                                  getDetails(item);
                                }}
                              >
                                <img
                                  loading="lazy"
                                  src={item.reward_img_url}
                                  className="prizecontainer"
                                  alt="oops..."
                                />
                              </Paper>
                              <div className="winnerhorizontalitems">
                                <img
                                  loading="lazy"
                                  src={euro}
                                  className="winnercoinimage"
                                  alt={"euroIcon"}
                                />
                                <span className="amount">
                                  {item.buying_token}
                                </span>
                              </div>
                            </div>
                          );
                        })}
                      </div>
                    )}
                  </div>
                ) : null;
              })
            ) : (
              <CNoData
                message={getWords("SORRY_NO_DATA_FOUND")}
                hasfooter={true}
                hasheader={true}
              />
            )}
          </div>
        </div>
      </div>
      <DialogBox
        openDialog={open}
        handleClose={() => {
          handleClose();
        }}
        fromTifa={false}
        giftItem={winDetails}
        tokenval={buyingtoken}
        btnLoader={btnLoader}
        // lessToken={userdata?.token < winDetails?.token || userdata?.token < 0}
        handleBtn={() => {
          const checkIsUserLogin = isUserLogin();
          if (checkIsUserLogin === false) {
            setOpen(false);
            setLoginModal(true);
          } else {
            showAlert(
              true,
              getWords("WARNING"),
              userdata?.token < winDetails?.token || userdata?.token < 0
                ? getWords("EARNED_MORE_TOKENS")
                : `Sei sicuro di voler riscattere ${winDetails?.title}? Il codice verrà inviato alla mail che hai usato in fase di registrazione`,
              true
            );
          }
        }}
        handleBuyToken={() => {
          history.push("/buy-tokens");
        }}
      />

      <BottomTab />
      {renderLoginModal()}
      {renderSignUpModal()}
      {renderForgotPWDModal()}
      {renderSuccessModal()}
      {renderAlert()}
      {renderTransferComplete()}
      <NotificationPopup />
    </div>
  );
}

export default Winner;
