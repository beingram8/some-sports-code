import React from "react";
import PropTypes from "prop-types";
import { Setting } from "../../Utils/Setting";

function DisplayAd(props) {
  const { adUnit } = props;

  return (
    <div
      style={{
        padding: "10px",
      }}
    >
      <ins
        class="adsbygoogle"
        style={{ display: "block" }}
        data-ad-client={Setting.ADS_CLIENT_ID}
        data-ad-slot={adUnit}
        data-ad-format="auto"
        data-full-width-responsive="true"
        data-adtest="on"
      ></ins>
    </div>
  );
}

DisplayAd.propTypes = {
  adUnit: PropTypes.string,
};

DisplayAd.defaultProps = {
  adUnit: "",
};

export default DisplayAd;
