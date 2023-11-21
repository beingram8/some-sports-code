import { useDispatch, useSelector } from "react-redux";
import { useHistory } from "react-router-dom";
import React from "react";
import _ from "lodash";
import "./styles.scss";
import { bottomTabData } from "../../staticData";
import { getWords, refreshUserData } from "../../commonFunctions";
import RateIcon1 from "../../Assets/Images/rateRed.png";
import TifaIcon1 from "../../Assets/Images/tifaRed.png";
import RateIcon from "../../Assets/Images/rateBlack.png";
import TifaIcon from "../../Assets/Images/tifaBlack.png";
import VinciIcon1 from "../../Assets/Images/vinciRed.png";
import VinciIcon from "../../Assets/Images/vinciBlack.png";
import authActions from "../../Redux/reducers/auth/actions";

const { setSelectedTab } = authActions;

function BottomTab() {
  const history = useHistory();
  const dispatch = useDispatch();
  const { tab } = useSelector((state) => state.auth);

  function changeTab(index) {
    if (index !== 1) {
      refreshUserData();
    }
    dispatch(setSelectedTab(index));
    history.push(bottomTabData[index].path);
  }

  function renderBottomTab() {
    return (
      <div className="mainCon">
        {_.isArray(bottomTabData) && !_.isEmpty(bottomTabData)
          ? bottomTabData?.map((obj, index) => {
            const isSelected = bottomTabData[tab] === obj;
            const isBar = bottomTabData[tab]?.icon === "Bar";
            const isHome = bottomTabData[tab]?.icon === "Home";
            const isStar = bottomTabData[tab]?.icon === "Star";
            return (
              <div
                key={index}
                className="iconContainer"
                onClick={() => {
                  changeTab(index);
                }}
              >
                {isSelected ? <div className={"SelectedTabTopLine"} /> : null}
                <div className="BottomTabSubCon">
                  {obj.icon === "Bar" ? (
                    <img
                      loading="lazy"
                      className="BottomTabIconStyle"
                      src={isBar ? TifaIcon1 : TifaIcon}
                      alt={"vinci"}
                    />
                  ) : null}
                  {obj.icon === "Home" ? (
                    <img
                      loading="lazy"
                      className="BottomTabIconStyle"
                      src={isHome ? RateIcon1 : RateIcon}
                      alt={"vinci"}
                    />
                  ) : null}
                  {obj.icon === "Star" ? (
                    <img
                      loading="lazy"
                      className="BottomTabIconStyle"
                      src={isStar ? VinciIcon1 : VinciIcon}
                      alt={"vinci"}
                    />
                  ) : null}
                  <p
                    style={{
                      color: isSelected ? "#ED0F1B" : "#888888",
                      fontSize: window.innerWidth >= 640 ? "12px" : "11px",
                    }}
                    className="BottomTabTitleSty"
                  >
                    {getWords(obj.title)}
                  </p>
                </div>
              </div>
            );
          })
          : null}
      </div>
    );
  }

  return <div className="BottomTabContainer">{renderBottomTab()}</div>;
}

export default BottomTab;
