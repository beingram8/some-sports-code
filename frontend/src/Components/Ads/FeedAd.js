import React from "react";
import PropTypes from "prop-types";
import { Setting } from "../../Utils/Setting";

function FeedAd(props) {
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
        data-ad-format="fluid"
        data-adtest="on"
        data-ad-layout-key="-6s+ea+2i-1i-4k"
        data-ad-client={Setting.ADS_CLIENT_ID}
        data-ad-slot={adUnit}
      ></ins>
    </div>
  );
}

FeedAd.propTypes = {
  adUnit: PropTypes.string,
};

FeedAd.defaultProps = {
  adUnit: "",
};

export default FeedAd;
