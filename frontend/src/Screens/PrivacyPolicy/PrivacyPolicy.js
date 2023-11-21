import React, { useEffect } from "react";
import { getWords } from "../../commonFunctions";
import CWebView from "../../Components/CWebView";
import { Setting } from "../../Utils/Setting";
import NotificationPopup from "../../Components/NotificationPopup";

function PrivacyPolicy() {
  useEffect(() => {
    document.title = Setting.page_name.PRIVACY_POLICY;
  }, []);

  return (
    <div>
      <CWebView title={getWords("PRIVACY_POLICY")} slug={"privacy_policy"} />
      <NotificationPopup />
    </div>
  );
}

export default PrivacyPolicy;
