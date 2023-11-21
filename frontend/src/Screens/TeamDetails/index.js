/* eslint-disable array-callback-return */
/* eslint-disable jsx-a11y/alt-text */
import React, { useState, useEffect, useRef } from "react";
import { RWebShare } from "react-web-share";
import "./styles.scss";
import "../../Styles/common.scss";
import { useHistory } from "react-router-dom";
import Slider from "@material-ui/core/Slider";
import { withStyles } from "@material-ui/core/styles";
import {
  getWords,
  addAnalyticsEvent,
  getRemainingSeconds,
  // getAgeValue,
} from "../../commonFunctions";
import Protected from "../../Components/Protected";
import SuccessModal from "../../Modals/SuccessModal/index";
import Header from "../../Components/Header/index";
import { useSelector } from "react-redux";
import { Setting } from "../../Utils/Setting";
import { getApiData, getAPIProgressData } from "../../Utils/APIHelper";
import _ from "lodash";
import CRequestLoader from "../../Loaders/CRequestLoader/index";
import CNoData from "../../Components/CNoData/index";
import CAlert from "../../Components/CAlert/index";
import CButton from "../../Components/CButton";
import TimerIcon from "../../Assets/Images/timer.png";
import CCountDown from "../../Components/CCountDown";
import renderHTML from "react-render-html";
import CPlayerProfileLoader from "../../Loaders/CPlayerProfileLoader/index";
import TransferComplete from "../../Modals/TransferComplete/index";
import StarRatings from 'react-star-ratings';

const CustomSlider = withStyles({
  root: {
    color: "#ED0F1B",
    height: 5,
    marginRight: 20,
    marginLeft: 20,
  },
  thumb: {
    backgroundColor: "#ED0F1B",
    border: "2px solid #ED0F1B",
    height: 15,
    width: 15,
  },
  active: {},
  track: {
    height: 5,
  },
  rail: {
    height: 5,
  },
})(Slider);

const TeamDetails = (props) => {
  const data = props?.location?.state?.listData;

  const isPastMatch = props?.location?.state?.isPastMatch;
  const isTabTwo = props?.location?.state?.fromTabTwo;

  const scrollRef = useRef(null);
  const history = useHistory();
  const [successModal, setSuccessModal] = useState(false);
  const [fromReview, setFromReview] = useState(false);
  const [CountDownBool, setCountDownBool] = useState(false);
  const { userdata } = useSelector((state) => state.auth);

  const [matchData, setMatchdata] = useState({});

  const [btnLoader, setBtnLoader] = useState(false);

  const [callFunc, setCallFunction] = useState(false);
  const [otherCall, setOtherCall] = useState(false);
  const [dispNote, setDisplayNote] = useState(false);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const tabList = [
    {
      id: 1,
      title: data?.name_of_home,
      total_points:
        !_.isNull(data?.home_total_point) && !_.isEmpty(data?.home_total_point)
          ? data?.home_total_point
          : 0,
    },
    {
      id: 2,
      title: data?.name_of_away,
      total_points:
        !_.isNull(data?.away_total_point) && !_.isEmpty(data?.away_total_point)
          ? data?.away_total_point
          : 0,
    },
  ];
  const [defaultTab, setDefaultTab] = useState(tabList[0]);
  const defaultVote = 2.5;

  // <================= For Team A =================>//
  const [playersList, setPlayersList] = useState([]);
  const [substituteList, setSubstituteList] = useState([]);
  const [coachData, setCoachData] = useState([]);

  const [teamPlayerArr, setTeamPlayerArr] = useState(playersList);
  const [teamCoachArr, setTeamCoachArr] = useState(coachData);
  const [teamSubstituteArr, setTeamSubstituteArr] = useState(substituteList);
  // <================= For Team A =================>//

  // <================= For Team B =================>//
  const [TeamBPlayersList, setTeamBPlayersList] = useState([]);
  const [TeamBSubstituteList, setTeamBSubstituteList] = useState([]);
  const [TeamBCoachData, setTeamBCoachData] = useState([]);

  const [teamBPlayerArr, setTeamBPlayerArr] = useState(TeamBPlayersList);
  const [teamBCoachArr, setTeamBCoachArr] = useState(TeamBCoachData);
  const [teamBSubstituteArr, setTeamBSubstituteArr] =
    useState(TeamBSubstituteList);
  // <================= For Team B =================>//

  const [shareURL, setShareURL] = useState("");
  const [remainingSeconds, setRemainingSec] = useState("");

  let status = [];
  let coachStatus = [];
  let subsitituteStatus = [];

  let teamBstatus = [];
  let TeamBcoachStatus = [];
  let TeamBsubsitituteStatus = [];

  const [pageLoader, setPageLoader] = useState(false);
  const [playerData, setPlayerData] = useState({});
  const [displayPlayerProfile, setDisplayPlayerProfile] = useState(false);

  const [displayAnim, setDisplayAnim] = useState(false);
  const [isearnedcoin, setIsEarnedcoin] = useState(false);

  // old data
  // const playerAvgVote = playerData?.average_vote;
  // const playerBdate = playerData?.birth_date;
  // const playerAge = getAgeValue(playerBdate);
  // const playerCity = playerData?.city;
  // const playerGamesPlayed = playerData?.games_played;
  // const playerHeight = playerData?.height;
  // const playerName = playerData?.name;
  // const playerNationality = playerData?.nationality;
  // const playerGoal = playerData?.player_goal;
  // const playerPhoto = playerData?.player_photo;
  // const playerPosition = playerData?.player_position;
  // const playerTeamLogo = playerData?.team_logo;
  // const playerWeight = playerData?.weight;

  // new data
  const playerDtl = playerData?.player_detail;
  const playerMatchList = playerData?.match_list;

  useEffect(() => {
    if (!isPastMatch || CountDownBool) {
      getMatchDetails();
    } else {
      getMatchVoteDetails();
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    document.title = Setting.page_name.TEAM_DETAILS;
  }, []);

  useEffect(() => {
    const values = !isPastMatch || CountDownBool;
    if (matchData?.is_already_voted === false) {
      if (remainingSeconds !== "00:00:00") {
        setTimeout(() => {
          const voteEndTime = data?.vote_closing_at;
          const sec = getRemainingSeconds(voteEndTime);
          setRemainingSec(sec);
          // renderMatchResult();
        }, 1000);
      } else if (values === false) {
        getMatchVoteDetails();
      }
    }

    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [matchData, remainingSeconds]);

  const showAlert = (
    open,
    title,
    message,
    callfunction,
    otherCall,
    displayNote
  ) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
    setCallFunction(callfunction);
    setOtherCall(otherCall);
    setDisplayNote(displayNote);
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
          if (callFunc) {
            submitVote();
          } else if (otherCall) {
            history.goBack();
          }
        }}
        showCancel={callFunc ? true : false}
        confirmUI={callFunc ? true : false}
        title={alertTitle}
        message={alertMessage}
        cancelText={getWords("CONTINUE_VOTE")}
        okText={getWords("CONFIRM")}
        displayNote={dispNote}
        note={getWords("NOTE_TWO_TEAMS")}
      />
    );
  }

  // get player profile
  async function getPlayerProfile(item) {
    setBtnLoader(true);
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: `${userToken}`,
    };
    const playerId = item?.player_id;

    try {
      let endPoint = `${Setting.endpoints.player_details}?player_id=${playerId}`;
      const response = await getApiData(endPoint, "GET", {}, header);
      if (response && response.status && response.status === true) {
        if (response?.data === "No data found") {
          setBtnLoader(false);
          showAlert(true, getWords("OOPS"), response?.data);
        } else {
          setPlayerData(response?.data);
          const eventData = {
            user_name: userdata?.username,
            first_name: userdata?.firstname,
            last_name: userdata?.lastname,
            email: userdata?.email,
            user_Pic: userdata?.user_image,
            player_name: response?.data?.player_detail?.name,
          };
          addAnalyticsEvent("User_Check_Player_Info_Event", eventData);
          setBtnLoader(false);
          setDisplayPlayerProfile(true);
        }
      } else {
        setBtnLoader(false);
        setPageLoader(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setBtnLoader(false);
      setPageLoader(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  // submit vote api call
  const submitVote = async () => {
    setBtnLoader(true);
    try {
      let endPoint = `${Setting.endpoints.match_vote}`;
      const voteData = {};

      let k = 0;

      // Team A Player //
      if (_.isArray(teamPlayerArr) && !_.isEmpty(teamPlayerArr)) {
        teamPlayerArr.map((obj, index) => {
          voteData[`UserMatchVote[${k}][match_id]`] = data?.match_id;
          voteData[`UserMatchVote[${k}][team_id]`] = data?.home_team_id;
          voteData[`UserMatchVote[${k}][player_id]`] = obj.player_id;
          voteData[`UserMatchVote[${k}][vote]`] = obj?.vote ? obj?.vote : 6;
          k++;
        });
      }

      // Team A Coach //
      if (_.isArray(teamCoachArr) && !_.isEmpty(teamCoachArr)) {
        teamCoachArr.map((obj, index) => {
          voteData[`UserMatchVote[${k}][match_id]`] = data?.match_id;
          voteData[`UserMatchVote[${k}][team_id]`] = data?.home_team_id;
          voteData[`UserMatchVote[${k}][player_id]`] = obj.player_id;
          voteData[`UserMatchVote[${k}][vote]`] = obj?.vote ? obj?.vote : 6;
          k++;
        });
      }

      // Team A Substitute //
      if (_.isArray(teamSubstituteArr) && !_.isEmpty(teamSubstituteArr)) {
        teamSubstituteArr.map((obj, index) => {
          voteData[`UserMatchVote[${k}][match_id]`] = data?.match_id;
          voteData[`UserMatchVote[${k}][team_id]`] = data?.home_team_id;
          voteData[`UserMatchVote[${k}][player_id]`] = obj.player_id;
          voteData[`UserMatchVote[${k}][vote]`] = obj?.vote ? obj?.vote : 6;
          k++;
        });
      }

      // Team B Player //
      if (_.isArray(teamBPlayerArr) && !_.isEmpty(teamBPlayerArr)) {
        teamBPlayerArr.map((obj, index) => {
          voteData[`UserMatchVote[${k}][match_id]`] = data?.match_id;
          voteData[`UserMatchVote[${k}][team_id]`] = data?.home_away_id;
          voteData[`UserMatchVote[${k}][player_id]`] = obj.player_id;
          voteData[`UserMatchVote[${k}][vote]`] = obj?.vote ? obj?.vote : 6;
          k++;
        });
      }

      // Team B Coach //
      if (_.isArray(teamBCoachArr) && !_.isEmpty(teamBCoachArr)) {
        teamBCoachArr.map((obj, index) => {
          voteData[`UserMatchVote[${k}][match_id]`] = data?.match_id;
          voteData[`UserMatchVote[${k}][team_id]`] = data?.home_away_id;
          voteData[`UserMatchVote[${k}][player_id]`] = obj.player_id;
          voteData[`UserMatchVote[${k}][vote]`] = obj?.vote ? obj?.vote : 6;
          k++;
        });
      }

      // Team B Substitute //
      if (_.isArray(teamBSubstituteArr) && !_.isEmpty(teamBSubstituteArr)) {
        teamBSubstituteArr.map((obj, index) => {
          voteData[`UserMatchVote[${k}][match_id]`] = data?.match_id;
          voteData[`UserMatchVote[${k}][team_id]`] = data?.home_away_id;
          voteData[`UserMatchVote[${k}][player_id]`] = obj.player_id;
          voteData[`UserMatchVote[${k}][vote]`] = obj?.vote ? obj?.vote : 6;
          k++;
        });
      }

      const response = await getAPIProgressData(
        endPoint,
        "POST",
        voteData,
        true
      );

      if (response?.status) {
        const eventData = {
          user_name: userdata?.username,
          first_name: userdata?.firstname,
          last_name: userdata?.lastname,
          email: userdata?.email,
          user_Pic: userdata?.user_image,
          match_id: data?.match_id,
        };

        // const voteData1 = response?.data?.vote_details;
        // console.log("votedata1 array=====>>>>> ", voteData1);
        addAnalyticsEvent("User_Submit_Vote_Event", eventData);
        setBtnLoader(false);
        if (
          _.isObject(response?.data) &&
          !_.isEmpty(response?.data) &&
          _.has(response?.data, "is_animation") &&
          response?.data?.is_animation
        ) {
          setIsEarnedcoin(true);
        } else {
          setIsEarnedcoin(false);
        }
        setTimeout(() => {
          setCountDownBool(true);
          setFromReview(true);
          setSuccessModal(true);
        }, 500);
      } else {
        setBtnLoader(false);
        setPageLoader(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setBtnLoader(false);
      setPageLoader(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  // current match details api call
  const getMatchDetails = async () => {
    setBtnLoader(true);
    const header = {
      authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = `${Setting.endpoints.match_details}?match_id=${data?.match_id}`;
      const response = await getApiData(endPoint, "get", {}, header);
      if (response?.status) {
        setBtnLoader(false);
        setMatchdata(response?.data?.match);

        const teamA =
          response &&
            response.data &&
            response.data.teams &&
            _.isArray(response.data.teams) &&
            !_.isEmpty(response.data.teams)
            ? response.data.teams[0]
            : {};

        const teamB =
          response &&
            response.data &&
            response.data.teams &&
            _.isArray(response.data.teams) &&
            !_.isEmpty(response.data.teams)
            ? response.data.teams[1]
            : {};

        // Team A Data //
        setPlayersList(teamA?.players);
        setSubstituteList(teamA?.substitue);
        setCoachData(teamA?.coach);

        setTeamPlayerArr(teamA?.players);
        setTeamCoachArr(teamA?.coach);
        setTeamSubstituteArr(teamA?.substitue);
        // Team A Data //

        // Team B Data //
        setTeamBPlayersList(teamB?.players);
        setTeamBSubstituteList(teamB?.substitue);
        setTeamBCoachData(teamB?.coach);

        setTeamBPlayerArr(teamB?.players);
        setTeamBCoachArr(teamB?.coach);
        setTeamBSubstituteArr(teamB?.substitue);
        // Team B Data //

        const eventData = {
          user_name: userdata?.username,
          first_name: userdata?.firstname,
          last_name: userdata?.lastname,
          email: userdata?.email,
          user_Pic: userdata?.user_image,
          match_id: response?.data?.match?.match_id,
          team_one_name: response?.data?.match?.name_of_home,
          team_two_name: response?.data?.match?.name_of_away,
        };
        addAnalyticsEvent("Current_Match_Details_Data_Event", eventData);
      } else {
        setBtnLoader(false);
        setPageLoader(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      setBtnLoader(false);
      setPageLoader(false);
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  async function getShareURL() {
    const header = {
      authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = `${Setting.endpoints.get_vote_card_share_url}?match_id=${data?.match_id}`;
      const response = await getApiData(endPoint, "get", null, header);

      if (response?.status) {
        const url = response?.data?.sharable_url;
        setShareURL(url);
        setBtnLoader(false);
      } else {
        setBtnLoader(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      setBtnLoader(false);
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  // already played match details api call
  const getMatchVoteDetails = async () => {
    setBtnLoader(true);
    const header = {
      authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = `${Setting.endpoints.match_vote_detail}?match_id=${data?.match_id}`;
      const response = await getApiData(endPoint, "get", {}, header);
      if (response?.status) {
        getShareURL();

        setMatchdata(response?.data?.match);

        const teamA =
          response &&
            response.data &&
            response.data.teams &&
            _.isArray(response.data.teams) &&
            !_.isEmpty(response.data.teams)
            ? response.data.teams[0]
            : {};

        const teamB =
          response &&
            response.data &&
            response.data.teams &&
            _.isArray(response.data.teams) &&
            !_.isEmpty(response.data.teams)
            ? response.data.teams[1]
            : {};

        // Team A Data //
        setPlayersList(teamA?.players);
        setSubstituteList(teamA?.substitue);
        setCoachData(teamA?.coach);
        // Team A Data //

        // Team B Data //
        setTeamBPlayersList(teamB?.players);
        setTeamBSubstituteList(teamB?.substitue);
        setTeamBCoachData(teamB?.coach);
        // Team B Data //

        const eventData = {
          user_name: userdata?.username,
          first_name: userdata?.firstname,
          last_name: userdata?.lastname,
          email: userdata?.email,
          user_Pic: userdata?.user_image,
          match_id: response?.data?.match?.match_id,
          team_one_name: response?.data?.match?.name_of_home,
          team_two_name: response?.data?.match?.name_of_away,
        };

        addAnalyticsEvent("Past_Match_Details_Data_Event", eventData);
      } else {
        setBtnLoader(false);
        showAlert(true, getWords("OOPS"), response?.message, false, true);
      }
    } catch (err) {
      setBtnLoader(false);
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  // change slider value
  const setSliderValue = (item, index, value, str) => {
    if (defaultTab?.id === 1) {
      if (str === "Players") {
        status = playersList;
        const arrayList = playersList;
        const updateAry = [...arrayList];

        updateAry[index].vote = value;
        status = updateAry;
        setTeamPlayerArr(status);
      } else if (str === "Coach") {
        coachStatus = coachData;
        const arrayList = coachData;
        const updateAry = [...arrayList];

        updateAry[index].vote = value;
        coachStatus = updateAry;
        setTeamCoachArr(coachStatus);
      } else if (str === "Substitite") {
        subsitituteStatus = substituteList;
        const arrayList = substituteList;
        const updateAry = [...arrayList];

        updateAry[index].vote = value;
        subsitituteStatus = updateAry;
        setTeamSubstituteArr(subsitituteStatus);
      }
    } else {
      if (str === "Players") {
        teamBstatus = TeamBPlayersList;
        const arrayList = TeamBPlayersList;
        const updateAry = [...arrayList];

        updateAry[index].vote = value;
        teamBstatus = updateAry;
        setTeamBPlayerArr(teamBstatus);
      } else if (str === "Coach") {
        TeamBcoachStatus = TeamBCoachData;
        const arrayList = TeamBCoachData;
        const updateAry = [...arrayList];

        updateAry[index].vote = value;
        TeamBcoachStatus = updateAry;
        setTeamBCoachArr(TeamBcoachStatus);
      } else if (str === "Substitite") {
        TeamBsubsitituteStatus = TeamBSubstituteList;
        const arrayList = TeamBSubstituteList;
        const updateAry = [...arrayList];

        updateAry[index].vote = value;
        TeamBsubsitituteStatus = updateAry;
        setTeamBSubstituteArr(TeamBsubsitituteStatus);
      }
    }
  };

  // add values of slider
  const steadd = (index, str) => {
    if (defaultTab?.id === 1) {
      if (str === "Players") {
        let array = playersList;
        status = playersList;
        const arrayList = playersList;
        const updateAry = [...arrayList];
        updateAry[index].vote = array[index]?.vote
          ? array[index]?.vote + 0.5
          : defaultVote + 0.5;
        status = updateAry;
        setTeamPlayerArr(status);
      } else if (str === "Coach") {
        let array = coachData;
        coachStatus = coachData;
        const arrayList = coachData;
        const updateAry = [...arrayList];
        updateAry[index].vote = array[index]?.vote
          ? array[index]?.vote + 0.5
          : defaultVote + 0.5;
        coachStatus = updateAry;
        setTeamCoachArr(coachStatus);
      } else if (str === "Substitite") {
        let array = substituteList;
        subsitituteStatus = substituteList;
        const arrayList = substituteList;
        const updateAry = [...arrayList];
        updateAry[index].vote = array[index]?.vote
          ? array[index]?.vote + 0.5
          : defaultVote + 0.5;
        subsitituteStatus = updateAry;
        setTeamSubstituteArr(subsitituteStatus);
      }
    } else {
      if (str === "Players") {
        let array = TeamBPlayersList;
        teamBstatus = TeamBPlayersList;
        const arrayList = TeamBPlayersList;
        const updateAry = [...arrayList];
        updateAry[index].vote = array[index]?.vote
          ? array[index]?.vote + 0.5
          : defaultVote + 0.5;
        teamBstatus = updateAry;
        setTeamBPlayerArr(teamBstatus);
      } else if (str === "Coach") {
        let array = TeamBCoachData;
        TeamBcoachStatus = TeamBCoachData;
        const arrayList = TeamBCoachData;
        const updateAry = [...arrayList];
        updateAry[index].vote = array[index]?.vote
          ? array[index]?.vote + 0.5
          : defaultVote + 0.5;
        TeamBcoachStatus = updateAry;
        setTeamBCoachArr(TeamBcoachStatus);
      } else if (str === "Substitite") {
        let array = TeamBSubstituteList;
        TeamBsubsitituteStatus = TeamBSubstituteList;
        const arrayList = TeamBSubstituteList;
        const updateAry = [...arrayList];
        updateAry[index].vote = array[index]?.vote
          ? array[index]?.vote + 0.5
          : defaultVote + 0.5;
        TeamBsubsitituteStatus = updateAry;
        setTeamBSubstituteArr(TeamBsubsitituteStatus);
      }
    }
  };

  // subtract values of slider
  const sub = (index, str) => {
    if (defaultTab?.id === 1) {
      if (str === "Players") {
        let array = playersList;
        status = playersList;
        const arrayList = playersList;
        const updateAry = [...arrayList];
        updateAry[index].vote = array[index]?.vote
          ? array[index]?.vote - 0.5
          : defaultVote - 0.5;
        status = updateAry;
        setTeamPlayerArr(status);
      } else if (str === "Coach") {
        let array = coachData;
        coachStatus = coachData;
        const arrayList = coachData;
        const updateAry = [...arrayList];
        updateAry[index].vote = array[index]?.vote
          ? array[index]?.vote - 0.5
          : defaultVote - 0.5;
        coachStatus = updateAry;
        setTeamCoachArr(coachStatus);
      } else if (str === "Substitite") {
        let array = substituteList;
        subsitituteStatus = substituteList;
        const arrayList = substituteList;
        const updateAry = [...arrayList];
        updateAry[index].vote = array[index]?.vote
          ? array[index]?.vote - 0.5
          : defaultVote - 0.5;
        subsitituteStatus = updateAry;
        setTeamSubstituteArr(subsitituteStatus);
      }
    } else {
      if (str === "Players") {
        let array = TeamBPlayersList;
        teamBstatus = TeamBPlayersList;
        const arrayList = TeamBPlayersList;
        const updateAry = [...arrayList];
        updateAry[index].vote = array[index]?.vote
          ? array[index]?.vote - 0.5
          : defaultVote - 0.5;
        teamBstatus = updateAry;
        setTeamBPlayerArr(teamBstatus);
      } else if (str === "Coach") {
        let array = TeamBCoachData;
        TeamBcoachStatus = TeamBCoachData;
        const arrayList = TeamBCoachData;
        const updateAry = [...arrayList];
        updateAry[index].vote = array[index]?.vote
          ? array[index]?.vote - 0.5
          : defaultVote - 0.5;
        TeamBcoachStatus = updateAry;
        setTeamBCoachArr(TeamBcoachStatus);
      } else if (str === "Substitite") {
        let array = TeamBSubstituteList;
        TeamBsubsitituteStatus = TeamBSubstituteList;
        const arrayList = TeamBSubstituteList;
        const updateAry = [...arrayList];
        updateAry[index].vote = array[index]?.vote
          ? array[index]?.vote - 0.5
          : defaultVote - 0.5;
        TeamBsubsitituteStatus = updateAry;
        setTeamBSubstituteArr(TeamBsubsitituteStatus);
      }
    }
  };

  function renderPlayerInfo(str) {
    let arrayData = [];

    if (str === "Players") {
      arrayData = defaultTab?.id === 1 ? playersList : TeamBPlayersList;
    } else if (str === "Coach") {
      arrayData = defaultTab?.id === 1 ? coachData : TeamBCoachData;
    } else if (str === "Substitite") {
      arrayData = defaultTab?.id === 1 ? substituteList : TeamBSubstituteList;
    }

    if (_.isArray(arrayData) && !_.isEmpty(arrayData)) {
      return (
        <div>
          {!isPastMatch || CountDownBool ? (
            <div className={"playerContainer"}>
              <div
                style={{
                  width: window.innerWidth >= 480 ? "100px" : "80px",
                }}
              >
                <span className="playerNameRating">
                  {str === "Players"
                    ? getWords("Players")
                    : str === "Substitite"
                      ? getWords("Substitute")
                      : getWords("Coach")}
                </span>
              </div>
            </div>
          ) : (
            <div className={"playerContainer"}>
              <div
                style={{
                  width: window.innerWidth >= 480 ? "100px" : "80px",
                  textAlign: "center",
                }}
              >
                <span className="playerNameRating">
                  {str === "Players"
                    ? getWords("Players")
                    : str === "Substitite"
                      ? getWords("Substitute")
                      : getWords("Coach")}
                </span>
              </div>

              <div className="teamDetailsDiv1">
                <div className="playerNameRatingDiv">
                  <div className="flexWidth100">
                    <span
                      className="playerNameRating"
                      style={{
                        paddingLeft: window.innerWidth < 370 ? 5 : 10,
                      }}
                    >
                      {getWords("NAME")}
                    </span>
                  </div>

                  <div
                    className="TDDiv1"
                    style={{
                      padding: window.innerWidth < 370 ? "0px 4px" : "0px 3px",
                    }}
                  >
                    <span className="playerNameRating">
                      {getWords("VOTE_NEW")}
                    </span>
                  </div>

                  <div
                    className="TDDiv2"
                    style={{
                      padding: window.innerWidth < 370 ? "0px 4px" : "0px 3px",
                    }}
                  >
                    <span
                      style={{
                        textAlign: "right",
                      }}
                      className="playerNameRating"
                    >
                      {getWords("Average_Vote")}
                    </span>
                  </div>

                  <div
                    className="TDDiv3"
                    style={{
                      padding: window.innerWidth < 370 ? "0px 4px" : "0px 3px",
                    }}
                  >
                    <span
                      style={{
                        textAlign: "right",
                      }}
                      className="playerNameRating"
                    >
                      {getWords("POINTS")}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          )}

          {arrayData.map((item, index) => {
            return (
              <div
                key={index}
                className={"playerContainer"}
                style={{
                  backgroundColor: index % 2 === 0 ? "#FFFFFF" : "#F6F6F6",
                }}
              >
                <div className="playerImageDiv">
                  <img
                    loading="lazy"
                    alt={"PlayerIcon"}
                    className="playerImg"
                    src={item?.photo}
                    onClick={() => {
                      getPlayerProfile(item);
                    }}
                  />
                </div>

                <div className="flexWidth100Column">
                  <div
                    className="playerNameRatingDiv"
                    style={{
                      display: "flex",
                      justifyContent: "space-between",
                    }}
                  >
                    <div class="TDDiv4">
                      <span
                        className="playerNameRating2"
                        style={{
                          paddingLeft: window.innerWidth < 370 ? 5 : 10,
                        }}
                      >
                        {item?.name !== ""
                          ? renderHTML(_.toString(item?.name))
                          : "-"}
                      </span>
                      <span
                        className="playerPositionRating"
                        style={{
                          paddingLeft: window.innerWidth < 370 ? 5 : 10,
                        }}
                      >
                        {item?.position !== ""
                          ? `(${getWords(`${item?.position?.toUpperCase()}`)})`
                          : "-"}
                      </span>
                    </div>

                    <div
                      className="TDDiv1"
                      style={{
                        padding: window.innerWidth < 370 ? "0px 7px" : "unset",
                      }}
                    >
                      <span className="playerNameRatingVote">
                        {_.has(item, "vote") ? item?.vote : ""}
                      </span>
                    </div>

                    {matchData?.is_vote_enabled ? <div
                      className="TDDiv2"
                      style={{
                        padding:
                          window.innerWidth < 370 ? "0px 8px" : "unset",
                      }}
                    > <span
                      style={{
                        textAlign: "right",
                      }}
                      className="playerNameRating2"
                    >
                        {item?.avg_vote !== "" ? item?.avg_vote : "-"}
                      </span></div> : (
                      <div
                        className="TDDiv2"
                        style={{
                          padding:
                            window.innerWidth < 370 ? "0px 8px" : "unset",
                        }}
                      >
                        <span
                          style={{
                            textAlign: "right",
                          }}
                          className="playerNameRating2"
                        >
                          {item?.avg_vote !== "" ? item?.avg_vote : "-"}
                        </span>
                      </div>
                    )}

                    {matchData?.is_vote_enabled ? <div
                      className="TDDiv3"
                      style={{
                        padding:
                          window.innerWidth < 370 ? "0px 8px" : "unset",
                      }}
                    ><span
                      style={{
                        textAlign: "right",
                      }}
                      className="playerNameRating2"
                    ></span></div> : (
                      <div
                        className="TDDiv3"
                        style={{
                          padding:
                            window.innerWidth < 370 ? "0px 8px" : "unset",
                        }}
                      >
                        <span
                          style={{
                            textAlign: "right",
                          }}
                          className="playerNameRating2"
                        >
                          {item?.point !== "" ? item?.point : "-"}
                        </span>
                      </div>
                    )}
                  </div>

                  {!isPastMatch || CountDownBool ? (
                    <div className="sliderFunctionDiv">
                      <div
                        className="plusminusButtonStyle"
                        onClick={() => {
                          if (item?.vote === 1) {
                            return true;
                          } else {
                            sub(index, str);
                          }
                        }}
                      >
                        <span className="plusminusTextStyle">-</span>
                      </div>

                      <div
                        style={{
                          // width: "100%",
                          marginRight: window.innerWidth >= 600 ? 40 : 35,
                          // paddingTop: window.innerWidth >= 600 ? 10 : 8,
                          paddingLeft: 15
                        }}
                      >
                        <StarRatings
                          rating={
                            item?.vote !== 0 && typeof item?.vote === "number"
                              ? item?.vote
                              : defaultVote
                          }
                          numberOfStars={5}
                          changeRating={(val) => {
                            setSliderValue(item, index, val, str);
                          }}
                          starRatedColor={'rgb(255, 239, 0)'}
                          starDimension={window.innerWidth >= 600 ? "30px" : "17px"}
                        ></StarRatings>
                        {/* <CustomSlider
                          step={0.5}
                          min={1}
                          max={10}
                          defaultValue={defaultVote}
                          value={
                            item?.vote !== 0 && typeof item?.vote === "number"
                              ? item?.vote
                              : defaultVote
                          }
                          onChange={(e, val) => {
                            if (window.innerWidth >= 600) {
                              setSliderValue(item, index, val, str);
                            } else {
                            }
                          }}
                        /> */}
                        {/* <div className="slidercheckpointDiv">
                          <span className="slidercheckpoint">1</span>
                          <span
                            style={{
                              marginRight:
                                window.innerWidth >= 1100
                                  ? 75
                                  : window.innerWidth >= 850
                                    ? 55
                                    : window.innerWidth >= 650
                                      ? 30
                                      : window.innerWidth >= 450
                                        ? 15
                                        : window.innerWidth >= 320
                                          ? 5
                                          : 5,
                            }}
                            className="slidercheckpoint"
                          >
                            5
                          </span>
                          <span className="slidercheckpoint">10</span>
                        </div> */}
                      </div>

                      <div
                        style={{
                          zIndex: 10,
                        }}
                        className="plusminusButtonStyle"
                        onClick={(e) => {
                          if (item?.vote >= 5) {
                            return true;
                          } else {
                            steadd(index, str);
                          }
                        }}
                      >
                        <span className="plusminusTextStyle">+</span>
                      </div>
                    </div>
                  ) : null}
                </div>
              </div>
            );
          })}
        </div>
      );
    }

    return null;
  }

  function renderTabs() {
    return (
      <div>
        <div className="tdTabDiv1">
          {tabList?.map((obj, index) => {
            const isSelected = _.isEqual(obj, defaultTab);
            return (
              <div
                className="tdTabDiv2"
                key={index}
                ref={scrollRef}
                style={{
                  borderBottom: `2px solid ${isSelected ? "#ED0F1B" : "hsl(0deg 0% 96%)"
                    }`,
                  color: `${isSelected ? "#222" : "#555"}`,
                }}
                onClick={() => {
                  setDefaultTab(obj);
                }}
              >
                <span
                  className="tabtitleTD"
                  style={{
                    fontSize: window.innerWidth >= 480 ? "20px" : "17px",
                  }}
                >
                  {obj.title}{" "}
                  {matchData?.is_already_voted ? `(${obj.total_points})` : null}
                </span>
              </div>
            );
          })}
        </div>
      </div>
    );
  }

  function renderVoteButton() {
    // fejanbhai told me to change this condition 14 aug 21 saturday 12:30 //
    const isShare = matchData?.is_already_voted;

    const isTeamAEmpty =
      _.isArray(playersList) &&
      _.isEmpty(playersList) &&
      _.isArray(coachData) &&
      _.isEmpty(coachData) &&
      _.isArray(substituteList) &&
      _.isEmpty(substituteList);

    const isTeamBEmpty =
      _.isArray(TeamBPlayersList) &&
      _.isEmpty(TeamBPlayersList) &&
      _.isArray(TeamBCoachData) &&
      _.isEmpty(TeamBCoachData) &&
      _.isArray(TeamBSubstituteList) &&
      _.isEmpty(TeamBSubstituteList);

    if (isTeamAEmpty && isTeamBEmpty) {
      return null;
    } else if (isShare && _.isString(shareURL) && shareURL !== "") {
      return (
        <RWebShare
          data={{
            text: "Vote Card",
            url: shareURL,
            title: "fanratingweb.com",
          }}
          onClick={() => {
            const shareMatchData = {
              user_name: userdata?.username,
              first_name: userdata?.firstname,
              last_name: userdata?.lastname,
              email: userdata?.email,
              user_Pic: userdata?.user_image,
              share_match_id: matchData?.match_id,
            };
            addAnalyticsEvent("User_Share_Voted_Match_Details", shareMatchData);
          }}
        >
          <div
            className="CommonContainer"
            style={{
              position: "fixed",
              bottom: 0,
              paddingBottom: "10px",
            }}
          >
            <CButton
              buttonStyle={{
                marginLeft: window.innerWidth >= 640 ? "0px" : "20px",
                marginRight: window.innerWidth >= 640 ? "0px" : "20px",
                marginTop: "0px",
                bottom: "0px",
                padding: "0px",
                height: "50px",
                width: window.innerWidth >= 640 ? "100%" : "calc(100% - 40px)",
              }}
              boldText={true}
              shareIcon
              buttonText={getWords("SHARE_BTN")}
              handleBtnClick={() => {
                return null;
              }}
            />
          </div>
        </RWebShare>
      );
    } else {
      return (
        <div
          className="CommonContainer"
          style={{
            position: "fixed",
            bottom: 0,
            paddingTop: "10px",
            paddingBottom: "10px",
          }}
        >
          <CButton
            buttonStyle={{
              marginTop: "0px",
              marginLeft: window.innerWidth >= 640 ? "0px" : "20px",
              marginRight: window.innerWidth >= 640 ? "0px" : "20px",
              bottom: "0px",
              padding: "0px",
              height: "50px",
              width: window.innerWidth >= 640 ? "100%" : "calc(100% - 40px)",
            }}
            addIcon
            boldText={true}
            buttonText={getWords("SUBMIT")}
            handleBtnClick={() => {
              console.log("on click");
              let countA = 0;
              let countB = 0;

              console.log("list ===>>>>> ", playersList, TeamBPlayersList);

              // tab 1
              if (_.isArray(playersList) && !_.isEmpty(playersList)) {
                playersList?.map((it) => {
                  // if (_.has(it, "vote") && it?.vote > 5) {
                  //   countA += 1;
                  // }
                  if (_.has(it, "vote")) {
                    countA += 1;
                  }
                });
              }

              // tab 2
              if (_.isArray(TeamBPlayersList) && !_.isEmpty(TeamBPlayersList)) {
                TeamBPlayersList?.map((it) => {
                  // if (_.has(it, "vote") && it?.vote > 5) {
                  //   countB += 1;
                  // }
                  if (_.has(it, "vote")) {
                    countB += 1;
                  }
                });
              }

              console.log("countA ====>>>> ", countA);
              console.log("countB ====>>>> ", countB);
              if (countA < 11 && countB < 11) {
                showAlert(
                  true,
                  getWords("ATTENTION"),
                  getWords("VOTE_ALL_PLAYERS")
                );
              } else {
                showAlert(
                  true,
                  getWords("READY_TO_SEND"),
                  `Vuoi confermare sia la pagella di ${data?.name_of_home} che di ${data?.name_of_away}? Conferma oppure modifica i voti.`,
                  true,
                  false,
                  true
                );
              }
            }}
          />
        </div>
      );
    }
  }

  function renderWinnerButton(str) {
    if (isTabTwo) {
      return (
        <div
          className="winnerButtonDiv1"
          onClick={() => {
            history.push({
              pathname: "/ranking",
              state: {
                fromTeamDetails: true,
                data: matchData,
                from: str,
              },
            });
          }}
        >
          <span className="viewStyleTD">{getWords("VIEW").toUpperCase()}</span>
          <span className="viewStyleTD">
            {getWords("FAN_WINNERS").toUpperCase()}
          </span>
        </div>
      );
    }
  }

  function renderMatchInfo() {
    return (
      <div
        className="OtherCommonContainer"
        style={{
          backgroundColor: "#ED0F18",
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
        }}
      >
        <div
          className="matchInfoDiv1"
          style={{
            width: window.innerWidth >= 1000 ? "50%" : "100%",
          }}
        >
          <div className="matchInfoDiv2">
            <span className="matchHeaderText">{matchData?.match_date}</span>
            <img
              className="TDteamLogo"
              loading="lazy"
              src={matchData?.logo_of_home}
              alt={"HomeIcon"}
            />

            <span className="teamName">{matchData?.name_of_home}</span>
            {renderWinnerButton("Home")}
          </div>

          <div className="TDDiv5">
            <span
              className="matchHeaderText"
              style={{
                paddingTop: 10,
                fontSize: window.innerWidth >= 450 ? 16 : 14,
                textAlign: "center",
              }}
            >
              {matchData?.league_name}
            </span>
            <span
              style={{
                textAlign: "center",
                paddingTop: "5px",
              }}
              className="scoreStyle"
            >
              {matchData?.goal_of_home_team} : {matchData?.goal_of_away_team}
            </span>

            {matchData?.is_already_voted ? null : (
              <div className="TDDiv6">
                <img loading="lazy" className="timerIconTD" src={TimerIcon} />
                <CCountDown remainingSeconds={remainingSeconds} />
              </div>
            )}
            <div className="animatequizeTD">
              <span className="alreadyVotedTextTD">
                {matchData?.is_already_voted
                  ? "" //getWords("MATCH_RESULTS")
                  : getWords("HURRY_UP_VOTE")}
              </span>
            </div>
          </div>

          <div className="TDDiv7">
            <span className="matchHeaderText">{matchData?.match_time}</span>
            <img
              loading="lazy"
              style={{
                width: "50px",
                height: "50px",
                padding: "23px 0px",
              }}
              src={matchData?.logo_of_away}
              alt={"AnotherTeamIcon"}
            />
            <span className="teamName">{matchData?.name_of_away}</span>
            {renderWinnerButton("Away")}
          </div>
        </div>
      </div>
    );
  }

  function renderNoData() {
    return (
      <CNoData
        message={getWords("SORRY_NO_DATA_FOUND")}
        hasheader={true}
        otherStyle={{
          display: "flex",
          alignItem: "center",
          justifyContent: "center",
          width: "100%",
          height: "100%",
        }}
      />
    );
  }

  function renderPlayersList() {
    const isTeamAEmpty =
      _.isArray(playersList) &&
      _.isEmpty(playersList) &&
      _.isArray(coachData) &&
      _.isEmpty(coachData) &&
      _.isArray(substituteList) &&
      _.isEmpty(substituteList);

    const isTeamBEmpty =
      _.isArray(TeamBPlayersList) &&
      _.isEmpty(TeamBPlayersList) &&
      _.isArray(TeamBCoachData) &&
      _.isEmpty(TeamBCoachData) &&
      _.isArray(TeamBSubstituteList) &&
      _.isEmpty(TeamBSubstituteList);

    if (
      (defaultTab?.id === 1 && isTeamAEmpty) ||
      (defaultTab?.id === 2 && isTeamBEmpty)
    ) {
      return renderNoData();
    } else {
      return (
        <div>
          <div>
            {renderPlayerInfo("Players")}
            {renderPlayerInfo("Substitite")}
            {renderPlayerInfo("Coach")}
          </div>
        </div>
      );
    }
  }

  if (btnLoader) {
    return (
      <Protected>
        <div className="MainContainer">
          <Header isSubScreen={true} />
          <CRequestLoader
            openModal={btnLoader}
            handleClose={() => {
              setBtnLoader(false);
            }}
          />
        </div>
      </Protected>
    );
  }

  const renderTeamDetails = () => {
    return (
      <div className="MainContainer">
        <Header isSubScreen={true} />
        <div className="TDDiv8">
          <div>{renderMatchInfo()}</div>
          <div>{renderTabs()}</div>
          <div>{renderPlayersList()}</div>
          <div>{renderVoteButton()}</div>
        </div>
        <SuccessModal
          successModal={successModal}
          handleClose={() => {
            setSuccessModal(false);

            if (isearnedcoin) {
              setTimeout(() => {
                setDisplayAnim(true);
              }, 500);
              setTimeout(() => {
                setDisplayAnim(false);
                history.goBack();
              }, 2500);
            }
          }}
          fromReview={fromReview}
          fromVote={fromReview}
        />
      </div>
    );
  };

  // new design as per 02/09/21
  const renderPlayerProfile1 = () => {
    return pageLoader ? (
      <div className="MainContainer">
        <CPlayerProfileLoader web={(window.innerWidth >= 600).toString()} />
      </div>
    ) : (
      <div className="MainContainer">
        <Header
          isSubScreen={true}
          onGoback={true}
          onBack={() => {
            setDisplayPlayerProfile(false);
          }}
        />
        <div className="CommonContainer ppmainContainer">
          <div>
            <div className="playerDetailsContainer">
              {/* player photo */}
              <img
                loading="lazy"
                className="playerImageStylePP"
                src={playerDtl?.player_photo}
                alt={"player_photo"}
              />
              <div className="playerDetailsDivPP">
                <div className="spaceBtnStylePP">
                  {/* player name */}
                  <div>
                    <span className="titleStylePP">{playerDtl?.name}</span>
                  </div>

                  {/* player avg vote */}
                  <div>
                    <span className="titleStylePP">
                      {getWords("Average_Vote")}
                    </span>
                  </div>
                </div>

                <div className="spaceBtnStylePP">
                  {/* player name */}
                  <div>
                    <span
                      style={{
                        marginLeft: 0,
                      }}
                      className="otherTeamNamepp"
                    >
                      {playerDtl?.team_name}
                    </span>
                  </div>

                  {/* player avg vote */}
                  <div>
                    <span className="otherTeamNamepp">
                      {playerDtl?.average_vote}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* divider */}
          <div className="dividerpp" />

          {/* player match list */}
          {renderPlayerMatchDetails()}
        </div>
      </div>
    );
  };

  // displaying player match list
  const renderPlayerMatchDetails = () => {
    return (
      <div className="margin20TD">
        {/* title */}
        <div className="otherTeamDtlsContainerpp zeroPadding">
          <div className="otherTeamDtlsContainerpp1 centerTitleStylePP">
            <div className={"otherTeamDtlspp centerTitleStylePP"}>
              <span className="titleStylePP">{getWords("MATCH")}</span>
            </div>
          </div>
          <div>
            <span className="titleStylePP">{getWords("POSITION")}</span>
          </div>
          <div>
            <span className="titleStylePP">{getWords("VOTE_NEW")}</span>
          </div>
        </div>
        {/* divider */}
        <div className="dividerpp" />
        {_.isArray(playerMatchList) && !_.isEmpty(playerMatchList)
          ? playerMatchList?.map((item, index) => {
            return (
              <div>
                <div className="otherTeamDtlsContainerpp">
                  <div className="otherTeamDtlsContainerpp1">
                    <div className={"otherTeamDtlspp"}>
                      <img
                        loading="lazy"
                        className="otherTeamFlagpp"
                        src={item?.logo_of_home}
                        alt={"otherTeamImg"}
                      />
                      <span className="otherTeamNamepp">
                        {item?.name_of_home}
                      </span>
                    </div>
                    <div className={"otherTeamDtlspp"}>
                      <img
                        loading="lazy"
                        className="otherTeamFlagpp"
                        src={item?.logo_of_away}
                        alt={"otherTeamFlagImg"}
                      />
                      <span
                        style={{
                          marginLeft:
                            item?.name_of_away.length > 9 &&
                              window.innerWidth <= 450 &&
                              window.innerWidth >= 370
                              ? 20
                              : 10,
                        }}
                        className="otherTeamNamepp"
                      >
                        {item?.name_of_away}
                      </span>
                    </div>
                  </div>

                  <div>
                    <span className="otherTeamNamepp">
                      {getWords(`${item.position.toUpperCase()}`)}
                    </span>
                  </div>
                  <div>
                    <span className="otherTeamNamepp">
                      {item.player_avg_vote === 0
                        ? "0.00"
                        : item.player_avg_vote}
                    </span>
                  </div>
                </div>
              </div>
            );
          })
          : null}
      </div>
    );
  };

  return (
    <Protected>
      {displayPlayerProfile ? renderPlayerProfile1() : renderTeamDetails()}

      <TransferComplete
        animationtype="coinrotation"
        openModal={displayAnim}
        handleClose={() => {
          setTimeout(() => {
            setDisplayAnim(false);
          }, 1000);
        }}
      />
      {renderAlert()}
    </Protected>
  );
};

// [{
//   "relation": ["delegate_permission/common.handle_all_urls"],
//   "target" : {
//     "namespace": "android_app",
//     "package_name": "com.fanratingweb.twa",
//     "sha256_cert_fingerprints":
//      ["D4:75:AA:F6:DD:A8:02:7B:F8:BF:91:0F:A6:77:BB:5A:CE:B3:FC:BE:96:53:E1:7C:B6:8C:BB:00:7E:31:9A:C8"]
//   }
// }
// ]

export default TeamDetails;
