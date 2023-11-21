import React, { useEffect } from "react";
import Lottie from "react-lottie";
import offline from "../../Assets/Lottie/offline.json";
import { getWords } from "../../commonFunctions";
import { Setting } from "../../Utils/Setting";
import "./styles.scss";

function Offline() {
  useEffect(() => {
    document.title = Setting.page_name.OFFLINE;
  }, []);

  return (
    <div className="offlinemain">
      <div className="offlineroot">
        <span className="offlineheader">FAN RATING!</span>
      </div>
      <div className="offlinemaindiv">
        <div className="offlinerootdiv">
          <div
            style={{
              paddingTop: "50px",
            }}
          >
            <Lottie
              options={{
                loop: true,
                autoplay: true,
                animationData: offline,
              }}
              height={window.innerWidth >= 500 ? 300 : 250}
              width={window.innerWidth >= 500 ? 300 : 250}
            />
          </div>

          <div className="offlinetextmain">
            <span
              className="offlinetextroot"
              style={{
                fontSize: window.innerWidth >= 500 ? "25px" : "20px",
              }}
            >
              {getWords("OFFLINE_TITLE")}
            </span>
            <span
              className="offlinebottmetitle"
              style={{
                fontSize: window.innerWidth >= 500 ? "15px" : "14px",
              }}
            >
              {getWords("OFFLINE_SUB_TITLE")}
            </span>
            <span
              className="offlinebottmetitle"
              style={{
                fontSize: window.innerWidth >= 500 ? "15px" : "14px",
              }}
            >
              {getWords("OFFLINE_DESC")}
            </span>
          </div>

          <div style={{ padding: "10px" }}>
            <div className="offlinebtnmain">
              <span className="offlinebtninner">
                {getWords("OFFLINE_REFRESH_BTN")}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Offline;
