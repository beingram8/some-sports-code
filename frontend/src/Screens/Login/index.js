import React, { useEffect, useState } from "react";
import { useDispatch } from "react-redux";
import { useHistory, useLocation } from "react-router-dom";
import _ from "lodash";
import CLandingSlider from "../../Components/CLandingSlider";
import {
    getWords,
    isUserLogin,
    addAnalyticsEvent,
    sendFCMTokenToServer,
    checkSurveyQuizIsEnable,
} from "../../commonFunctions";
import CButtonB from "../../Components/CButtonB/index";
import { Setting } from "./../../Utils/Setting"
import authActions from "../../Redux/reducers/auth/actions"
import "./styles.scss";
import "../../Styles/common.scss";
import { LandingPageData } from "../../staticData";
import APPICON from "../../Assets/Images/IMG_1136.webp";
import FBLogin from "../../Components/SocialLogin/FBLogin";
import GmailLogin from "../../Components/SocialLogin/GmailLogin";
import CAlert from "../../Components/CAlert/index";
import { getAPIProgressData } from "../../Utils/APIHelper";
import SocialAppleLogin from "../../Components/SocialLogin/SocialAppleLogin";
import ForgotPasswordModal from "../../Modals/ForgotPasswordModal/index";
const {
    setUserReferenceCode,
    setSelectedTeamData,
    setUserData,
} = authActions

function Login() {
    const [btnLoader, setBtnLoader] = useState(false);
    const history = useHistory();
    const checkIsUserLogin = isUserLogin();
    const location = useLocation();
    const dispatch = useDispatch();
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [errorPwdMessage, setErrorPwdMessage] = useState("");
    const [errorEmailMessage, setErrorEmailMessage] = useState("");
    const [alertOpen, setAlertOpen] = useState(false);
    const [alertTitle, setAlertTitle] = useState("");
    const [alertMessage, setAlertMessage] = useState("");
    const [forgorPwdModal, setForgotPwdModal] = useState(false);
    const [successModal, setSuccessModal] = useState(false);
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

    const handleClose = (uData, str) => {
        if (_.isObject(uData) && !_.isEmpty(uData)) {
            dispatch(setUserData(uData));
            setTimeout(() => {
                sendFCMTokenToServer();
                checkSurveyQuizIsEnable();
                if (!_.isEmpty(str) && _.isString(str)) {
                    addAnalyticsEvent(str, true);
                } else {
                    addAnalyticsEvent("Login_Event", true);
                }
            }, 2000);
            history.push("/rate")
        }
    }

    function renderSocialButtons() {
        return (
            <div>
                <FBLogin handleClose={handleClose} />
                <div className="divider-space"></div>
                <GmailLogin handleClose={handleClose} />
                <div className="divider-space"></div>
                <SocialAppleLogin handleClose={handleClose} />
                <div className="divider-space"></div>
            </div>
        );
    }

    function renderSignUpCon() {
        return (
            <div className="loginmodalnoaccountcontainer">
                <span className="loginmodalnoaccounttext">
                    {getWords("NOT_REGISTERED_YET")}
                </span>
                <span
                    className="loginmodalsignuptext"
                    onClick={() => {
                        history.push("/register")
                    }}
                >
                    {getWords("CREATE_AN_ACCOUNT")}
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
            <CButtonB
                btnLoader={btnLoader}
                buttonText={getWords("SIGN_IN")}
                outlined={false}
                textcolor={"#fff"}
                buttonStyle={{ background: "#D92B34" }}
                boldText={true}
                handleBtnClick={
                    btnLoader
                        ? null
                        : () => {
                            handleSubmit();
                        }
                }
            />
            // <CButton
            //     btnLoader={btnLoader}
            //     buttonText={getWords("SIGN_IN")}
            //     handleBtnClick={
            //         btnLoader
            //             ? null
            //             : () => {
            //                 handleSubmit();
            //             }
            //     }
            // />
        );
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



                    {/* {renderSocialButtons()} */}
                    {renderSignUpCon()}
                </div>
            </div>
        );
    }

    const renderRightSide = () => {
        return (
            <div className="right-side">
                <div className="d-flex flex-direction-column pw-20 row-50">

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
                            <h2>{getWords("LOGIN")}</h2>
                            <p>{getWords("WELCOME_BACK_ONBOARD")}</p>
                            {renderSocialButtons()}
                            <div className="loginmodalorcontainer">
                                <div className="loginOrdividerContainer">
                                    <div className="loginDivider" />
                                    <span className="loginmodalortext">{getWords("SIGN_IN_WITH_EAMIL")}</span>
                                    <div className="loginDivider" />
                                </div>
                            </div>
                            <div className="row-30">
                                {renderLoginForm()}
                                {renderAlert()}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        )
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

    function renderForgotPWDModal() {
        return (
            <ForgotPasswordModal
                forgorPwdModal={forgorPwdModal}
                handleClose={() => {
                    setForgotPwdModal(false);
                }}
                onSavePassword={() => {
                    setForgotPwdModal(false);
                    setSuccessModal(true);
                }}
            />
        );
    }
    const onForgotPasswordClick = () => {
        setForgotPwdModal(true);
    }
    function handleKeyEnter(e) {
        e.which = e.which || e.keyCode;
        if (e.which === 13) {
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

    const renderMainContent = () => {
        return (
            <>
                {renderForgotPWDModal()}
                <div className="d-flex flex-container">
                    {renderLeftSide()}
                    {renderRightSide()}
                </div>
            </>
        )
    }


    useEffect(() => {
        const isGetReferEarnToken = () => {
            const referenceCode =
                location && location.search && !_.isEmpty(location.search)
                    ? _.toString(location.search).substring(1)
                    : "";

            if (_.isString(referenceCode) && !_.isEmpty(referenceCode)) {
                dispatch(setUserReferenceCode(referenceCode));
            }
        }
        isGetReferEarnToken();

        document.title = Setting.page_name.LOGIN;
        if (checkIsUserLogin === true) {
            history.push("/rate");
        }
    })

    return (
        <div className="MainContainer">
            {renderMainContent()}
        </div>
    );


}

export default Login;
