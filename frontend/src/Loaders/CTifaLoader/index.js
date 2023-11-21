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
        viewBox={`0 0 400 ${web === "true" ? "500" : "800"}`}
        backgroundColor="#f3f3f3"
        foregroundColor="#ecebeb"
        style={{ position: "fixed" }}
        {...props}
      >
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "8" : "25"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "60"}
          height={web === "true" ? "7" : "15"}
        />
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "22" : "62"}
          rx="2"
          ry="2"
          width={web === "true" ? "30" : "70"}
          height={web === "true" ? "5" : "12"}
        />
        <circle
          cx={web === "true" ? "70" : "50"}
          cy={web === "true" ? "50" : "110"}
          r={web === "true" ? "15" : "25"}
        />
        <circle
          cx={web === "true" ? "110" : "110"}
          cy={web === "true" ? "50" : "110"}
          r={web === "true" ? "15" : "25"}
        />
        <circle
          cx={web === "true" ? "150" : "170"}
          cy={web === "true" ? "50" : "110"}
          r={web === "true" ? "15" : "25"}
        />
        <circle
          cx={web === "true" ? "190" : "230"}
          cy={web === "true" ? "50" : "110"}
          r={web === "true" ? "15" : "25"}
        />
        <circle
          cx={web === "true" ? "230" : "290"}
          cy={web === "true" ? "50" : "110"}
          r={web === "true" ? "15" : "25"}
        />
        <circle
          cx={web === "true" ? "270" : "350"}
          cy={web === "true" ? "50" : "110"}
          r={web === "true" ? "15" : "25"}
        />
        <circle
          cx={web === "true" ? "310" : "350"}
          cy={web === "true" ? "50" : "110"}
          r={web === "true" ? "15" : "25"}
        />
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "75" : "155"}
          rx="2"
          ry="2"
          width={web === "true" ? "30" : "70"}
          height={web === "true" ? "5" : "12"}
        />
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "88" : "180"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "130"}
          height={web === "true" ? "45" : "70"}
        />
        <rect
          x={web === "true" ? "150" : "165"}
          y={web === "true" ? "88" : "180"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "130"}
          height={web === "true" ? "45" : "70"}
        />
        <rect
          x={web === "true" ? "245" : "305"}
          y={web === "true" ? "88" : "180"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "70"}
          height={web === "true" ? "45" : "70"}
        />
        {web === "true" ? (
          <rect
            x={web === "true" ? "55" : "25"}
            y={web === "true" ? "138" : "180"}
            rx="2"
            ry="2"
            width={web === "true" ? "90" : "130"}
            height={web === "true" ? "45" : "70"}
          />
        ) : null}
        {web === "true" ? (
          <rect
            x={web === "true" ? "150" : "25"}
            y={web === "true" ? "138" : "180"}
            rx="2"
            ry="2"
            width={web === "true" ? "90" : "130"}
            height={web === "true" ? "45" : "70"}
          />
        ) : null}
        {web === "true" ? (
          <rect
            x={web === "true" ? "245" : "25"}
            y={web === "true" ? "138" : "180"}
            rx="2"
            ry="2"
            width={web === "true" ? "90" : "130"}
            height={web === "true" ? "45" : "70"}
          />
        ) : null}
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "198" : "270"}
          rx="2"
          ry="2"
          width={web === "true" ? "30" : "70"}
          height={web === "true" ? "5" : "12"}
        />
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "210" : "295"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "88%"}
          height={web === "true" ? "35" : "100"}
        />
        <rect
          x={web === "true" ? "150" : "25"}
          y={web === "true" ? "210" : "420"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "88%"}
          height={web === "true" ? "35" : "100"}
        />
        <rect
          x={web === "true" ? "245" : "25"}
          y={web === "true" ? "210" : "545"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "88%"}
          height={web === "true" ? "35" : "100"}
        />
        <rect
          x={web === "true" ? "55" : "25"}
          y={web === "true" ? "250" : "670"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "88%"}
          height={web === "true" ? "35" : "100"}
        />
        <rect
          x={web === "true" ? "150" : "405"}
          y={web === "true" ? "250" : "360"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "110"}
          height={web === "true" ? "35" : "50"}
        />
        <rect
          x={web === "true" ? "245" : "405"}
          y={web === "true" ? "250" : "360"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "110"}
          height={web === "true" ? "35" : "50"}
        />
        <rect
          x={web === "true" ? "55" : "405"}
          y={web === "true" ? "290" : "425"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "110"}
          height={web === "true" ? "35" : "50"}
        />
        <rect
          x={web === "true" ? "150" : "405"}
          y={web === "true" ? "290" : "425"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "110"}
          height={web === "true" ? "35" : "50"}
        />
        <rect
          x={web === "true" ? "245" : "405"}
          y={web === "true" ? "290" : "425"}
          rx="2"
          ry="2"
          width={web === "true" ? "90" : "110"}
          height={web === "true" ? "35" : "50"}
        />
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
