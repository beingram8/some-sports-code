/* eslint-disable array-callback-return */
import firebase from "firebase/app";
import "firebase/analytics";
import _ from "lodash";
import { EnLanguage } from "./Transalate/en";
import { ItLanguage } from "./Transalate/it";
import { SpLanguage } from "./Transalate/sp";
import { GeLanguage } from "./Transalate/ge";
import { ChLanguage } from "./Transalate/ch";
import { ArLanguage } from "./Transalate/ar";
import { FrLanguage } from "./Transalate/fr";
import { Setting, LANG_US, LANG_IT, LANG_SP, LANG_GE, LANG_CH, LANG_AR, LANG_FR } from "./Utils/Setting";
import { getApiData, getAPIProgressData } from "./Utils/APIHelper";
import authActions from "./Redux/reducers/auth/actions";
import { store } from "./Redux/store/configureStore";


export function getWords(key, params = null) {
  const {
    auth: { userdata },
  } = store.getState();
  let translatedWords = EnLanguage[key];
  if (userdata['language'] == LANG_US) {
    translatedWords = EnLanguage[key];
  }
  if (userdata['language'] == LANG_IT) {
    translatedWords = ItLanguage[key];
  }
  if (userdata['language'] == LANG_SP) {
    translatedWords = SpLanguage[key];
  }
  if (userdata['language'] == LANG_GE) {
    translatedWords = GeLanguage[key];
  }
  if (userdata['language'] == LANG_CH) {
    translatedWords = ChLanguage[key];
  }
  if (userdata['language'] == LANG_AR) {
    translatedWords = ArLanguage[key];
  }
  if (userdata['language'] == LANG_FR) {
    translatedWords = FrLanguage[key];
  }

  if (translatedWords !== undefined && translatedWords != null) {
    if (params != null && _.isObject(params) && !_.isEmpty(params)) {
      for (var key in params) {
        if (params.hasOwnProperty(key)) {
          translatedWords = translatedWords.replaceAll(`{${key}}`, params[key]);
        }
      }
    }
  }
  return translatedWords;
}

export function isUserLogin() {
  const {
    auth: { userdata },
  } = store.getState();

  if (_.isObject(userdata) && !_.isEmpty(userdata)) {
    return true;
  } else {
    return false;
  }
}

export function addAnalyticsEvent(key, data) {
  const {
    auth: { userdata },
  } = store.getState();

  const analytics = firebase.analytics();

  const uData = {
    user_name: userdata?.username,
    first_name: userdata?.firstname,
    last_name: userdata?.lastname,
    email: userdata?.email,
    user_Pic: userdata?.user_image,
    support_team_id: userdata?.team?.id,
    support_team_name: userdata?.team?.name,
  };

  if (data === true) {
    analytics.logEvent(key, uData);
  } else if (_.isObject(data) && !_.isEmpty(data)) {
    analytics.logEvent(key, data);
  }
}

function goBack() {
  store.dispatch(authActions.setLogoutLoad(false));
  store.dispatch(authActions.clearAllData());
}

export async function logoutProcess() {
  const {
    auth: { userdata, useruuid },
  } = store.getState();

  const isLogin = isUserLogin();

  if (isLogin) {
    store.dispatch(authActions.setLogoutLoad(true));
    const header = {
      Authorization: `Bearer ${userdata?.access_token}`,
    };

    addAnalyticsEvent("Logout_Event", true);
    setTimeout(async () => {
      try {
        let endPoint = `${Setting.endpoints.logout}?uuid=${useruuid}`;
        const response = await getApiData(endPoint, "GET", null, header);
        if (response && response.status && response.status === true) {
          goBack();
        } else {
          goBack();
        }
      } catch (err) {
        console.log("Catch Part", err);
        goBack();
      }
    }, 1000);
  } else {
    console.log("User Not Login Yet");
  }
}

// Set all Local storage data when user refresh any web page //
export function setAllDataFromLocalStorage() {
  const bCount = localStorage.getItem("badgeCount");
  const cTab = localStorage.getItem("currentTab");
  const uData = localStorage.getItem("userData");
  const sTeam = localStorage.getItem("selectedTeam");
  const uUUID = localStorage.getItem("userUUID");
  const loading = localStorage.getItem("logoutLoad");
  const sQData = localStorage.getItem("serveyQuizData");
  const sNewsData = localStorage.getItem("selectedNews");
  const referenceCode = localStorage.getItem("referCode");

  const teamListData = localStorage.getItem("teamList");
  const leagueListData = localStorage.getItem("leagueList");
  const finalTeamListData =
    _.isString(teamListData) && !_.isEmpty(teamListData)
      ? JSON.parse(teamListData)
      : [];
  const finalLeagueListData =
      _.isString(leagueListData) && !_.isEmpty(leagueListData)
        ? JSON.parse(leagueListData)
        : [];
  const finalSNewsData =
    _.isString(sNewsData) && !_.isEmpty(sNewsData) ? JSON.parse(sNewsData) : {};
  const finalUserData =
    _.isString(uData) && !_.isEmpty(uData) ? JSON.parse(uData) : {};
  const finalTeamData =
    _.isString(sTeam) && !_.isEmpty(sTeam) ? JSON.parse(sTeam) : {};
  const finalsQData =
    _.isString(sQData) && !_.isEmpty(sQData) ? JSON.parse(sQData) : {};
  const finalLoadValue =
    loading === true || loading === false ? loading : false;

  store.dispatch(authActions.setSelectedNews(finalSNewsData));
  store.dispatch(authActions.setBadgeCount(bCount));
  store.dispatch(authActions.setSelectedTab(cTab));
  store.dispatch(authActions.setUserData(finalUserData));
  store.dispatch(authActions.setSelectedTeamData(finalTeamData));
  store.dispatch(authActions.setUserUUID(uUUID));
  store.dispatch(authActions.setLogoutLoad(finalLoadValue));
  store.dispatch(authActions.setServeyQuizIsEnable(finalsQData));
  store.dispatch(authActions.setUserReferenceCode(referenceCode));
  store.dispatch(authActions.setTeamListData(finalTeamListData));
  store.dispatch(authActions.setLeagueListData(finalLeagueListData));
}

// Return Age value for Date //
export function getAgeValue(bdate) {
  const userBDate = bdate;
  let age = 0;

  const today = new Date();
  const birthDate = new Date(userBDate);

  age = today.getFullYear() - birthDate.getFullYear();
  const month = today.getMonth() - birthDate.getMonth();

  if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  return age;
}

// Check user is 18+ or not //
export function isUser18Plus() {
  const {
    auth: { userdata },
  } = store.getState();
  const userBDate = userdata?.birth_date;
  let returnValue = false;
  let age = 0;

  const today = new Date();
  const birthDate = new Date(userBDate);

  age = today.getFullYear() - birthDate.getFullYear();
  const month = today.getMonth() - birthDate.getMonth();

  if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }

  if (age >= 18) {
    returnValue = true;
  }
  return returnValue;
}

// Send FCM Token to Server //
export async function sendFCMTokenToServer() {
  const {
    auth: { useruuid },
  } = store.getState();

  const isLogin = isUserLogin();

  if (isLogin && _.isString(useruuid) && !_.isEmpty(useruuid)) {
    const tokenData = {
      "UserUuid[uuid]": useruuid,
    };

    try {
      let endPoint = Setting.endpoints.add_token;
      getAPIProgressData(endPoint, "POST", tokenData, true)
        .then((result) => { })
        .catch((err) => {
          console.log("login errr ===>>>> ", err);
        });
    } catch (err) {
      console.log("Catch Part", err);
    }
  }
}

// Check Servery is enable or not //
export async function checkSurveyQuizIsEnable() {
  const {
    auth: { userdata },
  } = store.getState();

  const isLogin = isUserLogin();

  if (isLogin) {
    const header = {
      Authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = Setting.endpoints.common;
      const response = await getApiData(endPoint, "GET", null, header);
      if (response && response.status && response.status === true) {
        const sqData = response?.data;
        store.dispatch(authActions.setServeyQuizIsEnable(sqData));
      } else {
      }
    } catch (err) {
      console.log("Something went wrong", err);
    }
  }
}

// get notification badge count
export async function getNotificationBadge() {
  const {
    auth: { userdata },
  } = store.getState();

  const isLogin = isUserLogin();

  if (isLogin) {
    const header = {
      Authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = Setting.endpoints.badgeCount;
      const response = await getApiData(endPoint, "GET", null, header);
      if (response && response.status && response.status === true) {
        const sqData = response?.data?.count;
        store.dispatch(authActions.setBadgeCount(_.toNumber(sqData)));
      } else {
      }
    } catch (err) {
      console.log("Something went wrong", err);
    }
  }
}

// refresh user data
export async function refreshUserData() {
  store.dispatch(authActions.setRefreshUserDataLoad(true));
  const {
    auth: { userdata },
  } = store.getState();

  const isLogin = isUserLogin();

  if (isLogin) {
    const header = {
      Authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = Setting.endpoints.user_response;
      const response = await getApiData(endPoint, "GET", null, header);
      if (response && response.status && response.status === true) {
        store.dispatch(authActions.setUserData(response?.data));
        store.dispatch(authActions.setRefreshUserDataLoad(false));
      } else {
        store.dispatch(authActions.setRefreshUserDataLoad(false));
      }
    } catch (err) {
      console.log("Something went wrong", err);
      store.dispatch(authActions.setRefreshUserDataLoad(false));
    }
  } else {
    store.dispatch(authActions.setUserData({}));
    store.dispatch(authActions.setRefreshUserDataLoad(false));
  }
}

// get remaining seconds
export function getRemainingSeconds(endTimeStemp) {
  const currentTimeStemp = Math.floor(new Date().getTime() / 1000);
  const remainingSeconds = endTimeStemp - currentTimeStemp;

  if (currentTimeStemp > endTimeStemp) {
    return "00:00:00";
  } else {
    const secFormat = new Date(remainingSeconds * 1000)
      .toISOString()
      .substr(11, 8);

    return secFormat;
  }
}

// get remaining time to launch app
export function getRemainingDaysAndTime() {
  const currentTimeStemp = Math.floor(new Date().getTime() / 1000); // Current Date Time
  const endTimeStemp = 1633026600; // Oct, 1 2021 00:00:00
  // const endTimeStemp = 1629849600;

  const remainingSeconds = endTimeStemp - currentTimeStemp;

  const d = Math.floor(remainingSeconds / (3600 * 24));
  const h = Math.floor((remainingSeconds % (3600 * 24)) / 3600);
  const m = Math.floor((remainingSeconds % 3600) / 60);
  const s = Math.floor(remainingSeconds % 60);

  const day = d >= 10 ? d : `0${d}`;
  const hour = h >= 10 ? h : `0${h}`;
  const min = m >= 10 ? m : `0${m}`;
  const sec = s >= 10 ? s : `0${s}`;

  if (remainingSeconds < 0) {
    return "00:00:00:00";
  }

  return (
    <div
      style={{
        display: "flex",
        flexDirection: "row",
      }}
    >
      <div
        style={{
          display: "flex",
          flexDirection: "column",
        }}
      >
        <div
          style={{
            flexDirection: "row",
            display: "flex",
          }}
        >
          <span
            style={{
              fontFamily: "Bungee",
              lineHeight: "1.5em",
            }}
          >
            {`${day}`}
          </span>
          <span
            style={{
              margin: "0px 30px",
              fontFamily: "Bungee",
              lineHeight: "1.5em",
            }}
          >
            :
          </span>
        </div>
        <span
          style={{
            fontFamily: "segoeui",
            fontSize: 18,
            alignSelf: "flex-start",
            lineHeight: "1.5em",
          }}
        >
          {`giorni`}
        </span>
      </div>
      <div
        style={{
          display: "flex",
          flexDirection: "column",
        }}
      >
        <div
          style={{
            flexDirection: "row",
            display: "flex",
          }}
        >
          <span
            style={{
              fontFamily: "Bungee",
              lineHeight: "1.5em",
            }}
          >
            {`${hour}`}
          </span>
          <span
            style={{
              margin: "0px 30px",
              fontFamily: "Bungee",
              lineHeight: "1.5em",
            }}
          >
            :
          </span>
        </div>
        <span
          style={{
            fontFamily: "segoeui",
            fontSize: 18,
            alignSelf: "flex-start",
            marginLeft: 20,
            lineHeight: "1.5em",
          }}
        >
          {`ore`}
        </span>
      </div>

      <div
        style={{
          display: "flex",
          flexDirection: "column",
        }}
      >
        <div
          style={{
            flexDirection: "row",
            display: "flex",
          }}
        >
          <span
            style={{
              fontFamily: "Bungee",
              lineHeight: "1.5em",
            }}
          >
            {`${min}`}
          </span>
          <span
            style={{
              margin: "0px 30px",
              fontFamily: "Bungee",
              lineHeight: "1.5em",
            }}
          >
            :
          </span>
        </div>
        <span
          style={{
            fontFamily: "segoeui",
            fontSize: 18,
            alignSelf: "flex-start",
            lineHeight: "1.5em",
          }}
        >
          {`minuti`}
        </span>
      </div>

      <div
        style={{
          display: "flex",
          flexDirection: "column",
        }}
      >
        <div
          style={{
            flexDirection: "row",
            display: "flex",
          }}
        >
          <span
            style={{
              fontFamily: "Bungee",
              lineHeight: "1.5em",
            }}
          >
            {` ${sec}`}
          </span>
        </div>
        <span
          style={{
            fontFamily: "segoeui",
            fontSize: 18,
            lineHeight: "1.5em",
          }}
        >
          {`secondi`}
        </span>
      </div>
      {/* <div>
        <span>{`${hour}`}</span>
        <span>{`ore`} </span>
      </div>
      <div>
        <span>{` ${min}`}</span>
        <span>{`minuti`} </span>
      </div>
      <div>
        <span>{` ${sec}`}</span>
        <span>{`secondi`} </span>
      </div> */}
    </div>
  );
}

// get Top 20 Team List data //
export async function getTeamListData() {
  store.dispatch(authActions.setTeamListLoader(true));
  const checkUserLogin = isUserLogin();

  const gData = {
    user_name: "Guest User",
  };

  const eventData = checkUserLogin ? true : gData;

  try {
    let endPoint = Setting.endpoints.team_list;
    const response = await getApiData(endPoint, "GET", null);
    addAnalyticsEvent("Team_List_Data_Event", eventData);
    if (response && response.status && response.status === true) {
      if (
        response &&
        response.data &&
        _.isArray(response.data) &&
        !_.isEmpty(response.data)
      ) {
        const updateAry = [...response?.data];
        response?.data?.map((item, index) => {
          updateAry[index].label = item?.name;
          updateAry[index].value = item?.name;
        });

        store.dispatch(authActions.setTeamListData(updateAry));
        store.dispatch(authActions.setTeamListLoader(false));
      } else {
        store.dispatch(authActions.setTeamListLoader(false));
      }
    } else {
      store.dispatch(authActions.setTeamListLoader(false));
    }
  } catch (err) {
    console.log("Catch Part", err);
    store.dispatch(authActions.setTeamListLoader(false));
  }
}

export const getCountrySymbol = (country) => {
  switch(country) {
    case "IT":
      country = "I";
    break;
    case "GB":
      country = "UK";
    break; 
    case "TR":
      country = "TK";
    break; 
    case "AE":
      country = "UAE"
    break;
    case "SA":
      country = "KSA";
    break; 
    case "PT":
      country = "P";
    break;
    default:
     country = country;
  }

  return country;
}
export async function getLeagueListData() {
  store.dispatch(authActions.setTeamListLoader(true));

  try {
    let endPoint = Setting.endpoints.league_list;
    const response = await getApiData(endPoint, "GET", null);
    if (response && response.status && response.status === true) {
      if (
        response &&
        response.data &&
        response.data.league_list &&
        _.isArray(response.data.league_list) &&
        !_.isEmpty(response.data.league_list)
      ) {
        const updateAry = [...response?.data?.league_list];
        response?.data?.league_list?.map((item, index) => {
          updateAry[index].label = `${item.name} (${getCountrySymbol(item.country)})`;
          updateAry[index].value = item?.id;
        });

        store.dispatch(authActions.setLeagueListData(updateAry));
        store.dispatch(authActions.setTeamListLoader(false));
      } else {
        store.dispatch(authActions.setTeamListLoader(false));
      }
    } else {
      store.dispatch(authActions.setTeamListLoader(false));
    }
  } catch (err) {
    console.log("Catch Part", err);
    store.dispatch(authActions.setTeamListLoader(false));
  }
}


export async function getTeamListByLeague(leageuId) {
    store.dispatch(authActions.setTeamListLoader(true));
    try {
      let endPoint = `${Setting.endpoints.list_by_league}?leagueId=${leageuId}`;

      const response = await getApiData(endPoint, "GET", null);
    
      if (response && response.status && response.status === true) {
        if (
          response &&
          response.data &&
          _.isArray(response.data) &&
          !_.isEmpty(response.data)
        ) {
          const updateAry = [...response?.data];
          response?.data?.map((item, index) => {
            updateAry[index].label = item?.name;
            updateAry[index].value = item?.name;
          });
  
          store.dispatch(authActions.setTeamListData(updateAry));
          store.dispatch(authActions.setTeamListLoader(false));
        } else {
          store.dispatch(authActions.setTeamListLoader(false));
          store.dispatch(authActions.setTeamListData([]));
        }
      } else {
        store.dispatch(authActions.setTeamListLoader(false));
        store.dispatch(authActions.setTeamListData([]));
      }
    } catch (err) {
      console.log("Catch Part", err);
      store.dispatch(authActions.setTeamListLoader(false));
      store.dispatch(authActions.setTeamListData([]));
    }
}