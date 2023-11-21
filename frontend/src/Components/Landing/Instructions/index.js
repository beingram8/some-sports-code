import React from "react";
import "./styles.scss";

function Instructions() {
  return (
    <div className="instructionsContainer">
      <label className="caption">
        segui le notizie, vota le partite della tua squadra del cuore e vinci
        subito
      </label>
      <h3 className="heading3">come funziona?</h3>
      <div className="instructionRow">
        {instructions.map((dataItem, index) => {
          return (
            <div className="bulletPoint">
              <label className="bulletText">{index + 1}.</label>
              <p className="para">{dataItem}</p>
            </div>
          );
        })}
      </div>
      <p>
        Partecipa alle dirette, guarda i video, commenta sul blog e gioca con il
        nostro Quiz!
      </p>
    </div>
  );
}

export default Instructions;

const instructions = [
  "Seleziona la tua squadra del cuore",
  "Vota le partite di Serie A: ricorda che hai 24h di tempo dal triplice fischio per fare la tua pagella",
  "Torna dopo 24h e scopri il tuo punteggio: ogni punto equivale a 1 Fan Coin e se hai vinto, ne guadagni ancora di più!",
  "Spendi i Fan Coin che ti sei meritato nella sezione VINCI riscattando fantastici premi: buoni Amazon, merchandising, buoni sconto e gift card!",
  "Accumula punti, scala la classifica e Sali di livello per votare sempre più partite!",
  "Non ti basta? Ricorda che puoi guadagnare Fan Coin giocando ed interagendo con altri Tifosi nella sezione TIFA",
];
