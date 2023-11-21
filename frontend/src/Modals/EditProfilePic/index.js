import MuiDialogContent from "@material-ui/core/DialogContent";
import { withStyles } from "@material-ui/core/styles";
import Dialog from "@material-ui/core/Dialog";
import React, { useState } from "react";
import Avatar from "react-avatar-edit";
import PropTypes from "prop-types";
import _ from "lodash";
import "./styles.scss";
import CButton from "../../Components/CButton";
import { getWords } from "../../commonFunctions";
import CancelIcon from "../../Assets/Images/cancel.png";

const DialogContent = withStyles((theme) => ({
  root: {
    padding: "0px",
    margin: "0px",
  },
}))(MuiDialogContent);

function EditProfilePic(props) {
  const { handleClose, openDialog, saveProfileProcess } = props;
  const [buttonLoader, setButtonLoader] = useState(false);
  const [imgURL, setImgURL] = useState("");
  const [fileData, setFileData] = useState(null);
  const [preview, setPreview] = useState(null);

  function onBeforeFileLoad(elem) {}

  function clearAllData() {
    handleClose();
    setImgURL("");
    setFileData(null);
    setPreview(null);
    setButtonLoader(false);
  }

  return (
    <Dialog onClose={handleClose} open={openDialog} className="maindialog">
      <DialogContent className="eppmaindialog">
        <div className="eppmaindialogsub">
          <span className="epptitletext">{getWords("UPLOAD_A_PHOTO")}</span>
          <img
            loading="lazy"
            src={CancelIcon}
            alt={"CancelIcon"}
            onClick={() => {
              clearAllData();
            }}
            className="eppcancelicon"
          />
        </div>

        <div className="eppsubcontainer">
          <div className="eppuserimage1">
            <Avatar
              height={100}
              width={100}
              onCrop={
                buttonLoader
                  ? null
                  : (preview) => {
                      setPreview(preview);
                    }
              }
              onClose={
                buttonLoader
                  ? null
                  : () => {
                      setFileData(null);
                      setPreview(null);
                    }
              }
              onBeforeFileLoad={
                buttonLoader
                  ? null
                  : () => {
                      onBeforeFileLoad();
                    }
              }
              onFileLoad={
                buttonLoader
                  ? null
                  : (file) => {
                      setFileData(file);
                    }
              }
              src={imgURL}
              mimeTypes={"image/jpeg,image/png"}
              backgroundColor="#0000"
              label={getWords("CHOOSE_FILE")}
              labelStyle={{
                fontSize: "10px",
                fontWeight: 400,
                color: "rgb(151, 151, 151)",
                display: "inline-block",
                fontFamily: "bungee",
                cursor: "pointer",
                lineHeight: "1.5em",
              }}
            />
          </div>

          {_.isEmpty(preview) ? null : (
            <div className="eppuserimage2container">
              <img
                loading="lazy"
                src={preview}
                className="eppuserimage2"
                alt={"Preview"}
              />
            </div>
          )}

          {fileData === null ? null : (
            <CButton
              btnLoader={buttonLoader}
              buttonText={getWords("SAVE_PHOTO")}
              handleBtnClick={() => {
                if (buttonLoader) {
                  return;
                } else {
                  setTimeout(() => {
                    clearAllData();
                  }, 1000);
                  saveProfileProcess(fileData);
                }
              }}
              buttonStyle={{
                bottom: 0,
                width: "85%",
              }}
            />
          )}
        </div>
      </DialogContent>
    </Dialog>
  );
}

EditProfilePic.propTypes = {
  handleOpen: PropTypes.bool,
  handleClose: PropTypes.func,
};

EditProfilePic.defaultProps = {
  handleOpen: false,
  handleClose: () => {},
};

export default EditProfilePic;
