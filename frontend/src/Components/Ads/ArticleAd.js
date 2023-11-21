import React from "react";
import PropTypes from "prop-types";
import { Setting } from "../../Utils/Setting";

function ArticleAd(props) {
  const { adUnit } = props;

  return (
    <div
      style={{
        padding: "10px",
      }}
    >
      <ins
        class="adsbygoogle"
        style={{ display: "block", textAlign: "center" }}
        data-ad-layout="in-article"
        data-ad-format="fluid"
        data-ad-client={Setting.ADS_CLIENT_ID}
        data-adtest="on"
        data-ad-slot={adUnit}
      ></ins>
    </div>
  );
}

ArticleAd.propTypes = {
  adUnit: PropTypes.string,
};

ArticleAd.defaultProps = {
  adUnit: "",
};

export default ArticleAd;
