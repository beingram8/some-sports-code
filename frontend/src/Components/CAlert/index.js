import MuiDialogContent from "@material-ui/core/DialogContent";
import { withStyles } from "@material-ui/core/styles";
import { makeStyles } from "@material-ui/core/styles";
import Dialog from "@material-ui/core/Dialog";
import renderHTML from "react-render-html";
import PropTypes from "prop-types";
import React from "react";
import _ from "lodash";
import "./styles.scss";
import { getWords } from "../../commonFunctions";
import CButton from "../../Components/CButton/index";
import MuiDialogActions from "@material-ui/core/DialogActions";

const DialogContent = withStyles((theme) => ({
  root: {},
}))(MuiDialogContent);

const DialogActions = withStyles((theme) => ({
  root: {
    flex: "0 0 auto",
    display: "block",
    padding: "8px",
    alignItems: "center",
    justifyContent: "center",
  },
}))(MuiDialogActions);

const useStyles = makeStyles((theme) => ({
  paper: {
    width: window.innerWidth >= 450 ? 400 : "100%",
    padding:
      window.innerWidth >= 450
        ? theme.spacing(0, 3, 2)
        : theme.spacing(0.5, 0.5, 0.5, 0.5),
  },
  modal: {
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
  },
}));

const useStyles1 = makeStyles((theme) => ({
  paper: {
    width: window.innerWidth >= 400 ? 400 : "100%",
    padding:
      window.innerWidth >= 450
        ? theme.spacing(0, 3, 2)
        : theme.spacing(0.5, 0.5, 0.5, 0.5),
  },
}));

const CAlert = (props) => {
  const classes = useStyles();
  const classes1 = useStyles1();
  const {
    onClose,
    open,
    title,
    message,
    onOkay,
    showCancel,
    handleBuyToken,
    lesstoken,
    cancelText,
    okText,
    note,
    confirmUI,
    buttonName,
    payLoader,
    displayNote,
  } = props;

  return (
    <Dialog
      onClose={onClose}
      open={open}
      transitionDuration={500}
      className="mainDialogCAlert"
    >
      {lesstoken ? (
        <DialogContent>
          <div className={confirmUI ? classes1.paper : classes.paper}>
            <h2
              className="titleStyleCA"
              style={{
                color:
                  title === "Success!" ||
                  title === "Successo!" ||
                  title === "AWARD REDEEMED!" ||
                  title === "PREMIO RISCATTATO!"
                    ? "#07F255"
                    : title === "Alert" ||
                      title === "Oops!" ||
                      title === "Warning!" ||
                      title === "Avvertimento!"
                    ? "#ED0F18"
                    : "#484848",
              }}
              id="simple-modal-title"
            >
              {title}
            </h2>
            <p className="messageStyleCA" id="simple-modal-description">
              {renderHTML(_.toString(message))}
            </p>
          </div>
        </DialogContent>
      ) : (
        <DialogContent>
          <div className={classes.paper}>
            <h2
              className="titleStyleCA"
              style={{
                color:
                  title === "Success!" ||
                  title === "Successo!" ||
                  title === "AWARD REDEEMED!" ||
                  title === "PREMIO RISCATTATO!"
                    ? "#07F255"
                    : title === "Alert" ||
                      title === "Oops!" ||
                      title === "Warning!" ||
                      title === "Avvertimento!" ||
                      title === "READY TO SEND?" ||
                      title === "PRONTO PER L’INVIO?"
                    ? "#ED0F18"
                    : "#484848",
              }}
              id="simple-modal-title"
            >
              {title}
            </h2>

            {note && displayNote ? (
              <p
                style={{
                  fontWeight: 600,
                }}
                className="messageStyleCA"
                id="simple-modal-description"
              >
                {renderHTML(_.toString(note))}
              </p>
            ) : null}

            <p className="messageStyleCA" id="simple-modal-description">
              {renderHTML(_.toString(message))}
            </p>

            {confirmUI ? null : (
              <div className="okBtnDivCA">
                {showCancel ? (
                  <div className="AlertCancelBtn" onClick={onClose}>
                    <span className="okTextStyleCA">{cancelText}</span>
                  </div>
                ) : null}
                <div onClick={onOkay} className="AlertOkayBtn">
                  <span className="okTextStyleCA">{okText}</span>
                </div>
              </div>
            )}
          </div>
        </DialogContent>
      )}
      {lesstoken ? (
        <DialogActions>
          <div
            // className="dialogContentStyle123456"
            style={{
              padding: window.innerWidth <= 450 ? "10px 0px" : "0px 10px",
              display: "flex",
              flexDirection: "row",
            }}
          >
            <CButton
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
              btnLoader={payLoader}
              buttonText={buttonName}
            />

            <CButton
              outlined
              buttonStyle={{
                marginTop: 0,
                bottom: 0,
                marginBottom: window.innerWidth > 450 ? 15 : 0,
                marginLeft: 5,
                width: "100%",
              }}
              handleBtnClick={() => {
                onClose();
              }}
              buttonText={getWords("CLOSE")}
            />
          </div>
        </DialogActions>
      ) : null}

      {confirmUI ? (
        <DialogActions>
          <div
            // className="dialogContentStyle123456"
            style={{
              padding: window.innerWidth <= 450 ? "10px 0px" : "0px 10px",
              display: "flex",
              flexDirection: "row",
            }}
          >
            <CButton
              outlined
              buttonStyle={{
                marginTop: 0,
                bottom: 0,
                marginBottom: window.innerWidth > 450 ? 15 : 0,
                marginRight: 5,
                width: "100%",
              }}
              handleBtnClick={() => {
                onClose();
              }}
              buttonText={cancelText}
            />

            <CButton
              buttonStyle={{
                marginTop: 0,
                bottom: 0,
                marginBottom: window.innerWidth > 450 ? 15 : 0,
                marginLeft: 5,
                width: "100%",
              }}
              handleBtnClick={() => {
                onOkay();
              }}
              buttonText={okText}
            />
          </div>
        </DialogActions>
      ) : null}
    </Dialog>
  );
};

CAlert.propTypes = {
  showCancel: PropTypes.bool,
  onClose: PropTypes.func,
  open: PropTypes.bool,
  title: PropTypes.string,
  message: PropTypes.string,
  onOkay: PropTypes.func,
  handleBuyToken: PropTypes.func,
  lesstoken: PropTypes.bool,
  cancelText: PropTypes.string,
  okText: PropTypes.string,
  note: PropTypes.string,
  confirmUI: PropTypes.bool,
  buttonName: PropTypes.string,
  payLoader: PropTypes.bool,
  displayNote: PropTypes.bool,
};

CAlert.defaultProps = {
  onClose: () => {},
  open: false,
  title: "",
  message: "",
  onOkay: () => {},
  showCancel: false,
  handleBuyToken: () => {},
  lesstoken: false,
  cancelText: getWords("CANCEL"),
  okText: getWords("OK"),
  note: "",
  confirmUI: false,
  buttonName: getWords("BUY_TOKENS"),
  payLoader: false,
  displayNote: false,
};

export default CAlert;
