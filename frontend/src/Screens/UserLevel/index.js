import { useSelector } from "react-redux";
import React, { useState, useEffect } from "react";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import { Setting } from "../../Utils/Setting";
import Header from "../../Components/Header/index";
import Protected from "../../Components/Protected";
import { getApiData } from "../../Utils/APIHelper";
import CNoData from "../../Components/CNoData/index";
import CNotificationLoader from "../../Loaders/CNotificationLoader";
import {
  getWords,
  addAnalyticsEvent,
  refreshUserData,
} from "../../commonFunctions";
import increaseIcon from "../../Assets/Images/increase.png";
import increaseIconW from "../../Assets/Images/increase_white.png";
import CAlert from "../../Components/CAlert/index";
import StripeCard from "../../Components/Payment/StripeCard";

const UserLevel = () => {
  const { userdata } = useSelector((state) => state.auth);
  const [levelList, setLevelList] = useState([]);
  const [currentLevel, setCurrentLevel] = useState({});
  const [pageLoader, setPageLoader] = useState(true);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [showCancel, setShowCancel] = useState(false);

  const [selectedPlan, setSelectedPlan] = useState({});
  const [openCard, setOpenCard] = useState(false);

  const [payLoader, setPayLoader] = useState(false);
  const [purchaseSucess, setPurchaseSucess] = useState(false);
  const [currentItem, setCurrentItem] = useState("");
  const [prevItem, setPreviousItem] = useState("");

  const user_current_token = userdata?.token;

  useEffect(() => {
    getLevelList();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    document.title = Setting.page_name.VOTE_LEVEL;
  }, []);

  async function getLevelList() {
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: userToken,
    };

    try {
      let endPoint = Setting.endpoints.dropdowns;
      const response = await getApiData(endPoint, "GET", null, header);
      addAnalyticsEvent("User_Level_Event", true);
      if (response && response.status && response.status === true) {
        if (_.isObject(response.data) && !_.isEmpty(response.data)) {
          const lData =
            response && response.data && response.data.levels
              ? response.data.levels.data
              : [];

          const clData =
            response && response.data && response.data.levels
              ? response.data.levels.current_level
              : {};

          setLevelList(lData);
          setCurrentLevel(clData);
          setPageLoader(false);
        } else {
          setPageLoader(false);
        }
      } else {
        setPageLoader(false);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setPageLoader(false);
    }
  }

  // purchase level api call
  const purchaseLevel = async (data) => {
    const cardID = data?.id;
    const selectedPlanID = selectedPlan?.id;
    setPayLoader(true);
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: userToken,
    };

    try {
      let endPoint = `${Setting.endpoints.purchase_level}?level_id=${selectedPlanID}&card_token=${cardID}`;
      const response = await getApiData(endPoint, "POST", null, header);
      addAnalyticsEvent("User_Level_Event", true);
      if (response && response.status && response.status === true) {
        refreshUserData();
        setPurchaseSucess(true);
        setPageLoader(false);
        setPayLoader(false);
        showAlert(true, getWords("SUCCESS"), response?.message);
      } else {
        setPageLoader(false);
        setPayLoader(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setPageLoader(false);
      setPayLoader(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  // show alert
  const showAlert = (open, title, message, showcancel) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
    setShowCancel(showcancel);
  };

  // clear all purchases
  function clearAllPurchaseData() {
    if (purchaseSucess) {
      getLevelList();
      setPayLoader(false);
      setPageLoader(false);
      setOpenCard(false);
      setAlertOpen(false);
      setPurchaseSucess(false);
    } else {
      setAlertOpen(false);
    }
  }

  // display alert
  function renderAlert() {
    const isBool = user_current_token >= currentItem;

    const btnName = isBool ? getWords("New_Token_key") : getWords("BUY_TOKENS");

    return (
      <CAlert
        open={alertOpen}
        onClose={
          payLoader
            ? null
            : () => {
              clearAllPurchaseData();
            }
        }
        onOkay={
          payLoader
            ? null
            : () => {
              clearAllPurchaseData();
            }
        }
        title={alertTitle}
        message={alertMessage}
        showCancel={showCancel}
        handleBuyToken={
          payLoader
            ? null
            : () => {
              if (isBool) {
                purchaseLevelFromUserToken();
              } else {
                setAlertOpen(false);
                setOpenCard(true);
              }
            }
        }
        lesstoken={prevItem < currentItem}
        buttonName={btnName}
        payLoader={payLoader}
      />
    );
  }

  // purchase level from user existing token api call
  const purchaseLevelFromUserToken = async () => {
    setPayLoader(true);
    const selectedPlanID = selectedPlan?.id;
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: userToken,
    };

    try {
      let endPoint = `${Setting.endpoints.purchase_level_by_token}?level_id=${selectedPlanID}`;
      const response = await getApiData(endPoint, "POST", null, header);

      if (response && response.status && response.status === true) {
        refreshUserData();
        setPageLoader(true);
        setPayLoader(false);
        setAlertOpen(false);
        getLevelList();
        // showAlert(true, getWords("SUCCESS"), response?.message);
      } else {
        setPageLoader(false);
        setPayLoader(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setPageLoader(false);
      setPayLoader(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  // render payment
  const renderPayment = () => {
    return (
      <StripeCard
        openDialog={openCard}
        handleClose={() => {
          setOpenCard(false);
        }}
        selectedPlan={selectedPlan}
        from={"UserLevel"}
        onPaymentClick={
          payLoader
            ? null
            : (defaultCard) => {
              purchaseLevel(defaultCard);
            }
        }
        payLoader={payLoader}
      />
    );
  };

  // display list header
  function renderListHeader() {
    return (
      <div
        style={{
          flexDirection: "row",
          display: "flex",
          padding: window.innerWidth > 450 ? "1% 7%" : "0% 1%",
          backgroundColor: "#F6F6F6",
          alignItems: "center",
        }}
      >
        <div
          style={{
            display: "flex",
            justifyContent: "space-around",
            width: "40%",
          }}
        >
          <span className="contentSpan1">{getWords("INDEX")}</span>
          <span className="contentSpan1">
            {getWords("LEVEL").toUpperCase()}
          </span>
        </div>
        <div
          style={{
            width: window.innerWidth > 450 ? "20%" : "4%",
          }}
        />
        <div
          style={{
            display: "flex",
            justifyContent: "space-between",
            width: "50%",
            marginLeft: 10,
          }}
        >
          <span className="contentSpan1">{getWords("VOTE_FOR_MATCHES")}</span>
          <span className="contentSpan1">
            {getWords("LEVEL_PRICE").toUpperCase()}
          </span>
          <span className="contentSpan1">
            {getWords("INCREASE_LEVEL1").toUpperCase()}
          </span>
        </div>
      </div>
    );
  }

  // renderList
  const renderList = () => {
    return _.isArray(levelList) && !_.isEmpty(levelList) ? (
      <div>
        {/* level list header */}
        {renderListHeader()}

        {/* list  */}
        <div>
          {levelList?.map((item, index) => {
            return (
              <div
                style={{
                  backgroundColor:
                    item.id === currentLevel.id
                      ? "#ed0f1b"
                      : index % 2 !== 0
                        ? "#F6F6F6"
                        : "#FFFFFF",
                }}
              >
                <div
                  className="OtherCommonContainer"
                  style={{
                    flexDirection: "row",
                    display: "flex",
                    padding: "1% 7%",
                  }}
                >
                  <div className="ULDiv1">
                    <div className="ULDiv2">
                      <span
                        className="nomatchforVote"
                        style={{
                          textAlign: "center",
                          color:
                            item.id === currentLevel.id ? "#FFF" : "#484848",
                        }}
                      >
                        {item?.id !== "" ? item?.id : "-"}
                      </span>
                    </div>
                    <div className="ULDiv21">
                      <div
                        style={{
                          display: "flex",

                          // width: 10,
                          // backgroundColor: "palegreen",
                          width: window.innerWidth >= 500 ? "none" : 200,
                          marginLeft:
                            window.innerWidth >= 500 && window.innerWidth <= 900
                              ? -70
                              : 0,
                        }}
                      >
                        <span
                          className="nomatchforVote"
                          style={{
                            color:
                              item.id === currentLevel.id ? "#FFF" : "#484848",
                          }}
                        >
                          {item?.level !== "" ? item?.level : "-"}
                        </span>
                      </div>
                    </div>
                  </div>
                  <div className="ULDivWidth20" />
                  <div className="ULDiv3">
                    <div className="ULDiv4">
                      <span
                        className="nomatchforVote"
                        style={{
                          textAlign: "center",
                          color:
                            item.id === currentLevel.id ? "#FFF" : "#484848",
                        }}
                      >
                        {item?.no_match_for_vote >= 0
                          ? item?.no_match_for_vote
                          : 0}
                      </span>
                    </div>
                    <div className="ULDiv4">
                      <span
                        className="nomatchforVote"
                        style={{
                          textAlign: "center",
                          color:
                            item.id === currentLevel.id ? "#FFF" : "#484848",
                        }}
                      >
                        {item?.level_price}
                      </span>
                    </div>
                    {item.id > currentLevel.id ? (
                      <div
                        className="ULDiv4"
                        onClick={() => {
                          // increase level buy making a payment
                          setSelectedPlan(item);
                          setCurrentItem(levelList[index].level_price);
                          setPreviousItem(levelList[index - 1]?.level_price);
                          // alert to be shown before making payment
                          showAlert(
                            true,
                            getWords("WARNING"),
                            getWords("FAN_COINGS_LEVET_UP_CONFIRM_MESSAGE", { level_price: item?.level_price }),
                            // `Ti servono ${
                            // // levelList[index].level_price -
                            // // levelList[index - 1]?.level_price
                            // item?.level_price
                            // } Fan Coins per salire di livello, sei sicuro di voler procedere?`,
                            true
                          );
                        }}
                        style={{
                          cursor: "pointer",
                        }}
                      >
                        <img
                          src={
                            item.id === currentLevel.id
                              ? increaseIconW
                              : increaseIcon
                          }
                          alt={"increase-icon"}
                          className="increaseLevelIcon"
                        />
                      </div>
                    ) : (
                      <div className="ULDiv4">
                        <span
                          style={{
                            color:
                              item.id === currentLevel.id ? "#FFF" : "#484848",
                          }}
                        >
                          -
                        </span>
                      </div>
                    )}
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    ) : (
      <CNoData message={getWords("SORRY_NO_DATA_FOUND")} hasheader={true} />
    );
  };

  if (pageLoader) {
    return (
      <Protected>
        <Header isSubScreen={true} />
        <CNotificationLoader web={(window.innerWidth >= 600).toString()} />
      </Protected>
    );
  }

  return (
    <Protected>
      <div className="MainContainer">
        <Header isSubScreen={true} />
        {renderList()}
        {renderAlert()}
        {renderPayment()}
      </div>
    </Protected>
  );
};

export default UserLevel;
