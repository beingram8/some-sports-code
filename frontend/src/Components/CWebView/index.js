import React, { useEffect, useState } from "react";
import renderHTML from "react-render-html";
import { useSelector } from "react-redux";
import PropTypes from "prop-types";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import Header from "../Header/index";
import { LANG_US, Setting } from "../../Utils/Setting";
import CAlert from "../../Components/CAlert/index";
import { getApiData } from "../../Utils/APIHelper";
import CNoData from "../../Components/CNoData/index";
import DisplayAd from "../../Components/Ads/DisplayAd";
import CCommonLoader from "../../Loaders/CCommonLoader/index.js";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";


const CWebView = (props) => {
  const { userdata } = useSelector((state) => state.auth);
  const [htmlData, setHTMLdata] = useState(null);
  const [loader, setLoader] = useState(true);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  const { slug, title } = props;

  useEffect(() => {
    getData();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  async function getData() {
    let eventData = {};
    let lang = LANG_US
    if (_.isEmpty(userdata)) {
      eventData = {
        user_name: "Guest User",
      };
    } else {
      eventData = true;
      lang = userdata.language;
    }

    try {
      let endPoint = `${Setting.endpoints.cms_detail}?slug=${slug}&lang=${lang}`;
      const response = await getApiData(endPoint, "GET", null);
      addAnalyticsEvent(`Check_${slug}_Page_Event`, eventData);
      if (response && response.status && response.status === true) {
        if (response && response.data && response.data.app_body) {
          setHTMLdata(response.data.app_body);
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
          setLoader(false);
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  const renderContent = () => {
    return (
      <div className="mainComMargin">
        <div className="MGB8">
          <span className="webviewTitle">{title}</span>
        </div>
        <DisplayAd adUnit={Setting.ads_Units.TEST_BANNER_AD} />
        {htmlData !== null ? (
          <div className="cwebviewContent">
            {renderHTML(_.toString(htmlData))}
          </div>
        ) : null}
      </div>
    );
  };

  return (
    <div className="MainContainer">
      <Header isSubScreen={true} />
      {loader ? (
        <CCommonLoader web={(window.innerWidth >= 600).toString()} />
      ) : !_.isEmpty(htmlData) ? (
        <div
          className="CommonContainer"
          style={{
            height: "calc(100% - 65px)",
            overflow: "auto",
          }}
        >
          {renderContent()}
        </div>
      ) : (
        <CNoData message={getWords("SORRY_NO_DATA_FOUND")} hasheader={true} />
      )}
      {renderAlert()}
    </div>
  );
};

CWebView.propTypes = {
  title: PropTypes.string,
  fromContact: PropTypes.bool,
  onChange: PropTypes.func,
  slug: PropTypes.string,
};

CWebView.defaultProps = {
  title: "",
  slug: "help",
  fromContact: false,
  onChange: PropTypes.func,
};

export default CWebView;
