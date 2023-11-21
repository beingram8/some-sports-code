import MuiDialogContent from "@material-ui/core/DialogContent";
import { useDispatch, useSelector } from "react-redux";
import { withStyles } from "@material-ui/core/styles";
import { isDesktop } from "react-device-detect";
import Dialog from "@material-ui/core/Dialog";
import React from "react";
import { getWords } from "../../commonFunctions";
import authActions from "../../Redux/reducers/auth/actions";
import AppLogo from "../../Assets/Images/IMG_1136.webp";
import CancelIcon from "../../Assets/Images/cancel_white.png";
import { YoutubePlayer } from "reactjs-media";
import { makeStyles } from "@material-ui/core/styles";
import { isAndroid } from "react-device-detect";
import ellipsis from "../../Assets/Images/ellipsis.png";
import share2 from "../../Assets/Images/share2.png";
import AddIcon from "../../Assets/Images/add_black.png";

const { setIsDisplayInstallPWAPopup } = authActions;

const DialogContent = withStyles((theme) => ({
  root: {
    padding: "0px",
    margin: "0px",
  },
}))(MuiDialogContent);

const useStyles = makeStyles({
  videoDiv: {
    width: "100%",
    height: "400px",
  },
});

function InstallAppTutorial() {
  const classes = useStyles();

  const { isDisplayPopUp } = useSelector((state) => state.auth);

  const dispatch = useDispatch();

  return (
    <Dialog
      onClose={() => {
        dispatch(setIsDisplayInstallPWAPopup(false));
      }}
      open={isDisplayPopUp}
      transitionDuration={500}
    >
      <DialogContent
        style={{
          paddingTop: "0px",
        }}
      >
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            justifyContent: "center",
          }}
        >
          <div
            style={{
              display: "flex",
              flexDirection: "row",
              alignItems: "center",
              justifyContent: "center",
              backgroundColor: "#ed0f1b",
              width: "100%",
              height: "70px",
            }}
          >
            <img
              loading="lazy"
              style={{
                width: 50,
                height: 50,
                borderRadius: "5px",
                paddingRight: "10px",
              }}
              src={AppLogo}
              alt={"appIcon"}
            />
            <span
              style={{
                color: "#FFFFFF",
                fontFamily: "slaztone",
                fontSize: "20px",
              }}
            >
              FAN RATING
            </span>

            <div
              style={{
                position: "absolute",
                height: "70px",
                width: "50px",
                top: 0,
                right: 0,
                zIndex: 10,
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
              }}
            >
              <img
                loading="lazy"
                src={CancelIcon}
                style={{
                  height: "20px",
                  width: "20px",
                  cursor: "pointer",
                }}
                onClick={() => {
                  dispatch(setIsDisplayInstallPWAPopup(false));
                }}
                alt={"cancelIcon"}
              />
            </div>
          </div>

          <div
            style={{
              backgroundColor: "#FFFFFF",
              alignItems: "center",
              justifyContent: "center",
              padding: "0px 40px 30px 40px",
              display: "flex",
              flexDirection: "column",
            }}
          >
            <span
              style={{
                textAlign: "center",
                lineHeight: "22px",
                fontFamily: "segoeui",
                marginTop: 10,
              }}
            >
              {isDesktop ? getWords("DESCTOP_INFO1") : getWords("DEVICE_INFO1")}
            </span>

            <span
              style={{
                textAlign: "center",
                lineHeight: "22px",
                fontFamily: "segoeui",
                marginBottom: 10,
              }}
            >
              {isDesktop ? getWords("DESCTOP_INFO2") : getWords("DEVICE_INFO2")}
            </span>

            <YoutubePlayer
              className={classes.videoDiv}
              src={"https://youtu.be/Reu1bDR--tM"}
              width={"100%"}
              height={"100%"}
              allowFullScreen
            />
          </div>

          {isAndroid ? (
            <div
              style={{
                margin: "0px 20px 20px 20px",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                textAlign: "center",
                flexDirection: "column",
              }}
            >
              <div>
                <span>{"Tocca "}</span>
                <img
                  src={ellipsis}
                  style={{
                    width: 15,
                    height: 15,
                  }}
                  alt={"ellipsis"}
                />
                <span>{` e poi “Installa App” o ”!Aggiungi a `}</span>
              </div>
              <span>
                {"Schermata Home” per installare la App sul tuo smartphone."}
              </span>
            </div>
          ) : (
            <div
              style={{
                margin: "0px 20px 20px 20px",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                textAlign: "center",
                flexDirection: "column",
              }}
            >
              <div>
                <span>{"Tocca "}</span>
                <img
                  src={share2}
                  style={{
                    width: 15,
                    height: 15,
                  }}
                  alt={"share"}
                />
                <span>{` e poi "Aggiungi a Home" `}</span>
              </div>
              <div>
                <img
                  src={AddIcon}
                  style={{
                    width: 15,
                    height: 15,
                  }}
                  alt={"ellipsis"}
                />
                <span>{" per installare la App sul tuo Iphone."}</span>
              </div>
            </div>
          )}
        </div>
      </DialogContent>
    </Dialog>
  );
}

export default InstallAppTutorial;
