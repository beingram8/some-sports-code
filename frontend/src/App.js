import React, { createContext, useEffect } from "react";
import firebase from "firebase/app";
import _ from "lodash";
import "firebase/auth";
import "firebase/firestore";
import { Detector } from "react-detect-offline";
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import RateNew from "./Screens/RateNew/index";
import Tifa from "./Screens/Tifa/Tifa";
import News from "./Screens/News/News";
import Winner from "./Screens/Winner/Winner";
import Ranking from "./Screens/Ranking/Ranking";
import EditProfile from "./Screens/EditProfile/EditProfile";
import Notifications from "./Screens/Notifications/Notifications";
import TeamDetails from "./Screens/TeamDetails";
import PrivacyPolicy from "./Screens/PrivacyPolicy/PrivacyPolicy";
import TermsCondition from "./Screens/TermsCondition/TermsCondition";
import Help from "./Screens/Help/Help";
import ContactUs from "./Screens/ContactUs/ContactUs";
import Logout from "./Components/Logout";
import PlayerProfile from "./Screens/PlayerProfile/PlayerProfile";
import AboutUs from "./Screens/AboutUs/AboutUs";
import MyTeam from "./Screens/MyTeam/MyTeam";
import BuyTokens from "./Screens/BuyTokens/BuyTokens";
import ResetPassword from "./Screens/ResetPassword/ResetPassword";
import Verification from "./Screens/Verification/Verification";
import InviteandEarnTokens from "./Screens/InviteandEarnTokens/InviteandEarnTokens";
import ShareableVoteCard from "./Screens/ShareableVoteCard/ShareableVoteCard";
import UserLevel from "./Screens/UserLevel";
import StartUp from "./Screens/StartUp";
import StartUpMobile from "./Screens/StartupMobile";
import BrokenPage from "./Screens/BrokenPage/BrokenPage";
import Offline from "./Screens/Offline/index";
import Teasing from "./Screens/Teasing";
import TeasingComment from "./Screens/TeasingComments";
import Roomdetail from "./Screens/RoomDetail";
import AddPost from "./Screens/AddPost";
import IntroScreen from "./Screens/IntroScreen";
import LandingPage from "./Screens/LandingPage/LandingPage";
import AllNews from "./Screens/AllNews";
import AllVideos from "./Screens/AllVideos";
import Welcome from "./Screens/Welcome";
import Login from "./Screens/Login";
import Register from "./Screens/Register";
import {
  isUserLogin,
  refreshUserData,
  getNotificationBadge,
  checkSurveyQuizIsEnable,
  setAllDataFromLocalStorage,
  getRemainingDaysAndTime,
  logoutProcess,
} from "./commonFunctions";

const firebaseConfig = {
  apiKey: "AIzaSyBExPnxSDQ9z5aISvS5knYBSkUKJmEvKVA",
  authDomain: "frpwd-8e595.firebaseapp.com",
  projectId: "frpwd-8e595",
  storageBucket: "frpwd-8e595.appspot.com",
  messagingSenderId: "1034598437002",
  appId: "1:1034598437002:web:4362dedf606d148bd2dd73",
  measurementId: "G-KEF679QMN0",
};

import { EnLanguage } from "./Transalate/en";
import { ItLanguage } from "./Transalate/it";
import { store } from "./Redux/store/configureStore";
// import translationContext from "./Context"

function App() {
  const checkIsUserLogin = isUserLogin();

  // disable right click
  // const handleContextMenu = (e) => e.preventDefault();
  useEffect(() => {
    // disable right click
    // document.addEventListener("contextmenu", handleContextMenu);
    isUserLogout();
    // refresh user data
    refreshUserData();
    checkSurveyQuizIsEnable();
    setAllDataFromLocalStorage();
  }, []);
  // const getWords = (key) => {
  //   const {
  //     auth: { userdata },
  //   } = store.getState();

  //   if (userdata['language'] == LANG_US) {
  //     return EnLanguage[key];
  //   }
  //   if (userdata['language'] == LANG_IT) {
  //     return ItLanguage[key];
  //   }
  //   return EnLanguage[key];
  // }
  const validpathregex = RegExp(`^${window.location.origin}/.[a-z-]*/.[a-z-]*`);

  useEffect(() => {
    document.addEventListener("visibilitychange", function () {
      if (document.visibilityState === "visible") {
        checkSurveyQuizIsEnable();
        getNotificationBadge();
        refreshUserData();
        isUserLogout();
      }
    });
  });

  function isUserLogout() {
    window.addEventListener("storage", (event) => {
      if (
        event?.key === "userData" &&
        !_.isEqual(event?.oldValue, event?.newValue) &&
        !_.isEmpty(event?.oldValue) &&
        (event?.newValue === "" || event?.newValue === null)
      ) {
        logoutProcess();
      }
    });
  }

  if (!firebase.apps.length) {
    firebase.initializeApp(firebaseConfig);
  }

  function isValidURL() {
    if (validpathregex.test(window.location.href)) {
      return <Route path="" component={BrokenPage} />;
    }
  }

  function isDisplayStartUpUI() {
    const remainingSec = getRemainingDaysAndTime();
    if (remainingSec !== "00:00:00:00" && checkIsUserLogin === false) {
      if (window.innerWidth >= 1100) {
        return <Route exact path="/" component={StartUp} />;
      } else {
        return <Route exact path="/" component={StartUpMobile} />;
      }
    } else {
      return <Route exact path="/" component={RateNew} />;
    }
  }

  // const remainingSec = getRemainingDaysAndTime();

  return (
    // <translationContext.Provider value={{ translate: getWords }}>
    <Detector
      render={({ online }) => {
        // if (online) {
        return (
          <Router>
            <Switch>
              {/* {isValidURL()} */}
              {/* {isDisplayStartUpUI()} */}
              {checkIsUserLogin === false ? (
                <Route exact path={"/"} component={Welcome} />
              ) : (
                <Route exact path={"/"} component={RateNew} />
              )}
              <Route exact path={"/login"} component={Login} />
              <Route exact path={"/register"} component={Register} />
              {/* {remainingSec !== "00:00:00:00" &&
                checkIsUserLogin === false ? (
                  window.innerWidth >= 1100 ? (
                    <Route path={"/start-up"} component={StartUp} />
                  ) : (
                    <Route path={"/start-up"} component={StartUpMobile} />
                  )
                ) : (
                  <Route path={"/rate"} component={RateNew} />
                )}                */}
              <Route path={"/rate"} component={RateNew} />
              <Route path="/tifa" component={Tifa} />
              <Route path="/news" component={News} />
              <Route path="/all-news" component={AllNews} />
              <Route path="/all-videos" component={AllVideos} />
              <Route path="/winner" component={Winner} />
              <Route path="/ranking" component={Ranking} />
              <Route path="/edit-profile" component={EditProfile} />
              <Route path="/notifications" component={Notifications} />
              <Route path="/team-details" component={TeamDetails} />
              <Route path="/privacy-policy" component={PrivacyPolicy} />
              <Route path="/terms-and-condition" component={TermsCondition} />
              <Route path="/help" component={Help} />
              <Route path="/contact-us" component={ContactUs} />
              <Route path="/logout" component={Logout} />
              <Route path="/player-profile" component={PlayerProfile} />
              <Route path="/my-team" component={MyTeam} />
              <Route path="/about-us" component={AboutUs} />
              <Route path="/teasing-room" component={Teasing} />
              <Route path="/teasing-comment" component={TeasingComment} />
              <Route
                path="/teasing-room-post-detail"
                component={Roomdetail}
              />
              <Route path="/add-post" component={AddPost} />
              <Route
                path="/invite-and-earn-tokens"
                component={InviteandEarnTokens}
              />
              <Route path="/buy-tokens" component={BuyTokens} />
              <Route path="/reset-password" component={ResetPassword} />
              <Route path="/verification" component={Verification} />
              <Route
                path="/shareable-vote-card"
                component={ShareableVoteCard}
              />
              <Route path="/user-level" component={UserLevel} />
              <Route path="/startup" component={StartUp} />
              <Route path="/landing" component={LandingPage} />
              <Route path="" component={BrokenPage} />
            </Switch>
          </Router>
        );
        // } else {
        //   return (
        //     <Router>
        //       <Route path={"/"} component={Offline} />
        //     </Router>
        //   );
        // }
      }}
    />
    // </translationContext.Provider>
  );
}

export default App;
