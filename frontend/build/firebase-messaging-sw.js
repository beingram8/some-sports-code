/* eslint-disable no-undef */
importScripts("https://www.gstatic.com/firebasejs/8.6.7/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.6.7/firebase-messaging.js");

firebase.initializeApp({
  apiKey: "AIzaSyBExPnxSDQ9z5aISvS5knYBSkUKJmEvKVA",
  authDomain: "frpwd-8e595.firebaseapp.com",
  projectId: "frpwd-8e595",
  storageBucket: "frpwd-8e595.appspot.com",
  messagingSenderId: "1034598437002",
  appId: "1:1034598437002:web:4362dedf606d148bd2dd73",
  measurementId: "G-KEF679QMN0",
});

if (firebase.messaging.isSupported()) {
  const messaging = firebase.messaging();

  // Background Push Notifications //
  messaging.onBackgroundMessage(function (payload) {
    // console.log("Received background message ", payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
      body: payload.notification.body,
    };

    window.self.registration.showNotification(
      notificationTitle,
      notificationOptions
    );
  });
}

this.addEventListener("fetch", (event) => {
  if (!navigator.onLine) {
    event.waitUntil(
      this.registration.showNotification("Internet", {
        body: "Internet not working",
      })
    );
    event.respondWith(
      caches.match(event.request).then((resp) => {
        if (resp) {
          return resp;
        }
        let requestUrl = event.request.clone();
        fetch(requestUrl);
      })
    );
  }
});
