import "react-responsive-carousel/lib/styles/carousel.min.css";
import React, { useState, useEffect } from "react";
import { useSelector } from "react-redux";
import "react-activity/dist/Spinner.css";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import Header from "../../Components/Header";
import { Setting } from "../../Utils/Setting";
import CButton from "../../Components/CButton";
import { getApiData } from "../../Utils/APIHelper";
import CAlert from "../../Components/CAlert/index";
import CNoData from "../../Components/CNoData/index";
import Protected from "../../Components/Protected";
import arrow1 from "../../Assets/Images/right-arrow-2.png";
import StripeCard from "../../Components/Payment/StripeCard";
import arrow2 from "../../Assets/Images/right-arrow-1-1.png";
import CBuyTokenLoader from "../../Loaders/CBuyTokenLoader/index";
import {
  getWords,
  addAnalyticsEvent,
  refreshUserData,
} from "../../commonFunctions";
import DisplayAd from "../../Components/Ads/DisplayAd";
import Token50 from "../../Assets/Images/50Token.png";
import Token100 from "../../Assets/Images/100Token.png";
import Token500 from "../../Assets/Images/500Token.png";
import Token1000 from "../../Assets/Images/1000Token.png";
import Token2000 from "../../Assets/Images/2000Token.png";
import Token5000 from "../../Assets/Images/5000Token.png";
import NotificationPopup from "../../Components/NotificationPopup";

function BuyTokens() {
  const { userdata } = useSelector((state) => state.auth);
  const [pageLoader, setPageLoader] = useState(true);
  const [tokenPlan, setTokenPlan] = useState([]);
  const [openCard, setOpenCard] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertOpen, setAlertOpen] = useState(false);
  const [alertMessage, setAlertMessage] = useState("");
  const [transactionData, setTransactionData] = useState({});
  const [selectedPlan, setSelectedPlan] = useState({});

  const header = {
    authorization: `Bearer ${userdata?.access_token}`,
  };

  useEffect(() => {
    refreshUserData();
    getTokenPlanData();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    document.title = Setting.page_name.BUY_TOKEN;
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
        }}
        onOkay={() => {
          setAlertOpen(false);
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  // token plan api call
  const getTokenPlanData = async () => {
    try {
      let endPoint = Setting.endpoints.token_plan;
      const response = await getApiData(endPoint, "GET", null, header);
      addAnalyticsEvent("Buy_Token_Event", true);
      if (response?.status) {
        const tList = response?.data;
        setTokenPlan(tList);
        if (_.isArray(tList) && !_.isEmpty(tList)) {
          setSelectedPlan(tList[3]);
        }
        getTransactionList();
      } else {
        setPageLoader(false);
      }
    } catch (err) {
      setPageLoader(false);
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  // transaction list api call
  const getTransactionList = async () => {
    try {
      let endPoint = Setting.endpoints.transaction_list;
      const response = await getApiData(endPoint, "get", null, header);
      if (response?.status) {
        setPageLoader(false);
        setTransactionData(response.data);
      } else {
        setPageLoader(false);
      }
    } catch (err) {
      setPageLoader(false);
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  const renderPayment = () => {
    return (
      <StripeCard
        openDialog={openCard}
        handleClose={() => {
          setOpenCard(false);
          getTransactionList();
        }}
        selectedPlan={selectedPlan}
      />
    );
  };

  function renderTopTokenList() {
    if (_.isArray(tokenPlan) && !_.isEmpty(tokenPlan)) {
      return (
        <div>
          <div className="butTOkenDiv1">
            <div className="buyTokenDiv2">
              {_.isArray(tokenPlan) && !_.isEmpty(tokenPlan)
                ? tokenPlan?.map((item, index) => {
                    return (
                      <div
                        key={index}
                        className="buytokenimagecontainer"
                        onClick={() => {
                          setSelectedPlan(item);
                        }}
                        style={{
                          boxShadow:
                            item.id === selectedPlan.id
                              ? "0px 0px 8px 2px rgba(41, 41, 41, 0.25)"
                              : null,
                          border: `1px solid ${
                            item.id === selectedPlan.id ? "#ED0F18" : "#d3d3d3"
                          }`,
                        }}
                      >
                        {item.id === selectedPlan.id ? (
                          <div
                            className="planNameContainer"
                            style={{
                              marginTop:
                                item.name === "MIGLIOR OFFERTA" ? -25 : -19,
                            }}
                          >
                            <span className="planNameStyle">{item.name}</span>
                          </div>
                        ) : null}
                        <img
                          loading="lazy"
                          src={
                            item.id === 1
                              ? Token50
                              : item.id === 2
                              ? Token100
                              : item.id === 3
                              ? Token500
                              : item.id === 4
                              ? Token1000
                              : item.id === 5
                              ? Token2000
                              : item.id === 6
                              ? Token5000
                              : null
                          }
                          className="buytokenimage"
                          alt={"Payment"}
                        />
                        <div className="centercolumnStylePlan buytokenmargintop">
                          <span className="reelPricePlan">
                            {item.reel_amount}
                          </span>
                          <div className="centercolumnStylePlan">
                            <span className="planPriceStyle">{` ${item.price}€`}</span>
                            <span className="buytokendesctext">{`${item.token}`}</span>
                            <span className="buytokendesctext">{`Fan Coins`}</span>
                          </div>
                        </div>
                      </div>
                    );
                  })
                : null}
            </div>

            <CButton
              buttonStyle={{
                bottom: 0,
                width: window.innerWidth >= 600 ? "25%" : "45%",
              }}
              buttonText={getWords("BUY_TOKENS")}
              handleBtnClick={() => {
                setOpenCard(true);
              }}
            />
          </div>
        </div>
      );
    }

    return null;
  }

  function renderTokenHeader() {
    const totalToken =
      !_.isEmpty(userdata) && userdata?.token >= 0 ? userdata?.token : 0;
    return (
      <div>
        <div className="buytokenhorizontalitems1">
          <span className="buytokenyourtokentext">
            {getWords("TRANSACTION_LIST")}
          </span>
          <span className="buytokenyourtokentext">
            {getWords("YOUR_TOKENS")} : {totalToken}
          </span>
        </div>
      </div>
    );
  }

  function renderTransactionList() {
    const transactionList = transactionData?.data;

    return (
      <div>
        {renderTokenHeader()}

        {_.isArray(transactionList) && !_.isEmpty(transactionList) ? (
          <div>
            <div className="buytokenhorizontalitems2">
              <span className="buytokentokentext">
                {getWords("DESCRIPTION")}
              </span>
              <span className="buytokentokentext">{getWords("TOKENS")}</span>
            </div>

            <div className="buytokentransmaincontainer">
              {transactionList?.map((item, index) => {
                return (
                  <div
                    key={index}
                    style={{
                      borderTop: index === 0 ? null : "1px solid #E8E8E8",
                    }}
                    className="buytokentranscontainer"
                  >
                    <div style={{ display: "flex" }}>
                      <div className="buytokenarrowcontainer">
                        {item.transaction_type === "debit" ? (
                          <img
                            loading="lazy"
                            src={arrow2}
                            className="buytokenarrowimage"
                            alt={"Buy Token Arrow"}
                          />
                        ) : (
                          <img
                            loading="lazy"
                            src={arrow1}
                            className="buytokenarrowimage"
                            alt={"Buy Token Arrow1"}
                          />
                        )}
                      </div>
                      <div className="div3BT">
                        <span className="bttimetext">{item?.created_at}</span>
                        <span className="btusername">{item?.remark}</span>
                      </div>
                    </div>
                    <div>
                      {item.transaction_type === "debit" ? (
                        <span className="buytokentranstext appcolor">
                          -{item?.token}
                        </span>
                      ) : (
                        <span className="buytokentranstext greencolor">
                          +{item?.token}
                        </span>
                      )}
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        ) : (
          <CNoData
            message={getWords("SORRY_NO_DATA_FOUND")}
            hasfooter={true}
            hasheader={true}
            height={"calc(100vh - 400px)"}
          />
        )}
      </div>
    );
  }

  if (pageLoader) {
    return <CBuyTokenLoader web={(window.innerWidth >= 600).toString()} />;
  }

  if (pageLoader) {
    return (
      <Protected>
        <div className="MainContainer">
          <Header isSubScreen={true} />
          <CBuyTokenLoader web={(window.innerWidth >= 600).toString()} />
        </div>
      </Protected>
    );
  }

  if (_.isEmpty(tokenPlan) && _.isEmpty(transactionData)) {
    return (
      <Protected>
        <div className="MainContainer">
          <Header isSubScreen={true} />
          <CNoData
            message={getWords("SORRY_NO_DATA_FOUND")}
            hasfooter={true}
            hasheader={true}
          />
        </div>
      </Protected>
    );
  }

  return (
    <Protected>
      <div className="MainContainer">
        <Header isSubScreen={true} />
        <div className="CommonContainer brokrnpagemaincontainter">
          <div className="buytokensubmaindiv">
            <div className="buytokenMargin20">
              <span className="buytokentitletext">
                {getWords("BUY_TOKENS")}
              </span>
              <DisplayAd adUnit={Setting.ads_Units.TEST_BANNER_AD} />
            </div>
            {renderTopTokenList()}
            {renderTransactionList()}
          </div>
        </div>
        {renderPayment()}
        {renderAlert()}
        <NotificationPopup />
      </div>
    </Protected>
  );
}

export default BuyTokens;
