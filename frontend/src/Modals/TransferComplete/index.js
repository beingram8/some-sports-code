import React from "react";
import Lottie from "react-lottie";
import Dialog from "@material-ui/core/Dialog";
import MuiDialogContent from "@material-ui/core/DialogContent";
import { withStyles } from "@material-ui/core/styles";
import PropTypes from "prop-types";
import { makeStyles } from "@material-ui/core/styles";
import coinanimation from "../../Assets/Lottie/coinanimation.json";
import "./styles.scss";
import { getWords } from "../../commonFunctions";
import euro from "../../Assets/Images/fan_coins.png";

const DialogContent = withStyles((theme) => ({
  root: {
    padding: "0px",
    margin: "0px",
    backgroundColor: "#0000",
  },
}))(MuiDialogContent);

const useStyles = makeStyles((theme) => ({
  elevation24: {
    boxShadow:
      "0px 0px 0px 0px rgb(0 0 0 / 0%), 0px 0px 0px 0px rgb(0 0 0 / 0%), 0px 0px 0px 0px rgb(0 0 0 / 0%)",
    backgroundColor: "transparent",
  },
}));

const TransferComplete = (props) => {
  const { openModal, handleClose, animationtype } = props;
  const classes = useStyles();
  return (
    <Dialog
      classes={{ paper: classes.elevation24 }}
      onClose={handleClose}
      open={openModal}
      className="TCanime"
      style={{
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        backgroundColor: "transparent",
      }}
    >
      <DialogContent
        className="TCsubanime"
        style={{
          padding: "0px !important",
          height: "100%",
          width: "100%",
          display: "flex",
          // flexDirection: "column",
          alignItems: "center",
          justifyContent: "center",
          paddingBottom: 20,
          backgroundColor: "#0000",
        }}
      >
        {animationtype === "coinrotation" ? (
          <div
            style={{
              display: "flex",
              flexDirection: "column",
              alignItems: "center",
            }}
          >
            <img
              loading="lazy"
              src={euro}
              alt="coin"
              height={window.innerWidth <= 425 ? "35px" : "50px"}
              width={window.innerWidth <= 425 ? "35px" : "50px"}
              className="animatecoin"
            />
            <div
              style={{
                marginTop: window.innerWidth <= 425 ? "10px" : "35px",
                textAlign: "center",
                padding: "0px 20px",
              }}
            >
              <span
                style={{
                  color: "#fff",
                  fontFamily: "segoeui",
                  fontSize: window.innerWidth <= 425 ? "15px" : "20px",
                  fontWeight: "bold",
                }}
              >
                {getWords("CONGRATULATIONS_YOU_JUST_EARNED_A_COIN")}
              </span>
            </div>
          </div>
        ) : (
          <Lottie
            options={{
              loop: false,
              autoplay: true,
              animationData: coinanimation,
            }}
            height={"100%"}
            width={"100%"}
          />
        )}
      </DialogContent>
    </Dialog>
  );
};

TransferComplete.propTypes = {
  openModal: PropTypes.bool,
  handleClose: PropTypes.func,
  animationtype: PropTypes.func,
};

TransferComplete.defaultProps = {
  openModal: false,
  handleClose: () => {},
  animationtype: "",
};

export default TransferComplete;
