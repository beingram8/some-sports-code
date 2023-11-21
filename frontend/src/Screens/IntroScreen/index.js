import React, { useEffect } from "react";
import CSlider from "../../Components/CLandingSlider";
import { useDispatch, useSelector } from "react-redux";
import { useHistory, useLocation } from "react-router-dom";
import _ from "lodash";
import { isUserLogin, getRemainingDaysAndTime } from "../../commonFunctions";
import authActions from "../../Redux/reducers/auth/actions"
import "./styles.scss";
import "../../Styles/common.scss";
import { WelcomeScreenData } from "../../staticData";
import { addAnalyticsEvent } from "../../commonFunctions";
const {
  setUserReferenceCode
} = authActions
const IntroScreen = () => {
  const history = useHistory();
  const checkIsUserLogin = isUserLogin();
  const location = useLocation();
  const dispatch = useDispatch();
  const remainingSec = getRemainingDaysAndTime();

  useEffect(() => {
    isGetReferEarnToken();
  }, []);
  return (
    <div className="mainIntroDiv">
      <CSlider
        data={WelcomeScreenData}
        handleBtnClick={() => {
          const eventData = {
            user_name: "Guest User",
          };
          addAnalyticsEvent("Sign_Up_Button", eventData);
          if (remainingSec !== "00:00:00:00" && checkIsUserLogin === false) {
            history.push("/start-up");
          } else {
            history.push("/rate");
          }
        }}
      />

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
};

export default IntroScreen;
