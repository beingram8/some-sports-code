import React from "react";
import PropTypes from "prop-types";
import MuiDialogTitle from "@material-ui/core/DialogTitle";
import MuiDialogActions from "@material-ui/core/DialogActions";
import MuiDialogContent from "@material-ui/core/DialogContent";
import { makeStyles } from "@material-ui/core/styles";
import { withStyles } from "@material-ui/core/styles";
import CButton from "../../Components/CButton/index";
import Dialog from "@material-ui/core/Dialog";
import { isMacOs, isSafari } from "react-device-detect";
import CancelIcon from "../../Assets/Images/cancel_white.png";
import { getWords } from "../../commonFunctions";
import "./styles.scss";

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

const ReportModal = (props) => {
  const classes = useStyles();

  const {
    handleClose,
    openDialog,
    btnLoader,
    handleBtn,
    inputData,
    inputValue,
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
        <div className="reportmodal">
          <span className="main_name">{getWords("REPORT_POST")}</span>
          <div className="signupmodalclosebutton">
            <img
              loading="lazy"
              src={CancelIcon}
              className="signupmodalclosebuttonimage"
              alt="oops..."
              onClick={() => {
                handleClose();
              }}
            />
          </div>
        </div>
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
        className={"submaindialog"}
      >
        <div className="reportmain">
          <span className="addposttextStyleCU">
            {getWords("REPORT_REASON")}
          </span>
          <textarea
            type="text"
            id="caption"
            name="caption"
            className="addreportTextarea"
            value={inputValue}
            onChange={(e) => inputData(e)}
            rows={8}
            placeholder={getWords("REASON_PLACEHOLDER")}
          />
        </div>
      </DialogContent>
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
            buttonText={getWords("SUBMIT")}
          />
        </div>
      </DialogActions>
    </Dialog>
  );
};

ReportModal.propTypes = {
  openDialog: PropTypes.bool,
  handleClose: PropTypes.func,
  handleBtn: PropTypes.func,
  inputData: PropTypes.func,
  btnLoader: PropTypes.bool,
};

ReportModal.defaultProps = {
  openDialog: false,
  handleClose: () => {},
  inputData: () => {},
  handleBtn: () => {},
  btnLoader: false,
};

export default ReportModal;
