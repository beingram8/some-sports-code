import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useHistory, useLocation } from "react-router-dom";
import _ from "lodash";
import CLandingSlider from "../../Components/CLandingSlider";
import {
    getWords,
    addAnalyticsEvent,
    sendFCMTokenToServer,
    checkSurveyQuizIsEnable,
    getTeamListByLeague
} from "../../commonFunctions";
import Select from "react-dropdown-select";
import CButtonB from "../../Components/CButtonB/index";
import { Setting } from "../../Utils/Setting"
import { isUserLogin } from "../../commonFunctions";
import authActions from "../../Redux/reducers/auth/actions"
import "./styles.scss";
import "../../Styles/common.scss";
import { LandingPageData } from "../../staticData";
import APPICON from "../../Assets/Images/IMG_1136.webp";
import Checkbox from "@material-ui/core/Checkbox";
import { isAndroid, isIOS } from "react-device-detect";
import { getAPIProgressData, getApiData } from "../../Utils/APIHelper";
import FBLogin from "../../Components/SocialLogin/FBLogin";
import GmailLogin from "../../Components/SocialLogin/GmailLogin";
import CAlert from "../../Components/CAlert/index";
import SocialAppleLogin from "../../Components/SocialLogin/SocialAppleLogin";
import LoginWithMail from "../../Components/SocialLogin/LoginWithMail";
import renderHTML from "react-render-html";
import CircularProgress from "@material-ui/core/CircularProgress";

const {
    setUserData,
    setSelectedTeamData,
    setUserReferenceCode,
} = authActions;


function Register() {
    const [btnLoader, setBtnLoader] = useState(false);
    const history = useHistory();
    const checkIsUserLogin = isUserLogin();
    const location = useLocation();
    const dispatch = useDispatch();
    const { useruuid, referCode, userdata, teamList, leagueList } = useSelector(
        (state) => state.auth
    );
    const [isOnlySocialBtnCon, setOnlySocialBtnCon] = useState(true);
    const [teamSelectedData, setTeamSelectedData] = useState([]);
    const [leagueSelectedData, setLeaugeSelectedData] = useState(leagueList[0]);
    const [unameerrmsg, setUNameErrMsg] = useState("");
    const [passworderrmsg, setPasswordErrMsg] = useState("");
    const [emailErrMsg, setEmailErrMsg] = useState("");
    const [teamErrMsg, setTeamErrMsg] = useState("");
    const [leagueErrMsg, setLeagueErrMsg] = useState("");
    const [termsandconErrMsg, setTermsandconErrMsg] = useState(false);
    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");
    const [email, setEmail] = useState("");
    const [termsandcon, setTermsandcon] = useState(false);
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

    let emailRef = React.createRef();
    let usernameRef = React.createRef();
    let passwordRef = React.createRef();
    let dobRef = React.createRef();

    const emailregex =
        /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    const handleClose = (uData) => {
        if (_.isObject(uData) && !_.isEmpty(uData)) {
            dispatch(setUserData(uData));
            setTimeout(() => {
                sendFCMTokenToServer();
                checkSurveyQuizIsEnable();
            }, 2000);
            history.push("/rate")
        }
    }

    const onSignInClick = () => {
        history.push("/login")
    }

    // const getTeamListData = () => {
    //     if (leagueList.length > 0) {
    //         getTeamListByLeague(leagueList[0].value);
    //     }
    // }
    // handle enter key from keyboard
    function handleKeyEnter(e) {
        e.which = e.which || e.keyCode;

        // If the key press is Enter
        // eslint-disable-next-line eqeqeq
        if (e.which === 13) {
            switch (e.target.id) {
                case "email":
                    usernameRef.current.focus();
                    break;

                case "username":
                    passwordRef.current.focus();
                    break;

                case "password":
                    dobRef.current.focus();
                    break;

                default:
                    break;
            }
        }
    }

    function clearAllStateData() {
        setUNameErrMsg("");
        setPasswordErrMsg("");
        setEmailErrMsg("");
        setUsername("");
        setPassword("");
        setEmail("");
        setTermsandcon(false);
        setSaveBtnLoad(false);
        setRemoveAllData(false);
        setIsFromSocial(false);
        setProviderType("");
        setProviderKey("");
        setReadOnlyGoogle(false);
        setReadOnlyFB(false);
        setReadOnlyApple(false);
        setOnlySocialBtnCon(true);
        setTeamErrMsg("");
        setLeagueErrMsg("");
    }

    // proceed to sign up
    const proceedToSignup = () => {

        setEmailErrMsg("");
        setUNameErrMsg("");
        setPasswordErrMsg("");
        setTeamErrMsg("");
        setLeagueErrMsg("");
        // setDOBErrMsg("");
        // setDisplayDOBError(false);
        // setEighteenPlusErrMsg("");
        setTermsandconErrMsg("");
        setBtnLoader(true);
        signInProcess();
    };

    // validation of signup form
    function handleSubmit() {
        let valid = true;
        if (email === "") {
            setEmailErrMsg(getWords("ENTER_YOUR_EMAIL"));
            return (valid = false);
        } else if (!emailregex.test(email)) {
            setEmailErrMsg(getWords("PLEASE_ENTER_VALID_EMAIL_ADDRESS"));
            return (valid = false);
        } else if (username === "") {
            setUNameErrMsg(getWords("ENTER_YOUR_USER_NAME"));
            return (valid = false);
        }
        else if (password === "" && isFromSocial === false) {
            setPasswordErrMsg(getWords("ENTER_YOUR_PASSWORD"));
            return (valid = false);
        } else if (password.length < 8 && isFromSocial === false) {
            setPasswordErrMsg(getWords("PASSWORD_MUST_BE_MINIMUM_OF_8_CHARACTERS"));
            return (valid = false);
        } else if (!(_.isObject(teamSelectedData) && _.has(teamSelectedData, "id"))) {
            setTeamErrMsg(getWords("SELECT_TEAM"));
            return (valid = false);
        } else if (!(_.isObject(leagueSelectedData) && _.has(leagueSelectedData, "id"))) {
            setLeagueErrMsg(getWords("SELECT_LEAUGE"));
            return (valid = false);
        } else if (!termsandcon) {
            setTermsandconErrMsg(getWords("PLEASE_ACCEPT_TERMS_AND_CONDITIONS"));
            return (valid = false);
        } else {
            // const v = ValidateDate();
            // if (v) {
            proceedToSignup();
            // }
        }

        return valid;
    }

    async function signInProcess() {
        setSaveBtnLoad(true);
        const platForm = isAndroid ? "Android" : isIOS ? "Ios" : "Android";
        const signUpData = {
            "SignupForm[username]": username,
            "SignupForm[email]": email,
            "SignupForm[password]": password,
            "SignupForm[team_id]":
                _.isObject(teamSelectedData) && _.has(teamSelectedData, "id")
                    ? teamSelectedData.id
                    : "",
            "SignupForm[league_id]":
            _.isObject(leagueSelectedData) && _.has(leagueSelectedData, "id")
                ? leagueSelectedData.id
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
                    user_name: username,
                    email,
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
        // setFirstNAme(data?.first_name);

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
            <CButtonB
                btnLoader={saveBtnLoad}
                loaderColorWhite={true}
                buttonText={getWords("SIGN_UP")}
                outlined={true}
                textcolor={"#fff"}
                buttonStyle={{ background: "#D92B34" }}
                boldText={true}
                handleBtnClick={
                    saveBtnLoad
                        ? null
                        : () => {
                            handleSubmit();
                        }
                }
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
            </div>
        );
    }


    function renderForm() {
        return (
            <div>
                {/* {renderFirstLastNameCon()} */}
                <div className="inputDiv">
                    <span className="titleStyleSU">{getWords("EMAIL")}</span>
                    <input
                        autoComplete="false"
                        type="email"
                        id="email"
                        name="email"
                        className="loginmodalinputtext"
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
                        className="loginmodalinputtext"
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
                            className="loginmodalinputtext"
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
                    <span className="titleStyleSU">{"League"}</span>
                    <Select
                        name="leaugelist"
                        id="leaguelist"
                        multi={false}
                        className="dropdownselectSU"
                        options={leagueList}
                        color="#ED0F1B"
                        onChange={
                            saveBtnLoad
                                ? null
                                : (values) => {
                                    setLeaugeSelectedData(values[0]);
                                }
                        }
                        values={[leagueSelectedData]}
                    />
                    <span className="signupmodalerrormsg">{leagueErrMsg}</span>
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
                    {(_.isObject(teamSelectedData) && _.has(teamSelectedData, "id"))? null
                    :<span className="signupmodalerrormsg">{teamErrMsg}</span>}
                </div>
                {renderCheckBoxes()}

                {renderSubmitButton()}


            </div>
        );
    }

    function renderMainCon() {
        return (
            <div>
                {/* <div className="signupmodalcontent"> */}
                {renderForm()}
                {/* {renderSocialButtons()} */}
                {renderBackLoginLinkCon()}
                {/* </div> */}
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
                <div>
                    <a className="loginmodalsignuptext" onClick={() => setTerms(false)}>{getWords("BACK")}</a>
                </div>
            </div>
        );
    }

    function renderOnlyButtons() {
        return (
            <div className="signupmodalcontentcontainer">
                <div className="signupmodalcontent">
                    {/* <LoginWithMail
                        onClick={() => {
                            setOnlySocialBtnCon(false);
                        }}
                    /> */}
                    <LoginWithMail
                        onClick={() => {
                            setOnlySocialBtnCon(false);
                        }}
                    />
                    {renderSocialButtons()}

                </div>
            </div>
        );
    }

    const renderLeftSide = () => {
        return (
            <div className="left-side">
                <div className="left-side-inner">
                    <div className="d-flex flex-direction-column text-center pv-50 pw-20 row-50">
                        <CLandingSlider data={LandingPageData} />
                    </div>
                </div>
            </div>
        )

    }

    const renderRightSide = () => {
        return (
            <div className="right-side">
                <div className="d-flex flex-direction-column pv-50 pw-20 row-50">

                    <div className="text-center">
                        <img
                            loading="lazy"
                            src={APPICON}
                            className="mobAPPIcon"
                            alt={"app icon"}
                        />
                    </div>
                    <div className="container-60">
                        <div className="pw-20">
                            <h2>{getWords("SIGN_UP")}</h2>
                            <p>{getWords("JOIN_THE_COMMUNITY_OF_FOOTBALL_FANS")}</p>
                            <div className="signupmodalcontentcontainer2">
                                {isOnlySocialBtnCon ? renderOnlyButtons()
                                    : terms ? renderTermsCondition() :
                                        <>

                                            {/* <div className="loginmodalorcontainer">
                                                <div className="loginOrdividerContainer">
                                                    <div className="loginDivider" />
                                                    <span className="loginmodalortext">{getWords("OR")}</span>
                                                    <div className="loginDivider" />
                                                </div>
                                            </div> */}
                                            <div className="row-30">
                                                {renderMainCon()}
                                            </div>
                                        </>
                                }

                                {/* {terms? renderTermsCondition()
                                    : {renderOnlyButtons()}
                                      {renderMainCon()}} */}
                                {renderAlert()}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        )
    }

    const renderMainContent = () => {
        return (
            <div className="d-flex flex-container">
                {renderLeftSide()}
                {renderRightSide()}
            </div>
        )
    }

    useEffect(() => {
        isGetReferEarnToken();
    })

    useEffect(() => {
        document.title = Setting.page_name.REGISTER;
        if (checkIsUserLogin) {
            history.push("/rate");
        }
    });

    useEffect(() => {
        if (leagueList.length > 0) {
            console.log(leagueList[0])
            getTeamListByLeague(leagueSelectedData.value);
        }
    }, [leagueSelectedData, leagueList])

    useEffect(() => {
        if (teamList.length > 0) {
            setTeamSelectedData(teamList[0])
        }  else {
            setTeamSelectedData([]);
        }
    }, [teamList])

    return (
        <div className="MainContainer">
            {renderMainContent()}
        </div>
    );

    function isGetReferEarnToken() {
        const referenceCode =
            location && location.search && !_.isEmpty(location.search)
                ? _.toString(location.search).substring(1)
                : "";

        if (_.isString(referenceCode) && !_.isEmpty(referenceCode)) {
            dispatch(setUserReferenceCode(referenceCode));
        }
    }
}

export default Register;
