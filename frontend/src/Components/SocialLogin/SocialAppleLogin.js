import CircularProgress from "@material-ui/core/CircularProgress";
import {
  isIOS,
  isMacOs,
  isIOS13,
  isIPad13,
  isIPhone13,
  isIPod13,
} from "react-device-detect";
import { useSelector, useDispatch } from "react-redux";
import firebase from "firebase/app";
import _ from "lodash";
import React, { useState } from "react";
import { IconContext } from "react-icons";
import { AiFillApple } from "react-icons/ai";
import { Setting } from "../../Utils/Setting";
import CAlert from "../../Components/CAlert/index";
import { getAPIProgressData } from "../../Utils/APIHelper";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";
import authActions from "../../Redux/reducers/auth/actions";
import "./styles.scss";

const { setSelectedTeamData } = authActions;

// const clientId = "com.app.fanratingpwa"; // Service ID //

export default function SocialAppleLogin(props) {
  const { handleClose, from } = props;
  const dispatch = useDispatch();

  const { useruuid } = useSelector((state) => state.auth);
  const [btnLoader, setBtnLoader] = useState(false);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  async function signInWithApple() {
    const auth = firebase.auth();
    const provider = new firebase.auth.OAuthProvider("apple.com");
    provider.addScope("email");
    provider.addScope("name");
    const result = await auth.signInWithPopup(provider);

    const idTokenValue = result?.credential?.idToken;

    if (_.isString(idTokenValue) && !_.isEmpty(idTokenValue)) {
      const data = {
        "SocialForm[social_type]": 3,
        "SocialForm[uuid]": useruuid,
        "SocialForm[platform]": "Ios",
        "SocialForm[token]": idTokenValue,
      };
      appleLoginProcess(data);
    } else {
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  async function appleLoginProcess(data) {
    setBtnLoader(true);
    try {
      let endPoint = "";

      if (from === "Signup") {
        endPoint = Setting.endpoints.social_sign_up;
      } else {
        endPoint = Setting.endpoints.social_login;
      }

      const response = await getAPIProgressData(endPoint, "POST", data);

      if (response && response.status) {
        const uData = response?.data;
        if (from === "Signup") {
          handleClose(uData);
          setBtnLoader(false);
        } else {
          setBtnLoader(false);
          dispatch(setSelectedTeamData({}));
          handleClose(uData, "Apple_Login_Event");
        }
      } else {
        setBtnLoader(false);
        showAlert(
          true,
          getWords("OOPS"),
          response?.message ? response.message : response?.message
        );
      }
    } catch (err) {
      setBtnLoader(false);
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

  // Detects if device is in standalone mode
  const isInStandaloneMode = () =>
    "standalone" in window.navigator && window.navigator.standalone;

  if (isIOS || isMacOs || isIOS13 || isIPad13 || isIPhone13 || isIPad13) {
    if (!isInStandaloneMode()) {
      return (
        <div>
          <div
            className="appleButtonMainCon"
            onClick={
              btnLoader
                ? null
                : () => {
                  signInWithApple();
                }
            }
          >
            <div className="appleIconSty">
              <IconContext.Provider
                value={{
                  color: "#FFFFFF",
                }}
              >
                <AiFillApple />
              </IconContext.Provider>
            </div>
            {btnLoader ? (
              <CircularProgress
                style={{
                  width: 15,
                  height: 15,
                  color: "#FFFFFF",
                }}
              />
            ) : (
              <span className="appleButtonTextSty">
                {from === "Signup"
                  ? getWords("SIGN_UP_WITH_APPLE")
                  : getWords("SIGN_IN_WITH_APPLE")}
              </span>
            )}
          </div>
          {renderAlert()}
        </div>
      );
    } else {
      return (
        <div className="appleErrorCon">
          <span className="appleErrorTextSty">{getWords("APPLE_WARN")}</span>
        </div>
      );
    }
  }
  return null;
}
