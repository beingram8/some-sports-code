import React from "react";
import Lottie from "react-lottie";
import FootballLoader from "../../Assets/Lottie/redLoader.json";
import Dialog from "@material-ui/core/Dialog";
import MuiDialogContent from "@material-ui/core/DialogContent";
import { withStyles } from "@material-ui/core/styles";
import PropTypes from "prop-types";
import { makeStyles } from "@material-ui/core/styles";

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

const CRequestLoader = (props) => {
  const {
    openModal,
    // handleClose
  } = props;
  const classes = useStyles();

  return (
    <Dialog
      classes={{ paper: classes.elevation24 }}
      //   onClose={handleClose}
      open={openModal}
      style={{
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        backgroundColor: "transparent",
      }}
    >
      <DialogContent
        style={{
          padding: "0px !important",
          height: "220px",
          width: "350px",
          display: "flex",
          flexDirection: "column",
          alignItems: "center",
          justifyContent: "center",
          paddingBottom: 20,
        }}
      >
        <Lottie
          options={{
            loop: true,
            autoplay: true,
            animationData: FootballLoader,
          }}
          height={300}
          width={270}
        />
      </DialogContent>
    </Dialog>
  );
};

CRequestLoader.propTypes = {
  openModal: PropTypes.bool,
  handleClose: PropTypes.func,
};

CRequestLoader.defaultProps = {
  openModal: false,
  handleClose: () => {},
};

export default CRequestLoader;
