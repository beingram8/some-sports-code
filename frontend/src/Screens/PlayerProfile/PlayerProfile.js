import React, { useState, useEffect } from "react";
import { useHistory } from "react-router-dom";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import Header from "../../Components/Header";
import Protected from "../../Components/Protected";
import CPlayerProfileLoader from "../../Loaders/CPlayerProfileLoader/index";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";
import { useSelector } from "react-redux";
import { Setting } from "../../Utils/Setting";
import { getApiData } from "../../Utils/APIHelper";
import CAlert from "../../Components/CAlert/index";
import NotificationPopup from "../../Components/NotificationPopup";

function PlayerProfile(props) {
  const history = useHistory();
  const { userdata } = useSelector((state) => state.auth);
  const [pageLoader, setPageLoader] = useState(false);
  const [playerData, setPlayerData] = useState({});
  const data = props?.location?.state?.listData;

  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [callFunc, setCallFunction] = useState(false);

  useEffect(() => {
    getPlayerProfile();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    document.title = Setting.page_name.PLAYER_PROFILE;
  }, []);

  const showAlert = (open, title, message, callFunction) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
    setCallFunction(callFunction);
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
            // redirect to previous screen is player details not found
            history.goBack();
          }
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  // plater details api call
  async function getPlayerProfile(item) {
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: `${userToken}`,
    };
    const playerId = data?.player_id;

    try {
      let endPoint = `${Setting.endpoints.player_details}?player_id=${playerId}`;
      const response = await getApiData(endPoint, "GET", {}, header);
      if (response && response.status && response.status === true) {
        if (response && response.data) {
          setPlayerData(response.data);
          const eventData = {
            user_name: userdata?.username,
            first_name: userdata?.firstname,
            last_name: userdata?.lastname,
            email: userdata?.email,
            user_Pic: userdata?.user_image,
            player_name: response?.data?.player_detail?.name,
          };
          addAnalyticsEvent("User_Check_Player_Info_Event", eventData);
          setPageLoader(false);
        } else {
          setPageLoader(false);
        }
      } else {
        setPageLoader(false);
        showAlert(true, getWords("OOPS"), response?.message, true);
      }
    } catch (err) {
      console.log("Catch Part", err);
      setPageLoader(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  // new dynamic data
  const playerDtl = playerData?.player_detail;
  const playerMatchList = playerData?.match_list;

  // new design for player profile
  const renderPlayerProfile = () => {
    return (
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
    );
  };

  // displaying player match list
  const renderPlayerMatchDetails = () => {
    return (
      <div
        style={{
          margin: "20px 0px",
        }}
      >
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
        <div
          className="dividerpp"
          style={{
            marginTop: 20,
          }}
        />
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
                        {item.position !== "-"
                          ? getWords(`${item.position.toUpperCase()}`)
                          : " - "}
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

  return pageLoader ? (
    <Protected>
      <div className="MainContainer">
        <CPlayerProfileLoader web={(window.innerWidth >= 600).toString()} />
      </div>
    </Protected>
  ) : (
    <Protected>
      <div className="MainContainer">
        <Header isSubScreen={true} />

        {/* new design - 02/09/2021 */}
        {renderPlayerProfile()}
        {renderAlert()}
        <NotificationPopup />
      </div>
    </Protected>
  );
}

export default PlayerProfile;
