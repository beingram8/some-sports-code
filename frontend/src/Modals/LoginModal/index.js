import PropTypes from "prop-types";
import React, { useState } from "react";
import Dialog from "@material-ui/core/Dialog";
import MuiDialogContent from "@material-ui/core/DialogContent";
import { useDispatch } from "react-redux";
import { withStyles } from "@material-ui/core/styles";
import "./styles.scss";
import FBLogin from "../../Components/SocialLogin/FBLogin";
import CancelIcon from "../../Assets/Images/cancel_white.png";
import GmailLogin from "../../Components/SocialLogin/GmailLogin";
import { getWords } from "../../commonFunctions";
import CAlert from "../../Components/CAlert/index";
import { getAPIProgressData } from "../../Utils/APIHelper";
import { Setting } from "../../Utils/Setting";
import CButton from "../../Components/CButton/index";
import authActions from "../../Redux/reducers/auth/actions";
import SocialAppleLogin from "../../Components/SocialLogin/SocialAppleLogin";

const { setSelectedTeamData } = authActions;

const DialogContent = withStyles((theme) => ({
  root: {
    padding: "0px",
    margin: "0px",
  },
}))(MuiDialogContent);

function LoginModal(props) {
  const { handleClose, loginModal, onSignupClick, onForgotPasswordClick } =
    props;

  const dispatch = useDispatch();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [errorPwdMessage, setErrorPwdMessage] = useState("");
  const [errorEmailMessage, setErrorEmailMessage] = useState("");
  const [btnLoader, setBtnLoader] = useState(false);
  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const emailregex =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

  let emailREf = React.createRef();
  let passwordREf = React.createRef();

  function handleSubmit() {
    let valid = true;

    if (email === "") {
      setErrorEmailMessage(getWords("ENTER_YOUR_EMAIL"));
      return (valid = false);
    } else if (!emailregex.test(email)) {
      setErrorEmailMessage(getWords("PLEASE_ENTER_VALID_EMAIL_ADDRESS"));
      return (valid = false);
    } else if (password === "") {
      setErrorPwdMessage(getWords("ENTER_YOUR_PASSWORD"));
      return (valid = false);
    }
    // else if (password.length < 8) {
    //   setErrorPwdMessage(getWords("PASSWORD_MUST_BE_MINIMUM_OF_8_CHARACTERS"));
    //   return (valid = false);
    // }
    else if (valid === true) {
      loginProcess();
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

  async function loginProcess() {
    setErrorEmailMessage("");
    setErrorPwdMessage("");
    setBtnLoader(true);

    const loginData = {
      "LoginForm[email]": email,
      "LoginForm[password]": password,
    };

    try {
      let endPoint = Setting.endpoints.login;
      getAPIProgressData(endPoint, "post", loginData)
        .then((result) => {
          if (result?.status) {
            setEmail("");
            setPassword("");
            setBtnLoader(false);
            dispatch(setSelectedTeamData({}));
            const uData = result?.data;
            handleClose(uData);
          } else {
            setBtnLoader(false);
            showAlert(
              true,
              getWords("OOPS"),
              result?.message ? result?.message : result?.message
            );
          }
        })
        .catch((err) => {
          setBtnLoader(false);
          showAlert(
            true,
            getWords("WARNING"),
            getWords("Something_went_wrong")
          );
        });
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
      setBtnLoader(false);
    }
  }

  function renderHeader() {
    return (
      <div className="loginmodalsigninheading">
        <div className="loginmodalclosebutton">
          <img
            loading="lazy"
            src={CancelIcon}
            className="loginmodalclosebuttonimage"
            onClick={() => {
              handleClose();
            }}
            alt={"cancelIcon"}
          />
        </div>
        <span className="loginmodalsigninheadingtext">
          {getWords("SIGN_IN")}
        </span>
      </div>
    );
  }

  function renderSocialButtons() {
    return (
      <div>
        <FBLogin handleClose={handleClose} />
        <GmailLogin handleClose={handleClose} />
        <SocialAppleLogin handleClose={handleClose} />
      </div>
    );
  }

  function renderSignUpCon() {
    return (
      <div className="loginmodalnoaccountcontainer">
        <span className="loginmodalnoaccounttext">
          {getWords("NO_ACCOUNT")}
        </span>
        <span
          className="loginmodalsignuptext"
          onClick={() => {
            onSignupClick();
          }}
        >
          {" "}
          {getWords("SIGN_UP_CAPITAL")}
        </span>
      </div>
    );
  }

  function displayErrorMsg(msg) {
    return <span className="loginmodalerrormsg">{msg}</span>;
  }

  function renderForgotPwdCon() {
    return (
      <div className="loginmodalforgotpasswordcontainer">
        <span
          className="loginmodalforgotpassword"
          onClick={() => {
            onForgotPasswordClick();
          }}
        >
          {getWords("Forgot_Password")}
        </span>
      </div>
    );
  }

  function renderSubmitButton() {
    return (
      <CButton
        btnLoader={btnLoader}
        buttonText={getWords("SIGN_IN")}
        handleBtnClick={
          btnLoader
            ? null
            : () => {
              handleSubmit();
            }
        }
      />
    );
  }

  // handle enter key from keyboard
  function handleKeyEnter(e) {
    e.which = e.which || e.keyCode;

    // If the key press is Enter
    // eslint-disable-next-line eqeqeq
    if (e.which == 13) {
      switch (e.target.id) {
        case "email":
          passwordREf.current.focus();
          break;
        case "password":
          handleSubmit();
          break;

        default:
          break;
      }
    }
  }

  function renderLoginForm() {
    return (
      <div className="loginmodalcontentcontainer">
        <div className="loginmodalcontent">
          <span className="loginmodalemailpasswordtext">
            {getWords("EMAIL")}
          </span>
          <input
            autoComplete="false"
            type="email"
            id="email"
            name="email"
            className="loginmodalinputtext"
            onChange={
              btnLoader
                ? null
                : (e) => {
                  setEmail(e.target.value);
                }
            }
            value={email}
            ref={emailREf}
            onKeyPress={(e) => {
              handleKeyEnter(e);
            }}
          />
          {email === "" || !emailregex.test(email)
            ? displayErrorMsg(errorEmailMessage)
            : null}

          <span className="loginmodalemailpasswordtext">
            {getWords("PASSWORD")}
          </span>
          <input
            autoComplete="false"
            type="password"
            id="password"
            name="password"
            className="loginmodalinputtext"
            onChange={
              btnLoader
                ? null
                : (e) => {
                  setPassword(e.target.value);
                }
            }
            value={password}
            ref={passwordREf}
            onKeyPress={(e) => {
              handleKeyEnter(e);
            }}
          />
          {password === "" || password.length < 8
            ? displayErrorMsg(errorPwdMessage)
            : null}

          {renderForgotPwdCon()}
          {renderSubmitButton()}

          <div className="loginmodalorcontainer">
            <div className="loginOrdividerContainer">
              <div className="loginDivider" />
              <span className="loginmodalortext">{getWords("OR")}</span>
              <div className="loginDivider" />
            </div>
          </div>

          {renderSocialButtons()}
          {renderSignUpCon()}
        </div>
      </div>
    );
  }

  return (
    <Dialog
      onClose={() => {
        handleClose({});
      }}
      open={loginModal}
      transitionDuration={500}
      className="maindialog"
    >
      <meta
        name="viewport"
        content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"
      />
      <DialogContent className="loginmodalsubmaindialog">
        {renderHeader()}
        {renderLoginForm()}
        {renderAlert()}
      </DialogContent>
    </Dialog>
  );
}

LoginModal.propTypes = {
  loginModal: PropTypes.bool,
  handleClose: PropTypes.func,
  handleClickOpen: PropTypes.func,
};

LoginModal.defaultProps = {
  loginModal: false,
  handleClose: () => { },
  handleClickOpen: () => { },
};

export default LoginModal;
