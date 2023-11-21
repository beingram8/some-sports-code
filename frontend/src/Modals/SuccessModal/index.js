import React from "react";
import { withStyles } from "@material-ui/core/styles";
import Dialog from "@material-ui/core/Dialog";
import MuiDialogContent from "@material-ui/core/DialogContent";
import PropTypes from "prop-types";
import "./styles.scss";
import { getWords } from "../../commonFunctions";
import SuccessIcon from "../../Assets/Images/checkmark.png";
import TrophyImg from "../../Assets/Images/trophy.png";
import SmileyImg from "../../Assets/Images/smiley.png";
import CoinIcon from "../../Assets/Images/fan_coins.png";
import CButton from "../../Components/CButton";
import CancelIcon from "../../Assets/Images/cancel.png";

const DialogContent = withStyles((theme) => ({
  root: {
    padding: "0px",
    margin: "0px",
  },
}))(MuiDialogContent);

function SuccessModal(props) {
  const {
    successModal,
    handleClose,
    frmQuiz,
    fromReview,
    quizResult,
    fromSurvey,
  } = props;

  return (
    <Dialog onClose={handleClose} open={successModal} className="maindialog">
      {frmQuiz ? (
        <DialogContent className="successsubmaindialog">
          <div className="successheading">
            <span className="successquiztext">{getWords("QUIZ_RESULT")}</span>
          </div>
          <div className="successdiv">
            <div className="successcontent">
              <div className="successtoppadding">
                <img
                  loading="lazy"
                  className="successtrophyimage"
                  src={quizResult?.is_winner ? TrophyImg : SmileyImg}
                  alt={"Success"}
                />
              </div>
              <div className="successtoppadding">
                <span className="successredfont2 flexcenter">
                  {quizResult?.is_winner
                    ? getWords("CONGRATULATIONS")
                    : getWords("BETTER_LUCK_NEXT_TIME")}
                </span>
              </div>
              <div className="successdescription">
                <span className="successdescriptiontext">
                  {quizResult?.is_winner
                    ? getWords("QUIZ_CONGRATULATIONS")
                    : getWords("QUIZ_TRY_AGAIN")}
                </span>
              </div>
              <div className="successtoppadding">
                <span className="successscore">{getWords("YOUR_SCORE")}</span>
              </div>
              <div>
                <span className="successredfont2">
                  {quizResult?.correct_answer}
                </span>
                <span className="successblackfont">
                  /{quizResult?.total_question}
                </span>
              </div>
              <div className="successtoppadding">
                <span className="successscore">
                  {getWords("EARNED_TOKENS")}
                </span>
              </div>
              <div className="successtokencontainer">
                <img
                  loading="lazy"
                  className="successtokenimage"
                  src={CoinIcon}
                  alt={"success"}
                />
                <span className="successblackfont">
                  {quizResult?.earn_token}
                </span>
              </div>
              <CButton
                buttonText={getWords("CLOSE")}
                buttonStyle={{
                  bottom: 0,
                  width: "90%",
                }}
                handleBtnClick={() => {
                  handleClose();
                }}
              />
            </div>
          </div>
        </DialogContent>
      ) : (
        <DialogContent className="successsubmaindialog2 heightWidth">
          <div
            onClick={() => {
              handleClose();
            }}
            style={{
              width: "100%",
              justifyContent: "flex-end",
              display: "flex",
              marginTop: 10,
              marginRight: 20,
              cursor: "pointer",
            }}
          >
            <img
              loading="lazy"
              src={CancelIcon}
              style={{
                width: 20,
                height: 20,
              }}
              alt={"close icon"}
            />
          </div>
          <div className="successsubmaindialog2sub">
            <img
              loading="lazy"
              className="successsmileyimage"
              src={SuccessIcon}
              alt={"otherData"}
            />
            <span className="successredfont centertext">
              {frmQuiz
                ? getWords("THANK_YOU_FOR_SUBMITTING")
                : fromReview
                ? getWords("THANK_YOU_FOR_VOTING")
                : fromSurvey
                ? getWords("SURVEY_COMPLETED")
                : getWords("PASSWORD_CHANGE_SUCCESSFUL")}
            </span>
          </div>
        </DialogContent>
      )}
    </Dialog>
  );
}

SuccessModal.propTypes = {
  successModal: PropTypes.bool,
  handleClose: PropTypes.func,
  frmQuiz: PropTypes.bool,
  earnedTokens: PropTypes.number,
  score: PropTypes.number,
  fromSurvey: PropTypes.bool,
};

SuccessModal.defaultProps = {
  successModal: false,
  handleClose: () => {},
  frmQuiz: false,
  earnedTokens: 0,
  score: 0,
  fromSurvey: false,
};

export default SuccessModal;
