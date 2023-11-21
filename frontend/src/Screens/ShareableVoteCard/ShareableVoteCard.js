import React, { useState, useEffect } from "react";
import { useLocation } from "react-router-dom";
import renderHTML from "react-render-html";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import { Setting } from "../../Utils/Setting";
import { getApiData } from "../../Utils/APIHelper";
import CAlert from "../../Components/CAlert/index";
import Headers from "../../Components/Header/index";
import CNoData from "../../Components/CNoData/index";
import CRequestLoader from "../../Loaders/CRequestLoader/index";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";

const ShareableVoteCard = () => {
  const location = useLocation();

  const matchIdToken =
    location && location.search && !_.isEmpty(location.search)
      ? _.toString(location.search).substring(1)
      : "";

  const [matchData, setMatchdata] = useState({});
  const [btnLoader, setBtnLoader] = useState(true);

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  const [tabList, setTabList] = useState([]);
  const [defaultTab, setDefaultTab] = useState({});

  // <================= For Team A =================>//
  const [playersList, setPlayersList] = useState([]);
  const [substituteList, setSubstituteList] = useState([]);
  const [coachData, setCoachData] = useState([]);
  // <================= For Team A =================>//

  // <================= For Team B =================>//
  const [TeamBPlayersList, setTeamBPlayersList] = useState([]);
  const [TeamBSubstituteList, setTeamBSubstituteList] = useState([]);
  const [TeamBCoachData, setTeamBCoachData] = useState([]);
  // <================= For Team B =================>//

  useEffect(() => {
    getMatchDetails();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    document.title = Setting.page_name.VOTE_DETAILS;
  }, []);

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

  const getMatchDetails = async () => {
    setBtnLoader(true);
    try {
      let endPoint = `${Setting.endpoints.vote_card}?token=${matchIdToken}`;
      const response = await getApiData(endPoint, "get", null);

      if (response?.status) {
        setMatchdata(response?.data?.match);

        const tabDataList = [
          {
            id: 1,
            title: response?.data?.match?.name_of_home,
            total_points:
              !_.isNull(response?.data?.match?.home_total_point) &&
              !_.isEmpty(response?.data?.match?.home_total_point)
                ? response?.data?.match?.home_total_point
                : 0,
          },
          {
            id: 2,
            title: response?.data?.match?.name_of_away,
            total_points:
              !_.isNull(response?.data?.match?.away_total_point) &&
              !_.isEmpty(response?.data?.match?.away_total_point)
                ? response?.data?.match?.away_total_point
                : 0,
          },
        ];

        setTabList(tabDataList);
        setDefaultTab(tabDataList[0]);

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
          user: "Guest User",
          match_id: response?.data?.match?.match_id,
          team_one_name: response?.data?.match?.name_of_home,
          team_two_name: response?.data?.match?.name_of_away,
        };

        setTimeout(() => {
          setBtnLoader(false);
        }, 2000);
        addAnalyticsEvent("GUEST_USER_CHECK_SHARE_VOTED_CARD_INFO", eventData);
      } else {
        setBtnLoader(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      setBtnLoader(false);
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  function renderMatchDetails() {
    return (
      <div
        style={{
          display: "flex",
          height: "auto",
          flexDirection: "row",
          alignItems: "center",
          backgroundColor: "#ed0f1b",
          justifyContent: "space-between",
        }}
        className="OtherCommonContainer"
      >
        <div className="SVCDiv1">
          <span className="voteCardMatchHeaderText">
            {matchData?.match_date}
          </span>
          <img
            loading="lazy"
            className="SVCTeamLogo"
            src={matchData?.logo_of_home}
            alt={"HomeIcon"}
          />

          <span className="VoteCardTeamName">{matchData?.name_of_home}</span>
        </div>

        <div className="SVCDiv2">
          <span className="voteCardMatchHeaderText">
            {matchData?.league_name}
          </span>
          <span className="VoteCardScoreStyle">
            {matchData?.goal_of_home_team} : {matchData?.goal_of_away_team}
          </span>
          <span
            style={{
              textAlign: "center",
            }}
            className="voteCardMatchHeaderText"
          >
            {matchData?.match_ground}
          </span>
        </div>

        <div className="SVCDiv1">
          <span className="voteCardMatchHeaderText">
            {matchData?.match_time}
          </span>
          <img
            className="SVCTeamLogo"
            loading="lazy"
            src={matchData?.logo_of_away}
            alt={"AnotherTeamIcon"}
          />
          <span className="VoteCardTeamName">{matchData?.name_of_away}</span>
        </div>
      </div>
    );
  }

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

            <div className="SVCDiv3">
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
                    padding: window.innerWidth < 370 ? "0px 4px" : "unset",
                  }}
                >
                  <span className="playerNameRating">{getWords("VOTE")}</span>
                </div>

                <div
                  className="TDDiv2"
                  style={{
                    padding: window.innerWidth < 370 ? "0px 4px" : "unset",
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
                    padding: window.innerWidth < 370 ? "0px 4px" : "unset",
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
                    className="playerImg"
                    src={item?.photo}
                    alt={"PlayerData"}
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
                    <div className="TDDiv4">
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
                        {item?.vote === 0 ? 6 : item?.vote}
                      </span>
                    </div>

                    <div
                      className="TDDiv2"
                      style={{
                        padding: window.innerWidth < 370 ? "0px 8px" : "unset",
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

                    <div
                      className="TDDiv3"
                      style={{
                        padding: window.innerWidth < 370 ? "0px 8px" : "unset",
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
                  </div>
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
    if (_.isArray(tabList) && !_.isEmpty(tabList)) {
      return (
        <div>
          <div className="tdTabDiv1">
            {tabList.map((obj, index) => {
              const isSelected = _.isEqual(obj, defaultTab);
              return (
                <div
                  key={index}
                  className="tdTabDiv2"
                  style={{
                    borderBottom: `2px solid ${
                      isSelected ? "#ED0F1B" : "hsl(0deg 0% 96%)"
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
                      fontSize: "20px",
                    }}
                  >
                    {obj.title}
                  </span>
                </div>
              );
            })}
          </div>
        </div>
      );
    }
  }

  if (btnLoader) {
    return (
      <div style={{ height: "100%", position: "relative" }}>
        <Headers isSubScreen={true} removeBackArrow={true} />
        <CRequestLoader
          openModal={btnLoader}
          handleClose={() => {
            setBtnLoader(false);
          }}
        />
      </div>
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
          {renderPlayerInfo("Players")}
          {renderPlayerInfo("Substitite")}
          {renderPlayerInfo("Coach")}
        </div>
      );
    }
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

  return (
    <div className="MainContainer">
      <Headers isSubScreen={true} removeBackArrow={true} />
      <div className="SVCDiv">
        <div>{renderMatchDetails()}</div>
        <div>{renderTabs()}</div>
        <div>{renderPlayersList()}</div>
      </div>
      {renderAlert()}
    </div>
  );
};

export default ShareableVoteCard;
