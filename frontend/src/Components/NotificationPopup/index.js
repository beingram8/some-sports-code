import React from "react";
import { useSelector } from "react-redux";
import { useHistory } from "react-router-dom";
import "./styles.scss";
import "../../Styles/common.scss";
import AppIcon from "../../Assets/Images/fr_pwa_appLogo.png";

const NotificationPopup = () => {
  const history = useHistory();
  const { isNotifiy, notiData } = useSelector((state) => state.auth);
  const title = notiData?.title !== "" ? notiData?.title : "-";
  const description = notiData?.msg !== "" ? notiData?.msg : "-";

  return isNotifiy ? (
    <div
      onClick={() => {
        history.push("/notifications");
      }}
      className="CommonContainer"
      style={{
        cursor: "pointer",
      }}
    >
      <div className={`notification-container top-right`}>
        <div className="sub-div-for-notification">
          <div className="sub-flex-con">
            <div className="notification-image">
              <img
                loading="lazy"
                src={AppIcon}
                alt={"AppIcon"}
                className="noti-app-logo"
              />
              <span className="noti-app-title-text">FAN RATING</span>
            </div>
            <span className="notification-title">{title}</span>
            <span className="notification-message">{description}</span>
          </div>
        </div>
      </div>
    </div>
  ) : null;
};

export default NotificationPopup;
