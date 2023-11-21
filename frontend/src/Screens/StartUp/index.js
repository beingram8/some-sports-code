/* eslint-disable react-hooks/exhaustive-deps */
/* eslint-disable jsx-a11y/iframe-has-title */
import { useLocation, useHistory } from "react-router-dom";
import React, { useState, useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
// import Phone from "../../Assets/Images/phone.png";
import APPICON from "../../Assets/Images/IMG_1136.webp";
import authActions from "../../Redux/reducers/auth/actions";
import { getRemainingDaysAndTime } from "../../commonFunctions";
import UncleAunty from "../../Assets/Images/uncle_aunty-PNG.webp";
import { askForPermissionToReceiveNotifications } from "../../push-notification";
import { isMacOs, isSafari } from "react-device-detect";
import InstallAppTutorial from "../../Modals/InstallAppTutorial";
import { Setting } from "../../Utils/Setting";

const {
  setUserReferenceCode,
  setIsDisplayInstallPWAPopup,
  setFirstBoolValueForPopUp,
} = authActions;

const dataArray = [
  {
    id: "1.",
    desc: "Seleziona la tua squadra del cuore",
  },
  {
    id: "2.",
    desc: "Vota le partite di Serie A: ricorda che hai 24h di tempo dal triplice fischio per fare la tua pagella",
  },
  {
    id: "3.",
    desc: "Torna dopo 24h e scopri il tuo punteggio: ogni punto equivale a 1 Fan Coin e se hai vinto, ne guadagni ancora di piu!",
  },
  {
    id: "4.",
    desc: "Spendi i Fan Coin che ti sei meritato nella sezione VINCI riscattando fantastici premi: buoni Amazon merchandising, buoni sconto e gift card!",
  },
  {
    id: "5.",
    desc: "Accumula punti, scala la classifica e Sali di livello per votare sempre piu partite!",
  },
  {
    id: "6.",
    desc: "Non ti basta? Ricorda che puoi guadagnare Fan Coins giocando ed interagendo con altri tifosi nella sezione TIFA!",
  },
];

const StartUp = () => {
  const history = useHistory();
  const location = useLocation();
  const dispatch = useDispatch();
  const { firstBoolValue } = useSelector((state) => state.auth);
  const [remainingSeconds, setRemainingSec] = useState("");

  useEffect(() => {
    document.title = Setting.page_name.APP_NAME;
  }, []);

  useEffect(() => {
    if (firstBoolValue === 0) {
      setTimeout(() => {
        dispatch(setIsDisplayInstallPWAPopup(true));
        dispatch(setFirstBoolValueForPopUp(1));
      }, 30000);
    }
  }, []);

  useEffect(() => {
    isGetReferEarnToken();
    askForPermissionToReceiveNotifications();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    if (remainingSeconds !== "00:00:00:00") {
      setTimeout(() => {
        const sec = getRemainingDaysAndTime();
        setRemainingSec(sec);
      }, 1000);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [remainingSeconds]);

  function isGetReferEarnToken() {
    const referenceCode =
      location && location.search && !_.isEmpty(location.search)
        ? _.toString(location.search).substring(1)
        : "";

    if (_.isString(referenceCode) && !_.isEmpty(referenceCode)) {
      dispatch(setUserReferenceCode(referenceCode));
    }
  }

  const renderStartNowBtn = (pos) => {
    return (
      <div
        className="startButtnMain"
        onClick={() => {
          history.push("/rate");
        }}
        style={{
          marginBottom: pos === "bottom" ? "20px" : "none",
        }}
      >
        <div className="startUpNowBtn">
          <span className="startBtnTextWEb">Inizia subito!</span>
        </div>
      </div>
    );
  };

  const renderImages = () => {
    return (
      <div className="imagesDiv1">
        <div className="imagesDiv2">
          <img
            src={APPICON}
            className="appimageStyleWEb"
            style={{
              height: isMacOs && isSafari ? "30%" : "10%",
            }}
            alt={"appicon"}
            loading="lazy"
          />
        </div>

        <div className={isMacOs && isSafari ? "topconntent1" : "topconntent"}>
          <img
            src={UncleAunty}
            className="uncleAuntyimageWeb"
            style={{
              width: isMacOs && isSafari ? "none" : "100%",
              marginTop: isMacOs && isSafari ? "280px" : "none",
              position: isMacOs && isSafari ? "absolute" : "none",
              right: isMacOs && isSafari ? "-60px" : "none",
            }}
            alt={"fanrating"}
            loading="lazy"
          />
        </div>

        <div className="phoneImageDiv videoContentDiv">
          <iframe
            width="100%"
            height="300px"
            // src="https://www.youtube.com/embed/HxhxGZMWM_A?" // /autoplay=1&mute=1
            srcdoc="<style>*
            {padding:0;margin:0;overflow:hidden}html,body{height:100%}
            img,span{position:absolute;width:100%;top:0;bottom:0;margin:auto}
            span{height:1.5em;text-align:center;font:48px/1.5 sans-serif;color:white;
              text-shadow:0 0 0.5em black}</style>
              <a href=https://www.youtube.com/embed/HxhxGZMWM_A?>
              <img src=https://img.youtube.com/vi/HxhxGZMWM_A/maxresdefault.jpg alt='AltTagContent'><span>▶</span></a>"
            frameborder="0"
            allow="autoplay; encrypted-media"
            allowfullscreen
          />
        </div>
      </div>
    );
  };

  const renderTOPContent = () => {
    return (
      <div className="topContentMainDiv">
        {renderImages()}
        <div className="topconntent2">
          <div className="topconntent3">
            <span className="topcontentText1">
              {`Ascolta Arrigo Sacchi, gioca anche tu con Fan Rating e vinci fantastici premi!`}
            </span>
          </div>

          <div className="topContentDiv1">
            <div className="topContentDiv2">
              <span className="topconntent4">TIFA.</span>
              <span className="topconntent4">VOTA.</span>
              <span className="topconntent4">VINCI!</span>
            </div>
          </div>
          <div className="divStartbtn">{renderStartNowBtn()}</div>
        </div>
      </div>
    );
  };

  const renderArray = () => {
    return (
      <div className="textArrayDiv">
        <div className="textArrayDiv1">
          <div className="textArrayDiv2">
            <span className="textArrayId">{dataArray[0].id}</span>
            <span className="textArraydesc">{dataArray[0].desc}</span>
          </div>
          <div className="textArrayDiv2">
            <span className="textArrayId">{dataArray[1].id}</span>
            <span className="textArraydesc">{dataArray[1].desc}</span>
          </div>
        </div>
        <div className="textArrayDiv1">
          <div className="textArrayDiv2">
            <span className="textArrayId">{dataArray[2].id}</span>
            <span className="textArraydesc">{dataArray[2].desc}</span>
          </div>
          <div className="textArrayDiv2">
            <span className="textArrayId">{dataArray[3].id}</span>
            <span className="textArraydesc">{dataArray[3].desc}</span>
          </div>
        </div>
        <div className="textArrayDiv1">
          <div className="textArrayDiv2">
            <span className="textArrayId">{dataArray[4].id}</span>
            <span className="textArraydesc">{dataArray[4].desc}</span>
          </div>
          <div className="textArrayDiv2">
            <span className="textArrayId">{dataArray[5].id}</span>
            <span className="textArraydesc">{dataArray[5].desc}</span>
          </div>
        </div>
      </div>
    );
  };

  const renderContentBelowTopContent = () => {
    return (
      <div className="topContentDiv2">
        <div className="centerDivWeb">
          <span className="centerDivWebText1">Come Funziona?</span>
        </div>

        {renderArray()}

        <div className="topcontentText2">
          <span className="descriptionSUStyle">
            Participa alle dirette, guarda i video. commenta sul blog a gioca
            con il nostro Quiz! E se vuoi che Fan Rating non abbia piu segreti
            per te
          </span>
        </div>

        <div
          className="helpDivweb"
          onClick={() => {
            history.push("/help");
          }}
        >
          <span className="helpTextWeb">Visita il regolamento ufficiale</span>
        </div>
      </div>
    );
  };

  // const renderLaunchTimer = () => {
  //   return (
  //     <div className="webLauncher1">
  //       <div className="webLauncher2">
  //         <span className="weblauncherText1">Non manca molto</span>
  //         <div className="weblauncher3">
  //           <span className="timerStyle">{remainingSeconds}</span>
  //         </div>
  //       </div>
  //     </div>
  //   );
  // };

  return (
    <div className="MainContainer">
      <InstallAppTutorial />
      <div className="mainContentDiv1">
        <div className="topContentDiv2">
          <div>{renderTOPContent()}</div>
          <div className="marginTop50">{renderContentBelowTopContent()}</div>
          {/* <div className="marginTop50">{renderLaunchTimer()}</div> */}
          <div className="mainContentDiv2">
            <div className="mainContentDiv3">
              <div className="mainContentDiv4">
                <div className="width100">
                  <span className="textTitleWeb">TIFA.</span>
                </div>
                <div className="width100">
                  <span className="textDescWeb">
                    Scegli la tua squadra del cuore, leggi le news, guarda i
                    video e participa alle nostre dirette
                  </span>
                </div>
              </div>
            </div>
            <div className="mainContentDiv3">
              <div className="mainContentDiv4">
                <div className="width100">
                  <span className="textDescWeb">
                    Guarda le partite di Serie A e fai subito una pagella
                  </span>
                </div>
                <div className="contentDivDesc1">
                  <span className="textTitleWeb">VOTA.</span>
                </div>
              </div>
            </div>

            <div className="mainContentDiv3">
              <div className="mainContentDiv4">
                <div className="width100">
                  <span className="textTitleWeb">VINCI!</span>
                </div>
                <div className="width100">
                  <span className="textDescWeb">
                    Accumala i Coins e riscatta subito i nostri fantasciti
                    premi!
                  </span>
                </div>
              </div>
            </div>

            <div className="contentDivDesc2">
              <span
                className="contentDivDesc3"
                style={{
                  marginTop: isMacOs && isSafari ? 90 : 0,
                }}
              >
                Cosa stai aspettando?
              </span>
              <div className="width30">{renderStartNowBtn("bottom")}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default StartUp;
