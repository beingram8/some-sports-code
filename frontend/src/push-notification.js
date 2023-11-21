import firebase from "firebase/app";
import "firebase/messaging";
import authActions from "./Redux/reducers/auth/actions";
import { store } from "./Redux/store/configureStore";

const vKey =
  "BKKmEAc4hFLh5xDcbJX8ZfIvpcvwQ02cddan8_DYVPmMcJYr23kkLvdpsFSic5mnxvJSYTOQARHFLTKHQtCSldY";

export const askForPermissionToReceiveNotifications = async () => {
  if (firebase.messaging.isSupported()) {
    const messaging = firebase.messaging();
    try {
      const token = await messaging.getToken({ vapidKey: vKey });
      store.dispatch(authActions.setUserUUID(token));
      console.log("Your token is:", token);
    } catch (error) {
      console.log("Catch block =======================>", error.code);
      console.error(error);
    }
  }
};
