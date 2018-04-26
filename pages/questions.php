<?php
require_once('app/init.php');
/*$ser="localhost";
$user="root";
$password="WMDBizAssist";
$db="frostburgforward";*/

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
        $questions[$row['id']] = array($row['question'], $row['flow']);
    }
} else { echo 'No Results'; }

$answers = array();

$sql = "SELECT * FROM answers";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $answers[$row['question_id']][] = array($row['id'], $row['answer']);
    }
} else { echo 'No Results'; }

/*$resources = array();
$sql = "SELECT * FROM resources";
$result = $con->query($sql);

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $answers[$row['answer_id']] = array($row['link'], $row['text']);
    }
} else { echo 'No Results'; }*/
?>

<!doctype html>
<html>
    <head>
        <title id="abc">BizAssist Quick Start Tool</title>

        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,700" rel="stylesheet">
        <link href="css/app.css" type="text/css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        </script>
    </head>

    <body>
        <header>
            <nav class="grid-fixed">
                <a href="../index.html" class="title">Western Maryland BizAssist</a>

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

          var session = {responses:{}}
          var selectedAnswer;

          var questions = <?php echo json_encode($questions); ?>;
          var answers = <?php echo json_encode($answers); ?>;


          var currQuestion;
          var questionKeys = Object.keys(questions);
          var answerKeys = Object.keys(answers);

          //Convert the JSON text contained for question flow cases into JSON objects and store them back into the question array.
          for(var i=1; i<=questionKeys.length; i++){
            questions[i][1] = JSON.parse(questions[i][1]);
          }
          console.log(questions);

          $(document).ready(function(){
              currQuestion = 1;
              getQuestion(currQuestion);
              setAnswers(currQuestion);
          });

          function getQuestion(index){
              //If there is another question to be pulled form the questions array, display its text.
              if(index <= questionKeys.length){
                document.getElementById("question").innerHTML = questions[index][0];
              } else {
                document.getElementById("question").innerHTML = "";
              }
          }

          function updatePage(e){
              //Get information from the caller element.
              var caller = e.target || e.srcElement;
              //Get the answer id from the caller button.
              selectedAnswer = caller.id;
              //Add the answer selected by the user along with its id.
              session.responses[currQuestion] = selectedAnswer;
              var q_case = checkCase(currQuestion);
              if(q_case == null){
                currQuestion++;
              } else {
                switch(q_case){
                  case "END":
                    currQuestion = answerKeys.length+1;
                    break;
                  default:
                    currQuestion = q_case;
                }
              }
              console.log(q_case);
              //Clear all currently visible answer buttons.
              var parent = document.getElementById("answerButtons");
              parent.innerHTML = '';
              //Get the next question.
              getQuestion(currQuestion);
              //Do not attempt to set answers if the currQuestion variable exceeds the number of answer array entries.
              if(currQuestion <= answerKeys.length){  //If there are still available questions, set the answers for the next question.
                setAnswers(currQuestion);
              } else {  //Open the report page after all available questions have been answered.
                localStorage.setItem("session", JSON.stringify(session));
                window.location.href = 'report.php';
              }
          }

          function setAnswers(questionIndex){
              for(var i=0; i < answers[questionIndex].length; i++){
                  //Set variables containing the answer id and text to go into the displayed button.
                  var ans_id = answers[questionIndex][i][0];
                  var ans_text = answers[questionIndex][i][1];
                  //Create a div element that represents a button and holds the answer text.
                  var div = document.createElement("div");
                  div.className = "button";
                  //Create a link to be contained within the div to perform button click functions.
                  var a = document.createElement("a");
                  //Set the id field of this link to the answer of the corresponding answer entry.
                  a.id = ans_id;
                  a.className = "full";
                  a.innerHTML = ans_text;
                  //Call the updatePage function when clicked.
                  a.addEventListener("click", updatePage);
                  //Append the link to the div container.
                  div.appendChild(a);
                  //Append this div container/button to the container of answer buttons.
                  document.getElementById("answerButtons").appendChild(div);
              }
          }

          function checkCase(questionIndex){
              //Get the JSON case object for the current question.
              var jsonCase = questions[questionIndex][1];
              if(jsonCase != null){
                if(jsonCase.cases.length == 1){
                  return jsonCase.cases[0].to;
                } else {
                  var cases = jsonCase.cases;
                  for(var i=0; i<cases.length; i++){
                    if(session.responses[cases[i].question_condition] == cases[i].answer_condition){
                      return cases[i].to;
                    }
                  }
                  for(var i=0; i<cases.length; i++){
                    if(cases[i].question_condition === "DEFAULT"){ return cases[i].to;}
                  }
                  return null;
                }
              } else {
                return null;
                console.log("Nothing here");
              }
          }
        </script>
        <footer><div><p>Photo Credit: Gerald Snelson</p></div></footer>
    </body>
</html>

<script></script>
