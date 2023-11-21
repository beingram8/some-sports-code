import React, { useState } from "react";
import PropTypes from "prop-types";
import moment from "moment";
import _ from "lodash";
import { useDispatch, useSelector } from "react-redux";
import Select from "react-dropdown-select";
import Dialog from "@material-ui/core/Dialog";
import Checkbox from "@material-ui/core/Checkbox";
import { withStyles } from "@material-ui/core/styles";
import { isAndroid, isIOS } from "react-device-detect";
import MuiDialogContent from "@material-ui/core/DialogContent";
import "./styles.scss";
import { Setting } from "../../Utils/Setting";
import { getAPIProgressData, getApiData } from "../../Utils/APIHelper";
import FBLogin from "../../Components/SocialLogin/FBLogin";
import CancelIcon from "../../Assets/Images/cancel_white.png";
import GmailLogin from "../../Components/SocialLogin/GmailLogin";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";
import CAlert from "../../Components/CAlert/index";
import CButton from "../../Components/CButton";
import SocialAppleLogin from "../../Components/SocialLogin/SocialAppleLogin";
import LoginWithMail from "../../Components/SocialLogin/LoginWithMail";
import authActions from "../../Redux/reducers/auth/actions";
import renderHTML from "react-render-html";
import KeyboardBackspaceIcon from "@material-ui/icons/KeyboardBackspace";
import CircularProgress from "@material-ui/core/CircularProgress";

const { setUserReferenceCode, setSelectedTeamData } = authActions;

const genderOptions = [
  { id: 1, label: getWords("MALE"), value: getWords("MALE") },
  { id: 2, label: getWords("FEMALE"), value: getWords("FEMALE") },
  { id: 3, label: getWords("OTHER"), value: getWords("OTHER") },
];

const DialogContent = withStyles(() => ({
  root: {
    padding: "0px",
    margin: "0px",
  },
}))(MuiDialogContent);

const SignUpModal = (props) => {
  const { signUpModal, onSignInClick, handleClose } = props;
  const { useruuid, referCode, userdata, teamList } = useSelector(
    (state) => state.auth
  );
  const dispatch = useDispatch();
  const [dob, setDOB] = useState("");
  const [dobval, setDobVal] = useState(0);

  const [isOnlySocialBtnCon, setOnlySocialBtnCon] = useState(true);
  // const [teamSelectedData, setTeamSelectedData] = useState(selectedteam);
  const [teamSelectedData, setTeamSelectedData] = useState(teamList[0]);

  const [fnerrmsg, setFnErrMsg] = useState("");
  const [lnerrmsg, setLnErrMsg] = useState("");
  const [unameerrmsg, setUNameErrMsg] = useState("");
  const [passworderrmsg, setPasswordErrMsg] = useState("");
  const [doberrmsg, setDOBErrMsg] = useState("");
  const [displayDOBErr, setDisplayDOBError] = useState(false);
  const [emailErrMsg, setEmailErrMsg] = useState("");
  const [termsandconErrMsg, setTermsandconErrMsg] = useState(false);
  const [eighteenplusErrMsg, setEighteenPlusErrMsg] = useState(false);

  const [firstName, setFirstNAme] = useState("");
  const [lastName, setLastNAMe] = useState("");
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [email, setEmail] = useState("");
  const [termsandcon, setTermsandcon] = useState(false);
  const [eighteenplus, setEighteenPlus] = useState(false);
  const [genderValue, setGenderValue] = useState(genderOptions[0]);
  const [saveBtnLoad, setSaveBtnLoad] = useState(false);
  const [terms, setTerms] = useState(false);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  const [isFromSocial, setIsFromSocial] = useState(false);
  const [providerType, setProviderType] = useState("");
  const [providerKey, setProviderKey] = useState("");

  const [readOnlyGoogle, setReadOnlyGoogle] = useState(false);
  const [readOnlyFB, setReadOnlyFB] = useState(false);
  const [readOnlyApple, setReadOnlyApple] = useState(false);

  const [removeAllData, setRemoveAllData] = useState(false);
  const [htmlData, setHTMLdata] = useState(null);
  const [loader, setLoader] = useState(false);

  let fnameRef = React.createRef();
  let lnameRef = React.createRef();
  let emailRef = React.createRef();
  let usernameRef = React.createRef();
  let passwordRef = React.createRef();
  let dobRef = React.createRef();

  const emailregex =
    /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

  // const nameRegex = /^[A-Za-z]\\w{5, 29}$/;

  // const usernameRegex = /^[a-zA-Z0-9_]{5,}[a-zA-Z]+[0-9]*$/;

  const todaydate = new Date().getTime();

  // handle back press for date
  function keyPressFunc(e) {
    if (e.which === 8) {
      var val = dob;
      console.log(val);
      if (val.length === 3 || val.length === 6) {
        val = val.slice(0, val.length - 1);
        setDOB(val);
      }
    }
  }

  // handle change for date
  function handleChange(val) {
    if (val.length === 2) {
      val += "/";
    } else if (val.length === 5) {
      val += "/";
    }
    setDOB(val);
  }

  // handle enter key from keyboard
  function handleKeyEnter(e) {
    e.which = e.which || e.keyCode;

    // If the key press is Enter
    // eslint-disable-next-line eqeqeq
    if (e.which === 13) {
      switch (e.target.id) {
        case "first_name":
          lnameRef.current.focus();
          break;

        case "last_name":
          emailRef.current.focus();
          break;

        case "email":
          usernameRef.current.focus();
          break;

        case "username":
          passwordRef.current.focus();
          break;

        case "password":
          dobRef.current.focus();
          break;

        case "dob":
          dobRef.current.blur();
          break;

        default:
          break;
      }
    }
  }

  function clearAllStateData() {
    setDOB("");
    setDobVal(0);
    setFnErrMsg("");
    setLnErrMsg("");
    setUNameErrMsg("");
    setPasswordErrMsg("");
    setDOBErrMsg("");
    setDisplayDOBError(false);
    setEmailErrMsg("");
    setTermsandconErrMsg(false);
    setEighteenPlusErrMsg(false);
    setFirstNAme("");
    setLastNAMe("");
    setUsername("");
    setPassword("");
    setEmail("");
    setTermsandcon(false);
    setEighteenPlus(false);
    setGenderValue(genderOptions[0]);
    setSaveBtnLoad(false);
    setRemoveAllData(false);
    setIsFromSocial(false);
    setProviderType("");
    setProviderKey("");
    setReadOnlyGoogle(false);
    setReadOnlyFB(false);
    setReadOnlyApple(false);
    setOnlySocialBtnCon(true);
  }

  // vaidate birthdate
  const ValidateDate = () => {
    let isValid = true;
    const dtArray = dob.split("/");
    const dd = _.toNumber(dtArray[0]);
    const mm = _.toNumber(dtArray[1]);
    const yy = _.toNumber(dtArray[2]);
    const today1 = moment().format("YYYY");
    const age = today1 - yy;
    const listOfDays = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    if (mm === 1 || (mm > 2 && mm <= 12)) {
      if (dd > listOfDays[mm - 1]) {
        setDOBErrMsg(getWords("VALID_DATE"));
        setDisplayDOBError(true);
        return (isValid = false);
      }
    } else if (mm === 2) {
      let leapYear = false;
      if ((!(yy % 4) && yy % 100) || !(yy % 400)) {
        leapYear = true;
      } else if (!leapYear && dd >= 29) {
        setDOBErrMsg(getWords("NOT_LEAP_YEAR"));
        setDisplayDOBError(true);
        return (isValid = false);
      } else if (leapYear && dd > 29) {
        setDOBErrMsg(getWords("INVLAID_LEAP_YEAR"));
        setDisplayDOBError(true);
        return (isValid = false);
      }
    } else {
      setDOBErrMsg(getWords("INVALID_MONTH"));
      setDisplayDOBError(true);
      return (isValid = false);
    }
    if (isValid) {
      if (today1 < yy && age < 0) {
        setDOBErrMsg(getWords("FUTURE_DATE"));
        setDisplayDOBError(true);
        return (isValid = false);
      } else if (yy <= 1920) {
        setDOBErrMsg(getWords("INVALID_YEAR"));
        setDisplayDOBError(true);
        return (isValid = false);
      } else if (today1 === yy) {
        setDOBErrMsg(getWords("ALTEAST_EIGHTEEN"));
        setDisplayDOBError(true);
        return (isValid = false);
      } else if (isValid && today1 > yy && age < 13) {
        setDOBErrMsg(getWords("ALTEAST_EIGHTEEN"));
        setDisplayDOBError(true);
        return (isValid = false);
      } else {
        setDisplayDOBError(false);
        return (isValid = true);
      }
    }
    return isValid;
  };

  // proceed to sign up
  const proceedToSignup = () => {
    setFnErrMsg("");
    setLnErrMsg("");
    setEmailErrMsg("");
    setUNameErrMsg("");
    setPasswordErrMsg("");
    setDOBErrMsg("");
    setDisplayDOBError(false);
    setEighteenPlusErrMsg("");
    setTermsandconErrMsg("");
    signInProcess();
  };

  // validation of signup form
  function handleSubmit() {
    let valid = true;
    if (firstName === "") {
      setFnErrMsg(getWords("ENTER_YOUR_FIRST_NAME"));
      return (valid = false);
    }
    //  else if (nameRegex.test(firstName)) {
    //   setFnErrMsg(getWords("ENTER_VALID_FIRSTNAME"));
    //   return (valid = false);
    // }
    else if (lastName === "") {
      setLnErrMsg(getWords("ENTER_YOUR_LAST_NAME"));
      return (valid = false);
    }
    // else if (nameRegex.test(lastName)) {
    //   setLnErrMsg(getWords("ENTER_VALID_LASTNAME"));
    //   return (valid = false);
    // }
    else if (email === "") {
      setEmailErrMsg(getWords("ENTER_YOUR_EMAIL"));
      return (valid = false);
    } else if (!emailregex.test(email)) {
      setEmailErrMsg(getWords("PLEASE_ENTER_VALID_EMAIL_ADDRESS"));
      return (valid = false);
    } else if (username === "") {
      setUNameErrMsg(getWords("ENTER_YOUR_USER_NAME"));
      return (valid = false);
    }
    // else if (!usernameRegex.test(username)) {
    //   setUNameErrMsg(getWords("ENTER_VALID_USERNAME"));
    //   return (valid = false);
    // }
    else if (password === "" && isFromSocial === false) {
      setPasswordErrMsg(getWords("ENTER_YOUR_PASSWORD"));
      return (valid = false);
    } else if (password.length < 8 && isFromSocial === false) {
      setPasswordErrMsg(getWords("PASSWORD_MUST_BE_MINIMUM_OF_8_CHARACTERS"));
      return (valid = false);
    } else if (!termsandcon) {
      setTermsandconErrMsg(getWords("PLEASE_ACCEPT_TERMS_AND_CONDITIONS"));
      return (valid = false);
    } else if (!eighteenplus) {
      setEighteenPlusErrMsg(getWords("PLEASE_SELECT_IF_YOU_ARE_18"));
      return (valid = false);
    } else if (dob === "") {
      setDOBErrMsg(getWords("ENTER_YOUR_DATE_OF_BIRTH"));
      setDisplayDOBError(true);
      return (valid = false);
    } else if (dob.length !== 10) {
      setDOBErrMsg(getWords("PLEASE_ENTER_VALID_BIRTHDATE"));
      setDisplayDOBError(true);
      return (valid = false);
    } else if (dob.length === 10) {
      const v = ValidateDate();
      if (v) {
        proceedToSignup();
      }
    }

    return valid;
  }

  async function signInProcess() {
    setSaveBtnLoad(true);
    const platForm = isAndroid ? "Android" : isIOS ? "Ios" : "Android";
    const dtArray = dob.split("/");
    const dd = dtArray[0];
    const mm = dtArray[1];
    const yy = dtArray[2];
    const newDOB = `${yy}-${mm}-${dd}`;
    const signUpData = {
      "SignupForm[firstname]": firstName,
      "SignupForm[lastname]": lastName,
      "SignupForm[username]": username,
      "SignupForm[birth_date]": newDOB,
      "SignupForm[email]": email,
      "SignupForm[password]": password,
      "SignupForm[gender]": genderValue.id,
      "SignupForm[team_id]":
        _.isObject(teamSelectedData) && _.has(teamSelectedData, "id")
          ? teamSelectedData.id
          : "",
    };

    if (isFromSocial) {
      signUpData["SignupForm[provider_key]"] = providerKey;
      signUpData["SignupForm[provider_type]"] = providerType;
      signUpData["SignupForm[uuid]"] = useruuid;
      signUpData["SignupForm[platform]"] = platForm;
    }

    if (_.isString(referCode) && referCode !== "" && !_.isNull(referCode)) {
      signUpData["SignupForm[refer_code]"] = referCode;
    }

    try {
      let endPoint = Setting.endpoints.signup;
      const response = await getAPIProgressData(endPoint, "POST", signUpData);
      if (response?.status) {
        addAnalyticsEvent("Sign_Up_Event", {
          first_name: firstName,
          last_name: lastName,
          user_name: username,
          email,
          birthdate: newDOB,
          selectedTeam: teamSelectedData.name,
        });
        setTimeout(() => {
          dispatch(setUserReferenceCode(""));
          dispatch(setSelectedTeamData(teamSelectedData));
          if (response?.data?.access_token) {
            setSaveBtnLoad(false);
            clearAllStateData();
            handleClose(response?.data);
          } else {
            showAlert(true, getWords("SUCCESS"), response?.message, true);
          }
        }, 1500);
      } else {
        setSaveBtnLoad(false);
        showAlert(
          true,
          getWords("OOPS"),
          response?.message ? response.message : response?.message,
          false
        );
      }
    } catch (err) {
      console.log("Catch Part", err);
      setSaveBtnLoad(false);
      showAlert(
        true,
        getWords("WARNING"),
        getWords("Something_went_wrong"),
        false
      );
    }
  }

  const showAlert = (open, title, message, bool) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
    setRemoveAllData(bool);
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
          if (removeAllData) {
            setSaveBtnLoad(false);
            clearAllStateData();
            setTimeout(() => {
              handleClose();
            }, 1000);
          }
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  function renderHeader() {
    return (
      <div className="signupmodalsigninheading">
        {terms ? (
          <div>
            <KeyboardBackspaceIcon
              className="headerbackspaceiconstyleSU"
              onClick={() => {
                setTerms(false);
              }}
            />
          </div>
        ) : (
          <div />
        )}

        <span
          style={{
            marginLeft: terms ? 10 : 20,
          }}
          className="signupmodaltermsandcondition"
        >
          {terms ? getWords("TERMS_AND_CONDITION") : getWords("SIGN_UP")}
        </span>

        <div className="signupmodalclosebutton">
          <img
            loading="lazy"
            src={CancelIcon}
            className="signupmodalclosebuttonimage"
            alt="oops..."
            onClick={() => {
              clearAllStateData();
              handleClose();
              setReadOnlyGoogle(false);
              setReadOnlyFB(false);
              setReadOnlyApple(false);
            }}
          />
        </div>
      </div>
    );
  }

  function renderBackLoginLinkCon() {
    return (
      <div
        className={
          isOnlySocialBtnCon
            ? "signupmodalnoaccountcontainer2"
            : "signupmodalnoaccountcontainer"
        }
      >
        <span className="signupmodalhaveaccounttext">
          {getWords("HAVE_ACCOUNT")}
        </span>
        <span
          className="signupmodalsignuptext"
          onClick={() => {
            setOnlySocialBtnCon(true);
            onSignInClick();
          }}
        >
          {" "}
          {getWords("SIGN_IN_CAPITAL")}
        </span>
      </div>
    );
  }

  function renderSocialButtons() {
    return (
      <div>
        {readOnlyFB ? null : (
          <FBLogin
            handleClose={(data) => {
              if (data?.access_token) {
                handleClose(data);
              } else {
                setSignUpUserData(data, "F");
              }
            }}
            from={"Signup"}
          />
        )}
        {readOnlyGoogle ? null : (
          <GmailLogin
            handleClose={(data) => {
              if (data?.access_token) {
                handleClose(data);
              } else {
                setSignUpUserData(data, "G");
              }
            }}
            from={"Signup"}
          />
        )}
        {readOnlyApple ? null : (
          <SocialAppleLogin
            handleClose={(data) => {
              if (data?.access_token) {
                handleClose(data);
              } else {
                setSignUpUserData(data, "A");
              }
            }}
            from={"Signup"}
          />
        )}
      </div>
    );
  }

  function setSignUpUserData(data, str) {
    const isEditableEmail = _.isString(data?.email) && data?.email !== "";

    setIsFromSocial(true);
    setProviderType(data?.provider_type);
    setProviderKey(data?.provider_key);
    setEmail(data?.email);
    setFirstNAme(data?.first_name);

    if (str === "F") {
      setReadOnlyFB(isEditableEmail);
    } else if (str === "G") {
      setReadOnlyGoogle(isEditableEmail);
    } else if (str === "A") {
      setReadOnlyApple(isEditableEmail);
    }

    setOnlySocialBtnCon(false);
  }

  function renderSubmitButton() {
    return (
      <CButton
        btnLoader={saveBtnLoad}
        buttonText={getWords("SIGN_UP")}
        handleBtnClick={() => {
          if (saveBtnLoad) {
            return;
          } else {
            handleSubmit();
          }
        }}
      />
    );
  }

  function renderCheckBoxes() {
    return (
      <div className="signupmodalrendercheckbox">
        <div className="signupmodalcheckboxcontainer">
          <Checkbox
            checked={termsandcon}
            onChange={saveBtnLoad ? null : () => setTermsandcon(!termsandcon)}
            className="signupmodalcheckbox"
          />
          <div
            style={{
              cursor: "pointer",
            }}
            onClick={() => {
              // history.push("/terms-and-condition");
              setTerms(true);
              getCMSData(userdata, "terms_condition");
              // history.push({
              //   pathname: "/terms-and-condition",
              //   // state: {
              //   //   fromSignUp: true,
              //   // },
              // });
            }}
          >
            <span
              style={{
                borderBottom: "1px solid #484848",
              }}
              className="checkboxstyle"
            >
              {getWords("ACCEPT_TC")}
            </span>
          </div>

          {!termsandcon ? (
            <span className="signupmodalerrormsg">{termsandconErrMsg}</span>
          ) : null}
        </div>
        <div className="signupmodalcheckboxcontainer2">
          <Checkbox
            className="signupmodalcheckbox"
            checked={eighteenplus}
            onChange={saveBtnLoad ? null : () => setEighteenPlus(!eighteenplus)}
          />
          <span className="checkboxstyle">{getWords("I_AM_18_YEARS_OLD")}</span>
          {!eighteenplus ? (
            <span className="signupmodalerrormsg2">{eighteenplusErrMsg}</span>
          ) : todaydate - 410240376000 < dobval ? (
            <span className="signupmodalerrormsg2">{eighteenplusErrMsg}</span>
          ) : null}
        </div>
      </div>
    );
  }

  function renderFirstLastNameCon() {
    return (
      <div className="firstlastnamecontainer">
        <div className="inputDiv" style={{ width: "46%" }}>
          <span className="titleStyleSU">{getWords("FIRST_NAME")}</span>
          <input
            autoComplete="false"
            type="text"
            id="first_name"
            name="first_name"
            className="inputStyleSU"
            maxLength={10}
            onChange={(val) => {
              const name = val.target.value;
              setFirstNAme(name);
            }}
            value={firstName}
            ref={fnameRef}
            onKeyPress={(e) => {
              handleKeyEnter(e);
            }}
          />
          {firstName === "" ? (
            <span className="signupmodalerrormsg">{fnerrmsg}</span>
          ) : null}
        </div>

        <div className="inputDiv" style={{ width: "46%" }}>
          <span className="titleStyleSU">{getWords("LAST_NAME")}</span>
          <input
            autoComplete="false"
            type="text"
            id="last_name"
            name="last_name"
            className="inputStyleSU"
            maxLength={10}
            onChange={(val) => {
              setLastNAMe(val.target.value);
            }}
            value={lastName}
            ref={lnameRef}
            onKeyPress={(e) => {
              handleKeyEnter(e);
            }}
          />
          {lastName === "" ? (
            <span className="signupmodalerrormsg">{lnerrmsg}</span>
          ) : null}
        </div>
      </div>
    );
  }

  function renderForm() {
    return (
      <div>
        {renderFirstLastNameCon()}
        <div className="inputDiv">
          <span className="titleStyleSU">{getWords("EMAIL")}</span>
          <input
            autoComplete="false"
            type="email"
            id="email"
            name="email"
            className="inputStyleSU"
            onChange={
              saveBtnLoad
                ? null
                : (val) => {
                  setEmail(val.target.value);
                }
            }
            value={email}
            readOnly={readOnlyGoogle || readOnlyFB || readOnlyApple}
            ref={emailRef}
            onKeyPress={(e) => {
              handleKeyEnter(e);
            }}
          />
          {email === "" || !emailregex.test(email) ? (
            <span className="signupmodalerrormsg">{emailErrMsg}</span>
          ) : null}
        </div>

        <div className="inputDiv">
          <span className="titleStyleSU">{getWords("USERNAME")}</span>
          <input
            autoComplete="false"
            type="text"
            id="username"
            name="username"
            maxLength={10}
            className="inputStyleSU"
            onChange={
              saveBtnLoad
                ? null
                : (val) => {
                  setUsername(val.target.value);
                }
            }
            value={username}
            ref={usernameRef}
            onKeyPress={(e) => {
              handleKeyEnter(e);
            }}
          />
          {username === "" ? (
            <span className="signupmodalerrormsg">{unameerrmsg}</span>
          ) : null}
        </div>

        {isFromSocial ? null : (
          <div className="inputDiv">
            <span className="titleStyleSU">{getWords("PASSWORD")}</span>
            <input
              autoComplete="false"
              type="password"
              id="password"
              name="password"
              className="inputStyleSU"
              onChange={
                saveBtnLoad
                  ? null
                  : (val) => {
                    setPassword(val.target.value);
                  }
              }
              value={password}
              ref={passwordRef}
              onKeyPress={(e) => {
                handleKeyEnter(e);
              }}
            />
            {password === "" || password.length < 8 ? (
              <span className="signupmodalerrormsg">{passworderrmsg}</span>
            ) : null}
          </div>
        )}

        <div className="inputDiv">
          <span className="titleStyleSU">{getWords("DATE_OF_BIRTH")}</span>
          <input
            autoComplete="false"
            type="text"
            value={dob}
            id="dob"
            name="dob"
            className="inputStyleSU"
            maxLength={10}
            placeholder="DD/MM/YYYY"
            onChange={(text) => {
              const date = text.target.value;
              handleChange(date);
            }}
            ref={dobRef}
            onKeyPress={(e) => {
              handleKeyEnter(e);
            }}
            onKeyDown={keyPressFunc}
          />
          {displayDOBErr ? (
            <span className="signupmodalerrormsg">{doberrmsg}</span>
          ) : null}
        </div>

        <div className="inputDiv">
          <span className="titleStyleSU">{getWords("GENDER")}</span>
          <Select
            name="gender"
            id="gender"
            multi={false}
            className="dropdownselectSU"
            options={genderOptions}
            color="#ED0F1B"
            onChange={
              saveBtnLoad
                ? null
                : (values) => {
                  setGenderValue(values[0]);
                }
            }
            values={[genderValue]}
          />
        </div>

        <div className="inputDiv">
          <span className="titleStyleSU">{"Team"}</span>
          <Select
            name="teamlist"
            id="teamlist"
            multi={false}
            className="dropdownselectSU"
            options={teamList}
            color="#ED0F1B"
            onChange={
              saveBtnLoad
                ? null
                : (values) => {
                  setTeamSelectedData(values[0]);
                }
            }
            values={[teamSelectedData]}
          />
        </div>
        {/* )} */}

        {renderCheckBoxes()}

        {renderSubmitButton()}

        {/* <div className="loginmodalorcontainer">
          <div className="loginOrdividerContainer">
            <div className="loginDivider" />
            <span className="loginmodalortext">{getWords("OR")}</span>
            <div className="loginDivider" />
          </div>
        </div> */}
      </div>
    );
  }

  function renderMainCon() {
    return (
      <div className="signupmodalcontentcontainer">
        <div className="signupmodalcontent">
          {renderForm()}
          {/* {renderSocialButtons()} */}
          {renderBackLoginLinkCon()}
        </div>
      </div>
    );
  }

  async function getCMSData(userdata, slug) {
    setLoader(true);
    let eventData = {};

    if (_.isEmpty(userdata)) {
      eventData = {
        user_name: "Guest User",
      };
    } else {
      eventData = true;
    }

    try {
      let endPoint = `${Setting.endpoints.cms_detail}?slug=${slug}`;
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
        showAlert(true, getWords("OOPS"), response?.message, false);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setLoader(false);
      showAlert(
        true,
        getWords("WARNING"),
        getWords("Something_went_wrong"),
        false
      );
    }
  }

  function renderTermsCondition() {
    return loader ? (
      <div className="termsandconditionloadercontainer">
        <CircularProgress className="termsandconditionloader" />
      </div>
    ) : (
      <div className="termsandconditiondatamain">
        <div className="termsandconditiondata">
          {renderHTML(_.toString(htmlData))}
        </div>
      </div>
    );
  }

  function renderOnlyButtons() {
    return (
      <div className="signupmodalcontentcontainer">
        <div className="signupmodalcontent">
          <LoginWithMail
            onClick={() => {
              setOnlySocialBtnCon(false);
            }}
          />
          <div className="loginmodalorcontainer">
            <div className="loginOrdividerContainer">
              <div className="loginDivider" />
              <span className="loginmodalortext">{getWords("OR")}</span>
              <div className="loginDivider" />
            </div>
          </div>
          {renderSocialButtons()}
          {renderBackLoginLinkCon()}
        </div>
      </div>
    );
  }

  return (
    <Dialog
      onClose={() => {
        handleClose({});
        clearAllStateData();
      }}
      open={signUpModal}
      transitionDuration={500}
      className="maindialog"
    >
      <DialogContent
        className={
          isOnlySocialBtnCon
            ? "signupmodalsubmaindialog"
            : readOnlyGoogle
              ? "signupmodalsubmaindialog3"
              : "signupmodalsubmaindialog2"
        }
      >
        {renderHeader()}
        <div className="signupmodalcontentcontainer2">
          {isOnlySocialBtnCon
            ? renderOnlyButtons()
            : terms
              ? renderTermsCondition()
              : renderMainCon()}
          {renderAlert()}
        </div>
      </DialogContent>
    </Dialog>
  );
};

SignUpModal.propTypes = {
  signUpModal: PropTypes.bool,
  handleClose: PropTypes.func,
  onSignInClick: PropTypes.func,
};

SignUpModal.defaultProps = {
  signUpModal: false,
  handleClose: () => { },
  onSignInClick: () => { },
};

export default SignUpModal;
