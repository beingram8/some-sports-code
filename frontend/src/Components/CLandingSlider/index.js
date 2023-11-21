/* eslint-disable jsx-a11y/alt-text */
/* eslint-disable jsx-a11y/iframe-has-title */
import React, { useEffect } from "react";
import PropTypes from "prop-types";
import SwipeableViews from "react-swipeable-views";
import { autoPlay } from "react-swipeable-views-utils";
import { useTheme } from "@material-ui/core/styles";
import { getWords } from "../../commonFunctions";
import _ from "lodash";
import "./styles.scss";
import "../../Styles/common.scss";

const AutoPlaySwipeableViews = autoPlay(SwipeableViews);

const CSlider = (props) => {
  const { data, handleBtnClick } = props;
  const theme = useTheme();

  const [activeStep, setActiveStep] = React.useState(0);
  const handleStepChange = (step) => {
    setActiveStep(step);
  };
  useEffect(() => {
    data.forEach((picture) => {
      const img = new Image();
      img.src = picture.url;
    });
  })
  // display welcome data
  const renderImageData = () => {
    return (
      <div
        className="slider-image-wrapper"
      >
        {data[activeStep]?.round ?
          <div className="lottieStyleSLider">
            <img
              loading="lazy"
              src={data[activeStep]?.url}
              className="landingImageRound"
              alt={"app icon"}
            />
          </div> :
          <div className="lottieStyleSLiderNoneCircle">
            <img
              loading="lazy"
              src={data[activeStep]?.url}
              className="landingImage"
              alt={"app icon"}
            />
          </div>}
      </div>
    );
  };
  const renderDescriptionData = () => {
    return (
      <span className="descriptionTextStyle">
        {data[activeStep]?.description}
      </span>
    )
  }
  // render steps
  const renderSteps = () => {
    return (
      <div className="sliderDiv1">
        {data?.map((item, ind) => {
          return (
            <div className="sliderDot" onClick={() => { handleStepChange(ind) }}>

              <div
                className="sliderInnerDOt"
                style={{
                  width: ind === activeStep ? 10 : 2,
                  height: 2,
                  borderRadius: 10
                }}
              />
            </div>
          );
        })}
      </div>
    );
  };

  return (
    <div className="ImageSlider">
      <AutoPlaySwipeableViews
        axis={theme.direction === "rtl" ? "x-reverse" : "x"}
        index={activeStep}
        interval={7000}
        onChangeIndex={handleStepChange}
        enableMouseEvents
        style={{ width: "100%" }}
      >
        {_.isArray(data) && !_.isEmpty(data)
          ? data.map((step, index) => (
            <div className="sliderDataImageDiv" key={index}>
              {Math.abs(activeStep - index) <= 2
                ? renderImageData(index)
                : null}
            </div>
          ))
          : null}
      </AutoPlaySwipeableViews>
      <div className="container-60" id="text-slider">
        <div className="centerDescriptionSlider">
          <div className="descDivSlider">
            <SwipeableViews
              axis={theme.direction === "rtl" ? "x-reverse" : "x"}
              index={activeStep}
              onChangeIndex={handleStepChange}
              enableMouseEvents
              autoPlay={activeStep === 3 ? false : false}
              style={{ width: "100%" }}
            >
              {_.isArray(data) && !_.isEmpty(data)
                ? data.map((step, index) => (
                  <div className="sliderDataDiv" key={index}>
                    {Math.abs(activeStep - index) <= 2
                      ? renderDescriptionData()
                      : null}
                  </div>
                ))
                : null}
            </SwipeableViews>
            {renderSteps()}
          </div>
        </div>
      </div>
    </div>
  );
};

CSlider.propTypes = {
  handleBtnClick: PropTypes.func,
};

CSlider.defaultProps = {
  handleBtnClick: () => { },
};

export default CSlider;
