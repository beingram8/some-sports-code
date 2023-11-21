const actions = {
  SET_SELECTED_TAB: "auth/SET_SELECTED_TAB",
  SET_USER_DATA: "auth/SET_USER_DATA",
  SET_SELECTED_TEAM_DATA: "auth/SET_SELECTED_TEAM_DATA",
  SET_USER_UUID: "auth/SET_USER_UUID",
  CLEAR_ALL_STORAGE_DATA: "auth/CLEAR_ALL_STORAGE_DATA",
  SET_LOGOUT_LOAD: "auth/SET_LOGOUT_LOAD",
  SET_SERVEY_QUIZ_ENABLE_OR_NOT: "auth/SET_SERVEY_QUIZ_ENABLE_OR_NOT",
  SET_BADGE_COUNT: "auth/SET_BADGE_COUNT",
  SELECTED_NEWS_DATA: "auth/SELECTED_NEWS_DATA",
  SET_DISPLAY_NOTIFICATION_POP_UP: "auth/SET_DISPLAY_NOTIFICATION_POP_UP",
  SET_NOTI_DATA: "auth/SET_NOTI_DATA",
  SET_LOGO_ANIMATION_ON: "auth/SET_LOGO_ANIMATION_ON",
  SET_REFRESH_USER_DATA_LOAD: "auth/SET_REFRESH_USER_DATA_LOAD",
  SET_REFERENCE_CODE: "auth/SET_REFERENCE_CODE",
  SET_TEAM_LIST_DATA: "auth/SET_TEAM_LIST_DATA",
  SET_LEAGUE_LIST_DATA: "auth/SET_LEAGUE_LIST_DATA",
  SET_TEAM_LIST_LOADER: "auth/SET_TEAM_LIST_LOADER",

  SET_IS_DISPLAY_INSTALL_PWA_DESC_POPUP:
    "auth/SET_IS_DISPLAY_INSTALL_PWA_DESC_POPUP",

  SET_FIRST_BOOL_VALUE: "auth/SET_FIRST_BOOL_VALUE",

  setFirstBoolValueForPopUp: (firstBoolValue) => (dispatch) =>
    dispatch({
      type: actions.SET_FIRST_BOOL_VALUE,
      firstBoolValue,
    }),

  setIsDisplayInstallPWAPopup: (isDisplayPopUp) => (dispatch) =>
    dispatch({
      type: actions.SET_IS_DISPLAY_INSTALL_PWA_DESC_POPUP,
      isDisplayPopUp,
    }),

  setTeamListLoader: (teamListLoader) => (dispatch) =>
    dispatch({
      type: actions.SET_TEAM_LIST_LOADER,
      teamListLoader,
    }),

  setTeamListData: (teamList) => (dispatch) =>
    dispatch({
      type: actions.SET_TEAM_LIST_DATA,
      teamList,
    }),
  setLeagueListData: (leagueList) => (dispatch) =>
    dispatch({
      type: actions.SET_LEAGUE_LIST_DATA,
      leagueList,
    }),
  setUserReferenceCode: (referCode) => (dispatch) =>
    dispatch({
      type: actions.SET_REFERENCE_CODE,
      referCode,
    }),

  setRefreshUserDataLoad: (refreshDataLoad) => (dispatch) =>
    dispatch({
      type: actions.SET_REFRESH_USER_DATA_LOAD,
      refreshDataLoad,
    }),

  setlogoanimationon: (logoanimationon) => (dispatch) =>
    dispatch({
      type: actions.SET_LOGO_ANIMATION_ON,
      logoanimationon,
    }),

  setNotiData: (notiData) => (dispatch) =>
    dispatch({
      type: actions.SET_NOTI_DATA,
      notiData,
    }),

  displayNotificationPopUp: (isNotifiy) => (dispatch) =>
    dispatch({
      type: actions.SET_DISPLAY_NOTIFICATION_POP_UP,
      isNotifiy,
    }),

  setSelectedNews: (selectedNews) => (dispatch) =>
    dispatch({
      type: actions.SELECTED_NEWS_DATA,
      selectedNews,
    }),

  setBadgeCount: (badgeCount) => (dispatch) =>
    dispatch({
      type: actions.SET_BADGE_COUNT,
      badgeCount,
    }),

  setServeyQuizIsEnable: (serveyQuizData) => (dispatch) =>
    dispatch({
      type: actions.SET_SERVEY_QUIZ_ENABLE_OR_NOT,
      serveyQuizData,
    }),

  setLogoutLoad: (logoutLoad) => (dispatch) =>
    dispatch({
      type: actions.SET_LOGOUT_LOAD,
      logoutLoad,
    }),

  setSelectedTab: (tab) => (dispatch) =>
    dispatch({
      type: actions.SET_SELECTED_TAB,
      tab,
    }),

  setUserData: (userdata) => (dispatch) =>
    dispatch({
      type: actions.SET_USER_DATA,
      userdata,
    }),

  setSelectedTeamData: (selectedteam) => (dispatch) =>
    dispatch({
      type: actions.SET_SELECTED_TEAM_DATA,
      selectedteam,
    }),

  setUserUUID: (useruuid) => (dispatch) =>
    dispatch({
      type: actions.SET_USER_UUID,
      useruuid,
    }),

  clearAllData: () => (dispatch) =>
    dispatch({
      type: actions.CLEAR_ALL_STORAGE_DATA,
    }),
  // setLanguage: (selectedLanguage) => (dispatch) =>  {
  //   dispatch({
  //     type: actions.SET_LANGUAGE
  //     l
  //   })
  // }
};

export default actions;
