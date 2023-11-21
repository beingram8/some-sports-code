import React from "react";
import ContentLoader from "react-content-loader";
import PropTypes from "prop-types";

const CTeamlistLoader = (props) => {
  const { web } = props;
  return (
    <div>
      <ContentLoader
        speed={2}
        viewBox={`0 0 400 ${web === "true" ? "500" : "800"}`}
        backgroundColor="#f3f3f3"
        foregroundColor="#ecebeb"
        style={{ position: "fixed" }}
        {...props}
      >
        <rect
          x={web === "true" ? "170" : "160"}
          y={web === "true" ? "20" : "50"}
          rx="2"
          ry="2"
          width={web === "true" ? "70" : "80"}
          height={web === "true" ? "8" : "15"}
        />

        <rect
          x={web === "true" ? "110" : "80"}
          y={web === "true" ? "35" : "85"}
          rx="2"
          ry="2"
          width={web === "true" ? "190" : "240"}
          height={web === "true" ? "5" : "10"}
        />

        <rect
          x={web === "true" ? "145" : "150"}
          y={web === "true" ? "45" : "105"}
          rx="2"
          ry="2"
          width={web === "true" ? "120" : "105"}
          height={web === "true" ? "5" : "10"}
        />

        <rect
          x={web === "true" ? "70" : "40"}
          y={web === "true" ? "65" : "150"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "125" : "220"}
          y={web === "true" ? "65" : "150"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "180" : "40"}
          y={web === "true" ? "65" : "250"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "235" : "220"}
          y={web === "true" ? "65" : "250"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "290" : "40"}
          y={web === "true" ? "65" : "350"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />

        <rect
          x={web === "true" ? "70" : "220"}
          y={web === "true" ? "90" : "350"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "125" : "40"}
          y={web === "true" ? "90" : "450"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "180" : "220"}
          y={web === "true" ? "90" : "450"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "235" : "40"}
          y={web === "true" ? "90" : "550"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "290" : "220"}
          y={web === "true" ? "90" : "550"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />

        <rect
          x={web === "true" ? "70" : "40"}
          y={web === "true" ? "115" : "650"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "125" : "220"}
          y={web === "true" ? "115" : "650"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "180" : "40"}
          y={web === "true" ? "115" : "750"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "235" : "220"}
          y={web === "true" ? "115" : "750"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "290" : "40"}
          y={web === "true" ? "115" : "850"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />

        <rect
          x={web === "true" ? "70" : "220"}
          y={web === "true" ? "140" : "850"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "125" : "40"}
          y={web === "true" ? "140" : "950"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "180" : "220"}
          y={web === "true" ? "140" : "950"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "235" : "40"}
          y={web === "true" ? "140" : "1050"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
        <rect
          x={web === "true" ? "290" : "220"}
          y={web === "true" ? "140" : "1050"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "140"}
          height={web === "true" ? "20" : "70"}
        />
      </ContentLoader>
    </div>
  );
};

CTeamlistLoader.propTypes = {
  web: PropTypes.string,
};

CTeamlistLoader.defaultProps = {
  web: "false",
};

export default CTeamlistLoader;
