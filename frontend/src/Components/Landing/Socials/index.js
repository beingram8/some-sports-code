import React from "react";
import "./styles.scss";
import { BsYoutube } from "react-icons/bs";
import { BsFacebook } from "react-icons/bs";
import { BsInstagram } from "react-icons/bs";

const getIcon = (name) => {
  switch (name) {
    case "facebook":
      return (
        <BsFacebook fill="#000000" size={36} style={{ marginTop: "7px" }} />
      );
    case "instagram":
      return (
        <BsInstagram size={36} fill="#000000" style={{ marginTop: "7px" }} />
      );
    case "youtube":
      return (
        <BsYoutube size={38} fill="#000000" style={{ marginTop: "7px" }} />
      );
    default:
      return null;
  }
};

const Socials = ({ dataItem, padding }) => {
  return (
    <div className="socialsContainer">
      <img className="pageImage" src={dataItem.image} alt={dataItem.key} />
      <button
        className="iconButton"
        onClick={() => window.open(dataItem.link, "_blank")}
        style={window.innerWidth > 1024 ? { bottom: padding } : null}
      >
        <div className="iconArea">{getIcon(dataItem.key)}</div>
        {dataItem.buttonText}
      </button>
    </div>
  );
};

export default Socials;
