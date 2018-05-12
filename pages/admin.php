<?php
require_once('app/init.php');
$ser="localhost";
$user="root";
$password="WMDBizAssist";
$db="frostburgforward";

// Establishing a connection to the database.
$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//$con = new mysqli($ser, $user, $password, $db);

// Check the connection to the database.
if($con->connect_error) { die('Connection Failed: ' . $con->connect_error); }
echo 'Connection Successful';

$questions = array();

$sql = "SELECT * FROM questions";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $questions[] = array($row['id'], $row['question'], $row['flow']);
    }
} else { echo 'No Results'; }

$answers = array();

$sql = "SELECT * FROM answers";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $answers[] = array($row['id'], $row['question_id'], $row['answer']);
    }
} else { echo 'No Results'; }

$resources = array();

$sql = "SELECT * FROM resources";
$result = $con->query($sql);


?>
<html>
  <head>
    <script src="https://www.gstatic.com/firebasejs/4.13.0/firebase.js"></script>
    <script>
    // Initialize Firebase
    var config = {
      apiKey: "AIzaSyBWxn3avVxaZly4ZRfcJ47Ddv00nOL5L1s",
      authDomain: "wmd-bizassist.firebaseapp.com",
      databaseURL: "https://wmd-bizassist.firebaseio.com",
      projectId: "wmd-bizassist",
      storageBucket: "",
      messagingSenderId: "25335187061"
    };
    firebase.initializeApp(config);
    </script>
    <script src="https://www.gstatic.com/firebasejs/4.13.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.13.0/firebase-auth.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-118657595-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-118657595-1');
    </script>

    <title>WMD BizAssist Admin</title>

    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,700" rel="stylesheet">
    <link href="css/app.css" type="text/css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  </head>
  <body>
    <header>
        <nav class="grid-fixed">
            <a href="../index.html" class="title">Western Maryland BizAssist Admin</a>

            <ul>
                <li><a href="../index.html">Home</a></li>
                <li><a href="start.html">Business</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="single color">
            <div class="grid-fixed cf">
                <div class="c12">
                  <ul id="menu">
                      <li><div class="button" id="questions" onclick="adminView(this);"><a href="#">Questions</a></div></li>
                      <li><div class="button" id="answers" onclick="adminView(this);"><a href="#">Answers</a></div></li>
                      <li><div class="button" id="resources" onclick="adminView(this);"><a href="#">Resources</a></div></li>
                  </ul>
                </div>
            </div>
        </section>
        <section>
          <div>
            <ul id="menu">
              <li>
              <div id="col_1_div">
                <select id="col_1" name="col_1"></select>
                <ul>
                  <button>Add</button>
                  <button>Modify</button>
                  <button>Remove</button>
                </ul>
              </div>
              </li>
              <li>
              <div id="col_2_div">
                <select id="col_2" name="col_2"></select>
                <ul>
                  <button>Add</button>
                  <button>Modify</button>
                  <button>Remove</button>
                </ul>
              </div>
              </li>
              <li>
              <div id="col_3_div">
                <select id="col_3" name="col_3"></select>
                <ul>
                  <button>Add</button>
                  <button>Modify</button>
                  <button>Remove</button>
                </ul>
              </div>
              </li>
            </ul>
          </div>
        </section>
        <section>

        </section>
    </main>
    <script>

        var questions = <?php echo json_encode($questions); ?>;
        var answers = <?php echo json_encode($answers); ?>;
        var resources = <?php echo json_encode($resources); ?>;

        var questionKeys = Object.keys(questions);
        var answerKeys = Object.keys(answers);
        var resourceKeys = Object.keys(resources);

        $(document).ready(function(){

        });

        function adminView(element){
          var id = element.id;
          clearAll();
          switch(id){
            case 'questions':
              fillQuestions("col_1");
              break;
            case 'answers':
              fillAnswers("col_1");
              break;
            case 'resources':
              console.log("Resources");
              break;
            default:
          }
        }

        function fillQuestions(id){
          if(!document.getElementById(id)){
            var sel = document.createElement("select");
          } else {
            var sel = document.getElementById(id);
          }
          sel.size = questionKeys.length;
          for(var i=0; i<questions.length; i++){
            var option = document.createElement("option");
            option.innerHTML = questions[i][0] + ". " + questions[i][1];
            sel.add(option);
          }
        }

        function fillAnswers(id){
          if(!document.getElementById(id)){
            var sel = document.createElement("select");
          } else {
            var sel = document.getElementById(id);
          }
          sel.size = answerKeys.length;
          for(var i=0; i<answers.length; i++){
            var option = document.createElement("option");
            option.innerHTML = answers[i][0] + ". " + answers[i][2];
            sel.add(option);
          }
        }

        function fillResources(id){
          if(!document.getElementById(id)){
            var sel = document.createElement("select");
          } else {
            var sel = document.getElementById(id);
          }
          sel.size = resourceKeys.length;
          for(var i=0; i<questions.length; i++){
            var option = document.createElement("option");
            option.innerHTML = resources[i][0] + ". " + resources[i][2];
            sel.add(option);
          }
        }

        function clearAll(){
          document.getElementById("col_1").innerHTML = "";
          document.getElementById("col_2").innerHTML = "";
          document.getElementById("col_3").innerHTML = "";
        }
    </script>
  </body>
</html>
