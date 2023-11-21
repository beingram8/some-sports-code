import React from "react";
import "./styles.scss";
import "../../Styles/common.scss";
import HeroSection from "../../Components/Landing/HeroSection";
import Socials from "../../Components/Landing/Socials";
import Instructions from "../../Components/Landing/Instructions";

function LandingPage() {
  return (
    <div className="landingPageContainer">
      <HeroSection />
      <div className="socialContainer">
        {socialData.map((dataItem, index) => {
          return (
            <Socials dataItem={dataItem} padding={buttonPaddings[index]} />
          );
        })}
      </div>
      <Instructions />
    </div>
  );
}

export default LandingPage;

const socialData = [
  {
    id: 0,
    key: "facebook",
    link: "https://www.facebook.com/FanRating",
    buttonText: "visita la pagina Facebook!",
    image: "./fan-rating-facebook-page.png",
  },
  {
    id: 1,
    key: "instagram",
    link: "https://www.instagram.com/fan.rating/",
    buttonText: "seguici su Instagram",
    image: "./fan-rating-instagram-page.png",
  },
  {
    id: 2,
    key: "youtube",
    link: "https://www.youtube.com/c/FanRating",
    buttonText: "guardaci su YouTube",
    image: "./fan-rating-youtube-page.png",
  },
];

const buttonPaddings = ["190px", "400px", "20px"];
