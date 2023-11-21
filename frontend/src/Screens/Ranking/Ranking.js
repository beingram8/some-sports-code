import React, { useState, useEffect } from "react";
import "./styles.scss";
import "../../Styles/common.scss";
import Header from "../../Components/Header";
import { useSelector } from "react-redux";
import Rank1 from "../../Assets/Images/golden_web.png";
import Rank2 from "../../Assets/Images/silver_web.png";
import Rank3 from "../../Assets/Images/bronze_web.png";
import Rank11 from "../../Assets/Images/golden_mob.png";
import Rank12 from "../../Assets/Images/silver_mob.png";
import Rank13 from "../../Assets/Images/bronze_mob.png";
import _ from "lodash";
import CRank from "../../Components/CRank/index";
import CRankMobile from "../../Components/CRankMobile/index";
import RankingWebBG from "../../Assets/Images/Teambg_web.png";
import RankingMobBG from "../../Assets/Images/Team_Screen_2.png";
import RankingMobBG1 from "../../Assets/Images/Team_Screen_26.png";
import { Setting } from "../../Utils/Setting";
import { getApiData } from "../../Utils/APIHelper";
import CNoData from "../../Components/CNoData/index";
import CAlert from "../../Components/CAlert/index";
import {
  getWords,
  addAnalyticsEvent,
  isUserLogin,
} from "../../commonFunctions";
import CRankingLoader from "../../Loaders/CRankingLoader/index";
import NotificationPopup from "../../Components/NotificationPopup";

function Ranking(props) {
  const data1 = props?.location?.state;

  const [rankingListData, setRankListData] = useState({});
  const { userdata } = useSelector((state) => state.auth);
  const [firstPosData, setFirstPosData] = useState({});
  const [secondPosData, setSecondPosData] = useState({});
  const [thirdPosData, setThirdPosData] = useState({});

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [loader, setLoader] = useState(true);
  const [userRank, setUserRank] = useState({});
  const [isRankInTop20, setIsRankInTop20] = useState(null);

  const fromTeamDetails = data1?.fromTeamDetails;
  const matchData = data1?.data;
  const from = data1?.from;

  const guestUser = {
    user_name: "Guest User",
  };

  const checkUserLogin = isUserLogin();
  const eventData = checkUserLogin ? true : guestUser;

  useEffect(() => {
    if (fromTeamDetails) {
      displayTeamWinner();
    } else {
      getListData();
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    document.title = fromTeamDetails
      ? Setting.page_name.RANKING_FOR_WINNER
      : Setting.page_name.RANKING;
    // eslint-disable-next-line react-hooks/exhaustive-deps
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

  const displayTeamWinner = async () => {
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: userToken,
    };
    try {
      let endPoint = `${Setting.endpoints.team_winner}?match_id=${
        matchData?.match_id
      }&team_id=${
        from === "Home" ? matchData?.home_team_id : matchData?.home_away_id
      }`;
      const response = await getApiData(endPoint, "GET", null, header);
      if (response?.status) {
        setLoader(false);
        setRankListData(response?.data);
        setFirstPosData(response?.data[0]);
        setSecondPosData(response?.data[1]);
        setThirdPosData(response?.data[2]);
      }
    } catch (err) {
      setLoader(false);
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  async function getListData() {
    let endPoint = "";
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: userToken,
    };

    if (checkUserLogin) {
      endPoint = Setting.endpoints.user_ranking;
    } else {
      endPoint = `${Setting.endpoints.ranking}?lastrank=${0}`;
    }

    try {
      const response = await getApiData(endPoint, "GET", null, header);
      addAnalyticsEvent("Ranking_Event", eventData);
      if (response?.status) {
        const top20 = response?.data?.rows;
        const userRank = response?.data?.user_position;

        if (
          _.isArray(top20) &&
          !_.isEmpty(top20) &&
          _.isObject(userRank) &&
          !_.isEmpty(userRank)
        ) {
          const isInTop20 = top20.findIndex((v) => _.isEqual(v, userRank));
          setIsRankInTop20(isInTop20);
        }
        setRankListData(top20);
        setUserRank(userRank);
        setFirstPosData(response?.data?.rows[0]);
        setSecondPosData(response?.data?.rows[1]);
        setThirdPosData(response?.data?.rows[2]);
        setLoader(false);
      } else {
        setLoader(false);
      }
    } catch (err) {
      setLoader(false);
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  const renderOtherPlayerList = () => {
    return (
      <div>
        {_.isArray(rankingListData) && !_.isEmpty(rankingListData)
          ? rankingListData?.map((item, index) => {
              const isMatchUserInTop20 = isRankInTop20 === index;
              return item.rank === 1 ||
                item.rank === 2 ||
                item.rank === 3 ? null : (
                <div key={index}>
                  <div
                    className="CommonContainer"
                    style={{
                      backgroundColor: isMatchUserInTop20
                        ? "rgba(237, 15, 24, 0.5)"
                        : item.rank % 2 === 0
                        ? "#F6F6F6"
                        : "#FFFFFF",
                      position: "unset",
                    }}
                  >
                    <div className="dataItem">
                      <div className="centerAlign2">
                        <span
                          className="whiteSegoe"
                          style={{
                            fontSize: 18,
                            alignItems: "center",
                            display: "flex",
                            width: 30,
                            justifyContent: "center",
                            color: isMatchUserInTop20 ? "#FFFFFF" : "#484848",
                          }}
                        >
                          {item.rank}
                        </span>
                        <div>
                          <img
                            loading="lazy"
                            className="playerImage"
                            src={item.user_photo}
                            alt={"playerData"}
                          />
                        </div>
                        <div
                          style={{
                            display: "flex",
                            flexDirection: "column",
                            width: window.innerWidth < 500 ? 140 : "none",
                          }}
                        >
                          <span
                            className="whiteSegoe"
                            style={{
                              fontSize: 16,
                              color: isMatchUserInTop20 ? "#FFFFFF" : "#484848",
                            }}
                          >
                            {item.name}
                          </span>
                          <div
                            className="centerAlign2"
                            style={{
                              marginTop: 10,
                            }}
                          >
                            <img
                              loading="lazy"
                              className="flagImage"
                              src={item.team_photo}
                              alt={"flagImage"}
                            />
                            <span
                              className="whiteSegoe"
                              style={{
                                color: isMatchUserInTop20
                                  ? "#FFFFFF"
                                  : "#656565",
                                fontSize: 13,
                                marginLeft: 5,
                              }}
                            >
                              {item.user_team_name}
                            </span>
                          </div>
                        </div>
                      </div>
                      <div
                        className="centerAlign2"
                        style={{ width: 30, justifyContent: "center" }}
                      >
                        <span
                          className="whiteSegoe"
                          style={{
                            fontSize: window.innerWidth >= 640 ? 20 : 15,
                            color: isMatchUserInTop20 ? "#FFFFFF" : "#484848",
                          }}
                        >
                          {item.points}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              );
            })
          : null}
      </div>
    );
  };

  const renderTop3Player = () => {
    return (
      <div
        style={{
          height: window.innerWidth >= 600 ? "505px" : "none",
          backgroundImage: `url(${
            window.innerWidth >= 730
              ? RankingWebBG
              : window.innerWidth <= 450
              ? RankingMobBG
              : RankingMobBG1
          }`,
          position: "unset",
        }}
        className="CommonContainer"
      >
        <div
          className="spacebetweenStyle"
          style={{
            marginBottom: 10,
            marginLeft: window.innerWidth >= 640 ? 0 : 20,
          }}
        >
          <span className="rankingTitle">{getWords("RANKING")}</span>
        </div>
        {window.innerWidth >= 730 ? (
          <div className="RankingDiv3">
            <div className="rankingMT150">
              <CRank
                rank={Rank2}
                borderColor={"#C0C0C0"}
                positionBool={false}
                data={secondPosData}
                web={(window.innerWidth >= 750).toString()}
              />
            </div>
            <div className="rankingMH80">
              <CRank
                rank={Rank1}
                borderColor={"#FFD700"}
                positionBool={true}
                data={firstPosData}
                web={(window.innerWidth >= 750).toString()}
              />
            </div>
            <div className="rankingMT150">
              <CRank
                rank={Rank3}
                borderColor={"#CD7F32"}
                positionBool={false}
                data={thirdPosData}
                web={(window.innerWidth >= 750).toString()}
              />
            </div>
          </div>
        ) : (
          <div className="rankingCenter">
            <div className="centerAlign1">
              <div className="rankingMT50">
                <CRankMobile
                  rank={Rank12}
                  borderColor={"#C0C0C0"}
                  positionBool={false}
                  data={secondPosData}
                />
              </div>
              <div className="RankingMarginH">
                <CRankMobile
                  rank={Rank11}
                  borderColor={"#FFD700"}
                  positionBool={true}
                  data={firstPosData}
                />
              </div>
              <div className="rankingMT50">
                <CRankMobile
                  rank={Rank13}
                  borderColor={"#CD7F32"}
                  positionBool={false}
                  data={thirdPosData}
                />
              </div>
            </div>
          </div>
        )}
      </div>
    );
  };

  const renderTop3Winner = () => {
    return (
      <div
        style={{
          height: window.innerWidth >= 600 ? "505px" : "none",
          backgroundImage: `url(${
            window.innerWidth >= 730
              ? RankingWebBG
              : window.innerWidth <= 450
              ? RankingMobBG
              : RankingMobBG1
          }`,
          position: "unset",
        }}
        className="CommonContainer"
      >
        <div
          className="spacebetweenStyle"
          style={{
            marginBottom: 10,
            marginLeft: window.innerWidth >= 600 ? 0 : 10,
          }}
        >
          <span className="rankingTitle">{getWords("FAN_WINNERS")}</span>
        </div>
        {window.innerWidth >= 730 ? (
          <div className="RankingDiv3">
            <div className="rankingMT150">
              <CRank
                rank={Rank2}
                borderColor={"#C0C0C0"}
                positionBool={false}
                data={secondPosData}
                web={(window.innerWidth >= 750).toString()}
              />
            </div>
            <div className="rankingMH80">
              <CRank
                rank={Rank1}
                borderColor={"#FFD700"}
                positionBool={true}
                data={firstPosData}
                web={(window.innerWidth >= 750).toString()}
              />
            </div>
            <div className="rankingMT150">
              <CRank
                rank={Rank3}
                borderColor={"#CD7F32"}
                positionBool={false}
                data={thirdPosData}
                web={(window.innerWidth >= 750).toString()}
              />
            </div>
          </div>
        ) : (
          <div className="rankingCenter">
            <div className="centerAlign1">
              <div className="rankingMT50">
                <CRankMobile
                  rank={Rank12}
                  borderColor={"#C0C0C0"}
                  positionBool={false}
                  data={secondPosData}
                />
              </div>
              <div className="RankingMarginH">
                <CRankMobile
                  rank={Rank11}
                  borderColor={"#FFD700"}
                  positionBool={true}
                  data={firstPosData}
                />
              </div>
              <div className="rankingMT50">
                <CRankMobile
                  rank={Rank13}
                  borderColor={"#CD7F32"}
                  positionBool={false}
                  data={thirdPosData}
                />
              </div>
            </div>
          </div>
        )}
      </div>
    );
  };

  const renderTeamWinnerDetails = () => {
    return (
      <div>
        {_.isArray(rankingListData) && !_.isEmpty(rankingListData) ? (
          rankingListData?.map((item, index) => {
            return item.rank === 1 ||
              item.rank === 2 ||
              item.rank === 3 ? null : (
              <div key={index}>
                <div
                  className="CommonContainer"
                  style={{
                    backgroundColor:
                      item.rank % 2 === 0 ? "#F6F6F6" : "#FFFFFF",
                    position: "unset",
                  }}
                >
                  <div className="dataItem">
                    <div className="centerAlign2">
                      <span
                        className="whiteSegoe"
                        style={{
                          fontSize: 18,
                          alignItems: "center",
                          display: "flex",
                        }}
                      >
                        {item.rank}
                      </span>
                      <div>
                        <img
                          loading="lazy"
                          className="playerImage"
                          src={item.user_photo}
                          alt={"playerData"}
                        />
                      </div>
                      <div className="rankingColumn">
                        <span
                          className="whiteSegoe"
                          style={{
                            fontSize: 16,
                          }}
                        >
                          {item.name}
                        </span>
                        <div
                          className="centerAlign2"
                          style={{
                            marginTop: 10,
                          }}
                        >
                          <img
                            loading="lazy"
                            className="flagImage"
                            src={item?.team?.team_logo}
                            alt={"flagImage"}
                          />
                          <span
                            className="whiteSegoe"
                            style={{
                              color: "#656565",
                              fontSize: 13,
                              marginLeft: 5,
                            }}
                          >
                            {item?.team?.team_name}
                          </span>
                        </div>
                      </div>
                    </div>
                    <div className="centerAlign2">
                      <span
                        className="whiteSegoe"
                        style={{
                          fontSize: 20,
                        }}
                      >
                        {item.points}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            );
          })
        ) : (
          <CNoData message={getWords("SORRY_NO_DATA_FOUND")} hasheader={true} />
        )}
      </div>
    );
  };

  return loader ? (
    <CRankingLoader web={(window.innerWidth >= 750).toString()} />
  ) : (
    <div className="MainContainer">
      <Header isSubScreen={true} />

      {fromTeamDetails ? (
        <div
          className="containerRankings"
          style={{
            height: "calc(100% - 65px)",
          }}
        >
          {renderTop3Winner()}
          {renderTeamWinnerDetails()}
        </div>
      ) : _.isArray(rankingListData) && !_.isEmpty(rankingListData) ? (
        <div
          className="containerRankings"
          style={{
            height:
              checkUserLogin &&
              isRankInTop20 < 0 &&
              _.isObject(userRank) &&
              !_.isEmpty(userRank)
                ? "calc(100% - 145px)"
                : "calc(100% - 65px)",
          }}
        >
          {renderTop3Player()}
          {renderOtherPlayerList()}
        </div>
      ) : (
        <CNoData message={getWords("SORRY_NO_DATA_FOUND")} hasheader={true} />
      )}
      {renderAlert()}
      <NotificationPopup />
    </div>
  );
}

export default Ranking;
