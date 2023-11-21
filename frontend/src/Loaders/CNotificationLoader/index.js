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
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "5" : "20"}
          rx="2"
          ry="2"
          width={web === "true" ? "88" : "100"}
          height={web === "true" ? "5" : "6"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "15" : "60"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "150"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "40" : "60"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "150"}
        />
        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "65" : "60"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "150"}
        />
        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "90" : "60"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "150"}
        />
        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "115" : "60"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "150"}
        />
        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "140" : "60"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "150"}
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
