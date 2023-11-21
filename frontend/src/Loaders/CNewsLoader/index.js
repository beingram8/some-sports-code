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
        viewBox={`0 0 400 ${web === "true" ? "250" : "600"}`}
        backgroundColor="#f3f3f3"
        foregroundColor="#ecebeb"
        style={{ position: "fixed" }}
        {...props}
      >
        <rect
          x={web === "true" ? "122" : "0"}
          y={web === "true" ? "5" : "0"}
          rx="2"
          ry="2"
          width={web === "true" ? "150" : "100%"}
          height={web === "true" ? "85  " : "200"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "95" : "225"}
          rx="2"
          ry="2"
          width={web === "true" ? "35" : "40"}
          height={web === "true" ? "5" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "105" : "260"}
          rx="2"
          ry="2"
          width={web === "true" ? "30" : "80%"}
          height={web === "true" ? "5" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "115" : "285"}
          rx="2"
          ry="2"
          width={web === "true" ? "72%" : "85%"}
          height={web === "true" ? "4" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "125" : "310"}
          rx="2"
          ry="2"
          width={web === "true" ? "72%" : "82%"}
          height={web === "true" ? "4" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "135" : "335"}
          rx="2"
          ry="2"
          width={web === "true" ? "72%" : "88%"}
          height={web === "true" ? "4" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "145" : "360"}
          rx="2"
          ry="2"
          width={web === "true" ? "40%" : "85%"}
          height={web === "true" ? "4" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "145" : "385"}
          rx="2"
          ry="2"
          width={web === "true" ? "40%" : "89%"}
          height={web === "true" ? "4" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "145" : "410"}
          rx="2"
          ry="2"
          width={web === "true" ? "40%" : "89%"}
          height={web === "true" ? "4" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "145" : "435"}
          rx="2"
          ry="2"
          width={web === "true" ? "40%" : "30%"}
          height={web === "true" ? "4" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "145" : "470"}
          rx="2"
          ry="2"
          width={web === "true" ? "40%" : "90%"}
          height={web === "true" ? "4" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "145" : "495"}
          rx="2"
          ry="2"
          width={web === "true" ? "40%" : "90%"}
          height={web === "true" ? "4" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "145" : "520"}
          rx="2"
          ry="2"
          width={web === "true" ? "40%" : "80%"}
          height={web === "true" ? "4" : "10"}
        />
        {/* <circle cx="20" cy="20" r="20" /> */}
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
