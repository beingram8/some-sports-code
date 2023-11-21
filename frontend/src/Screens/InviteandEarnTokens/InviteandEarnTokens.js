import CircularProgress from "@material-ui/core/CircularProgress";
import React, { useState, useEffect } from "react";
import { RWebShare } from "react-web-share";
import { useSelector } from "react-redux";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import Header from "../../Components/Header";
import { Setting } from "../../Utils/Setting";
import { getWords } from "../../commonFunctions";
import CAlert from "../../Components/CAlert/index";
import { getApiData } from "../../Utils/APIHelper";
import Protected from "../../Components/Protected";
import DisplayAd from "../../Components/Ads/DisplayAd";
import playerimage from "../../Assets/Images/footballfans.jpg";
import NotificationPopup from "../../Components/NotificationPopup";

function InviteandEarnTokens(props) {
  const { userdata } = useSelector((state) => state.auth);
  const [pageLoader, setPageLoader] = useState(true);
  const [shareURL, setSharURL] = useState("");

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  useEffect(() => {
    referAndEarn();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    document.title = Setting.page_name.INVITE_EARN;
  }, []);

  async function referAndEarn() {
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: userToken,
    };
    try {
      const endPoint = Setting.endpoints.refer;
      const response = await getApiData(endPoint, "GET", null, header);
      if (response?.status) {
        setPageLoader(false);
        setSharURL(response?.data);
      } else {
        setPageLoader(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setPageLoader(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

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

  if (pageLoader) {
    return (
      <Protected>
        <div className="MainContainer">
          <Header isSubScreen={true} />
          <div className="CommonContainer inviteandearnmaincontainer">
            <CircularProgress className="inviteandearnprogress" />
          </div>
        </div>
      </Protected>
    );
  }

  return (
    <Protected>
      <div className="MainContainer">
        <Header isSubScreen={true} />
        <div className="CommonContainer editprofilemain">
          <div className="inviteandearnimagecontainer">
            <div class="iecutCorner">
              <img
                loading="lazy"
                src={playerimage}
                className="inviteandearnimage"
                alt="player"
              />
            </div>
          </div>
          <div className="ieinviteandearndesccontainer">
            <div className="inviteandearndesc">
              <span className="eititletext">
                {getWords("INVITE_A_FRIEND_AND_RECEIVE_25_TOKENS")}
              </span>
              <span className="eidesctext">
                {getWords("INVITE_FRIENDS_DESCRIPTION")}
              </span>

              <div style={{ width: "100%" }}>
                <DisplayAd adUnit={Setting.ads_Units.TEST_BANNER_AD} />
              </div>

              {_.isString(shareURL) && !_.isEmpty(shareURL) ? (
                <RWebShare
                  data={{
                    text: getWords("INVITE_INFO"),
                    url: shareURL,
                    title: "fanratingweb.com",
                  }}
                  onClick={() => {
                    console.log("shared successfully!");
                    // history.push("/rate");
                  }}
                >
                  <div className="inviteandearnsharebtn">
                    <span className="inviteandearnsharebtn2">
                      {getWords("INVITE_AND_EARN")}
                    </span>
                  </div>
                </RWebShare>
              ) : null}
            </div>
          </div>
        </div>
        {renderAlert()}
        <NotificationPopup />
      </div>
    </Protected>
  );
}

export default InviteandEarnTokens;
