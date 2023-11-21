import React, { useEffect } from "react";
import { getWords } from "../../commonFunctions";
import CWebView from "../../Components/CWebView";
import NotificationPopup from "../../Components/NotificationPopup";
import { Setting } from "../../Utils/Setting";

function TermsCondition() {
  useEffect(() => {
    document.title = Setting.page_name.TERMS_CONDITIONS;
  }, []);

  return (
    <div>
      <CWebView
        title={getWords("TERMS_AND_CONDITION")}
        slug={"terms_condition"}
      />
      <NotificationPopup />
    </div>
  );
}

export default TermsCondition;
