import React, { useEffect } from "react";
import { getWords } from "../../commonFunctions";
import CWebView from "../../Components/CWebView";
import NotificationPopup from "../../Components/NotificationPopup";
import { Setting } from "../../Utils/Setting";

function Help() {
  useEffect(() => {
    document.title = Setting.page_name.HELP;
  }, []);

  return (
    <div>
      <CWebView title={getWords("HELP")} slug={"help"} />
      <NotificationPopup />
    </div>
  );
}

export default Help;
