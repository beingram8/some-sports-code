import React from "react";
import ContentLoader from "react-content-loader";
import Header from "../../Components/Header/index";
import PropTypes from "prop-types";

const CPlayerProfileLoader = (props) => {
  const { web } = props;
  return (
    <div>
      <Header isSubScreen={true} />
      <ContentLoader
        speed={2}
        viewBox={`0 0 400 ${web === "true" ? "500" : "800"}`}
        backgroundColor="#f3f3f3"
        foregroundColor="#ecebeb"
        style={{ position: "fixed" }}
        {...props}
      >
        <rect
          x={web === "true" ? "110" : "0"}
          y={web === "true" ? "0" : "0"}
          rx="2"
          ry="2"
          width={web === "true" ? "45%" : "100%"}
          height={web === "true" ? "90" : "200"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "95" : "215"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "80"}
          height={web === "true" ? "8" : "10"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "108" : "235"}
          rx="2"
          ry="2"
          width={web === "true" ? "30" : "50"}
          height={web === "true" ? "5" : "10"}
        />

        <circle
          cx={web === "true" ? "310" : "360"}
          cy={web === "true" ? "104" : "230"}
          r={web === "true" ? "12" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "120" : "270"}
          rx="2"
          ry="2"
          width={web === "true" ? "80" : "110"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "150" : "145"}
          y={web === "true" ? "120" : "270"}
          rx="2"
          ry="2"
          width={web === "true" ? "80" : "110"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "245" : "270"}
          y={web === "true" ? "120" : "270"}
          rx="2"
          ry="2"
          width={web === "true" ? "80" : "110"}
          height={web === "true" ? "10" : "20"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "140" : "310"}
          rx="2"
          ry="2"
          width={web === "true" ? "35" : "40"}
          height={web === "true" ? "5" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "150" : "330"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "35"}
          height={web === "true" ? "5" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "160" : "350"}
          rx="2"
          ry="2"
          width={web === "true" ? "30" : "45"}
          height={web === "true" ? "5" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "170" : "370"}
          rx="2"
          ry="2"
          width={web === "true" ? "40" : "40"}
          height={web === "true" ? "5" : "10"}
        />
        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "180" : "390"}
          rx="2"
          ry="2"
          width={web === "true" ? "25" : "25"}
          height={web === "true" ? "5" : "10"}
        />

        <rect
          x={web === "true" ? "285" : "350"}
          y={web === "true" ? "140" : "310"}
          rx="2"
          ry="2"
          width={web === "true" ? "40" : "30"}
          height={web === "true" ? "5" : "10"}
        />
        <rect
          x={web === "true" ? "305" : "350"}
          y={web === "true" ? "150" : "330"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "30"}
          height={web === "true" ? "5" : "10"}
        />
        <rect
          x={web === "true" ? "305" : "350"}
          y={web === "true" ? "160" : "350"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "30"}
          height={web === "true" ? "5" : "10"}
        />
        <rect
          x={web === "true" ? "305" : "350"}
          y={web === "true" ? "170" : "370"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "30"}
          height={web === "true" ? "5" : "10"}
        />
        <rect
          x={web === "true" ? "300" : "350"}
          y={web === "true" ? "180" : "390"}
          rx="2"
          ry="2"
          width={web === "true" ? "25" : "30"}
          height={web === "true" ? "5" : "10"}
        />
      </ContentLoader>
    </div>
  );
};

CPlayerProfileLoader.propTypes = {
  web: PropTypes.string,
};

CPlayerProfileLoader.defaultProps = {
  web: "false",
};

export default CPlayerProfileLoader;
