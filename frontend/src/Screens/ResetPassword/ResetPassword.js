import React, { useState, useEffect } from "react";
import CircularProgress from "@material-ui/core/CircularProgress";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import { Setting } from "../../Utils/Setting";
import { getWords } from "../../commonFunctions";
import { getAPIProgressData } from "../../Utils/APIHelper";
import CAlert from "../../Components/CAlert/index";
import { useHistory } from "react-router-dom";

function ResetPassword(props) {
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

  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [errorPwdMessage, setErrorPwdMessage] = useState("");
  const [errorCPwdMessage, setErrorCPwdMessage] = useState("");
  const [saveBtnLoad, setSaveBtnLoad] = useState(false);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [callFunc, setCallFunction] = useState(false);

  useEffect(() => {
    document.title = Setting.page_name.RESET_PASSWORD;
  }, []);

  function handleSubmit() {
    let valid = true;

    if (password === "") {
      setErrorPwdMessage("Enter your password");
      return (valid = false);
    } else if (password.length < 8) {
      setErrorPwdMessage(getWords("PASSWORD_MUST_BE_MINIMUM_OF_8_CHARACTERS"));
      return (valid = false);
    } else if (confirmPassword === "") {
      setErrorCPwdMessage("Enter Confirm Password");
      return (valid = false);
    } else if (confirmPassword !== password) {
      setErrorCPwdMessage("Password and Confirm Password must be same");
      return (valid = false);
    } else if (valid === true) {
      setErrorPwdMessage("");
      setErrorCPwdMessage("");
      resetPwdProcess();
    }
  }

  async function resetPwdProcess() {
    setSaveBtnLoad(true);
    const resetPwdData = {
      "ResetPasswordForm[password]": password,
    };

    try {
      let endPoint = `${Setting.endpoints.forgot_password}?token=${finalTokenValue}`;
      const response = await getAPIProgressData(endPoint, "POST", resetPwdData);
      if (response?.status) {
        setSaveBtnLoad(false);
        setPassword("");
        setConfirmPassword("");
        showAlert(
          true,
          getWords("SUCCESS"),
          getWords("Password_Success"),
          true
        );

        // redirect to team list screen
        // history.push("/");
      } else {
        setSaveBtnLoad(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      setSaveBtnLoad(false);
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  const showAlert = (open, title, message, callFunction) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
    setCallFunction(callFunction);
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
          if (callFunc) {
            history.push("/");
          }
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  return (
    <div className="mainResetPwdInfoCon">
      <div
        onClick={() => {
          history.push("/rate");
        }}
        className="resetPwdheaderCon"
      >
        <span className="resetPwdHTitle">FAN RATING!</span>
      </div>
      <div className="resetDivCenter">
        <div className="ResetPwdContainer">
          <div className="ResetPwdDivCU" style={{ marginTop: "10px" }}>
            <span className="ResetTextStyleCU">{getWords("NEW_PASSWORD")}</span>
            <input
              autoComplete="false"
              type="password"
              id="new_password"
              name="new_password"
              className="ResetInputtextCU"
              value={password}
              onChange={
                saveBtnLoad
                  ? null
                  : (e) => {
                    setPassword(e.target.value);
                  }
              }
            />
            {password === "" || password.length < 8 ? (
              <span className="resetPwdErr">{errorPwdMessage}</span>
            ) : null}
          </div>
          <div className="ResetPwdDivCU" style={{ marginTop: "10px" }}>
            <span className="ResetTextStyleCU">
              {getWords("CONFIRM_NEW_PASSWORD")}
            </span>
            <input
              autoComplete="false"
              type="password"
              id="confirm_new_password"
              name="confirm_new_password"
              className="ResetInputtextCU"
              value={confirmPassword}
              onChange={
                saveBtnLoad
                  ? null
                  : (e) => {
                    setConfirmPassword(e.target.value);
                  }
              }
            />
            {confirmPassword === "" || confirmPassword !== password ? (
              <span className="resetPwdErr">{errorCPwdMessage}</span>
            ) : null}
          </div>

          <div
            onClick={
              saveBtnLoad
                ? null
                : () => {
                  handleSubmit();
                }
            }
            style={{
              cursor: "pointer",
            }}
            className="ResetButtonDivCU"
          >
            {saveBtnLoad ? (
              <CircularProgress
                style={{
                  width: 20,
                  height: 20,
                  marginLeft: 20,
                  color: "white",
                }}
              />
            ) : (
              <span className="ResetButtonTextCU">
                {getWords("SAVE_PASSWORD")}
              </span>
            )}
          </div>
        </div>
      </div>
      {renderAlert()}
    </div>
  );
}

export default ResetPassword;
