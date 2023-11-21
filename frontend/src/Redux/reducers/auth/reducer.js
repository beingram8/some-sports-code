import types from "./actions";

const initialState = {
  tab: 1,
  userdata: {},
  selectedteam: {},
  useruuid: "",
  logoutLoad: false,
  serveyQuizData: {},
  badgeCount: 0,
  selectedNews: {},
  isNotifiy: false,
  notiData: {},
  logoanimationon: true,
  refreshDataLoad: false,
  referCode: "",
  teamList: [],
  teamListLoader: false,
  isDisplayPopUp: false,
  firstBoolValue: 0,
};

export default function reducer(state = initialState, action) {
  switch (action.type) {
    case types.SET_LOGO_ANIMATION_ON:
      localStorage.setItem("is logo animation on ? ", action.logoanimationon);
      return {
        ...state,
        logoanimationon: action.logoanimationon,
      };

    case types.SET_FIRST_BOOL_VALUE:
      return {
        ...state,
        firstBoolValue: action.firstBoolValue,
      };

    case types.SET_IS_DISPLAY_INSTALL_PWA_DESC_POPUP:
      return {
        ...state,
        isDisplayPopUp: action.isDisplayPopUp,
      };

    case types.SET_TEAM_LIST_LOADER:
      return {
        ...state,
        teamListLoader: action.teamListLoader,
      };

    case types.SET_SELECTED_TAB:
      localStorage.setItem("currentTab", action.tab);
      return {
        ...state,
        tab: action.tab,
      };

    case types.SET_TEAM_LIST_DATA:
      localStorage.setItem("teamList", JSON.stringify(action.teamList));
      return {
        ...state,
        teamList: action.teamList,
      };
    case types.SET_LEAGUE_LIST_DATA:
        localStorage.setItem("leagueList", JSON.stringify(action.leagueList));
        return {
          ...state,
          leagueList: action.leagueList,
        };
    case types.SET_REFERENCE_CODE:
      localStorage.setItem("referCode", action.referCode);
      return {
        ...state,
        referCode: action.referCode,
      };

    case types.SET_REFRESH_USER_DATA_LOAD:
      return {
        ...state,
        refreshDataLoad: action.refreshDataLoad,
      };

    case types.SET_USER_DATA:
      localStorage.setItem("userData", JSON.stringify(action.userdata));
      return {
        ...state,
        userdata: action.userdata,
      };

    case types.SET_NOTI_DATA:
      return {
        ...state,
        notiData: action.notiData,
      };

    case types.SET_DISPLAY_NOTIFICATION_POP_UP:
      return {
        ...state,
        isNotifiy: action.isNotifiy,
      };

    case types.SET_SELECTED_TEAM_DATA:
      localStorage.setItem("selectedTeam", JSON.stringify(action.selectedteam));
      return {
        ...state,
        selectedteam: action.selectedteam,
      };

    case types.SET_USER_UUID:
      localStorage.setItem("userUUID", action.useruuid);
      return {
        ...state,
        useruuid: action.useruuid,
      };

    case types.SET_LOGOUT_LOAD:
      localStorage.setItem("logoutLoad", action.logoutLoad);
      return {
        ...state,
        logoutLoad: action.logoutLoad,
      };

    case types.SET_BADGE_COUNT:
      localStorage.setItem("badgeCount", action.badgeCount);
      return {
        ...state,
        badgeCount: action.badgeCount,
      };

    case types.SELECTED_NEWS_DATA:
      localStorage.setItem("selectedNews", JSON.stringify(action.selectedNews));
      return {
        ...state,
        selectedNews: action.selectedNews,
      };

    case types.SET_SERVEY_QUIZ_ENABLE_OR_NOT:
      localStorage.setItem(
        "serveyQuizData",
        JSON.stringify(action.serveyQuizData)
      );
      return {
        ...state,
        serveyQuizData: action.serveyQuizData,
      };

    case types.CLEAR_ALL_STORAGE_DATA:
      // localStorage.clear();
      localStorage.removeItem("selectedNews");
      localStorage.removeItem("badgeCount");
      localStorage.removeItem("logoutLoad");
      localStorage.removeItem("selectedTeam");
      localStorage.removeItem("userData");
      localStorage.removeItem("currentTab");
      localStorage.removeItem("serveyQuizData");
      localStorage.removeItem("referCode");
      return {
        ...state,
        tab: 1,
        userdata: {},
        selectedteam: {},
        logoutLoad: false,
        serveyQuizData: {},
        badgeCount: 0,
        selectedNews: {},
        isNotifiy: false,
        notiData: {},
        refreshDataLoad: false,
        referCode: "",
      };

    default:
      return state;
  }
}
