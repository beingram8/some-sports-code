import React from "react";
import "./styles.scss";
import "../../Styles/common.scss";
import Header from "../../Components/Header";
import { getWords } from "../../commonFunctions";
import { createBrowserHistory } from "history";

function BrokenPage() {
  const history = createBrowserHistory({ forceRefresh: true });

  return (
    <div className="MainContainer">
      <Header startup />
      <div className="blankpagemaincontainer">
        <div className="blankPageDiv2">
          <span className="blankpagenotavailable">
            {getWords("SORRY_THIS_PAGE_IS_NOT_AVAILABLE")}
          </span>
          <div className="blankpagesecondlinecontainer">
            <span className="blankpagebrokentext">
              {getWords("THE_LINK_YOU_FOLLOWED_MAY_BE_BROKEN")}
            </span>
            <span
              className="blankpagebrokentextlink"
              onClick={() => {
                history.replace("/rate");
              }}
            >
              &nbsp;{getWords("GO_BACK_TO_FANRATINGWEB")}
            </span>
          </div>
        </div>
      </div>
    </div>
  );
}

export default BrokenPage;
