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

const CButton = (props) => {
  const {
    socialFBLogin,
    socialGoogleLogin,
    buttonText,
    handleBtnClick,
    handleSocialBtnClick,
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
  } = props;

  return socialFBLogin ? (
    <div
      className="socialFBButtonStyle"
      onClick={() => {
        if (handleSocialBtnClick) {
          handleSocialBtnClick();
        }
      }}
    >
      <div className="socialIconDivStyle">
        <IconContext.Provider
          value={{
            color: "#FFFFFF",
          }}
        >
          <FaFacebookF />
        </IconContext.Provider>
      </div>
      <span className="fbButtonStyle">{buttonText}</span>
    </div>
  ) : socialGoogleLogin ? (
    <div
      className="socialButtonStyle"
      onClick={() => {
        if (handleSocialBtnClick) {
          handleSocialBtnClick();
        }
      }}
    >
      <div className="socialIconDivStyle">
        <img
          loading="lazy"
          className="googleButtonIcon"
          src={googleIcon}
          alt={"socialIcon"}
        />
      </div>

      <span className="googleButtonStyle">{buttonText}</span>
    </div>
  ) : (
    <div
      className={outlined ? "cButtonStyleOutlined" : "cButtonStyle"}
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
            color: outlined ? "#ed0f1b" : "#FFFFFF",
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

CButton.propTypes = {
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
};

CButton.defaultProps = {
  outlined: false,
  bungeeText: false,
  socialFBLogin: false,
  socialGoogleLogin: false,
  buttonText: "",
  handleBtnClick: () => {},
  handleSocialBtnClick: () => {},
  btnLoader: false,
  buttonStyle: {},
  boldText: false,
  shareIcon: false,
  addIcon: false,
  color: "#fff",
  selectedteamicon: "",
};

export default CButton;
