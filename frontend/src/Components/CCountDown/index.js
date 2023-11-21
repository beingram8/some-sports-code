import React from "react";
import PropsTypes from "prop-types";
import "./styles.scss";

const CCountDown = (props) => {
  const { remainingSeconds } = props;

  return <span className="countDownTextSty">{remainingSeconds}</span>;
};

CCountDown.propTypes = {
  remainingSeconds: PropsTypes.string,
};

CCountDown.defaultProps = {
  remainingSeconds: "",
};

export default CCountDown;
