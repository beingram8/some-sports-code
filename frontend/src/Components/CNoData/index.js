import PropTypes from "prop-types";
import React from "react";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import no_result_image from "../../Assets/Images/No Results.png";

const CNoData = (props) => {
  const { message, hasheader, hasfooter, height, otherStyle } = props;

  if (_.isObject(otherStyle) && !_.isEmpty(otherStyle)) {
    return (
      <div style={otherStyle}>
        <div className="teamlistnodatafoundcontainer">
          <img
            loading="lazy"
            src={no_result_image}
            className="teamlistnodatafoundimage"
            alt={"resultData"}
          />
          <span className="teamlistnodatafoundtext">{message}</span>
        </div>
      </div>
    );
  }

  return (
    <div
      style={{
        height:
          hasheader === true && hasfooter === true
            ? height
            : hasheader === true || hasfooter === true
            ? "calc(100% - 65px)"
            : "100%",
        width: "100%",
      }}
    >
      <div className="teamlistnodatafoundcontainer">
        <img
          loading="lazy"
          src={no_result_image}
          className="teamlistnodatafoundimage"
          alt={"NodataFound"}
        />
        <span className="teamlistnodatafoundtext">{message}</span>
      </div>
    </div>
  );
};

CNoData.propTypes = {
  message: PropTypes.string,
  hasheader: PropTypes.bool,
  hasfooter: PropTypes.bool,
  height: PropTypes.any,
};

CNoData.defaultProps = {
  message: "No data found!",
  hasfooter: false,
  hasheader: false,
  height: "calc(100% - 130px)",
};

export default CNoData;
