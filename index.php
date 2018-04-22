S<?php
//require_once('init.php');
$ser="localhost";
$user="root";
$password="WMDBizAssist";
$db="frostburgforward";

// Establishing a connection to the database.
//$con = new mysqli(DB_HOST, DB_USER, DB_PASS);
$con = new mysqli($ser, $user, $password, $db);

// Check the connection to the database.
if($con->connect_error) { die('Connection Failed: ' . $con->connect_error); }
echo 'Connection Successful';

$questions = array();
$question_flow = array();

$sql = "SELECT * FROM questions";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $questions[$row['id']] = $row['question'];
        $question_flow[$row['id']] = $row['flow'];
    }
} else { echo 'No Results'; }

$answers = array();

$sql = "SELECT * FROM answers";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $answers[$row['question_id']][] = $row['answer'];
    }
} else { echo 'No Results'; }

$resources = array();
$sql = "SELECT * FROM resources";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $answers[$row['answer_id']] = array($row['link'], $row['text']);
    }
} else { echo 'No Results'; }
?>

<!doctype html>
<html>
    <head>
        <title id="abc">Frostburg Forward - Business Tool</title>

        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,700" rel="stylesheet">
        <link href="mockup/css/app.css" type="text/css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>

    <body>
        <header>
            <nav class="grid-fixed">
                <a href="pages/index.html" class="title">Frostburg Forward</a>

                <ul>
                    <li><a href="pages/index.html">Home</a></li>
                    <li><a href="pages/start.html">Business</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="single color">
                <div class="grid-fixed cf">
                    <div id="questions" class="c8">
                        <h1 id="question"></h1>
                        <div id="answerButtons">
                          <!--<div class="button" onclick="updateQuestion()"><a class="full">Yes</a></div>
                          <div class="button"><a href="start-q2b.html" class="full">No</a></div>-->
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <script>
          var questions = <?php echo json_encode($questions); ?>;
          var answers = <?php echo json_encode($answers); ?>;
          var resources = <?php echo json_encode($resources); ?>;

          var currQuestion;
          var questionKeys = Object.keys(questions);
          var answerKeys = Object.keys(answers);

          function getQuestion(index){
            console.log(index);
            console.log(questions.length);
              if(index <= questionKeys.length){
                document.getElementById("question").innerHTML = questions[index];
                console.log(questions[index]);
              } else {
                document.getElementById("question").innerHTML = "";
              }
          }

          function updatePage(){
              var parent = document.getElementById("answerButtons");
              parent.innerHTML = '';
              currQuestion++;
              getQuestion(currQuestion);
              setAnswers(currQuestion);
          }

          function setAnswers(questionIndex){
              for(var i=0; i < answers[questionIndex].length; i++){
                  var div = document.createElement("div");
                  var a = document.createElement("a");
                  a.className = "full";
                  a.innerHTML = answers[questionIndex][i];
                  a.addEventListener("click", updatePage);
                  div.className = "button";
                  div.appendChild(a);
                  //div.onclick = updateQuestion();
                  //var btn = $('<button/>', {text: answers[questionIndex][i], id: i+1, click: updateQuestion()});
                  document.getElementById("answerButtons").appendChild(div);
              }
          }

          $(document).ready(function(){
              currQuestion = 1;
              getQuestion(currQuestion);
              setAnswers(currQuestion);
          });
        </script>
        <footer></footer>
    </body>
</html>

<script></script>
