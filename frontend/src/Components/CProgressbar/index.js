import React from "react";
import "./styles.scss";

const Progress_bar = ({ progress, currentLevel, nextLevel }) => {
  return (
    <div>
      <div className="progressCon">
        <span className="currentLevelText">{currentLevel}</span>
        <span className="currentLevelText">{nextLevel}</span>
      </div>
      <div className="progressbarMainCon">
        <div className="Childdiv" style={{ width: `${progress}%` }}>
          {parseInt(progress) > 25 ? (
            <span className={"progresstext"}>{`${progress}%`}</span>
          ) : null}
        </div>
        {parseInt(progress) <= 25 ? (
          <span className={"progresstext2"}>{`${progress}%`}</span>
        ) : null}
      </div>
    </div>
  );
};

export default Progress_bar;
