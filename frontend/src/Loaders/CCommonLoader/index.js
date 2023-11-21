import React from "react";
import ContentLoader from "react-content-loader";
import PropTypes from "prop-types";

const CCommonLoader = (props) => {
  const { web } = props;
  return (
    <div>
      <ContentLoader
        speed={2}
        viewBox={`0 0 400 ${web === "true" ? "250" : "600"}`}
        backgroundColor="#f3f3f3"
        foregroundColor="#ecebeb"
        style={{ position: "fixed" }}
        {...props}
      >
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "10" : "20"}
          rx="2"
          ry="2"
          width={web === "true" ? "30" : "60"}
          height={web === "true" ? "8" : "15"}
        />

        <rect
          x={web === "true" ? "70" : "40"}
          y={web === "true" ? "30" : "50"}
          rx="2"
          ry="2"
          width={web === "true" ? "calc(70% - 15px" : "calc(90% - 25px)"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "40" : "70"}
          rx="2"
          ry="2"
          width={web === "true" ? "70%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "50" : "90"}
          rx="2"
          ry="2"
          width={web === "true" ? "70%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "60" : "110"}
          rx="2"
          ry="2"
          width={web === "true" ? "50%" : "70%"}
          height={web === "true" ? "5" : "8"}
        />

        <rect
          x={web === "true" ? "70" : "40"}
          y={web === "true" ? "75" : "140"}
          rx="2"
          ry="2"
          width={web === "true" ? "calc(70% - 15px" : "calc(90% - 25px)"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "85" : "160"}
          rx="2"
          ry="2"
          width={web === "true" ? "70%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "95" : "180"}
          rx="2"
          ry="2"
          width={web === "true" ? "70%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "105" : "200"}
          rx="2"
          ry="2"
          width={web === "true" ? "68%" : "30%"}
          height={web === "true" ? "5" : "8"}
        />

        <rect
          x={web === "true" ? "70" : "40"}
          y={web === "true" ? "120" : "230"}
          rx="2"
          ry="2"
          width={web === "true" ? "calc(70% - 15px" : "calc(90% - 25px)"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "130" : "250"}
          rx="2"
          ry="2"
          width={web === "true" ? "70%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "140" : "270"}
          rx="2"
          ry="2"
          width={web === "true" ? "70%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "150" : "290"}
          rx="2"
          ry="2"
          width={web === "true" ? "10%" : "45%"}
          height={web === "true" ? "5" : "8"}
        />

        <rect
          x={web === "true" ? "70" : "40"}
          y={web === "true" ? "165" : "320"}
          rx="2"
          ry="2"
          width={web === "true" ? "calc(70% - 15px" : "calc(90% - 25px)"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "175" : "340"}
          rx="2"
          ry="2"
          width={web === "true" ? "70%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "185" : "360"}
          rx="2"
          ry="2"
          width={web === "true" ? "70%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "195" : "380"}
          rx="2"
          ry="2"
          width={web === "true" ? "70%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "205" : "400"}
          rx="2"
          ry="2"
          width={web === "true" ? "50%" : "20%"}
          height={web === "true" ? "5" : "8"}
        />

        <rect
          x={web === "true" ? "70" : "40"}
          y={web === "true" ? "220" : "430"}
          rx="2"
          ry="2"
          width={web === "true" ? "calc(70% - 15px" : "calc(90% - 25px)"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "230" : "450"}
          rx="2"
          ry="2"
          width={web === "true" ? "70%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "240" : "470"}
          rx="2"
          ry="2"
          width={web === "true" ? "70%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "250" : "490"}
          rx="2"
          ry="2"
          width={web === "true" ? "70%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "260" : "510"}
          rx="2"
          ry="2"
          width={web === "true" ? "60%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "260" : "530"}
          rx="2"
          ry="2"
          width={web === "true" ? "60%" : "90%"}
          height={web === "true" ? "5" : "8"}
        />
        <rect
          x={web === "true" ? "55" : "15"}
          y={web === "true" ? "260" : "550"}
          rx="2"
          ry="2"
          width={web === "true" ? "60%" : "80%"}
          height={web === "true" ? "5" : "8"}
        />
        {/* <circle cx="20" cy="20" r="20" /> */}
      </ContentLoader>
    </div>
  );
};

CCommonLoader.propTypes = {
  web: PropTypes.string,
};

CCommonLoader.defaultProps = {
  web: "false",
};

export default CCommonLoader;
