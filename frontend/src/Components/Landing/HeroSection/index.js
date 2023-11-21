import React from "react";
import "./styles.scss";

function Hero() {
  return (
    <div className="heroContainer">
      <div className="imageSection">
        <label className="headerTitle">FAN RATING!</label>
        <img
          className="featuredImage"
          src="./landing-page-smartphone.png"
          alt="smartphone"
        />
      </div>
      <div className="contentSection">
        <img className="logo" src="/logo-serie-a.png" alt="serie-a-logo" />
        <p className="caption">
          segui le notizie, vota le partite della tua squadra del cuore e vinci
          subito
        </p>
        <h1 className="heading">Tifa.</h1>
        <h1 className="heading">Vota.</h1>
        <h1 className="heading">Vinci.</h1>
        <p className="para">
          Noi di Fan Rating abbiamo come mission coinvolgerei Tifosi di Serie A
          in un gioco che valorizzi la loro opinione! Il nostro obiettivo creare
          una Casa per tutti i Tifosi, un luogo sicuro dove la vostra opinione
          venga valorizzata realmente e trasmessa su diverse piattaforme, per
          comunicare ciò che davvero conta: quello che vogliono i Tifosi!
        </p>
        <p className="para">
          Segui i nostri contenuti editoriali, la Fan Rating TV e il nostro blog
          e diventa anche tu un vero tifoso!
        </p>
        <button
          className="actionButton"
          onClick={() => window.open("https://fanratingweb.com/", "_blank")}
        >
          Inizia subito!
        </button>
      </div>
    </div>
  );
}

export default Hero;
