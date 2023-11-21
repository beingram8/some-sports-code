import React from "react";
import { Setting } from "../../Utils/Setting";

function AMPAutoAd() {
  return (
    <div>
      <amp-auto-ads
        type="adsense"
        data-ad-client={Setting.ADS_CLIENT_ID}
      ></amp-auto-ads>
    </div>
  );
}

AMPAutoAd.propTypes = {};

AMPAutoAd.defaultProps = {};

export default AMPAutoAd;
