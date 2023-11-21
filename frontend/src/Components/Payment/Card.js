import { useStripe, useElements, CardElement } from "@stripe/react-stripe-js";
import CircularProgress from "@material-ui/core/CircularProgress";
import React, { useState, useEffect } from "react";
import { useSelector } from "react-redux";
import _ from "lodash";
import CButton from "../CButton/index";
import { Setting } from "../../Utils/Setting";
import Checkbox from "@material-ui/core/Checkbox";
import CAlert from "../../Components/CAlert/index";
import deleteIcon from "../../Assets/Images/bin.png";
import CancelIcon from "../../Assets/Images/cancel_white.png";
import {
  getWords,
  addAnalyticsEvent,
  refreshUserData,
} from "../../commonFunctions";
import { getApiData, getAPIProgressData } from "../../Utils/APIHelper";

const CARD_OPTIONS = {
  iconStyle: "solid",
  style: {
    base: {
      iconColor: "#484848",
      color: "#000",
      fontWeight: 500,
      fontFamily: "segoeui",
      fontSize:
        window.innerWidth > 420
          ? "18px"
          : window.innerWidth > 370
          ? "15px"
          : "13px",
      fontSmoothing: "antialiased",
      ":-webkit-autofill": {
        color: "#484848",
      },
      "::placeholder": {
        color: "#484848",
      },
    },
    invalid: {
      iconColor: "#ed0f1b",
      color: "#ed0f1b",
    },
  },
};

const Card = (props) => {
  const { onClose, selectedPlan, onPaymentClick, payLoader } = props;
  const stripe = useStripe();
  const elements = useElements();
  const { userdata } = useSelector((state) => state.auth);
  const [cardList, setCardList] = useState([]);
  const [defaultCard, setDefaultCard] = useState({});
  // const [selectedCard, setSelectedCard] = useState(false);
  const [addNewCard, setAddNewCard] = useState(false);
  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [pageLoader, setPageLoader] = useState(true);
  const [saveCardLoad, setSaveCardLoad] = useState(false);
  const [callFunc, setCallFunction] = useState(false);
  const [closeCard, setCloseCard] = useState(false);

  const [payLoad, setPayLoad] = useState(false);

  useEffect(() => {
    getCardList();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const handleSubmit = async (event) => {
    if (!stripe || !elements) {
      return;
    }
    const cardElement = elements.getElement(CardElement);

    stripe.createToken(cardElement).then(function (result) {
      // Handle result.error or result.token
      if (result?.error?.message) {
        const errorMsg = result?.error?.message;
        showAlert(true, getWords("OOPS"), errorMsg);
      } else {
        addNewCardAPICall(result?.token?.id);
      }
    });
  };

  // make payment API call
  const makePaymentProcess = async () => {
    setPayLoad(true);
    const planId = selectedPlan?.id;
    try {
      let endPoint = `${Setting.endpoints.make_payment}?plan_id=${planId}&card_token=${defaultCard?.id}`;
      const response = await getAPIProgressData(endPoint, "POST", null, true);
      if (response?.status) {
        refreshUserData();
        setPayLoad(false);
        const eventData = {
          user_name: userdata?.username,
          first_name: userdata?.firstname,
          last_name: userdata?.lastname,
          email: userdata?.email,
          user_Pic: userdata?.user_image,
          purchase_Plan_id: planId,
        };

        addAnalyticsEvent("Make_Payment", eventData);
        showAlert(true, getWords("SUCCESS"), response?.message, false, true);
      } else {
        setPayLoad(false);
        const ErrorMsg = response?.message;
        showAlert(true, getWords("OOPS"), ErrorMsg);
      }
    } catch (err) {
      setPayLoad(false);
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  // card list api call
  const getCardList = async () => {
    setPageLoader(true);
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: userToken,
    };
    try {
      let endPoint = `${Setting.endpoints.payment_card_list}`;
      const response = await getApiData(endPoint, "GET", null, header);

      if (response?.status) {
        setPageLoader(false);
        setCardList(response?.data?.cards?.data);
      } else {
        setPageLoader(false);
      }
    } catch (err) {
      setPageLoader(false);
      console.log("Catch Part", err);
    }
  };

  // add new card api call
  const addNewCardAPICall = async (CardTokenValue) => {
    setSaveCardLoad(true);
    try {
      let endPoint = `${Setting.endpoints.add_new_card}?token=${CardTokenValue}`;
      const response = await getAPIProgressData(endPoint, "POST", null, true);
      const eventData = {
        user_name: userdata?.username,
        first_name: userdata?.firstname,
        last_name: userdata?.lastname,
        email: userdata?.email,
        user_Pic: userdata?.user_image,
        new_add_card_id: CardTokenValue,
      };

      if (response?.status) {
        setPageLoader(true);
        getCardList();
        setAddNewCard(false);
        addAnalyticsEvent("Add_New_Payment_Card_Event", eventData);
      } else {
        const ErrorMsg = response?.message;
        showAlert(true, getWords("OOPS"), ErrorMsg);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  // delete card api call
  const deleteCard = async () => {
    const deletedCardId = defaultCard?.id;
    setPageLoader(true);
    try {
      let endPoint = `${Setting.endpoints.delete_card}?token=${deletedCardId}`;
      const response = await getAPIProgressData(endPoint, "POST", null, true);

      const eventData = {
        user_name: userdata?.username,
        first_name: userdata?.firstname,
        last_name: userdata?.lastname,
        email: userdata?.email,
        user_Pic: userdata?.user_image,
        delete_card_id: deletedCardId,
      };

      if (response?.status) {
        getCardList();
        addAnalyticsEvent("Delete_Payment_Card_Event", eventData);
      } else {
        const ErrorMsg = response?.message;
        showAlert(true, getWords("OOPS"), ErrorMsg);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  const showAlert = (open, title, message, callFunction, closeCard) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
    setCallFunction(callFunction);
    setCloseCard(closeCard);
  };

  function renderAlert() {
    return (
      <CAlert
        showCancel={callFunc}
        open={alertOpen}
        onClose={() => {
          setAlertOpen(false);
        }}
        onOkay={() => {
          if (callFunc) {
            deleteCard();
          } else if (closeCard) {
            onClose();
          }
          setSaveCardLoad(false);
          setAlertOpen(false);
          setPageLoader(false);
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  // modal header
  const renderHeader = (title) => {
    return (
      <div className="paymentHeaderBG">
        <div className="paymentmodalclosebutton">
          <img
            loading="lazy"
            src={CancelIcon}
            className="paymentmodalclosebuttonimage"
            alt={"CancelIcon"}
            onClick={() => {
              onClose();
            }}
          />
        </div>
        <span className="headerTextStylePayment">{title}</span>
      </div>
    );
  };

  function cardSelection(cardData) {
    const isAddedCard = _.isEqual(defaultCard, cardData);

    if (_.isEmpty(defaultCard)) {
      setDefaultCard(cardData);
    } else {
      if (isAddedCard) {
        setDefaultCard({});
      } else {
        setDefaultCard(cardData);
      }
    }
  }

  // card list
  const renderCardList = () => {
    if (pageLoader) {
      return (
        <div>
          {renderHeader(getWords("Card_Details"))}
          <div
            style={{
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              padding: "50px",
            }}
          >
            <CircularProgress
              style={{
                width: 15,
                height: 15,
                color: "#ED0F1B",
              }}
            />
          </div>
        </div>
      );
    }

    return (
      <div>
        {renderHeader(getWords("Card_Details"))}
        <div
          style={{
            height: "50vh",
            // backgroundColor: "pink",
          }}
          className="cardContentcontainer"
        >
          {_.isArray(cardList) && !_.isEmpty(cardList) ? (
            cardList?.map((item, index) => {
              const isEqual = _.isEqual(defaultCard, item);
              return (
                <div
                  key={index}
                  className="carListItemDiv"
                  // onClick={() => {
                  //   cardSelection(item);
                  //   !_.isEmpty(defaultCard) && isEqual
                  //     ? setSelectedCard(!selectedCard)
                  //     : setSelectedCard(true);
                  // }}
                >
                  <div
                    style={{
                      background: index % 2 === 0 ? "#EEEEEE" : "#FFFFFF",
                    }}
                    className="cardCenterALign"
                  >
                    <div
                      style={{
                        display: "flex",
                        justifyContent: "space-between",
                      }}
                    >
                      <span className="cardNameStyle">
                        {_.toUpper(item?.brand)}
                      </span>
                      {isEqual ? (
                        <div
                          onClick={() => {
                            showAlert(
                              true,
                              getWords("WARNING"),
                              getWords("Delete_Message"),
                              true
                            );
                          }}
                        >
                          <img
                            loading="lazy"
                            className="cardIconStyle"
                            src={deleteIcon}
                            alt={"DeleteIcon"}
                          />
                        </div>
                      ) : null}
                    </div>
                    <div
                      style={{
                        marginTop: 5,
                        display: "flex",
                        justifyContent: "space-between",
                      }}
                    >
                      <span className="cardNoStyle">
                        **** **** **** {item?.last4}
                      </span>
                      <span className="cardNoStyle" style={{ paddingLeft: 20 }}>
                        {`${item?.exp_month}/${item?.exp_year}`}
                      </span>
                    </div>

                    <div
                      style={{
                        display: "flex",
                        alignItems: "center",
                        marginTop: 5,
                      }}
                    >
                      <Checkbox
                        // checked={isEqual && selectedCard}
                        checked={isEqual}
                        onChange={
                          () => cardSelection(item)
                          // !_.isEmpty(defaultCard) && isEqual
                          //   ? setSelectedCard(!selectedCard)
                          //   : setSelectedCard(true)
                        }
                        size={window.innerWidth >= 370 ? "medium" : "small"}
                        style={{
                          padding: "0px 5px 0px 0px",
                          color: "#ED0F18",
                        }}
                      />
                      <span className="defaultCardTextStyle">
                        {getWords("Use_default_card")}
                      </span>
                    </div>
                  </div>
                </div>
              );
            })
          ) : (
            <div className="noCardAvailableDiv">
              <span>{getWords("No_cards")}</span>
            </div>
          )}
        </div>

        {_.isArray(cardList) &&
        !_.isEmpty(cardList) &&
        !_.isEmpty(defaultCard) ? (
          <CButton
            boldText={true}
            buttonStyle={{
              bottom: 0,
              width: "calc(100% - 60px)",
              // marginBottom: 20,
              marginLeft: 20,
              marginRight: 20,
              marginTop: 0,
            }}
            buttonText={
              payLoad || payLoader ? (
                <CircularProgress
                  style={{
                    width: 15,
                    height: 15,
                    color: "#FFFFFF",
                  }}
                />
              ) : (
                getWords("Pay")
              )
            }
            handleBtnClick={
              payLoad || payLoader
                ? null
                : () => {
                    if (onPaymentClick) {
                      onPaymentClick(defaultCard);
                    } else {
                      makePaymentProcess();
                    }
                  }
            }
          />
        ) : null}
        <CButton
          boldText={true}
          buttonStyle={{
            bottom: 0,
            width: "calc(100% - 60px)",
            marginBottom: 10,
            marginLeft: 20,
            marginRight: 20,
            marginTop: 10,
          }}
          addIcon={true}
          buttonText={getWords("Add_New_Card")}
          handleBtnClick={() => {
            setAddNewCard(true);
          }}
        />
      </div>
    );
  };

  // add new card
  const renderAddNewCard = () => {
    const isBool = _.isArray(cardList) && !_.isEmpty(cardList);
    return (
      <div>
        {renderHeader(getWords("Add_New_Card"))}
        <div
          style={{
            width: "100%",
            padding: "0px 0px",
            overflow: "auto",
            display: "flex",
          }}
        >
          <div className="cardContentcontainerAddNew">
            <CardElement options={CARD_OPTIONS} />
          </div>
          <div style={{ color: "transparent" }}>.</div>
        </div>

        <div
          style={{
            display: "flex",
            flexDirection: "column",
          }}
        >
          {isBool ? (
            <CButton
              boldText={true}
              outlined
              buttonStyle={{
                bottom: 0,
                margin: 10,
              }}
              buttonText={getWords("CARD_LIST")}
              handleBtnClick={() => {
                setAddNewCard(false);
              }}
            />
          ) : null}

          <CButton
            btnLoader={saveCardLoad}
            boldText={true}
            addIcon={!saveCardLoad}
            buttonStyle={{
              bottom: 0,
              margin: 10,
            }}
            buttonText={getWords("ADD_CARD")}
            handleBtnClick={() => {
              if (saveCardLoad) {
                return;
              } else {
                handleSubmit(false);
              }
            }}
          />
        </div>
      </div>
    );
  };

  return (
    <div>
      {addNewCard ? null : renderCardList()}
      {addNewCard ? renderAddNewCard() : null}
      {renderAlert()}
    </div>
  );
};

export default Card;
