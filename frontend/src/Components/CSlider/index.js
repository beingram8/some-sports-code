/* eslint-disable jsx-a11y/alt-text */
/* eslint-disable jsx-a11y/iframe-has-title */
import React from "react";
import PropTypes from "prop-types";
import SwipeableViews from "react-swipeable-views";
import { autoPlay } from "react-swipeable-views-utils";
import { useTheme } from "@material-ui/core/styles";
import { getWords } from "../../commonFunctions";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";
import NextIcon from "../../Assets/Images/right-arrow1.png";
import PreviousIcon from "../../Assets/Images/left-arrow1.png";
import CheckIcon from "../../Assets/Images/draw-check-mark.png";
import Lottie from "react-lottie";
import CButton from "../CButton/index";
// import welcome_video from "../../Assets/Video/welcome_video.mp4";
import { isIOS } from "react-device-detect";

const AutoPlaySwipeableViews = autoPlay(SwipeableViews);

const CSlider = (props) => {
  const { data, handleBtnClick } = props;
  const theme = useTheme();

  const [activeStep, setActiveStep] = React.useState(0);

  const handleNext = () => {
    setActiveStep((prevActiveStep) => prevActiveStep + 1);
  };

  const handleBack = () => {
    setActiveStep((prevActiveStep) => prevActiveStep - 1);
  };

  const handleStepChange = (step) => {
    setActiveStep(step);
  };

  // display welcome data
  const renderTutorialData = () => {
    return (
      <div
        style={{
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
          flexDirection: "column",
        }}
      >
        <div>
          <div className="centerDescriptionSlider">
            <span
              style={{
                fontSize:
                  window.innerWidth <= 450
                    ? activeStep === 3
                      ? 40
                      : 60
                    : window.innerWidth <= 370
                    ? activeStep === 3
                      ? 20
                      : 40
                    : 50,
              }}
              className="sliderTitleStyle"
            >
              {data[activeStep]?.title}
            </span>
          </div>
        </div>
        <div>
          {activeStep === 3 ? (
            <div className="iframeStyleSlider">
              <iframe
                width={"100%"}
                height={"100%"}
                src={data[activeStep]?.url}
                frameborder="0"
                allowfullscreen
              />
            </div>
          ) : (
            <div className="lottieStyleSLider">
              <Lottie
                options={{
                  loop: true,
                  autoplay: true,
                  animationData: data[activeStep]?.url,
                }}
                height={"100%"}
                width={"100%"}
              />
            </div>
          )}
        </div>
        <div>
          <div className="centerDescriptionSlider">
            <div className="descDivSlider">
              <span className="descriptionTextStyle">
                {data[activeStep]?.description}
              </span>
            </div>
          </div>
        </div>
      </div>
    );
  };

  // render steps
  const renderSteps = () => {
    return (
      <div
        className="CommonContainer"
        style={{
          position: "fixed",
          bottom: window.innerWidth >= 450 ? "0px" : "20px",
          left: "0px",
          right: "0px",
          backgroundColor: "#ED0F18",
          height: window.innerWidth <= 400 ? 30 : 50,
        }}
      >
        <div className="sliderDiv1">
          <div
            className="sliderDiv2"
            onClick={() => {
              if (activeStep > 0) {
                handleBack();
              }
            }}
          >
            {window.innerWidth > 500 ? (
              <span
                className="previousNextTextStyleTut"
                style={{
                  color: activeStep > 0 ? "#FFF" : "#FFF",
                }}
              >
                {getWords("PREVIOUS")}
              </span>
            ) : activeStep === 3 || activeStep === 0 ? (
              <div className="iconStyleSlider" />
            ) : (
              <img src={PreviousIcon} className="iconStyleSlider" />
            )}
          </div>

          <div className="sliderDotDiv">
            {data?.map((item, ind) => {
              return (
                <div className="sliderDot">
                  <div
                    className="sliderInnerDOt"
                    style={{
                      width: ind === activeStep ? 10 : 2,
                      height: ind === activeStep ? 10 : 2,
                    }}
                  />
                </div>
              );
            })}
          </div>
          <div
            className="nextDivSlider"
            onClick={() => {
              if (activeStep === 3) {
                handleBtnClick();
              } else {
                handleNext();
              }
            }}
          >
            {window.innerWidth > 500 ? (
              <span
                className="previousNextTextStyleTut"
                style={{
                  color: "#FFF",
                }}
              >
                {getWords("NEXT")}
              </span>
            ) : activeStep === 3 ? (
              <img src={CheckIcon} className="iconStyleSlider" />
            ) : (
              <img className="iconStyleSlider" src={NextIcon} />
            )}
          </div>
        </div>
      </div>
    );
  };

  const renderStartButton = () => {
    return (
      <div
        className="CommonContainer"
        style={{
          position: "fixed",
          bottom: window.innerWidth >= 450 ? "0px" : "20px",
          left: "0px",
          right: "0px",
          backgroundColor: "#ED0F18",
          height: window.innerWidth <= 400 ? 30 : 50,
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
        }}
      >
        <CButton
          bungeeText
          buttonText={"INIZIA A GIOCARE"}
          textcolor={"#ED0F18"}
          buttonStyle={{
            backgroundColor: "#FFF",
            width: 300,
          }}
          btntextfontSize={20}
          handleBtnClick={() => {
            handleBtnClick();
          }}
        />
      </div>
    );
  };

  return (
    <div className="sliderMainContainer">
      <AutoPlaySwipeableViews
        axis={theme.direction === "rtl" ? "x-reverse" : "x"}
        index={activeStep}
        interval={5000}
        onChangeIndex={handleStepChange}
        enableMouseEvents
        autoplay={activeStep === 3 ? false : false}
        style={{ width: "100%" }}
      >
        {_.isArray(data) && !_.isEmpty(data)
          ? data.map((step, index) => (
              <div className="sliderDataDiv" key={index}>
                {Math.abs(activeStep - index) <= 2
                  ? renderTutorialData()
                  : null}
              </div>
            ))
          : null}
      </AutoPlaySwipeableViews>

      {activeStep === 3 ? renderStartButton() : renderSteps()}
    </div>
  );
};

CSlider.propTypes = {
  handleBtnClick: PropTypes.func,
};

CSlider.defaultProps = {
  handleBtnClick: () => {},
};

export default CSlider;
