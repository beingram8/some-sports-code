import React, { useState, useEffect } from "react";
import Button from "@material-ui/core/Button";
import { useHistory } from "react-router-dom";
import { withStyles } from "@material-ui/core/styles";
import Lottie from "react-lottie";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import { Setting } from "../../Utils/Setting";
import { getAPIProgressData } from "../../Utils/APIHelper";
import CRequestLoader from "../../Loaders/CRequestLoader/index";
import SuccessJson from "../../Assets/Lottie/checkLottie.json";
import FailJson from "../../Assets/Lottie/failLottie.json";
import CAlert from "../../Components/CAlert/index";
import { getWords } from "../../commonFunctions";
const ColorButton = withStyles((theme) => ({
  root: {
    width: "500px",
    borderRadius: 5,
    color: theme.palette.getContrastText("#ED0F1B"),
    backgroundColor: "#ED0F1B",
    "&:hover": {
      backgroundColor: "#e00",
    },
  },
}))(Button);

function Verification(props) {
  const history = useHistory();
  const resetTokenValue =
    props &&
    props.location &&
    props.location.search &&
    !_.isEmpty(props.location.search)
      ? _.toString(props.location.search)
      : "";
  const finalTokenValue =
    !_.isEmpty(resetTokenValue) && _.isString(resetTokenValue)
      ? resetTokenValue.substring(1)
      : "-";

  const [pageLoader, setPageLoader] = useState(false);
  const [isVerify, setIsVerify] = useState(false);
  const [displayIcon, setDisplayIcon] = useState(false);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  useEffect(() => {
    verifyEmail();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    document.title = Setting.page_name.VERIFICATION;
  }, []);

  async function verifyEmail() {
    setPageLoader(true);
    try {
      let endPoint = `${Setting.endpoints.verify_email}?token=${finalTokenValue}`;
      const response = await getAPIProgressData(endPoint, "POST");
      setPageLoader(false);
      setDisplayIcon(true);
      setIsVerify(response?.status);
    } catch (err) {
      setPageLoader(false);
      console.log("Catch Part", err);
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

  function renderSuccessInfo() {
    return (
      <div className="successInfoCon">
        <Lottie
          options={{
            loop: true,
            autoplay: true,
            animationData: displayIcon
              ? isVerify
                ? SuccessJson
                : FailJson
              : null,
          }}
          style={{
            padding: "0px !important",
          }}
          height={100}
          width={100}
        />
        {displayIcon ? (
          <div
            className="iconDivVF"
            style={{
              top: isVerify ? -50 : 0,
            }}
          >
            <span className="verifyText">
              {isVerify ? getWords("VERIFIED") : getWords("NOT_VERIFIED")}
            </span>
            <span className="verifyTextMessage">
              {isVerify
                ? getWords("VERIFICATION_SUCCESS")
                : getWords("VERIFICATION_FAILED")}
            </span>

            {isVerify ? (
              <div className="margin20Div">
                <ColorButton
                  id="savepassword"
                  variant="contained"
                  color="secondary"
                  onClick={() => {
                    history.push("/rate");
                  }}
                >
                  <span className="goTextStyle">{getWords("GO")}</span>
                </ColorButton>
              </div>
            ) : null}
          </div>
        ) : null}
      </div>
    );
  }

  return (
    <div>
      <div
        onClick={() => {
          history.push("/rate");
        }}
        className="headerCon"
      >
        <span className="HTitle">FAN RATING!</span>
      </div>
      <div
        className="emailContentDiv"
        style={{
          height: window.innerHeight / 1.18,
        }}
      >
        {pageLoader ? (
          <div>
            <p className="emailVerificationP">
              {getWords("EMAIL_VERIFICATION")}
            </p>
            <CRequestLoader openModal={pageLoader} />
          </div>
        ) : null}
        <div>
          <div className="successInfoStyle">{renderSuccessInfo()}</div>
        </div>
      </div>
      {renderAlert()}
    </div>
  );
}

export default Verification;
