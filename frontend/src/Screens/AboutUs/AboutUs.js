import React, { useEffect } from "react";
import { Setting } from "../../Utils/Setting";
import { getWords } from "../../commonFunctions";
import CWebView from "../../Components/CWebView";
import NotificationPopup from "../../Components/NotificationPopup";

function AboutUs() {
  useEffect(() => {
    document.title = Setting.page_name.ABOUT_US;
  }, []);

  return (
    <div>
      <CWebView title={getWords("ABOUT_US")} slug={"mission"} />
      <NotificationPopup />
    </div>
  );
}

export default AboutUs;
