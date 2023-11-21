import React, { useState, useEffect } from "react";
import { useHistory } from "react-router-dom";
import "./styles.scss";
import "../../Styles/common.scss";
import Header from "../../Components/Header";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";
import _ from "lodash";
import { getApiData } from "../../Utils/APIHelper";
import { useSelector } from "react-redux";
import { Setting } from "../../Utils/Setting";
import CAlert from "../../Components/CAlert/index";
import CMyTeamLoader from "../../Loaders/CMyTeamLoader/index";
import NotificationPopup from "../../Components/NotificationPopup";
import CNoData from "../../Components/CNoData/index";
import Protected from "../../Components/Protected";
import DisplayAd from "../../Components/Ads/DisplayAd";

function MyTeam() {
  const history = useHistory();
  const [data, setData] = useState({});
  const [loader, setLoader] = useState(true);
  const { userdata } = useSelector((state) => state.auth);
  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  useEffect(() => {
    getMyTeamList();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    document.title = Setting.page_name.MY_TEAM;
  }, []);

  const getMyTeamList = async () => {
    const userToken = `Bearer ${userdata?.access_token}`;
    const header = {
      Authorization: userToken,
    };
    try {
      const endPoint = Setting.endpoints.my_team_list;
      const response = await getApiData(endPoint, "GET", null, header);
      addAnalyticsEvent("User_Check_My_Team_Event", true);
      if (response?.status) {
        setLoader(false);
        setData(response?.data);
      } else {
        setLoader(false);
        if (response?.message === "No data found") {
        } else {
          showAlert(true, getWords("OOPS"), response?.message);
        }
      }
    } catch (err) {
      console.log("Catch Part", err);
      setLoader(false);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

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

  const renderMyTeam = () => {
    return (
      <div className="myteamsubcontainer">
        {_.isArray(data?.team_player) && !_.isEmpty(data?.team_player)
          ? data?.team_player?.map((item, index) => {
              return (
                <div
                  key={index}
                  className="myteamplayerlistcontainer"
                  style={{
                    backgroundColor: index % 2 === 0 ? "#F6F6F6" : "#FFFFFF",
                  }}
                >
                  <div className="myteamplayerlist">
                    <img
                      loading="lazy"
                      src={item?.photo}
                      className="myteamplayerimage"
                      alt={"playerData"}
                      style={{
                        cursor: "pointer",
                      }}
                      onClick={() => {
                        history.push({
                          pathname: "/player-profile",
                          state: {
                            listData: item,
                          },
                        });
                      }}
                    />

                    <div className="jerseyDivContainer">
                      <span className="myteamjerseyno">
                        {item.jersey_no === null ? "-" : item.jersey_no}
                      </span>
                    </div>

                    <div className="myteamnamerole">
                      <span className="myteamplayername">{item.name}</span>
                      <span className="myteamplayerrole">
                        {item?.player_position !== ""
                          ? `(${getWords(
                              `${item?.player_position.toUpperCase()}`
                            )})`
                          : "-"}
                      </span>
                    </div>
                  </div>
                  <div className="myTeamplayerPointsStyle">
                    <span className="myteampoints">{item.average_rate}</span>
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
      <div className="MainContainer">
        <Header isSubScreen={true} />
        {loader ? (
          <CMyTeamLoader web={(window.innerWidth >= 640).toString()} />
        ) : !_.isEmpty(data) && _.isObject(data) ? (
          <div className="myteammaindiv">
            {!_.isEmpty(data?.team) && _.isObject(data?.team) ? (
              <div className="myteamhorizontalitems">
                <span className="myteamtitle">{data?.team?.name}</span>
                <img
                  loading="lazy"
                  className="myteamselectedimage"
                  src={data?.team?.icon}
                  alt={"TeamData"}
                />
              </div>
            ) : null}
            <DisplayAd adUnit={Setting.ads_Units.TEST_BANNER_AD} />
            {renderMyTeam()}
          </div>
        ) : (
          <CNoData message={getWords("SORRY_NO_DATA_FOUND")} hasheader={true} />
        )}
        {renderAlert()}
        <NotificationPopup />
      </div>
    </Protected>
  );
}

export default MyTeam;
