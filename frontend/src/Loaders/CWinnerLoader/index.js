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
        viewBox={`0 0 400 ${web === "true" ? "300" : "500"}`}
        backgroundColor="#f3f3f3"
        foregroundColor="#ecebeb"
        style={{ position: "fixed" }}
        {...props}
      >
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "10" : "25"}
          rx="2"
          ry="2"
          width={web === "true" ? "35" : "70"}
          height={web === "true" ? "10" : "15"}
        />
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "31" : "62"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "80"}
          height={web === "true" ? "7" : "12"}
        />
        <rect
          x={web === "true" ? "325" : "345"}
          y={web === "true" ? "31" : "62"}
          rx="2"
          ry="2"
          width={web === "true" ? "25" : "35"}
          height={web === "true" ? "7" : "12"}
        />
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "45" : "90"}
          rx="2"
          ry="2"
          width={web === "true" ? "95" : "200"}
          height={web === "true" ? "50" : "100"}
        />
        <rect
          x={web === "true" ? "155" : "240"}
          y={web === "true" ? "45" : "90"}
          rx="2"
          ry="2"
          width={web === "true" ? "95" : "140"}
          height={web === "true" ? "50" : "100"}
        />
        {web === "true" ? (
          <rect x={"255"} y={"45"} rx="2" ry="2" width={"95"} height={"50"} />
        ) : null}
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "106" : "215"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "70"}
          height={web === "true" ? "7" : "12"}
        />
        <rect
          x={web === "true" ? "325" : "345"}
          y={web === "true" ? "106" : "215"}
          rx="2"
          ry="2"
          width={web === "true" ? "25" : "35"}
          height={web === "true" ? "7" : "12"}
        />
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "120" : "245"}
          rx="2"
          ry="2"
          width={web === "true" ? "95" : "200"}
          height={web === "true" ? "50" : "100"}
        />
        <rect
          x={web === "true" ? "155" : "240"}
          y={web === "true" ? "120" : "245"}
          rx="2"
          ry="2"
          width={web === "true" ? "95" : "140"}
          height={web === "true" ? "50" : "100"}
        />
        {web === "true" ? (
          <rect x={"255"} y={"120"} rx="2" ry="2" width={"95"} height={"50"} />
        ) : null}
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "181" : "370"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "70"}
          height={web === "true" ? "7" : "12"}
        />
        <rect
          x={web === "true" ? "325" : "345"}
          y={web === "true" ? "181" : "370"}
          rx="2"
          ry="2"
          width={web === "true" ? "25" : "35"}
          height={web === "true" ? "7" : "12"}
        />
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "195" : "400"}
          rx="2"
          ry="2"
          width={web === "true" ? "95" : "200"}
          height={web === "true" ? "50" : "100"}
        />
        <rect
          x={web === "true" ? "155" : "240"}
          y={web === "true" ? "195" : "400"}
          rx="2"
          ry="2"
          width={web === "true" ? "95" : "140"}
          height={web === "true" ? "50" : "100"}
        />
        {web === "true" ? (
          <rect x={"255"} y={"195"} rx="2" ry="2" width={"95"} height={"50"} />
        ) : null}
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
