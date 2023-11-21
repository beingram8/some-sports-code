import React, { useState, useEffect, useRef } from "react";
import { withStyles } from "@material-ui/core/styles";
import Dialog from "@material-ui/core/Dialog";
import MuiDialogContent from "@material-ui/core/DialogContent";
import PropTypes from "prop-types";
import "./styles.scss";
import { getWords } from "../../commonFunctions";
import CancelIcon from "../../Assets/Images/cancel_white.png";
import CButton from "../../Components/CButton";
import { useSelector } from "react-redux";
import Grid from "@material-ui/core/Grid";
import _ from "lodash";
import CNoData from "../../Components/CNoData/index";

const DialogContent = withStyles((theme) => ({
  root: {
    padding: "0px",
    margin: "0px",
  },
}))(MuiDialogContent);

function TeamSelectionModal(props) {
  const { teamList } = useSelector((state) => state.auth);

  const { openDialog, handleClose, sTem, onSave } = props;

  const [selectedTeam, setSelectedTeam] = useState(sTem);


  const usePrevious = (value) => {
    const ref = useRef();
    useEffect(() => {
      ref.current = value;
    });
    return ref.current;
  };

  const prevSTeam = usePrevious(selectedTeam);

  function renderHeader() {
    return (
      <div className="teamselectionheading">
        <div className="teamselectinomodalclosebutton">
          <img
            loading="lazy"
            src={CancelIcon}
            className="teamselectionclosebuttonimage"
            onClick={() => {
              handleClose();
              setSelectedTeam(prevSTeam);
            }}
            alt={"cancelIcon"}
          />
        </div>
        <span className="teamselectionheadingtext">
          {getWords("CHOOSE_YOUR_TEAM")}
        </span>
      </div>
    );
  }

  return (
    <Dialog
      open={openDialog}
      transitionDuration={500}
      className="teamselectionmaindialog"
    >
      <DialogContent className="subteamselectionmodal">
        {renderHeader()}
        <div className="teamselectionsubmaindiv">
          <Grid container className="tsteamcontainer">
            {_.isArray(teamList) && !_.isEmpty(teamList) ? (
              teamList?.map((item, index) => {
                return (
                  <div
                    className="tsteamlogotextcontainer"
                    key={index}
                    style={{
                      border:
                        item.id === selectedTeam.id ? "1px solid #f00" : "none",
                      width: "max-content",
                      height: item.id === selectedTeam.id ? "86px" : "88px",
                      boxShadow:
                        item.id === selectedTeam.id
                          ? "0px 1px 6px 0px rgba(255,0,0,0.6)"
                          : "none",
                    }}
                    onClick={() => {
                      setSelectedTeam(item);
                    }}
                  >
                    <img
                      loading="lazy"
                      src={item.logo}
                      className="tsteamsimagestyle"
                      alt="teamlogo"
                    />
                    <span className="tsteamname">{item.name}</span>
                  </div>
                );
              })
            ) : (
              <CNoData message={getWords("SOMETHING_WENT_WRONG")} />
            )}
          </Grid>
          <CButton
            boldText={true}
            buttonStyle={{
              width: window.innerWidth > 425 ? 300 : "85%",
              position: "absolute",
              bottom: 10,
              marginTop: 0,
            }}
            buttonText={getWords("CHANGE_TEAM")}
            handleBtnClick={() => {
              onSave(selectedTeam);
            }}
          />
        </div>
      </DialogContent>
    </Dialog>
  );
}

TeamSelectionModal.propTypes = {
  openDialog: PropTypes.bool,
  handleClose: PropTypes.func,
  sTem: PropTypes.object,
  onSave: PropTypes.func,
};

TeamSelectionModal.defaultProps = {
  openDialog: false,
  handleClose: () => { },
  sTem: {},
  onSave: () => { },
};

export default TeamSelectionModal;
