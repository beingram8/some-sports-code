import FacebookLogin from "react-facebook-login/dist/facebook-login-render-props";
import CircularProgress from "@material-ui/core/CircularProgress";
import { isAndroid, isIOS } from "react-device-detect";
import { useSelector, useDispatch } from "react-redux";
import React, { useState } from "react";
import CButton from "../CButton";
import { Setting } from "../../Utils/Setting";
import CAlert from "../../Components/CAlert/index";
import { getAPIProgressData } from "../../Utils/APIHelper";
import { getWords } from "../../commonFunctions";
import authActions from "../../Redux/reducers/auth/actions";
import _ from "lodash";

const { setSelectedTeamData } = authActions;

const fbAppId = "204029194905792";

export default function FBLogin(props) {
  const { handleClose, from } = props;
  const dispatch = useDispatch();
  const { useruuid } = useSelector((state) => state.auth);
  const [btnLoader, setBtnLoader] = useState(false);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  const responseFacebook = (response) => {
    const accessToken = response?.accessToken;
    const platForm = isAndroid ? "Android" : isIOS ? "Ios" : "Android";

    if (_.isString(accessToken) && !_.isEmpty(accessToken)) {
      setBtnLoader(true);
      const data = {
        "SocialForm[social_type]": 1,
        "SocialForm[uuid]": useruuid,
        "SocialForm[platform]": platForm,
        "SocialForm[token]": accessToken,
      };
      LoginProcess(data);
    }
  };

  async function LoginProcess(data) {
    try {
      let endPoint = Setting.endpoints.social_login;

      if (from === "Signup") {
        endPoint = Setting.endpoints.social_sign_up;
      }

      const response = await getAPIProgressData(endPoint, "POST", data);

      if (response && response.status) {
        if (from === "Signup") {
          handleClose(response?.data);
          setBtnLoader(false);
        } else {
          setBtnLoader(false);
          const uData = response?.data;
          dispatch(setSelectedTeamData({}));
          handleClose(uData, "FB_Login_Event");
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

  return (
    <div>
      <FacebookLogin
        appId={fbAppId}
        autoLoad={false}
        fields="name,email,picture"
        scope="public_profile"
        disableMobileRedirect={true}
        callback={responseFacebook}
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
                getWords("SIGN_UP_WITH_FACEBOOK")
              ) : (
                getWords("SIGN_IN_WITH_FACEBOOK")
              )
            }
            socialFBLogin={true}
            handleSocialBtnClick={
              btnLoader
                ? null
                : () => {
                    renderProps.onClick();
                  }
            }
          />
        )}
      />
      {renderAlert()}
    </div>
  );
}
