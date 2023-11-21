import React, { useState } from "react";
import PropTypes from "prop-types";
import { makeStyles, withStyles } from "@material-ui/core/styles";
import _ from "lodash";
import { getWords, addAnalyticsEvent } from "../../commonFunctions";
import Check from "../../Assets/Images/check_green.png";
import Cancel from "../../Assets/Images/cancel.png";
import Dialog from "@material-ui/core/Dialog";
import MuiDialogContent from "@material-ui/core/DialogContent";
import AppIcon from "../../Assets/Images/fr_pwa_appLogo.png";
import renderHTML from "react-render-html";
import { useSelector } from "react-redux";
import { Setting } from "../../Utils/Setting";
import { getApiData, getAPIProgressData } from "../../Utils/APIHelper";
import "./styles.scss";
import CAlert from "../../Components/CAlert/index";
import CButton from "../../Components/CButton/index";

const useStyles = makeStyles((theme) => ({
  root: {
    justifyContent: "center",
    marginLeft: 5,
    marginRight: 5,
  },
  iconsStyle: {
    width: "30%",
    height: "70px",
    // objectFit: "contain",
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

const QuizModal = (props) => {
  const { quizDetails, quizModal, handleClose, getQuizResult } = props;

  const classes = useStyles();
  const [selectedQues, setSelectedQues] = useState(0);
  const [isDisabled, setIsDisabled] = useState(false);
  const [questionArr, setQuestionArr] = useState([]);
  const [defaultOption, setDefaultOption] = useState({});
  const { userdata } = useSelector((state) => state.auth);
  const [correctAns, setCorrectAns] = useState(false);
  const [tempArr, setemp] = useState([]);
  const [quizFinish, setQuizFinish] = useState(false);
  const [alertOpen, setAlertOpen] = useState(false);
  const [alertTitle, setAlertTitle] = useState("");
  const [alertMessage, setAlertMessage] = useState("");

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

  const getQuizList = async () => {
    const header = {
      authorization: `Bearer ${userdata?.access_token}`,
    };

    try {
      let endPoint = `${Setting.endpoints.quiz_list}`;
      const response = await getApiData(endPoint, "GET", {}, header);
      if (response?.status) {
        addAnalyticsEvent("User_Get_Quiz_List_Event", true);
        setQuestionArr(response?.data?.rows);
        setemp(response?.data?.rows);
      } else {
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };

  // store answer api call
  const getStoreAnswer = async (question_id, selected_option) => {
    setCorrectAns(false);
    try {
      let endPoint = `${Setting.endpoints.quiz_store_answer}`;
      const data = {
        "QuizAnswer[question_id]": question_id,
        "QuizAnswer[selected_option]": selected_option.option,
      };
      const response = await getAPIProgressData(endPoint, "POST", data, true);

      if (response?.status) {
        setCorrectAns(response?.data?.is_correct);
        setDefaultOption(selected_option);
      } else {
        setCorrectAns(false);
        setDefaultOption(selected_option);
        showAlert(true, getWords("OOPS"), response?.message);
      }
    } catch (err) {
      console.log("Catch Part", err);
      showAlert(true, getWords("WARNING"), getWords("Something_went_wrong"));
    }
  };
  return (
    <Dialog
      onClose={() => {
        handleClose(false);
      }}
      open={quizModal}
      // onClose={(event, reason) => {
      //   if (reason !== "backdropClick") {
      //   }
      // }}
      // className="maindialogFD"
      className={classes.root}
    >
      <DialogContent className={"submaindialogFd"}>
        <meta
          name="viewport"
          content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"
        />
        <div>
          <div>
            {quizFinish ? (
              <div>
                <div className="quiztitleDiv">
                  <img
                    loading="lazy"
                    className={classes.iconsStyle}
                    src={AppIcon}
                    alt={"AppIcon"}
                  />
                  <span className="quiztitleStyle">
                    {getWords("QUIZ_INSTRUCTIONS")}
                  </span>
                </div>
                <div className="qmhowtoplay">
                  <span className="howToPlay">{quizDetails?.title}</span>
                </div>
                <div className="divMargin">
                  <span className="contentStyle">
                    {renderHTML(_.toString(quizDetails?.description))}
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
                            <div
                              className="optionsDiv"
                              onClick={() => {
                                if (!isDisabled) {
                                } else {
                                  getStoreAnswer(item?.id, it);
                                  setIsDisabled(false);
                                }
                              }}
                              style={{
                                backgroundColor: "#FFFFFF",
                                border: `1px solid ${
                                  isSelected
                                    ? correctAns
                                      ? "#07F255"
                                      : "#ED0F1B"
                                    : "#ED0F1B"
                                }`,
                              }}
                            >
                              {isSelected ? (
                                correctAns ? (
                                  <div className="divCenter">
                                    <img
                                      loading="lazy"
                                      className="iconStyle"
                                      src={Check}
                                      alt={"CheckIcon"}
                                    />
                                  </div>
                                ) : (
                                  <div className="divCenter">
                                    <img
                                      loading="lazy"
                                      className="iconStyle"
                                      src={Cancel}
                                      alt={"CancelIcon"}
                                    />
                                  </div>
                                )
                              ) : null}
                              <span className="optionStyle">{it?.option}</span>
                            </div>
                          ) : null;
                        })
                      : null}
                  </div>
                );
              })
            ) : (
              <div>
                <div className="quiztitleDiv">
                  <img
                    loading="lazy"
                    className={classes.iconsStyle}
                    src={AppIcon}
                    alt={"AppIcon"}
                  />
                  <span className="quiztitleStyle">
                    {getWords("QUIZ_INSTRUCTIONS")}
                  </span>
                </div>
                <div className="qmhowtoplay">
                  <span className="howToPlay">{quizDetails?.title}</span>
                </div>
                <div className="divMargin">
                  <span className="contentStyle">
                    {renderHTML(_.toString(quizDetails?.description))}
                  </span>
                </div>
              </div>
            )}
          </div>
          <div className="buttonContainer">
            <CButton
              buttonText={
                selectedQues === questionArr?.length - 1
                  ? getWords("FINISH")
                  : getWords("NEXT_2")
              }
              buttonStyle={{
                bottom: 0,
                width: "100%",
              }}
              handleBtnClick={() => {
                if (selectedQues === questionArr?.length - 1) {
                  setQuizFinish(true);
                  setSelectedQues(0);
                  setQuestionArr(tempArr);
                  handleClose(selectedQues === questionArr?.length - 1);
                  setDefaultOption({});
                  getQuizResult();
                } else if (selectedQues === questionArr?.length) {
                  if (_.isArray(questionArr) && _.isEmpty(questionArr)) {
                    getQuizList();
                  }
                  setSelectedQues(0);
                  setDefaultOption({});
                  setQuestionArr(tempArr);
                  setQuizFinish(false);
                  setIsDisabled(true);
                } else {
                  if (_.isArray(questionArr) && _.isEmpty(questionArr)) {
                    getQuizList();
                  } else {
                    if (selectedQues >= 0) {
                    }
                  }
                  setQuizFinish(false);
                  setIsDisabled(true);
                  setSelectedQues(selectedQues + 1);
                  setDefaultOption({});
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

QuizModal.propTypes = {
  quizModal: PropTypes.bool,
  handleClose: PropTypes.func,
};

QuizModal.defaultProps = {
  quizModal: false,
  handleClose: () => {},
};

export default QuizModal;
