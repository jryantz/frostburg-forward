
src="https://cdn.firebase.com/libs/firebaseui/2.5.1/firebaseui.js">

(function() {

  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyBWxn3avVxaZly4ZRfcJ47Ddv00nOL5L1s",
    authDomain: "wmd-bizassist.firebaseapp.com",
    databaseURL: "https://wmd-bizassist.firebaseio.com",
    projectId: "wmd-bizassist",
    storageBucket: "wmd-bizassist.appspot.com",
    messagingSenderId: "25335187061"
  };
  firebase.initializeApp(config);
  
  // Gathering Elements
  var Email = document.getElementById('Email');
  var Password = document.getElementById('Passoword');
  var btnSubmit = document.getElementById('Submit');
  
  // Adding a click event
  btnSubmit.addEventListener('click', e => {
	  // Get email and password fields
	  var email = Email.value;
	  var pass = Password.value;
	  var auth = firebase.auth();
	  // Sign In
	  var promise = auth.signInWithEmailAndPassword(email,pass);
	  promise.catch(e => console.log(e.message));
	  
  });
  
  // Realtime Listener
  firebase.auth().onAuthStateChanged(firebaseUser => {
	  if(firebaseUser){
		  console.log(firebaseUser);
		  btnLogout.classList.remove('hide');
	  } else {
		  console.log('not logged in');
		  btnLogout.classList.add('hide');
	  }
  });
  
  
  
  
  
} );