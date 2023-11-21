import React from "react";
import { IconContext } from "react-icons";
import { AiOutlineMail } from "react-icons/ai";
import { getWords } from "../../commonFunctions";
import "./styles.scss";

export default function LoginWithMail(props) {
  const { onClick } = props;

  return (
    <div>
      <div
        className="emailLogButtonSty"
        onClick={() => {
          onClick();
        }}
      >
        <div className="appleIconSty">
          <IconContext.Provider
            value={{
              color: "#FFFFFF",
            }}
          >
            <AiOutlineMail />
          </IconContext.Provider>
        </div>
        <span className="appleButtonTextSty">{getWords("SIGNUP_EMAIL")}</span>
      </div>
    </div>
  );
}
