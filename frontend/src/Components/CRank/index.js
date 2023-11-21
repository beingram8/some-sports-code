import React from "react";
import _ from "lodash";
import PropTypes from "prop-types";
import CoinW from "../../Assets/Images/coinW.png";
import CoinD from "../../Assets/Images/coinDark.png";
import "./styles.scss";

const CRank = (props) => {
  const { rank, borderColor, data } = props;
  return (
    <div>
      <div className="cRankTitleSty">
        <span className="rankingNameStyle">{data?.name?.slice(0, 12)}</span>
      </div>
      <div className="rankingDiv1">
        <div className="rankingDiv2">
          <div className="rankingDiv3">
            <div
              className="topthreePosition"
              style={{
                left: data?.rank === 1 ? -120 : -115,
                backgroundColor: `${borderColor}`,
                border: `1px solid ${borderColor}`,
              }}
            >
              <span
                style={{
                  color: data?.rank === 1 ? "#484848" : "#FFFFFF",
                  fontFamily: "segoeui",
                }}
              >
                {data?.rank}
              </span>
            </div>
            <img
              loading="lazy"
              className="rankingImageStyle"
              style={{
                border: `1px solid ${borderColor}`,
                backgroundColor: data?.user_photo?.includes(".svg")
                  ? "#FFF"
                  : `${borderColor}`,
              }}
              src={data?.user_photo}
              alt={"RankingData"}
            />

            <div className="rankingDiv11">
              <div className="rankingdiv12">
                <img
                  loading="lazy"
                  className="rankingIconStyle"
                  src={data?.rank === 1 ? CoinD : CoinW}
                  alt={"RankData"}
                />
                <span
                  className="rankingTopthreePoints"
                  style={{
                    color: data?.rank === 1 ? "#484848" : "#fff",
                  }}
                >
                  {data?.points}
                </span>
              </div>

              <div className="rankingTeamDiv">
                {data?.team_photo === "" ? null : (
                  <img
                    loading="lazy"
                    className="rankingFlagStyle"
                    src={
                      _.has(data, "team")
                        ? data.team.team_logo
                        : data.team_photo
                    }
                    alt={"rankingData"}
                  />
                )}
                <span
                  className="rankingTeamName"
                  style={{
                    color: data?.rank === 1 ? "#484848" : "#fff",
                  }}
                >
                  {_.has(data, "team")
                    ? data?.team?.team_name
                    : data.user_team_name}
                </span>
              </div>
            </div>
          </div>
          <img
            loading="lazy"
            className="rankingImage123"
            src={rank}
            alt={"RankingData"}
          />
        </div>
      </div>
    </div>
  );
};

CRank.propTypes = {
  positionBool: PropTypes.bool,
  data: PropTypes.func,
};

CRank.defaultProps = {
  positionBool: false,
  data: () => {},
};

export default CRank;
