import React from "react";
import ContentLoader from "react-content-loader";
import Header from "../../Components/Header/index";
import PropTypes from "prop-types";

const CBuyTokenLoader = (props) => {
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
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "5" : "20"}
          rx="2"
          ry="2"
          width={web === "true" ? "80" : "150"}
          height={web === "true" ? "8" : "20"}
        />

        <rect
          x={web === "true" ? "185" : "170"}
          y={web === "true" ? "25" : "60"}
          rx="2"
          ry="2"
          width={web === "true" ? "25" : "60"}
          height={web === "true" ? "25" : "60"}
        />

        <rect
          x={web === "true" ? "173" : "140"}
          y={web === "true" ? "55" : "140"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "130"}
          height={web === "true" ? "5" : "15"}
        />

        <rect
          x={web === "true" ? "188" : "180"}
          y={web === "true" ? "65" : "170"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "40"}
          height={web === "true" ? "2" : "5"}
        />

        <rect
          x={web === "true" ? "165" : "100"}
          y={web === "true" ? "75" : "200"}
          rx="2"
          ry="2"
          width={web === "true" ? "70" : "215"}
          height={web === "true" ? "9" : "25"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "90" : "260"}
          rx="2"
          ry="2"
          width={web === "true" ? "40" : "80"}
          height={web === "true" ? "5" : "15"}
        />

        <rect
          x={web === "true" ? "300" : "300"}
          y={web === "true" ? "90" : "260"}
          rx="2"
          ry="2"
          width={web === "true" ? "40" : "80"}
          height={web === "true" ? "5" : "15"}
        />

        <rect
          x={web === "true" ? "65" : "40"}
          y={web === "true" ? "100" : "290"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "40"}
          height={web === "true" ? "5" : "10"}
        />

        <rect
          x={web === "true" ? "320" : "340"}
          y={web === "true" ? "100" : "290"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "40"}
          height={web === "true" ? "5" : "10"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "115" : "325"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "120"}
          height={web === "true" ? "10" : "30"}
        />

        <rect
          x={web === "true" ? "320" : "320"}
          y={web === "true" ? "115" : "325"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "60"}
          height={web === "true" ? "10" : "30"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "130" : "370"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "120"}
          height={web === "true" ? "10" : "30"}
        />

        <rect
          x={web === "true" ? "320" : "320"}
          y={web === "true" ? "130" : "370"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "60"}
          height={web === "true" ? "10" : "30"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "145" : "415"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "120"}
          height={web === "true" ? "10" : "30"}
        />

        <rect
          x={web === "true" ? "320" : "320"}
          y={web === "true" ? "145" : "415"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "60"}
          height={web === "true" ? "10" : "30"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "160" : "460"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "120"}
          height={web === "true" ? "10" : "30"}
        />

        <rect
          x={web === "true" ? "320" : "320"}
          y={web === "true" ? "160" : "460"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "60"}
          height={web === "true" ? "10" : "30"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "175" : "505"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "120"}
          height={web === "true" ? "10" : "30"}
        />

        <rect
          x={web === "true" ? "320" : "320"}
          y={web === "true" ? "175" : "505"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "60"}
          height={web === "true" ? "10" : "30"}
        />

        <rect
          x={web === "true" ? "55" : "20"}
          y={web === "true" ? "190" : "550"}
          rx="2"
          ry="2"
          width={web === "true" ? "50" : "120"}
          height={web === "true" ? "10" : "30"}
        />

        <rect
          x={web === "true" ? "320" : "320"}
          y={web === "true" ? "190" : "550"}
          rx="2"
          ry="2"
          width={web === "true" ? "20" : "60"}
          height={web === "true" ? "10" : "30"}
        />

        {/* <circle cx="20" cy="20" r="20" /> */}
      </ContentLoader>
    </div>
  );
};

CBuyTokenLoader.propTypes = {
  web: PropTypes.string,
};

CBuyTokenLoader.defaultProps = {
  web: "false",
};

export default CBuyTokenLoader;
