import _ from "lodash";
import React, { useEffect, useState } from "react";
import { useDispatch } from "react-redux";
import { useHistory, useLocation } from "react-router-dom";
import CLandingSlider from "../../Components/CLandingSlider";
import { getWords, getTeamListData, getLeagueListData } from "../../commonFunctions";
import CButtonB from "../../Components/CButtonB/index";
import { Setting } from "./../../Utils/Setting"
import { isUserLogin } from "../../commonFunctions";
import authActions from "../../Redux/reducers/auth/actions"
import "./styles.scss";
import "../../Styles/common.scss";
import { LandingPageData } from "../../staticData";
import APPICON from "../../Assets/Images/IMG_1136.webp";
const {
    setUserReferenceCode
} = authActions

function Welcome() {
    const [btnLoader, setBtnLoader] = useState(false);
    const history = useHistory();
    const checkIsUserLogin = isUserLogin();
    const location = useLocation();
    const dispatch = useDispatch();
    function renderLoginButton() {
        return (
            <CButtonB
                btnLoader={btnLoader}
                buttonText={getWords("SIGN_IN")}
                outlined={true}
                textcolor={"#1D1D1D"}
                boldText={true}
                handleBtnClick={
                    () => history.push("/login")
                }
            />
        );
    }

    function renderRegisterButton() {
        return (
            <CButtonB
                btnLoader={btnLoader}
                outlined={true}
                textcolor={"#fff"}
                buttonStyle={{ background: "#D92B34" }}
                boldText={true}
                buttonText={getWords("CREATE_AN_ACCOUNT")}
                handleBtnClick={
                    () => history.push("register")
                }
            />
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
                <div className="d-flex flex-direction-column text-center pv-50 pw-20 row-50">

                    <div className="text-center">
                        <img
                            loading="lazy"
                            src={APPICON}
                            className="mobAPPIcon"
                            alt={"app icon"}
                        />
                    </div>
                    <div className="container-60">
                        <div className="pv-50 pw-20">
                            <h2 className="divider-space">{getWords("WELCOME_TO_FAN_RATING")}</h2>

                            <div className="divider divider-space" />
                            <div className="divider-space">
                                {renderLoginButton()}
                            </div>
                            <div className="divider-space">
                                {renderRegisterButton()}
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
        getTeamListData();
        getLeagueListData();
    })

    useEffect(() => {
        document.title = Setting.page_name.WELCOME;
        if (checkIsUserLogin) {
            history.push("/rate");
        }
    });


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

export default Welcome;
