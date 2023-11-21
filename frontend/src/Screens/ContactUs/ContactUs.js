import React, { useState, useEffect } from "react";
import { useHistory } from "react-router-dom";
import "./styles.scss";
import "../../Styles/common.scss";
import { Setting } from "../../Utils/Setting";
import CButton from "../../Components/CButton";
import Header from "../../Components/Header/index";
import CAlert from "../../Components/CAlert/index";
import DisplayAd from "../../Components/Ads/DisplayAd";
import { getAPIProgressData } from "../../Utils/APIHelper";
import NotificationPopup from "../../Components/NotificationPopup";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";

function ContactUs() {
  const history = useHistory();
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [message, setMessage] = useState("");
  const [loader, setLoader] = useState(false);
  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [issuccess, setIsSuccess] = useState("false");

  useEffect(() => {
    document.title = Setting.page_name.CONTACT_US;
  }, []);

  const showAlert = (open, title, message, istrue) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
    setIsSuccess(istrue);
  };

  function clearData() {
    setName("");
    setEmail("");
    setMessage("");
    setLoader(false);
    setAlertOpen(false);
    history.goBack();
  }

  function closeAlert() {
    if (issuccess) {
      clearData();
    } else {
      setAlertOpen(false);
    }
  }

  function renderAlert() {
    return (
      <CAlert
        open={alertOpen}
        onClose={() => {
          closeAlert();
        }}
        onOkay={() => {
          closeAlert();
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  async function saveFeedBack() {
    setLoader(true);
    const feedBackData = {
      "ContactForm[name]": name,
      "ContactForm[email]": email,
      "ContactForm[body]": message,
    };

    try {
      let endPoint = Setting.endpoints.contact_us;
      const response = await getAPIProgressData(endPoint, "POST", feedBackData);
      if (response?.status) {
        const uData = {
          name: name,
          email: email,
          message: message,
        };
        addAnalyticsEvent("FeedBack_event", uData);
        showAlert(true, getWords("SUCCESS"), response?.message, true);
      } else {
        setLoader(false);
        showAlert(true, getWords("OOPS"), response?.message, false);
      }
    } catch (err) {
      showAlert(
        true,
        getWords("WARNING"),
        getWords("Something_went_wrong"),
        false
      );
      console.log("Catch Part", err);
      setLoader(false);
    }
  }

  const renderContactUs = () => {
    return (
      <div className="CommonContainer addpostmaincontainer">
        <div
          style={{
            paddingTop: window.innerWidth >= 500 ? 25 : 15,
          }}
        >
          <span className="contectUsTitle">{getWords("CONTACT_US")}</span>
        </div>

        <div className="contactInfoContainer">
          <div className="divInfoUI">
            <span className="contactUStextStyleCU">{getWords("NAME")}</span>
            <input
              autoComplete="false"
              type="text"
              id="name"
              name="name"
              className="contectUsInputUI"
              value={name}
              onChange={loader ? null : (e) => setName(e.target.value)}
            />
          </div>
          <div className="divInfoUI">
            <span className="contactUStextStyleCU">{getWords("EMAIL")}</span>
            <input
              autoComplete="false"
              type="email"
              id="email"
              name="email"
              className="contectUsInputUI"
              value={email}
              onChange={loader ? null : (e) => setEmail(e.target.value)}
            />
          </div>
        </div>

        <div className="contactUsMessageDivCU buytokenmargintop">
          <span className="contactUStextStyleCU">{getWords("MESSAGE")}</span>
          <textarea
            autoComplete="false"
            type="text"
            id="message"
            name="message"
            className="ContactTextAreaCU"
            value={message}
            onChange={loader ? null : (e) => setMessage(e.target.value)}
            rows={8}
          />
        </div>

        <CButton
          buttonStyle={{
            width:
              window.innerWidth > 825
                ? 310
                : window.innerWidth > 639
                  ? "43%"
                  : window.innerWidth > 400
                    ? "calc(46% - 30px)"
                    : window.innerWidth > 300
                      ? "none"
                      : "none",
            marginLeft: window.innerWidth > 639 ? 0 : 15,
            marginRight: window.innerWidth > 639 ? 0 : 15,
            marginTop: 25,
          }}
          btnLoader={loader}
          buttonText={getWords("SUBMIT")}
          handleBtnClick={() => {
            if (loader) {
              return;
            } else {
              saveFeedBack();
            }
          }}
        />
        <DisplayAd adUnit={Setting.ads_Units.TEST_BANNER_AD} />
      </div>
    );
  };

  return (
    <div className="MainContainer">
      <Header isSubScreen={true} />
      <div className="brokrnpagemaincontainter">
        <div className="mainContactUsSubCon">
          {renderContactUs()}
          {renderAlert()}
        </div>
      </div>
      <NotificationPopup />
    </div>
  );
}

export default ContactUs;
