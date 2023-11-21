import React from "react";
import PropTypes from "prop-types";
import CoinW from "../../Assets/Images/coinW.png";
import CoinD from "../../Assets/Images/coinDark.png";
import "./styles.scss";
import _ from "lodash";

const CRankMobile = (props) => {
  const { rank, borderColor, data } = props;
  return (
    <div className="mainConForMobile">
      <div className="userDEtailsRM">
        <div className="nameStyleDivRm">
          <span className="nameStyleRM">{data?.name?.slice(0, 12)}</span>
        </div>

        <div
          className="rankBadgeDiv"
          style={{
            backgroundColor: `${borderColor}`,
            border: `1px solid ${borderColor}`,
          }}
        >
          <span
            style={{
              color: data.rank === 1 ? "#484848" : "#FFFFFF",
              fontFamily: "segoeui",
            }}
          >
            {data.rank}
          </span>
        </div>

        <div
          style={{
            zIndex: 10,
          }}
          className="userPhotoDiv"
        >
          <img
            loading="lazy"
            className="uesrPhotoStyle"
            style={{
              border: `1px solid ${borderColor}`,
              backgroundColor: data?.user_photo?.includes(".svg")
                ? "#FFF"
                : `${borderColor}`,
            }}
            src={data.user_photo}
            alt={"userData"}
          />
        </div>
        <div className="div1Style">
          <div className="div123Style">
            <div className="pointsDiv">
              <img
                loading="lazy"
                className="coinIconStyle"
                src={data.rank === 1 ? CoinD : CoinW}
                alt={"CoinIcon"}
              />
              <span
                className="pointsStyleRM"
                style={{
                  color: data.rank === 1 ? "#484848" : "#fff",
                }}
              >
                {data.points}
              </span>
            </div>
            <div className="teamDetailsDiv">
              <img
                loading="lazy"
                className="coinIconStyle"
                src={
                  _.has(data, "team") ? data?.team?.team_logo : data.team_photo
                }
                alt={"coinIcon"}
              />
              <span
                className="teamNameStyle"
                style={{
                  color: data.rank === 1 ? "#484848" : "#fff",
                }}
              >
                {_.has(data, "team")
                  ? data?.team?.team_name
                  : data.user_team_name}
              </span>
            </div>
          </div>
          <img
            loading="lazy"
            className="rankStripeStyle"
            src={rank}
            alt={"CoinIcon"}
          />
        </div>
      </div>
    </div>
  );
};

CRankMobile.propTypes = {
  positionBool: PropTypes.bool,
  data: PropTypes.object,
};

CRankMobile.defaultProps = {
  positionBool: false,
  data: {},
};

export default CRankMobile;
