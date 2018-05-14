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

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $resources[] = array($row['id'], $row['answer_id'], utf8_encode($row['text']), utf8_encode($row['link']), utf8_encode($row['condition']), $row['tag']);
    }
} else { echo 'No Results'; }

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
        <section id="content_section">
          <div>
            <ul id="select_content" class="select_content">
              <li>
              <div id="col_1_div">
                <select id="col_1" name="col_1" size=20></select>
              </div>
              </li>
              <li>
              <div id="col_2_div">
                <select id="col_2" name="col_2" size=20></select>
              </div>
              </li>
              <li>
              <div id="col_3_div">
                <select id="col_3" name="col_3" size=20></select>
              </div>
              </li>
            </ul>
            <ul id="action_buttons">
              <button onclick="add();">Add</button>
              <button onclick="modify();">Modify</button>
              <button onclick="remove();">Remove</button>
            </ul>
            <section id="content_interface">
              <form id="interface_form">
                <div id="q_add" style="display:none">
                  <label for="q_num">Question Number</label>
                  <input type="number" name="question_number" id="q_num"><br>
                  <label for="q_text">Question Text</label>
                  <input type="text" name="question_text" id="q_text"><br>
                  <label for="cond_chk">Conditions</label>
                  <input type="checkbox" name="condition_chk" id="cond_chk"><br>
                  <div id="cond_div">
                  </div>
                  <input type="submit" name="submit_add" id="submit_q" value="Add Question">
                </div>
                <div id="q_modify" style="display:none">
                  <label for="q_num">Question Number</label>
                  <input type="number" name="question_number" id="q_num"><br>
                  <label for="q_text">Question Text</label>
                  <input type="text" name="question_text" id="q_text"><br>
                  <div id="cond_div">
                  </div>
                  <input type="submit" name="submit_modify" id="submit_mod_q" value="Submit Changes">
                </div>
                <div id="q_remove" style="display:none">
                  <label for="q_num">Question Number</label>
                  <input type="number" name="question_number" id="q_num" readonly><br>
                  <label for="q_text">Question Text</label>
                  <input type="text" name="question_text" id="q_text" readonly><br>
                  <div id="cond_div" readonly>
                  </div>
                  <input type="submit" name="submit_delete" id="submit_del_q" value="Confirm Delete">
                </div>
                <div id="a_add" style="display:none">
                  <label for="q_num">For Question</label>
                  <input type="number" name="question_number" id="q_num"><br>
                  <label for="a_text">Answer Text</label>
                  <input type="text" name="answer_text" id="a_text"><br>
                  <input type="submit" name="submit_add" id="submit_a" value="Add Answer">
                </div>
                <div id="a_modify" style="display:none">
                  <label for="q_num">For Question</label>
                  <input type="number" name="question_number" id="q_num"><br>
                  <label for="a_text">Answer Text</label>
                  <input type="text" name="answer_text" id="a_text"><br>
                  <input type="submit" name="submit_add" id="submit_mod_a" value="Submit Changes">
                </div>
                <div id="a_remove" style="display:none">
                  <label for="q_num">For Question</label>
                  <input type="number" name="question_number" id="q_num" readonly><br>
                  <label for="a_text">Answer Text</label>
                  <input type="text" name="answer_text" id="a_text" readonly><br>
                  <input type="submit" name="submit_add" id="submit_del_a" value="Confirm Delete">
                </div>
                <div id="r_add" style="display:none">
                </div>
                <div id="r_modify" style="display:none">
                </div>
                <div id="r_remove" style="display:none">
                </div>
              </form>
            </section>
          </div>
        </section>
        <section>

        </section>
    </main>
    <script>

        var currentView = "";

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
              currentView = "questions";
              fillQuestions("col_1");
              break;
            case 'answers':
              currentView = "answers";
              fillAnswers("col_1");
              break;
            case 'resources':
              currentView = "resources";
              fillResources("col_1");
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
          //sel.size = questionKeys.length;
          for(var i=0; i<questions.length; i++){
            var option = document.createElement("option");
            option.id = "q_"+i;
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
          //sel.size = answerKeys.length;
          for(var i=0; i<answers.length; i++){
            var option = document.createElement("option");
            option.id = "a_"+i;
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
          //sel.size = resourceKeys.length;
          for(var i=0; i<resources.length; i++){
            var option = document.createElement("option");
            option.id = "r_"+i;
            if(resources[i][2] != ""){
              option.innerHTML = resources[i][0] + ". " + resources[i][2];
            } else {
              option.innerHTML = resources[i][0] + ". [Resource text determined by condition. Click on this resource and select 'Modify' below to see these conditions.]" ;
            }
            sel.add(option);
          }
        }

        function clearAll(){
          document.getElementById("col_1").innerHTML = "";
          document.getElementById("col_2").innerHTML = "";
          document.getElementById("col_3").innerHTML = "";
        }

        function add(){
          switch(currentView){
            case "questions":
              hideAll();
              var add_div = document.getElementById("q_add");
              add_div.style.display = "block";
              break;
            case "answers":
              hideAll();
              var add_div = document.getElementById("a_add");
              add_div.style.display = "block";
              break;
            case "resources":
              hideAll();
              var add_div = document.getElementById("r_add");
              add_div.style.display = "block";
              break;
            default:
          }
        }

        function modify(){
          switch(currentView){
            case "questions":
              hideAll();
              var add_div = document.getElementById("q_modify");
              add_div.style.display = "block";
              break;
            case "answers":
              hideAll();
              var add_div = document.getElementById("a_modify");
              add_div.style.display = "block";
              break;
            case "resources":
              hideAll();
              var add_div = document.getElementById("r_modify");
              add_div.style.display = "block";
              break;
            default:
          }
        }

        function remove(){
          switch(currentView){
            case "questions":
              hideAll();
              var add_div = document.getElementById("q_remove");
              add_div.style.display = "block";
              break;
            case "answers":
              hideAll();
              var add_div = document.getElementById("a_remove");
              add_div.style.display = "block";
              break;
            case "resources":
              hideAll();
              var add_div = document.getElementById("r_remove");
              add_div.style.display = "block";
              break;
            default:
          }
        }

        function hideAll(){
          var add_div = document.getElementById("q_add");
          add_div.style.display = "none";
          var modify_div = document.getElementById("q_modify");
          modify_div.style.display = "none";
          var remove_div = document.getElementById("q_remove");
          remove_div.style.display = "none";
          add_div = document.getElementById("a_add");
          add_div.style.display = "none";
          modify_div = document.getElementById("a_modify");
          modify_div.style.display = "none";
          remove_div = document.getElementById("a_remove");
          remove_div.style.display = "none";
          add_div = document.getElementById("r_add");
          add_div.style.display = "none";
          modify_div = document.getElementById("r_modify");
          modify_div.style.display = "none";
          remove_div = document.getElementById("r_remove");
          remove_div.style.display = "none";
        }
    </script>
  </body>
</html>
