import React from "react";
import ContentLoader from "react-content-loader";
import "../../Styles/common.scss";
import PropTypes from "prop-types";

const CNotificationLoader = (props) => {
  const { web } = props;
  return (
    <div>
      <ContentLoader
        speed={2}
        viewBox={`0 0 400 ${web === "true" ? "160" : "700"}`}
        backgroundColor="#f3f3f3"
        foregroundColor="#ecebeb"
        style={{ position: "fixed" }}
        {...props}
      >
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "15" : "30"}
          rx="2"
          ry="2"
          width={web === "true" ? "40" : "60"}
          height={web === "true" ? "9" : "20"}
        />

        <circle
          cx={web === "true" ? "330" : "350"}
          cy={web === "true" ? "18" : "35"}
          r={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "40" : "80"}
          rx="2"
          ry="2"
          width={web === "true" ? "71%" : "86%"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "55" : "115"}
          rx="2"
          ry="2"
          width={web === "true" ? "71%" : "86%"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "70" : "150"}
          rx="2"
          ry="2"
          width={web === "true" ? "71%" : "86%"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "85" : "185"}
          rx="2"
          ry="2"
          width={web === "true" ? "71%" : "86%"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "100" : "220"}
          rx="2"
          ry="2"
          width={web === "true" ? "71%" : "86%"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "115" : "255"}
          rx="2"
          ry="2"
          width={web === "true" ? "71%" : "86%"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "130" : "290"}
          rx="2"
          ry="2"
          width={web === "true" ? "71%" : "86%"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "145" : "325"}
          rx="2"
          ry="2"
          width={web === "true" ? "71%" : "86%"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "160" : "360"}
          rx="2"
          ry="2"
          width={web === "true" ? "71%" : "86%"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "175" : "395"}
          rx="2"
          ry="2"
          width={web === "true" ? "71%" : "86%"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "190" : "430"}
          rx="2"
          ry="2"
          width={web === "true" ? "71%" : "86%"}
          height={web === "true" ? "10" : "20"}
        />
      </ContentLoader>
    </div>
  );
};

CNotificationLoader.propTypes = {
  web: PropTypes.string,
};

CNotificationLoader.defaultProps = {
  web: "false",
};

export default CNotificationLoader;
