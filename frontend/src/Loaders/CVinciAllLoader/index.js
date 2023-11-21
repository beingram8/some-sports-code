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
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "10" : "25"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "87%"}
          height={web === "true" ? "45" : "120"}
        />
        <rect
          x={web === "true" ? "150" : "25"}
          y={web === "true" ? "10" : "195"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "87%"}
          height={web === "true" ? "45" : "120"}
        />
        <rect
          x={web === "true" ? "245" : "25"}
          y={web === "true" ? "10" : "365"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "87%"}
          height={web === "true" ? "45" : "120"}
        />
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "58" : "155"}
          rx="2"
          ry="2"
          width={web === "true" ? "25" : "50"}
          height={web === "true" ? "10" : "20"}
        />
        <rect
          x={web === "true" ? "150" : "25"}
          y={web === "true" ? "58" : "325"}
          rx="2"
          ry="2"
          width={web === "true" ? "25" : "50"}
          height={web === "true" ? "10" : "20"}
        />
        <rect
          x={web === "true" ? "245" : "25"}
          y={web === "true" ? "58" : "495"}
          rx="2"
          ry="2"
          width={web === "true" ? "25" : "50"}
          height={web === "true" ? "10" : "20"}
        />
        {web === "true" ? (
          <rect
            x={web === "true" ? "55" : "25"}
            y={web === "true" ? "75" : "115"}
            rx="2"
            ry="2"
            width={web === "true" ? "90" : "110"}
            height={web === "true" ? "45" : "55"}
          />
        ) : null}
        {web === "true" ? (
          <rect
            x={web === "true" ? "150" : "145"}
            y={web === "true" ? "75" : "115"}
            rx="2"
            ry="2"
            width={web === "true" ? "90" : "110"}
            height={web === "true" ? "45" : "55"}
          />
        ) : null}
        {web === "true" ? (
          <rect
            x={web === "true" ? "245" : "265"}
            y={web === "true" ? "75" : "115"}
            rx="2"
            ry="2"
            width={web === "true" ? "90" : "110"}
            height={web === "true" ? "45" : "55"}
          />
        ) : null}
        {web === "true" ? (
          <rect
            x={web === "true" ? "55" : "25"}
            y={web === "true" ? "123" : "25"}
            rx="2"
            ry="2"
            width={web === "true" ? "25" : "70"}
            height={web === "true" ? "10" : "15"}
          />
        ) : null}
        {web === "true" ? (
          <rect
            x={web === "true" ? "150" : "25"}
            y={web === "true" ? "123" : "25"}
            rx="2"
            ry="2"
            width={web === "true" ? "25" : "70"}
            height={web === "true" ? "10" : "15"}
          />
        ) : null}
        {web === "true" ? (
          <rect
            x={web === "true" ? "245" : "25"}
            y={web === "true" ? "123" : "25"}
            rx="2"
            ry="2"
            width={web === "true" ? "25" : "70"}
            height={web === "true" ? "10" : "15"}
          />
        ) : null}
        {web === "true" ? (
          <rect
            x={web === "true" ? "55" : "25"}
            y={web === "true" ? "140" : "25"}
            rx="2"
            ry="2"
            width={web === "true" ? "90" : "70"}
            height={web === "true" ? "45" : "15"}
          />
        ) : null}
        {web === "true" ? (
          <rect
            x={web === "true" ? "55" : "25"}
            y={web === "true" ? "188" : "25"}
            rx="2"
            ry="2"
            width={web === "true" ? "25" : "70"}
            height={web === "true" ? "10" : "15"}
          />
        ) : null}
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
