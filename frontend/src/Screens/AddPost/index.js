import CircularProgress from "@material-ui/core/CircularProgress";
import React, { useState, useEffect, useCallback } from "react";
import Header from "../../Components/Header/index";
import { useHistory } from "react-router-dom";
import { useSelector } from "react-redux";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import { Setting } from "../../Utils/Setting";
import CButton from "../../Components/CButton";
import CAlert from "../../Components/CAlert/index";
import CancelIcon from "../../Assets/Images/cancel.png";
import { getAPIProgressData } from "../../Utils/APIHelper";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";
import TransferComplete from "../../Modals/TransferComplete";
import { useDropzone } from "react-dropzone";

const AddPost = (props) => {
  const history = useHistory();
  const postdata = props?.location?.state?.data;

  const [uploadedDocument, setUploadedDocument] = useState([]);

  const isEditData = _.isObject(postdata) && !_.isEmpty(postdata);
  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

  const [fileData, setFileData] = useState({});
  const [media, setMedia] = useState("");
  const [caption, setCaption] = useState(postdata?.caption);

  const { userdata } = useSelector((state) => state.auth);
  const [cLoad, setCLoad] = useState(false);
  const [displayAnim, setDisplayAnim] = useState(false);

  useEffect(() => {
    document.title = Setting.page_name.TEASING_ROOM_ADD_POST;
  }, []);

  // uploaded file
  const onDrop = useCallback((acceptedFiles) => {
    // Do something with the files\
    console.log("acceptedFiles ========>>>>>> ", acceptedFiles);
    setUploadedDocument(acceptedFiles);
  }, []);

  const { getRootProps, getInputProps, isDragActive } = useDropzone({ onDrop });

  const showAlert = (open, title, message) => {
    setAlertOpen(open);
    setAlertTitle(title);
    setAlertMessage(message);
  };

  function renderAlert() {
    return (
      <CAlert
        open={alertOpen}
        onClose={() => {
          setCLoad(false);
          setAlertOpen(false);
        }}
        onOkay={() => {
          setCLoad(false);
          setAlertOpen(false);
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  async function getPostFromUser() {
    setCLoad(true);
    try {
      let endPoint = `${Setting.endpoints.teasing_add_post}`;

      if (isEditData) {
        endPoint = `${Setting.endpoints.teasing_edit_post}?id=${postdata?.id}`;
      }

      const fileTypeValue = uploadedDocument[0]?.type;

      console.log("uploadedDocument[0].name ======>>>>> ", uploadedDocument);

      let addEditRoomData = {};

      if (isEditData) {
        addEditRoomData = {
          "TeasingRoom[caption]": caption,
          "TeasingRoom[is_video]": postdata?.is_video,
        };
      } else {
        addEditRoomData = {
          "TeasingRoom[caption]": caption,
          "TeasingRoom[media]": uploadedDocument[0],
          "TeasingRoom[is_video]": fileTypeValue.includes("video") ? 1 : 0,
        };
      }

      const response = await getAPIProgressData(
        endPoint,
        "POST",
        addEditRoomData,
        true
      );
      if (response?.status) {
        if (isEditData) {
          const modifyPostData = {
            user_name: userdata?.username,
            first_name: userdata?.firstname,
            last_name: userdata?.lastname,
            email: userdata?.email,
            user_Pic: userdata?.user_image,
            update_Post_id: postdata?.id,
            caption: caption,
          };
          addAnalyticsEvent("Teasing_Room_Post_Modify_Event", modifyPostData);
        } else {
          const addPostData = {
            user_name: userdata?.username,
            first_name: userdata?.firstname,
            last_name: userdata?.lastname,
            email: userdata?.email,
            user_Pic: userdata?.user_image,
            new_post_file: fileData?.name,
            caption: caption,
          };
          addAnalyticsEvent("Teasing_Room_Add_New_Post_Event", addPostData);
        }

        if (response?.data?.is_animation === true) {
          setDisplayAnim(true);
        }

        setTimeout(() => {
          setCaption("");
          setMedia("");
          setFileData({});
          setCLoad(false);
          history.goBack();
        }, 1000);
      } else {
        setCLoad(false);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  }

  function validation() {
    if (_.isEmpty(caption)) {
      showAlert(true, getWords("OOPS"), getWords("ADD_CAPTION_VALIDATION"));
    } else if (isEditData === false && _.isEmpty(media)) {
      showAlert(true, getWords("OOPS"), getWords("ADD_POST_VALIDATION"));
    } else {
      getPostFromUser();
    }
  }

  function filetypevalidation() {
    const fileTypeValue = fileData?.type;
    if (!_.isEmpty(fileTypeValue)) {
      if (fileTypeValue.includes("image") || fileTypeValue.includes("video")) {
        return true;
      } else {
        return false;
      }
    } else {
      return null;
    }
  }

  //   handle selected file
  const handleFileChange = (event) => {
    const { target } = event;
    const { files } = target;
    setFileData(files[0]);

    // file for preview
    if (files && files[0]) {
      var reader = new FileReader();

      reader.onloadstart = () => setCLoad(true);

      reader.onload = (event) => {
        if (files[0]?.type === "video/x-matroska") {
          showAlert(true, getWords("OOPS"), getWords("UPLOAD_FAILED"), true);
        } else {
          setMedia(event.target.result);
        }
        setCLoad(false);
      };
      reader.readAsDataURL(files[0]);
    }
  };

  function getMobileOperatingSystem() {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;

    // Windows Phone must come first because its UA also contains "Android"
    if (/windows phone/i.test(userAgent)) {
      return "Windows Phone";
    }

    if (/android/i.test(userAgent)) {
      return "Android";
    }

    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
      return "iOS";
    }

    return "unknown";
  }

  const renderAddPost = () => {
    const isValidFile = filetypevalidation();
    return (
      <div
        className="CommonContainer addpostroot"
        style={{
          padding: window.innerWidth <= 639 ? "0px 20px" : "none",
        }}
      >
        <div className="divInfoUIAP">
          <span className="addposttextStyleCU">{getWords("CAPTION")}</span>
          <textarea
            type="text"
            id="caption"
            name="caption"
            className="addpostTextAreaCU"
            placeholder={getWords("POST_CAPTION")}
            value={caption}
            onChange={cLoad ? null : (e) => setCaption(e.target.value)}
            rows={8}
          />
        </div>

        <div className="divInfoUIAP">
          <span className="addposttextStyleCU">{getWords("MEDIA")}</span>

          {isEditData ? (
            <div className="divInfoUIAP1">
              <div className="mediaPreviewDiv">
                {postdata.is_video === 0 ? (
                  <img
                    className="mediaImagePreview"
                    src={postdata.post_media}
                    alt={"post media"}
                  />
                ) : (
                  <video
                    className="mediaImagePreview"
                    controls
                    src={postdata.post_media}
                  />
                )}
              </div>
            </div>
          ) : !_.isEmpty(media) ? (
            <div className="divInfoUIAP1">
              <div className="mediaPreviewDiv">
                <div className="addpostMediaCancel">
                  <img
                    onClick={
                      cLoad
                        ? null
                        : () => {
                            setMedia("");
                            setFileData({});
                          }
                    }
                    className="addpostCancelIcon"
                    src={CancelIcon}
                    alt={"cancel-icon"}
                  />
                </div>
                {isValidFile === false ? (
                  <div className="editprofilemaindiv1">
                    <span className="invalidspan">
                      {getWords("INVALID_FILE")}
                    </span>
                  </div>
                ) : fileData?.type?.includes("image") ? (
                  <img
                    className="mediaImagePreview"
                    src={media}
                    alt={"post media"}
                  />
                ) : fileData?.type.includes("video") ? (
                  <video className="mediaImagePreview" controls src={media} />
                ) : null}
              </div>
            </div>
          ) : (
            // <input
            //   id="files"
            //   type="file"
            //   accept={"image/*, video/*, media_type/*"}
            //   capture="camera"
            //   placeholder="select file"
            //   onChange={handleFileChange}
            // />
            <div
              style={{
                padding: 10,
                cursor: "pointer",
                display: "flex",
                alignItems: "center",
              }}
              className={
                getMobileOperatingSystem() === "iOS"
                  ? "editprofileinputtext1"
                  : "editprofileinputtext"
              }
            >
              <div {...getRootProps()}>
                <input {...getInputProps()} />
                {isDragActive ? (
                  <span
                    style={{
                      color: "#656565",
                    }}
                  >
                    Drop the files here ...
                  </span>
                ) : !_.isEmpty(uploadedDocument) ? (
                  <span
                    style={{
                      color: "#656565",
                    }}
                  >
                    File to be uploaded: {uploadedDocument[0].name}
                  </span>
                ) : (
                  <span
                    style={{
                      color: "#656565",
                    }}
                  >
                    Drag 'n' drop some files here, or click to select files
                  </span>
                )}
              </div>
            </div>
          )}

          <CButton
            buttonText={
              cLoad ? (
                <CircularProgress className="post_loader" />
              ) : (
                getWords("SUBMIT")
              )
            }
            handleBtnClick={
              isValidFile === false
                ? null
                : cLoad
                ? null
                : () => {
                    // validation();
                    getPostFromUser();
                  }
            }
            buttonStyle={{
              marginTop: 50,
            }}
          />
        </div>
      </div>
    );
  };

  return (
    <div className="MainContainer">
      <Header isSubScreen={true} />
      <div className="mainContainerAP">
        <div className="CommonContainer addpostmaincontainer">
          <div className="addposthead">
            <span className="Headingmain">
              {isEditData ? getWords("EDIT_POST") : getWords("ADD_POST")}
            </span>
          </div>
        </div>

        {renderAddPost()}
        {renderAlert()}
        <TransferComplete
          animationtype="coinrotation"
          openModal={displayAnim}
          handleClose={() => {
            setTimeout(() => {
              setDisplayAnim(false);
            }, 1000);
          }}
        />
      </div>
    </div>
  );
};

export default AddPost;
