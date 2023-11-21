import React from "react";
import ContentLoader from "react-content-loader";
import Header from "../../Components/Header/index";
import BottomTab from "../../Components/BottomTab/index";
import PropTypes from "prop-types";

const CContentLoader = (props) => {
  const { web } = props;
  return (
    <div>
      <Header />
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
          y={web === "true" ? "8" : "20"}
          rx="2"
          ry="2"
          width={web === "true" ? "88" : "100"}
          height={web === "true" ? "5" : "6"}
        />

        <rect
          x={web === "true" ? "100" : "60"}
          y={web === "true" ? "18" : "35"}
          rx="2"
          ry="2"
          width={web === "true" ? "70" : "100"}
          height={web === "true" ? "5" : "6"}
        />
        <rect
          x={web === "true" ? "230" : "230"}
          y={web === "true" ? "18" : "35"}
          rx="2"
          ry="2"
          width={web === "true" ? "70" : "100"}
          height={web === "true" ? "5" : "6"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "25" : "43"}
          rx="1"
          ry="1"
          width={web === "true" ? "290" : "360"}
          height="1"
        />
        <rect
          x={web === "true" ? "150" : "20"}
          y={web === "true" ? "35" : "60"}
          rx="2"
          ry="2"
          width={web === "true" ? "95" : "360"}
          height={web === "true" ? "35" : "150"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "80" : "230"}
          rx="2"
          ry="2"
          width={web === "true" ? "105" : "150"}
          height={web === "true" ? "5" : "6"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "90" : "250"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "70"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "115" : "330"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "70"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "140" : "410"}
          rx="2"
          ry="2"
          width={web === "true" ? "290" : "360"}
          height={web === "true" ? "20" : "70"}
        />

        {web === "true" ? null : (
          <rect
            x={web === "true" ? "55" : "20"}
            y={web === "true" ? "115" : "490"}
            rx="2"
            ry="2"
            width={web === "true" ? "95" : "360"}
            height={web === "true" ? "35" : "70"}
          />
        )}

        {web === "true" ? null : (
          <rect
            x={web === "true" ? "55" : "20"}
            y={web === "true" ? "115" : "570"}
            rx="2"
            ry="2"
            width={web === "true" ? "95" : "360"}
            height={web === "true" ? "35" : "70"}
          />
        )}
      </ContentLoader>
      <div style={{ position: "absolute", bottom: 0, width: "100%" }}>
        <BottomTab />
      </div>
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
