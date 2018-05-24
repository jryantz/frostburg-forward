
(function() {

  
  
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
