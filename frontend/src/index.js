import React from "react";
import ReactDOM from "react-dom";
import { Provider } from "react-redux";
import { store, persistor } from "./Redux/store/configureStore";
import { PersistGate } from "redux-persist/integration/react";
import App from "./App";

import "./index.scss";

ReactDOM.render(
  <Provider store={store}>
    <PersistGate loading={null} persistor={persistor}>
      <React.StrictMode>
        <App />
      </React.StrictMode>
    </PersistGate>
  </Provider>,
  document.getElementById("root")
);
