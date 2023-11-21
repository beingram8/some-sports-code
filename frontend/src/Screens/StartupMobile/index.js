/* eslint-disable jsx-a11y/iframe-has-title */
import { useLocation, useHistory } from "react-router-dom";
import React, { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import APPICON from "../../Assets/Images/IMG_1136.webp";
import authActions from "../../Redux/reducers/auth/actions";
import UncleAunty from "../../Assets/Images/uncle_aunty-PNG.webp";
import { askForPermissionToReceiveNotifications } from "../../push-notification";
import { isMacOs, isSafari } from "react-device-detect";
import InstallAppTutorial from "../../Modals/InstallAppTutorial";

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

const StartUpMobile = () => {
  const history = useHistory();
  const location = useLocation();
  const dispatch = useDispatch();
  const { firstBoolValue } = useSelector((state) => state.auth);

  useEffect(() => {
    isGetReferEarnToken();
    askForPermissionToReceiveNotifications();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    if (firstBoolValue === 0) {
      setTimeout(() => {
        dispatch(setIsDisplayInstallPWAPopup(true));
        dispatch(setFirstBoolValueForPopUp(0));
      }, 30000);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

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
        onClick={() => {
          history.push("/rate");
        }}
        className="startBtnDiv"
      >
        <div
          style={{
            backgroundColor: pos === "bottom" ? "#ED0F18" : "#fff",
            width: pos === "bottom" ? "100%" : "50%",
            marginBottom:
              pos === "bottom"
                ? isMacOs && isSafari
                  ? "20px"
                  : "none"
                : "none",
          }}
          className="startUpNowBtnMobile"
        >
          <span
            className="startBtnText"
            style={{
              color: pos === "bottom" ? "#FFF" : "#ED0F18",
            }}
          >
            Inizia subito!
          </span>
        </div>
      </div>
    );
  };

  const renderTOPContent = () => {
    return (
      <div className="topContentDivMob1">
        <div className="topContentDivMob2">
          <img
            loading="lazy"
            src={APPICON}
            className="mobAPPIcon"
            alt={"app icon"}
          />
        </div>
        <div className="mobTextStyle1Div">
          <span className="mobTextStyle1">
            {`Ascolta Arrigo Sacchi, gioca anche tu con Fan Rating e vinci fantastici premi!`}
          </span>
        </div>

        <div className="videoContentDivMob">
          <iframe
            width="100%"
            height="300px"
            src="https://www.youtube.com/embed/HxhxGZMWM_A?autoplay=1&mute=1"
            frameborder="0"
            allow="autoplay; encrypted-media"
            allowfullscreen
          />
        </div>

        <div className="mobTextStyle2Div">
          <span className="mobTextStyle2">TIFA.</span>
          <span className="mobTextStyle2">VOTA.</span>
          <span className="mobTextStyle2">VINCI!</span>
        </div>

        {renderStartNowBtn()}

        <div className="mainImage">
          <img loading="lazy" src={UncleAunty} alt={"fanrating"} />
        </div>
      </div>
    );
  };

  const renderArray = () => {
    return (
      <div className="contentDivMOb">
        {_.isArray(dataArray) && !_.isEmpty(dataArray)
          ? dataArray?.map((item) => {
              return (
                <div
                  style={{
                    width: window.innerWidth >= 350 ? "90%" : "85%",
                    margin: "8px 20px",
                  }}
                >
                  <span className="contentIdMOb">{item.id}</span>
                  <div className="contentDEscMobDiv">
                    <span className="contentDEscMob">{item.desc}</span>
                  </div>
                </div>
              );
            })
          : null}
      </div>
    );
  };

  // const renderLaunchTimer = () => {
  //   return (
  //     <div className="launchTimerDivMain">
  //       <div className="launchTImerCenter">
  //         <span className="launchTImerCenter1">Non manca molto</span>
  //         <div
  //           className="divMob01"
  //           style={{
  //             width:
  //               window.innerWidth >= 370
  //                 ? "300px"
  //                 : window.innerWidth >= 350
  //                 ? "250px"
  //                 : "200px",
  //           }}
  //         >
  //           <div className="launchTImerCenter">
  //             <span className="timerStyleMobileDate">1</span>
  //             <span className="timerStyleMobileMonth">ottobre</span>
  //           </div>
  //         </div>
  //       </div>
  //     </div>
  //   );
  // };

  return (
    <div
      className="MainContainer"
      style={{
        top: 0.1,
        bottom: 0.1,
      }}
    >
      <InstallAppTutorial />
      <div className="divMob02">
        <div className="divMobCOlumn">
          <div>{renderTOPContent()}</div>

          <div
            className="spanMob01"
            style={{
              marginTop: isMacOs && isSafari ? 50 : "none",
            }}
          >
            <span className="spanMob02">
              {`Partecipa anche tu al nostro gioco, solo per veri tifosi! Diventa un tester e ottieni tanti vantaggi prima del lancio`}
            </span>
          </div>
          <div
            className="spanMob01"
            style={{
              marginTop: isMacOs && isSafari ? 50 : "none",
            }}
          >
            <span className="spanMob03">Come Funziona?</span>
          </div>

          <div>{renderArray()}</div>
          <div
            onClick={() => {
              history.push("/help");
            }}
            className="spanMob01"
            style={{
              cursor: "pointer",
              margin: "20px 0px",
            }}
          >
            <span className="divMob20">Visita il regolamento ufficiale</span>
          </div>

          {/* <div
            style={{
              margin: "30px 0px",
            }}
          >
            {renderLaunchTimer()}
          </div> */}

          <div className="divCenterMob03">
            <div className="divCenterMob01">
              <span className="divCenterMob02">Cosa stai aspettando?</span>
            </div>
          </div>

          <div
            className="divStyle20"
            style={{
              marginTop: isMacOs && isSafari ? 70 : "none",
            }}
          >
            {renderStartNowBtn("bottom")}
          </div>

          {isMacOs && isSafari ? <div className="blankDivMob" /> : null}
        </div>
      </div>
    </div>
  );
};

export default StartUpMobile;
