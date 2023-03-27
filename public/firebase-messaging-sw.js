importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
   
firebase.initializeApp({
    apiKey: "AIzaSyC-SjmTAA8a263sh83pqBwuDTj5l7UJQRg",
    projectId: "push-notifications-ea922",
    messagingSenderId: "361692257040",
    appId: "1:361692257040:web:fe2910d1d2a72d3054e395"
});
  
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});