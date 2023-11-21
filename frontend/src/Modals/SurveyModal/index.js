import React, { useState } from "react";
import PropTypes from "prop-types";
import CircularProgress from "@material-ui/core/CircularProgress";
import { makeStyles, withStyles } from "@material-ui/core/styles";
import _ from "lodash";
import {
  getWords,
  addAnalyticsEvent,
  refreshUserData,
} from "../../commonFunctions";
import Dialog from "@material-ui/core/Dialog";
import MuiDialogContent from "@material-ui/core/DialogContent";
import renderHTML from "react-render-html";
import { useSelector } from "react-redux";
import { Setting } from "../../Utils/Setting";
import { getApiData, getAPIProgressData } from "../../Utils/APIHelper";
import "./styles.scss";
import CAlert from "../../Components/CAlert/index";
import CButton from "../../Components/CButton/index";
import Coin from "../../Assets/Images/fan_coins.png";
import { useHistory } from "react-router-dom";
import CancelIcon from "../../Assets/Images/cancel_white.png";

const useStyles = makeStyles((theme) => ({
  root: {
    justifyContent: "center",
    marginLeft: 5,
    marginRight: 5,
  },
  iconsStyle: {
    width: "30%",
    height: "70px",
    objectFit: "cover",
  },
  paper: {
    position: "absolute",
    width: 400,
    backgroundColor: theme.palette.background.paper,
    // border: "2px solid #000",
    boxShadow: theme.shadows[5],
    padding: theme.spacing(2, 4, 3),
  },
}));

const DialogContent = withStyles((theme) => ({
  root: {
    padding: "0px",
    margin: "0px",
  },
}))(MuiDialogContent);

const SurveyModal = (props) => {
  const {
    surveyDetails,
    surveyModal,
    handleClose,
    getSurveyResult,
    from,
    buyMatchData,
    errorData,
    onSuccess,
  } = props;
  const history = useHistory();
  const classes = useStyles();
  const [selectedQues, setSelectedQues] = useState(0);
  const [questionArr, setQuestionArr] = useState([]);
  const [defaultOption, setDefaultOption] = useState({});
  const [defaultQuestion, setDefaultQuestion] = useState({});
  const { userdata } = useSelector((state) => state.auth);
  const [tempArr, setemp] = useState([]);
  const [surveyFinish, setSurveyFinish] = useState(false);
  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");
  const [payLoad, setPayLoad] = useState(false);

  // get survey list api call
  const getSurveyList = async () => {
    const header = {
      authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = `${Setting.endpoints.survey_list}?`;
      const response = await getApiData(endPoint, "GET", {}, header);
      addAnalyticsEvent("User_Get_Survey_List_Event", true);
      if (response?.status) {
        if (
          _.isArray(response?.data?.rows) &&
          _.isEmpty(response?.data?.rows)
        ) {
          handleClose();
        } else {
          setQuestionArr(response?.data?.rows);
          setemp(response?.data?.rows);
        }
      } else {
        showAlert(true, getWords("WARNING"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  // store survey option api call
  const storeSurveyOption = async (survey_question_id, survey_option_id) => {
    try {
      let endPoint = `${Setting.endpoints.store_survey_option}`;
      const data = {
        "SurveyUserSelectedOption[survey_question_id]": survey_question_id,
        "SurveyUserSelectedOption[survey_option_id]": survey_option_id,
      };
      const response = await getAPIProgressData(endPoint, "POST", data, true);
      if (response?.status) {
        setSelectedQues(selectedQues + 1);
        setDefaultOption({});
        setDefaultQuestion({});
      } else {
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

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
          setAlertOpen(false);
        }}
        onOkay={() => {
          setAlertOpen(false);
        }}
        title={alertTitle}
        message={alertMessage}
      />
    );
  }

  async function buyMatch() {
    setPayLoad(true);
    const matchId = buyMatchData?.match_id;
    const header = {
      authorization: `Bearer ${userdata?.access_token}`,
    };
    try {
      let endPoint = `${Setting.endpoints.unlock_match_for_vote}?match_id=${matchId}`;
      const response = await getApiData(endPoint, "GET", null, header);
      if (response?.status) {
        setPayLoad(false);
        refreshUserData();
        onSuccess();
      } else {
        setPayLoad(false);
        errorData(response?.message);
      }
    } catch (err) {
      setPayLoad(false);
      console.log("Catch Part", err);
      errorData("WAR");
    }
  }

  if (from === "RateScreen") {
    return (
      <Dialog onClose={handleClose} open={surveyModal} className={classes.root}>
        <DialogContent className={"submaindialogFd"}>
          <div>
            <div>
              <div className="buyTokenModalHeader">
                <span className="buyTokentitleStyle">
                  {getWords("TO_UNLOCK_MATCH")}
                </span>
                <div className="unlockmodalclosebutton">
                  <img
                    loading="lazy"
                    src={CancelIcon}
                    className="unlockmodalclosebuttonimage"
                    onClick={() => {
                      handleClose();
                    }}
                    alt={"cancelIcon"}
                  />
                </div>
              </div>
              <div className="surveydivmaincontainer">
                <span className="contentStyle">{getWords("UNLOCK_MATCH")}</span>
                <div className="surveydivmaincontainersub">
                  <img
                    loading="lazy"
                    style={{
                      width: "8vh",
                      height: "8vh",
                    }}
                    src={Coin}
                    alt={"coinIcon"}
                  />
                  <span className="surveytokentext">
                    {`${buyMatchData?.unlock_token} ${getWords(
                      "TOKENS_VALUE"
                    )}`}
                  </span>
                </div>
              </div>
            </div>
            <div className="surveyunlocktokencontainer">
              {/* unlock button */}
              <div className="surveyunlockbuttoncontainer">
                <CButton
                  buttonText={
                    payLoad ? (
                      <CircularProgress className="surveyunlockbuttonloader" />
                    ) : (
                      getWords("BUY_MATCH_BTN_TEXT")
                    )
                  }
                  buttonStyle={{
                    bottom: 0,
                    width: "100%",
                  }}
                  handleBtnClick={
                    payLoad
                      ? null
                      : () => {
                          buyMatch();
                        }
                  }
                />
              </div>
              {/* buy more token button */}
              {userdata?.token <= buyMatchData?.unlock_token ? (
                <div className="buymoretokencontainer">
                  <CButton
                    buttonText={getWords("BUY_MORE_TOKENS")}
                    buttonStyle={{
                      bottom: 0,
                      width: "100%",
                    }}
                    handleBtnClick={
                      payLoad
                        ? null
                        : () => {
                            history.push("/buy-tokens");
                          }
                    }
                  />
                </div>
              ) : null}
            </div>
          </div>
        </DialogContent>
      </Dialog>
    );
  }

  return (
    <Dialog
      onClose={() => {
        handleClose(false);
      }}
      open={surveyModal}
      className={classes.root}
    >
      <DialogContent className={"submaindialogFd"}>
        <div>
          <div>
            {surveyFinish ? (
              <div>
                <div className="quiztitleDiv">
                  <span className="quiztitleStyle">{getWords("SURVEY")}</span>
                </div>

                <div className="howtoplaycontainer">
                  <span className="howToPlay">{surveyDetails?.title}</span>
                </div>
                <div className="divMargin">
                  <span className="contentStyle">
                    {renderHTML(_.toString(surveyDetails?.description))}
                  </span>
                </div>
              </div>
            ) : _.isArray(questionArr) && !_.isEmpty(questionArr) ? (
              questionArr?.map((item, index) => {
                return (
                  <div>
                    <div className="questionBG">
                      {/* question */}
                      <div className="questionDiv">
                        <span
                          className="questionStyle"
                          style={{
                            padding: selectedQues === index ? "25px" : "0px",
                          }}
                        >
                          {selectedQues === index ? item?.question : null}
                        </span>
                      </div>
                    </div>
                    {/* options */}
                    {!_.isEmpty(item?.options) && _.isArray(item?.options)
                      ? item?.options?.map((it) => {
                          const isSelected = _.isEqual(defaultOption, it);
                          return selectedQues === index ? (
                            item.option_type === "Image" ? (
                              <div
                                className="optionsDiv"
                                style={{
                                  display: "inline-grid",
                                  backgroundColor: "#FFFFFF",
                                  border: "1px solid #ED0F1B",
                                }}
                              >
                                <img
                                  loading="lazy"
                                  className="optionsimage"
                                  src={it?.options}
                                  alt={"Options"}
                                />
                              </div>
                            ) : (
                              <div
                                className="optionsDiv"
                                onClick={() => {
                                  setDefaultQuestion(item);
                                  setDefaultOption(it);
                                }}
                                style={{
                                  display: "flex",
                                  backgroundColor: "#FFFFFF",
                                  border: `${
                                    isSelected ? "2px" : "1px"
                                  } solid #ED0F1B`,
                                }}
                              >
                                <span
                                  className="optionStyle"
                                  style={{
                                    marginLeft: 10,
                                    color: "#484848",
                                  }}
                                >
                                  {it?.options}
                                </span>
                              </div>
                            )
                          ) : null;
                        })
                      : null}
                  </div>
                );
              })
            ) : (
              <div>
                <div className="surveytitleDiv">
                  <span className="surveytitleStyle">{getWords("SURVEY")}</span>
                </div>
                <div className="surveycontainer">
                  <img
                    loading="lazy"
                    className={classes.iconsStyle}
                    src={surveyDetails?.sponsor_adv}
                    alt={"Icons"}
                  />
                  <span
                    style={{
                      marginLeft: 10,
                    }}
                    className="howToPlay"
                  >
                    {surveyDetails?.title}
                  </span>
                </div>
                <div className="surveydivMargin">
                  <span className="contentStyle">
                    {renderHTML(_.toString(surveyDetails?.description))}
                  </span>
                </div>
              </div>
            )}
          </div>
          <div className="buttonContainer">
            <CButton
              buttonText={
                selectedQues === questionArr?.length - 1
                  ? // _.isArray(questionArr) && !_.isEmpty(questionArr)
                    getWords("FINISH")
                  : getWords("NEXT_2")
              }
              buttonStyle={{
                bottom: 0,
                width: "100%",
              }}
              handleBtnClick={() => {
                if (selectedQues === questionArr?.length - 1) {
                  setDefaultQuestion({});
                  if (!_.isEmpty(defaultOption)) {
                    storeSurveyOption(
                      defaultQuestion?.question_id,
                      defaultOption?.option_id
                    );
                    setSurveyFinish(true);
                    setSelectedQues(0);
                    setQuestionArr(tempArr);
                    handleClose(selectedQues === questionArr?.length - 1);
                    getSurveyResult();
                  } else {
                    showAlert(true, "Alert!", getWords("SELECT_ONE_OPTION"));
                  }
                } else if (selectedQues === questionArr?.length) {
                  if (_.isArray(questionArr) && _.isEmpty(questionArr)) {
                    getSurveyList();
                  }
                  setSelectedQues(0);
                  setQuestionArr(tempArr);
                  setSurveyFinish(false);

                  // setIsDisabled(true);
                } else {
                  if (_.isArray(questionArr) && _.isEmpty(questionArr)) {
                    getSurveyList();
                  } else {
                    if (selectedQues >= 0) {
                    }
                  }
                  if (!_.isEmpty(defaultOption)) {
                    setSurveyFinish(false);

                    // setIsDisabled(true);
                    storeSurveyOption(
                      defaultQuestion?.question_id,
                      defaultOption?.option_id
                    );
                    // setSelectedQues(selectedQues + 1);
                    // setDefaultOption({});
                  } else {
                    showAlert(true, "Alert!", getWords("SELECT_ONE_OPTION"));
                  }
                }
              }}
            />
          </div>
        </div>
      </DialogContent>
      {renderAlert()}
    </Dialog>
  );
};

SurveyModal.propTypes = {
  surveyModal: PropTypes.bool,
  handleClose: PropTypes.func,
  getSurveyResult: PropTypes.func,
  from: PropTypes.string,
  onSuccess: PropTypes.func,
};

SurveyModal.defaultProps = {
  surveyModal: false,
  handleClose: () => {},
  getSurveyResult: () => {},
  onSuccess: () => {},
  from: "",
};

export default SurveyModal;
