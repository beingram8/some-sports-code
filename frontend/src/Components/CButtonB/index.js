import CircularProgress from "@material-ui/core/CircularProgress";
import { FaFacebookF } from "react-icons/fa";
import { IconContext } from "react-icons";
import PropsTypes from "prop-types";
import React from "react";
import "./styles.scss";
import AddIcon from "../../Assets/Images/add.png";
import AddIconRed from "../../Assets/Images/add_red.png";
import googleIcon from "../../Assets/Images/google.png";
import ShareIcon from "../../Assets/Images/share_white.png";

const CButtonB = (props) => {
  const {
    buttonText,
    handleBtnClick,
    btnLoader,
    buttonStyle,
    bungeeText,
    outlined,
    boldText,
    addIcon,
    shareIcon,
    textcolor,
    selectedteamicon,
    btntextfontSize,
    className,
    loaderColorWhite,
  } = props;

  return (
    <div
      className={outlined ? `cButtonStyleOutlinedB ${className}` : `cButtonStyleB ${className}`}
      style={buttonStyle}
      onClick={() => {
        if (handleBtnClick) {
          handleBtnClick();
        }
      }}
    >
      {addIcon ? (
        <img
          loading="lazy"
          style={{
            width: window.innerWidth >= 370 ? 25 : 20,
            height: window.innerWidth >= 370 ? 25 : 20,
            marginRight: 20,
          }}
          src={outlined ? AddIconRed : AddIcon}
          alt={"AddIcon"}
        />
      ) : shareIcon ? (
        <img
          loading="lazy"
          style={{
            width: window.innerWidth >= 370 ? 25 : 20,
            height: window.innerWidth >= 370 ? 25 : 20,
            marginRight: 20,
          }}
          src={ShareIcon}
          alt={"ShareIcon"}
        />
      ) : selectedteamicon ? (
        <img
          loading="lazy"
          style={{
            width: window.innerWidth >= 370 ? 25 : 20,
            height: window.innerWidth >= 370 ? 25 : 20,
            marginRight: 10,
          }}
          src={selectedteamicon}
          alt={"SelectTeamIcon"}
        />
      ) : null}
      {btnLoader ? (
        <CircularProgress
          style={{
            width: 20,
            height: 20,
            color: outlined ? loaderColorWhite ? "#FFFFFF" : "#ed0f1b" : "#FFFFFF",
          }}
        />
      ) : (
        <span
          style={{
            fontFamily: bungeeText ? "Bungee" : "segoeui",
            fontWeight: boldText ? 600 : "normal",
            color: textcolor,
            fontSize: btntextfontSize,
            textAlign: "center",
          }}
          className={outlined ? "cBtnTextStyleOutlined" : "cBtnTextStyle"}
        >
          {buttonText}
        </span>
      )}
    </div>
  );
};

CButtonB.propTypes = {
  outlined: PropsTypes.bool,
  bungeeText: PropsTypes.bool,
  socialFBLogin: PropsTypes.bool,
  socialGoogleLogin: PropsTypes.bool,
  buttonText: PropsTypes.string,
  handleBtnClick: PropsTypes.func,
  handleSocialBtnClick: PropsTypes.func,
  btnLoader: PropsTypes.bool,
  buttonStyle: PropsTypes.any,
  boldText: PropsTypes.bool,
  shareIcon: PropsTypes.bool,
  addIcon: PropsTypes.bool,
  color: PropsTypes.string,
  selectedteamicon: PropsTypes.string,
  className: PropsTypes.string,
};

CButtonB.defaultProps = {
  outlined: false,
  bungeeText: false,
  buttonText: "",
  handleBtnClick: () => { },
  btnLoader: false,
  buttonStyle: {},
  boldText: false,
  shareIcon: false,
  addIcon: false,
  color: "#fff",
  selectedteamicon: "",
  className: "",
  loaderColorWhite: false
};

export default CButtonB;
