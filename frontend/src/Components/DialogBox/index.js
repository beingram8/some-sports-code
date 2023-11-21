import React from "react";
import _ from "lodash";
import PropTypes from "prop-types";
import renderHTML from "react-render-html";
import Dialog from "@material-ui/core/Dialog";
import { makeStyles } from "@material-ui/core/styles";
import { withStyles } from "@material-ui/core/styles";
import { isMacOs, isSafari } from "react-device-detect";
import MuiDialogTitle from "@material-ui/core/DialogTitle";
import MuiDialogActions from "@material-ui/core/DialogActions";
import MuiDialogContent from "@material-ui/core/DialogContent";
import "./styles.scss";
import CButton from "../CButton/index";
import CVideoPlayer from "../CVideoPlayer";
import { getWords } from "../../commonFunctions";

const DialogContent = withStyles((theme) => ({
  root: {
    padding: "0px",
    margin: "0px",
  },
}))(MuiDialogContent);

const DialogTitle = withStyles((theme) => ({
  root: {
    flex: "0 0 auto",
    margin: 0,
    padding: "0px 0px",
  },
}))(MuiDialogTitle);

const DialogActions = withStyles((theme) => ({
  root: {
    flex: "0 0 auto",
    display: "block",
    padding: "8px",
    alignItems: "center",
    justifyContent: "center",
  },
}))(MuiDialogActions);

const useStyles = makeStyles(() => ({
  paper: { maxWidth: "400px" },
}));

function DialogBox(props) {
  const classes = useStyles();
  const {
    openDialog,
    handleClose,
    giftItem,
    isStream,
    fromnews,
    handleBtn,
    hideView,
    onPlayVideo,
    btnLoader,
    lessToken,
    handleBuyToken,
  } = props;

  return (
    <Dialog
      onClose={handleClose}
      open={openDialog}
      transitionDuration={500}
      className="maindialog"
      classes={{ paper: classes.paper }}
    >
      <DialogTitle>
        {giftItem.reward_img_url ? (
          <img
            loading="lazy"
            src={giftItem?.reward_img_url}
            className="giftimage"
            alt={"giftData"}
          />
        ) : isStream ? (
          <div className="giftimage2">
            <CVideoPlayer
              src={"https://www.twitch.tv/fanrating"}
              isStream={isStream}
              videoposter={giftItem?.thumb_img}
            />
          </div>
        ) : (
          <CVideoPlayer
            src={
              giftItem?.is_external === "2"
                ? giftItem?.external_link
                : giftItem?.is_external === "1"
                ? giftItem?.video_url
                : giftItem?.video_url
            }
            videoposter={giftItem.thumb_img}
            onPlayVideo={(value) => {
              const data = { id: giftItem?.id, videoPlayed: value };
              onPlayVideo(data);
            }}
          />
        )}
      </DialogTitle>
      <DialogContent
        style={{
          width:
            isMacOs && isSafari
              ? "400px"
              : window.innerWidth > 450
              ? "400px"
              : window.innerWidth <= 375
              ? "310px"
              : window.innerWidth <= 350
              ? "280px"
              : window.innerWidth <= 320
              ? "250px"
              : "346px",
        }}
        className={isStream || hideView ? "submaindialog12" : "submaindialog"}
      >
        {isStream || hideView ? null : (
          <div
            style={{
              paddingBottom: fromnews ? 0 : 15,
            }}
            className="dialogdiv"
          >
            <div
              style={{
                width: "100%",
              }}
            >
              <span className="dialogtitletext">{giftItem?.title}</span>
              <div
                style={{
                  padding: "10px 0px",
                }}
                className="dialogdesctextcontainer"
              >
                <span className="dialogdesctext">
                  {renderHTML(_.toString(giftItem?.description))}
                </span>
              </div>
            </div>
          </div>
        )}
      </DialogContent>
      {isStream || hideView ? null : lessToken ? (
        <DialogActions>
          <div
            style={{
              padding: window.innerWidth <= 450 ? "10px 0px" : "0px 10px",
              display: "flex",
              flexDirection: "row",
            }}
          >
            <CButton
              btnLoader={btnLoader}
              buttonStyle={{
                marginTop: 0,
                bottom: 0,
                marginBottom: window.innerWidth > 450 ? 15 : 0,
                marginRight: 5,
                width: "100%",
              }}
              handleBtnClick={() => {
                handleBuyToken();
              }}
              buttonText={getWords("BUY_TOKENS")}
            />

            <CButton
              btnLoader={btnLoader}
              outlined
              buttonStyle={{
                marginTop: 0,
                bottom: 0,
                marginBottom: window.innerWidth > 450 ? 15 : 0,
                marginLeft: 5,
                width: "100%",
              }}
              handleBtnClick={() => {
                handleClose();
              }}
              buttonText={getWords("CLOSE")}
            />
          </div>
        </DialogActions>
      ) : (
        <DialogActions>
          <div
            style={{
              padding: window.innerWidth <= 450 ? "10px 0px" : "0px 10px",
            }}
          >
            <CButton
              btnLoader={btnLoader}
              buttonStyle={{
                marginTop: 0,
                bottom: 0,
                marginBottom: window.innerWidth > 450 ? 15 : 0,
              }}
              handleBtnClick={() => {
                handleBtn();
              }}
              buttonText={
                fromnews
                  ? getWords("CLOSE")
                  : `Riscatta per (${giftItem?.token}) Fan Coins`
              }
            />
          </div>
        </DialogActions>
      )}
    </Dialog>
  );
}

DialogBox.propTypes = {
  openDialog: PropTypes.bool,
  handleClose: PropTypes.func,
  handleBtn: PropTypes.func,
  handleClickOpen: PropTypes.func,
  onPlayVideo: PropTypes.func,
  btnLoader: PropTypes.bool,
  hideView: PropTypes.bool,
  isStream: PropTypes.bool,
  lessToken: PropTypes.bool,
  handleBuyToken: PropTypes.func,
};

DialogBox.defaultProps = {
  openDialog: false,
  handleClose: () => {},
  handleBtn: () => {},
  handleClickOpen: () => {},
  onPlayVideo: () => {},
  btnLoader: false,
  hideView: false,
  isStream: false,
  lessToken: false,
  handleBuyToken: () => {},
};

export default DialogBox;
