import CircularProgress from "@material-ui/core/CircularProgress";
import { isAndroid, isIOS } from "react-device-detect";
import _ from "lodash";
import React, { useState } from "react";
import CButton from "../CButton/index";
import { useSelector, useDispatch } from "react-redux";
import GoogleLogin from "react-google-login";
import { Setting } from "../../Utils/Setting";
import CAlert from "../../Components/CAlert/index";
import { getAPIProgressData } from "../../Utils/APIHelper";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";
import authActions from "../../Redux/reducers/auth/actions";

const { setSelectedTeamData } = authActions;

const clientId =
  "268007771217-jt2p5uqj08r9j2opev56vlamp4qpnkn8.apps.googleusercontent.com"; // frpwa-316409 //

export default function GmailLogin(props) {
  const { handleClose, from } = props;
  const dispatch = useDispatch();
  const { useruuid } = useSelector((state) => state.auth);
  const [btnLoader, setBtnLoader] = useState(false);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  function responseGoogle(response) {
    console.log("response -> responseGoogle=======>>>>>> ", response);
    const accessToken = response?.accessToken;
    const platForm = isAndroid ? "Android" : isIOS ? "Ios" : "Android";
    console.log("platForm =======>>>>>> ", platForm);

    if (_.isString(accessToken) && !_.isEmpty(accessToken)) {
      const data = {
        "SocialForm[social_type]": 2,
        "SocialForm[uuid]": useruuid,
        "SocialForm[platform]": platForm,
        "SocialForm[token]": accessToken,
      };
      LoginProcess(data);
    }
  }

  async function LoginProcess(data) {
    try {
      let endPoint = Setting.endpoints.social_login;

      if (from === "Signup") {
        endPoint = Setting.endpoints.social_sign_up;
      }

      const response = await getAPIProgressData(endPoint, "POST", data);

      console.log("response -> LoginProcess =====>>>>>>> ", response);
      if (response && response.status) {
        if (from === "Signup") {
          handleClose(response?.data);
          setBtnLoader(false);
        } else {
          setBtnLoader(false);
          const uData = response?.data;
          dispatch(setSelectedTeamData({}));
          handleClose(uData, "Gmail_Login_Event");
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

  return (
    <div>
      <GoogleLogin
        clientId={clientId}
        onSuccess={responseGoogle}
        onFailure={(fail) => {
          console.log("google failed -> ", fail);
        }}
        onAutoLoadFinished={() => { }}
        autoLoad={false}
        cookiePolicy={"single_host_origin"}
        render={(renderProps) => (
          <CButton
            buttonText={
              btnLoader ? (
                <CircularProgress
                  style={{
                    width: 15,
                    height: 15,
                    color: "#FFFFFF",
                  }}
                />
              ) : from === "Signup" ? (
                getWords("SIGN_UP_WITH_GOOGLE")
              ) : (
                getWords("SIGN_IN_WITH_GOOGLE")
              )
            }
            socialGoogleLogin={true}
            handleSocialBtnClick={() => {
              console.log("google clicked!");
              if (renderProps.disabled || btnLoader) {
                return null;
              } else {
                renderProps.onClick();
              }
            }}
          />
        )}
      />
      {renderAlert()}
    </div>
  );
}
