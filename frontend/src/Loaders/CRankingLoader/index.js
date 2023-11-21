import React from "react";
import ContentLoader from "react-content-loader";
import Header from "../../Components/Header/index";
import PropTypes from "prop-types";

const CContentLoader = (props) => {
  const { web } = props;
  return (
    <div>
      <Header isSubScreen={true} />
      <ContentLoader
        speed={2}
        viewBox={`0 0 400 ${web === "true" ? "500" : "1000"}`}
        backgroundColor="#f3f3f3"
        foregroundColor="#ecebeb"
        style={{ position: "fixed" }}
        {...props}
      >
        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "8" : "20"}
          rx="2"
          ry="2"
          width={web === "true" ? "88" : "150"}
          height={web === "true" ? "5" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "185" : "60"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "150"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "20" : "230"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "80" : "50"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "110" : "250"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "50"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "135" : "320"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "50"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "160" : "390"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "50"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "160" : "460"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "50"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "160" : "530"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "50"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "160" : "600"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "50"}
        />
      </ContentLoader>
    </div>
  );
};

CContentLoader.propTypes = {
  web: PropTypes.string,
};

CContentLoader.defaultProps = {
  web: "false",
};

export default CContentLoader;
