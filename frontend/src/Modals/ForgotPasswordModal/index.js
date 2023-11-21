import React, { useState } from "react";
import { withStyles } from "@material-ui/core/styles";
import Dialog from "@material-ui/core/Dialog";
import MuiDialogContent from "@material-ui/core/DialogContent";
import PropTypes from "prop-types";
import "./styles.scss";
import CancelIcon from "../../Assets/Images/cancel_white.png";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";
import { getAPIProgressData } from "../../Utils/APIHelper";
import { Setting } from "../../Utils/Setting";
import CAlert from "../../Components/CAlert/index";
import CButton from "../../Components/CButton";

const DialogContent = withStyles((theme) => ({
  root: {
    padding: "0px",
    margin: "0px",
  },
}))(MuiDialogContent);

function ForgotPasswordModal(props) {
  const { handleClose, forgorPwdModal } = props;

  const [email, setEmail] = useState("");
  const [errorEmailMessage, setErrorEmailMessage] = useState("");
  const [saveBtnLoad, setSaveBtnLoad] = useState(false);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [callFunc, setCallFunction] = useState(false);

  const emailregex =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

  function cleanStateData() {
    handleClose();
    setEmail("");
    setErrorEmailMessage("");
    setSaveBtnLoad(false);
    setAlertOpen(false);
    setAlertTitle("");
    setAlertMessage("");
    setCallFunction(false);
  }

  function handleSubmit() {
    let valid = true;

    if (email === "") {
      setErrorEmailMessage(getWords("ENTER_YOUR_EMAIL"));
      return (valid = false);
    } else if (!emailregex.test(email)) {
      setErrorEmailMessage(getWords("PLEASE_ENTER_VALID_EMAIL_ADDRESS"));
      return (valid = false);
    } else if (valid === true) {
      setErrorEmailMessage("");
      resetPwdProcess();
    }
  }

  async function resetPwdProcess() {
    setSaveBtnLoad(true);
    const resetPwdData = {
      "PasswordResetRequestForm[email]": email,
    };

    try {
      let endPoint = Setting.endpoints.reset_password;
      const response = await getAPIProgressData(endPoint, "POST", resetPwdData);
      if (response?.status) {
        setSaveBtnLoad(false);
        addAnalyticsEvent("Forgot_Password_Event", {
          user_mail: email,
        });
        showAlert(true, getWords("SUCCESS"), response?.message, true);
      } else {
        setSaveBtnLoad(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      setSaveBtnLoad(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
      console.log("Catch Part", err);
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
          if (callFunc) {
            cleanStateData();
          } else {
            setAlertOpen(false);
          }
        }}
        onOkay={() => {
          if (callFunc) {
            handleClose();
            setEmail("");
          }
          setAlertOpen(false);
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  return (
    <Dialog
      onClose={() => {
        cleanStateData();
      }}
      open={forgorPwdModal}
      transitionDuration={500}
      className="maindialog"
    >
      <DialogContent className={"submaindialogFd"}>
        <meta
          name="viewport"
          content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"
        />
        <div style={{ height: "100%" }}>
          <div className="headerFD">
            <div className="closeIconDiv">
              <img
                loading="lazy"
                src={CancelIcon}
                className="closeIconStyle"
                onClick={() => {
                  cleanStateData();
                }}
                alt={"CancelIcon"}
              />
            </div>

            <span className="titleStyleFP">{getWords("FORGOT_PASSWORD")}</span>
          </div>
          <div className="contentDiv">
            <div
              style={{
                marginTop: 5,
              }}
            >
              <span className="textStyle">{getWords("EMAIL")}</span>
              <input
                autoComplete="false"
                type="email"
                id="email"
                name="email"
                className="inputstyle"
                onChange={
                  saveBtnLoad
                    ? null
                    : (e) => {
                      setEmail(e.target.value);
                    }
                }
                value={email}
              />
              {email === "" || !emailregex.test(email) ? (
                <div>
                  <span className="loginmodalerrormsg">
                    {errorEmailMessage}
                  </span>
                </div>
              ) : null}
            </div>

            <CButton
              btnLoader={saveBtnLoad}
              boldText={true}
              buttonText={getWords("RESET_PASSWORD")}
              buttonStyle={{
                bottom: window.innerWidth >= 640 ? -20 : -8,
              }}
              handleBtnClick={() => {
                if (saveBtnLoad) {
                  return;
                } else {
                  handleSubmit();
                }
              }}
            />
          </div>
        </div>
      </DialogContent>
      {renderAlert()}
    </Dialog>
  );
}

ForgotPasswordModal.propTypes = {
  handleClickOpen: PropTypes.func,
  forgorPwdModal: PropTypes.bool,
  handleClose: PropTypes.func,
  onSavePassword: PropTypes.func,
};

ForgotPasswordModal.defaultProps = {
  handleClickOpen: () => { },
  forgorPwdModal: false,
  handleClose: () => { },
  onSavePassword: () => { },
};

export default ForgotPasswordModal;
