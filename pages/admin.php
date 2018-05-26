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

if(isset($_POST['add_question_number']) && isset($_POST['add_question_text']) && isset($_POST['submit_add_q']) && isset($_POST['add_q_id'])){ //Query handler for adding questions
  $q_num = $_POST['add_question_number'];
  var_dump($q_num);
  $q_text = $_POST['add_question_text'];
  $next_q = $q_num+1;
  $sql = "INSERT INTO `questions` (`id`, `question`) VALUES (".$q_num.", '".$q_text."')";
  if(isset($_POST['condition_chk']) && $_POST['condition_chk'] == 'yes'){
    if(isset($_POST['to_q']) && isset($_POST['if_q']) && isset($_POST['a_is'])){
      $jsonCaseStr = "{ \"cases\": [{\"to\":".$_POST['to_q'].", \"answer_condition\":".$_POST['a_is'].", \"question_condition\":".$_POST['if_q']."}, {\"to\":".$next_q.", \"answer_condition\": \"DEFAULT\", \"question_condition\": \"DEFAULT\"}]}";
      $jsonCaseStr = utf8_encode($jsonCaseStr);
    }
    $sql = "INSERT INTO `questions` (`id`, `question`, `flow`) VALUES ($q_num, '".$q_text."', '".$jsonCaseStr."')";
    echo $sql;
  }
  if ($con->query($sql) === TRUE) {
      alert("Entry Added Successfully");
  } else {
      alert("Error: " . $sql . "<br>" . $con->error);
  }
}

if(isset($_POST['modify_question_number']) && isset($_POST['modify_question_text']) && isset($_POST['submit_modify_question']) && isset($_POST['mod_q_id'])){ //Query handler for modifying questions
  $q_num = $_POST['modify_question_number'];
  $q_text = $_POST['modify_question_text'];
  $q_id = $_POST['mod_q_id'];
  $sql = "UPDATE `questions` SET `question`='".$q_text."' WHERE id=".$q_id."";
  if(isset($_POST['condition_chk']) && $_POST['condition_chk'] == 'yes'){
    if(isset($_POST['to_q']) && isset($_POST['if_q']) && isset($_POST['a_is'])){
      //$jsonCaseStr = "{"cases"": [{""to"": ".$_POST['to_q'].", ""answer_condition"": ".$_POST['a_is'].", ""question_condition"": ".$_POST['if_q']."}, {""to"": ".($q_num+1).", ""answer_condition"": ""DEFAULT"", ""question_condition"": ""DEFAULT""}]}";
      $jsonCaseStr = "{}";
        $jsonCaseStr = utf8_encode($jsonCaseStr);
    }
    $sql = "UPDATE `questions` SET `question`=".$q_text.", `flow`=".$jsonCaseStr." WHERE id=".$q_id."";
  }
  if ($con->query($sql) === TRUE) {
      alert("Entry Modified Successfully");
  } else {
      alert("Error: " . $sql . "<br>" . $con->error);
  }
}

if(isset($_POST['remove_question_number']) && isset($_POST['remove_question_text']) && isset($_POST['submit_delete_question']) && isset($_POST['rem_q_id'])){ //Query handler for removing questions
  $q_id = $_POST['rem_q_id'];
  $sql = "DELETE FROM `questions` WHERE id=".$q_id."";
  if ($con->query($sql) === TRUE) {
      alert("Entry Deleted Successfully.");
  } else {
      alert("Error: " . $sql . "<br>" . $con->error);
  }
}

if(isset($_POST['add_ans_question_number']) && isset($_POST['add_answer_text']) && isset($_POST['submit_add_a']) && isset($_POST['add_a_id'])){ //Query handler for adding answers
  $q_num = $_POST['add_ans_question_number'];
  $a_text = $_POST['add_answer_text'];
  $sql = "INSERT INTO `answers` (`question_id`, `answer`) VALUES (".$q_num.", '".$a_text."')";
  if ($con->query($sql) === TRUE) {
      alert("Entry Added Successfully");
  } else {
      alert("Error: " . $sql . "<br>" . $con->error);
  }
}

if(isset($_POST['mod_question_number']) && isset($_POST['mod_answer_text']) && isset($_POST['submit_mod_a']) && isset($_POST['mod_a_id'])){ //Query handler for modifying answers
  $q_num = $_POST['mod_question_number'];
  $a_text = $_POST['mod_answer_text'];
  $a_id = $_POST['mod_a_id'];
  $sql = "UPDATE `answers` SET `question_id`=".$q_num.", `answer`='".$a_text."' WHERE id=".$a_id."";
  if ($con->query($sql) === TRUE) {
      alert("Entry Modified Successfully");
  } else {
      alert("Error: " . $sql . "<br>" . $con->error);
  }
}

if(isset($_POST['del_question_number']) && isset($_POST['del_answer_text']) && isset($_POST['submit_del_a']) && isset($_POST['rem_a_id'])){ //Query handler for removing answers
  $a_id = $_POST['rem_a_id'];
  $sql = "DELETE FROM `answers` WHERE id=".$a_id."";
  if ($con->query($sql) === TRUE) {
      alert("Entry Deleted Successfully.");
  } else {
      alert("Error: " . $sql . "<br>" . $con->error);
  }
}

if(isset($_POST['submit_add_r'])){ //Query handler for adding resources
  $num_conditions = $_POST['num_resource_conditions'];
  var_dump($num_conditions);
  $ans_id = $_POST['answer_id'];
  var_dump($ans_id);
  $res_tag = $_POST['resource_tag'];
  var_dump($res_tag);
  $res_text = $_POST['a_resource_text'];
  $res_link = $_POST['a_resource_link'];
  $for_q = $_POST['a_for_question'];
  if($for_q == ""){
    $for_q = "NULL";
  }
  $for_a = $_POST['a_for_answer'];
  if($for_a == ""){
    $for_a = "NULL";
  }
  if($num_conditions > 0){
    echo $yes;
    $jsonStart = "{ \"conditions\": [";
    $jsonObjFormat = "{\"question\": %s, \"answer\": %s, \"text\": \"%s\", \"link\": \"%s\"}";
    $firstCondition = sprintf($jsonObjFormat, $for_q, $for_a, $res_text, $res_link);
    $jsonCaseStr = $jsonStart.$firstCondition;
    for($i=1; $i<=$num_conditions; $i++){
      $jsonCaseStr .= ", ";
      $c_question = $_POST['for_question'.$i];
      $c_answer = $_POST['for_answer'.$i];
      $c_text = $_POST['resource_text'.$i];
      $c_link = $_POST['resource_link'.$i];
      $conditionText = sprintf($jsonObjFormat, $c_question, $c_answer, $c_text, $c_link);
      $jsonCaseStr .= $conditionText;
    }
    $jsonEnd = "]}";
    $jsonCaseStr .= $jsonEnd;
    $jsonCaseStr = utf8_encode($jsonCaseStr);
    $sql = "INSERT INTO `resources` (`answer_id`, `tag`, `link`, `text`, `condition`) VALUES ($ans_id, '".$res_tag."', '', '', '".$jsonCaseStr."')";
    var_dump($sql);
  } else {
    echo $no;
    $sql = "INSERT INTO `resources` (`answer_id`, `tag`, `question_condition`, `answer_condition`, `link`, `text`) VALUES ($ans_id, '".$res_tag."', $for_q, $for_a, '".$res_link."', '".$res_text."')";
    echo $sql;
  }

  if ($con->query($sql) === TRUE) {
      alert("Entry Added Successfully");
  } else {
      alert("Error: " . $sql . "<br>" . $con->error);
  }
}

if(isset($_POST['submit_mod_r'])){ //Query handler for modifying resources
  $q_num = $_POST['mod_question_number'];
  $a_text = $_POST['mod_answer_text'];
  $r_id = $_POST['mod_r_id'];
  $sql = "UPDATE `answers` SET `question_id`=".$q_num.", `answer`='".$a_text."' WHERE id=".$a_id."";
  if ($con->query($sql) === TRUE) {
      alert("Entry Modified Successfully");
  } else {
      alert("Error: " . $sql . "<br>" . $con->error);
  }
}

if(isset($_POST['submit_del_r'])){ //Query handler for removing resources
  $r_id = $_POST['rem_r_id'];
  $sql = "DELETE FROM `resources` WHERE id=".$r_id."";
  if ($con->query($sql) === TRUE) {
      alert("Entry Deleted Successfully.");
  } else {
      alert("Error: " . $sql . "<br>" . $con->error);
  }
}

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

function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
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
                <h3 id="col_1_header"></h3>
                <div id="col_1_div">
                  <select id="col_1" name="col_1" size=20 onchange="selectionChanged(this);"></select>
                </div>
              </li>
              <li>
                <h3 id="col_2_header"></h3>
                <div id="col_2_div">
                  <select id="col_2" name="col_2" size=20 onchange="updateCol3();"></select>
                </div>
              </li>
              <li>
                <h3 id="col_3_header"></h3>
                <div id="col_3_div">
                  <select id="col_3" name="col_3" size=20></select>
                </div>
              </li>
            </ul>
            <ul id="action_buttons">
              <button class="action_button" onclick="add();">Add</button>
              <button class="action_button" onclick="modify();">Modify</button>
              <button class="action_button" onclick="remove();">Remove</button>
            </ul>
            <section id="content_interface">
              <form id="interface_form" method="post">
                <div id="q_add" style="display:none">
                  <input type="hidden" name="add_q_id" id="add_q_id">
                  <div class="form_element_container">
                    <label for="q_num">Question Number</label>
                    <input class="number" type="number" name="add_question_number" id="q_num"><br>
                  </div>
                  <div class="form_element_container">
                    <label for="q_text">Question Text</label>
                    <input  type="text" name="add_question_text" id="q_text"><br>
                  </div>
                  <div class="form_element_container">
                    <label for="cond_chk">Conditions</label>
                    <input type="checkbox" name="condition_chk" id="cond_chk" value="yes"><br>
                  </div>
                  <div class="form_element_container">
                    <div id="cond_div" style="display:none">
                      <label for="to_q">To:</label>
                      <input class="number"  type="number" name="to_q" id="to_q">
                      <label for="if_q">If Question </label>
                      <input class="number"  type="number" name="if_q" id="if_q">
                      <label for="a_is">Answer Is: </label>
                      <input class="number"  type="number" name="a_is" id="a_is">
                    </div>
                  </div>
                  <div class="submit_div">
                    <input class="admin_submit" type="submit" name="submit_add_q" id="submit_q" value="Add Question">
                  </div>
                </div>
                <div id="q_modify" style="display:none">
                  <input type="hidden" name="mod_q_id" id="mod_q_id">
                  <div class="form_element_container">
                    <label for="q_m_num">Question Number</label>
                    <input class="number"  type="number" name="modify_question_number" id="q_m_num"><br>
                  </div>
                  <div class="form_element_container">
                    <label for="q_m_text">Question Text</label>
                    <input  type="text" name="modify_question_text" id="q_m_text"><br>
                  </div>
                  <div class="form_element_container">
                    <div id="cond_div">
                    </div>
                  </div>
                  <div class="submit_div">
                    <input class="admin_submit" type="submit" name="submit_modify_question" id="submit_mod_q" value="Submit Changes">
                  </div>
                </div>
                <div id="q_remove" style="display:none">
                  <input type="hidden" name="rem_q_id" id="rem_q_id">
                  <div class="form_element_container">
                    <label for="q_r_num">Question Number</label>
                    <input class="number"  type="number" name="remove_question_number" id="q_r_num" readonly><br>
                  </div>
                  <div class="form_element_container">
                    <label for="q_r_text">Question Text</label>
                    <input  type="text" name="remove_question_text" id="q_r_text" readonly><br>
                  </div>
                  <div class="form_element_container">
                    <div id="cond_div" readonly>
                    </div>
                  </div>
                  <div class="submit_div">
                    <input class="admin_submit" type="submit" name="submit_delete_question" id="submit_del_q" value="Confirm Delete">
                  </div>
                </div>
                <div id="a_add" style="display:none">
                  <input type="hidden" name="add_a_id" id="add_a_id">
                  <div class="form_element_container">
                    <label for="a_q_num">For Question</label>
                    <input class="number"  type="number" name="add_ans_question_number" id="a_q_num"><br>
                  </div>
                  <div class="form_element_container">
                    <label for="a_text">Answer Text</label>
                    <input  type="text" name="add_answer_text" id="a_text"><br>
                  </div>
                  <div class="submit_div">
                    <input class="admin_submit" type="submit" name="submit_add_a" id="submit_a" value="Add Answer">
                  </div>
                </div>
                <div id="a_modify" style="display:none">
                  <input type="hidden" name="mod_a_id" id="mod_a_id">
                  <div class="form_element_container">
                    <label for="a_q_m_num">For Question</label>
                    <input class="number"  type="number" name="mod_question_number" id="a_q_m_num"><br>
                  </div>
                  <div class="form_element_container">
                    <label for="a_m_text">Answer Text</label>
                    <input  type="text" name="mod_answer_text" id="a_m_text"><br>
                  </div>
                  <div class="submit_div">
                    <input class="admin_submit" type="submit" name="submit_mod_a" id="submit_mod_a" value="Submit Changes">
                  </div>
                </div>
                <div id="a_remove" style="display:none">
                  <input type="hidden" name="rem_a_id" id="rem_a_id">
                  <div class="form_element_container">
                    <label for="a_q_r_num">For Question</label>
                    <input class="number"  type="number" name="del_question_number" id="a_q_r_num" readonly><br>
                  </div>
                  <div class="form_element_container">
                    <label for="a_r_text">Answer Text</label>
                    <input  type="text" name="del_answer_text" id="a_r_text" readonly><br>
                  </div>
                  <div class="submit_div">
                    <input class="admin_submit" type="submit" name="submit_del_a" id="submit_del_a" value="Confirm Delete">
                  </div>
                </div>
                <div id="r_add" style="display:none">
                  <input type="hidden" name="add_r_id" id="add_r_id">
                    <div id="a_resource_view">
                      <input type="hidden" name="num_resource_conditions" id="num_r_c">
                      <div id="a_resource_interface">
                        <div class="form_element_container">
                          <label for="a_ans_id">For Answer: </label>
                          <input class="number" type="number" name="answer_id" id="a_ans_id">
                          <label for="a_r_tag_sel">Tag</label>
                          <select name="resource_tag" class="tag_select" id="a_r_tag_sel"></select><br>
                        </div>
                        <div class="form_element_container">
                          <label for="a_r_text">Text</label>
                          <input  type="text" name="a_resource_text" id="a_r_text"><br>
                        </div>
                        <div class="form_element_container">
                          <label for="a_r_link">Link</label>
                          <input  type="text" name="a_resource_link" id="a_r_link"><br>
                        </div>
                        <div class="form_element_container">
                          <label for="a_for_q">If Question </label>
                          <input class="number"  type="number" name="a_for_question" id="a_for_q">
                          <label for="a_for_a">Answer Is: </label>
                          <input class="number"  type="number" name="a_for_answer" id="a_for_a">
                        </div>
                      </div>
                    </div>
                    <input type="checkbox" id="add_res_condition">
                    <button type="button" id="add_condition_btn" onclick="addResourceCondition();" disabled>Add Another Condition</button>
                    <div class="submit_div">
                      <input class="admin_submit" type="submit" name="submit_add_r" id="submit_r" value="Add Resouce">
                    </div>
                </div>
                <div id="r_modify" style="display:none">
                  <input type="hidden" name="mod_r_id" id="mod_r_id">
                    <div id="m_resource_view">
                      <div id="m_resource_interface">
                        <div class="form_element_container">
                          <label for="r_tag_sel_m">Tag</label>
                          <select class="tag_select" id="r_tag_sel_m"></select><br>
                        </div>
                        <div class="form_element_container">
                          <label for="m_r_text">Text</label>
                          <input  type="text" name="m_resource_text" id="m_r_text"><br>
                        </div>
                        <div class="form_element_container">
                          <label for="m_r_link">Link</label>
                          <input  type="text" name="m_resource_link" id="m_r_link"><br>
                        </div>
                        <div class="form_element_container">
                          <label for="for_q">For Question </label>
                          <input class="number" type="number" name="m_for_question" id="for_q_m">
                          <label for="for_a">Answer: </label>
                          <input class="number" type="number" name="m_for_answer" id="for_a_m">
                        </div>
                      </div>
                    </div>
                    <input type="checkbox" id="add_res_condition">
                    <button type="button" id="add_condition_btn" onclick="addResourceCondition();" disabled>Add Another Condition</button>
                    <div class="submit_div">
                      <input class="admin_submit" type="submit" name="submit_mod_r" id="submit_r" value="Submit Changes">
                    </div>
                </div>
                <div id="r_remove" style="display:none">
                  <input type="hidden" name="rem_r_id" id="rem_r_id">
                    <div id="r_resource_view">
                      <div id="r_resource_interface">
                        <div class="form_element_container">
                          <label for="r_tag_sel_r">Tag</label>
                          <select class="tag_select" id="r_tag_sel_r" disabled></select><br>
                        </div>
                        <div class="form_element_container">
                          <label for="r_t_r_text">Text</label>
                          <input  type="text" name="r_resource_text" id="r_t_r_text" readonly><br>
                        </div>
                        <div class="form_element_container">
                          <label for="r_t_r_link">Link</label>
                          <input  type="text" name="r_resource_link" id="r_t_r_link" readonly><br>
                        </div>
                        <div class="form_element_container">
                          <label for="for_q_r">For Question </label>
                          <input class="number"  type="number" name="r_for_question" id="for_q_r"  readonly>
                          <label for="for_a_r">Answer: </label>
                          <input class="number"  type="number" name="r_for_answer" id="for_a_r" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="submit_div">
                      <input class="admin_submit" type="submit" name="submit_del_r" id="submit_del_r" value="Confirm Delete">
                    </div>
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
        var currentAction = "";
        var sel_id;
        var sel_id2;
        var num_resource_conditions = 0;
        var res_condition_counter = document.getElementById("num_r_c");
        res_condition_counter.value = num_resource_conditions;

        var questions = <?php echo json_encode($questions); ?>;
        var answers = <?php echo json_encode($answers); ?>;
        var resources = <?php echo json_encode($resources); ?>;

        var questionKeys = Object.keys(questions);
        var answerKeys = Object.keys(answers);
        var resourceKeys = Object.keys(resources);

        //fillResourceTags("r_tag_sel");

        var conditionDiv = document.getElementById("cond_div");
        var checkbox = document.getElementById("cond_chk");
        checkbox.onchange = function() {
          if(checkbox.checked) {
            conditionDiv.style.display = 'block';
          } else {
            conditionDiv.style.display = 'none';
          }
        }

        var resConditionChk = document.getElementById("add_res_condition");
        resConditionChk.onchange = function() {
          if(resConditionChk.checked) {
            document.getElementById("add_condition_btn").disabled = false;
          } else {
            document.getElementById("add_condition_btn").disabled = true;
          }
        }

        $(document).ready(function(){

        });

        function adminView(element){
          var id = element.id;
          clearAll();
          switch(id){
            case 'questions':
              currentView = "questions";
              fillQuestions("col_1");
              document.getElementById("col_1_header").innerHTML = "Questions";
              document.getElementById("col_2_header").innerHTML = "Answers";
              document.getElementById("col_3_header").innerHTML = "Resources";
              break;
            case 'answers':
              currentView = "answers";
              fillAnswers("col_1");
              document.getElementById("col_1_header").innerHTML = "Answers";
              document.getElementById("col_2_header").innerHTML = "Question";
              document.getElementById("col_3_header").innerHTML = "Resources";
              break;
            case 'resources':
              currentView = "resources";
              fillResources("col_1");
              fillAnswers("col_2");
              document.getElementById("col_1_header").innerText = "Resources";
              document.getElementById("col_2_header").innerText = "Answers";
              document.getElementById("col_3_header").innerText = "Question";
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
            option.id = i;
            option.innerHTML = questions[i][0] + ". " + questions[i][1];
            sel.add(option);
          }
        }

        function fillAnswers(id){
          if(!document.getElementById(id)){
            var sel = document.createElement("select");
          } else {
            var sel = document.getElementById(id);
            sel.innerHTML = '';
          }
          //sel.size = answerKeys.length;
          for(var i=0; i<answers.length; i++){
            var option = document.createElement("option");
            option.id = i;
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
            option.id = i;
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
          num_resource_conditions = 0;
          currentAction = "add";
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
              fillAnswers("col_2");
              fillResourceTags("a_r_tag_sel");
              break;
            default:
          }
        }

        function modify(){
          num_resource_conditions = 0;
          currentAction = "modify";
          switch(currentView){
            case "questions":
              hideAll();
              var modify_div = document.getElementById("q_modify");
              modify_div.style.display = "block";
              break;
            case "answers":
              hideAll();
              var modify_div = document.getElementById("a_modify");
              modify_div.style.display = "block";
              break;
            case "resources":
              hideAll();
              var modify_div = document.getElementById("r_modify");
              modify_div.style.display = "block";
              break;
            default:
          }
        }

        function remove(){
          num_resource_conditions = 0;
          currentAction = "remove";
          switch(currentView){
            case "questions":
              hideAll();
              var remove_div = document.getElementById("q_remove");
              remove_div.style.display = "block";
              break;
            case "answers":
              hideAll();
              var remove_div = document.getElementById("a_remove");
              remove_div.style.display = "block";
              break;
            case "resources":
              hideAll();
              var remove_div = document.getElementById("r_remove");
              remove_div.style.display = "block";
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

        function selectionChanged(element){
            var id = element.id;
            var select = document.getElementById(id);
            var options = select.options;
            sel_id = options[options.selectedIndex].id;
            console.log(sel_id);
            switch(currentView){
              case "questions":
                switch(currentAction){
                  case "add":
                    var form_id = document.getElementById("add_q_id");
                    break;
                  case "modify":
                    var form_id = document.getElementById("mod_q_id");
                    var num = document.getElementById("q_m_num");
                    num.value = questions[sel_id][0];
                    var text = document.getElementById("q_m_text");
                    text.value = questions[sel_id][1];
                    form_id.value = questions[sel_id][0];
                    break;
                  case "remove":
                    var form_id = document.getElementById("rem_q_id");
                    var num = document.getElementById("q_r_num");
                    num.value = questions[sel_id][0];
                    var text = document.getElementById("q_r_text");
                    text.value = questions[sel_id][1];
                    form_id.value = questions[sel_id][0];
                    break;
                  default:
                }
                break;
              case "answers":
                switch(currentAction){
                  case "add":
                    var form_id = document.getElementById("add_a_id");
                    break;
                  case "modify":
                    var form_id = document.getElementById("mod_a_id");
                    var num = document.getElementById("a_q_m_num");
                    num.value = answers[sel_id][1];
                    var text = document.getElementById("a_m_text");
                    text.value = answers[sel_id][2];
                    form_id.value = answers[sel_id][0];
                    break;
                  case "remove":
                    var form_id = document.getElementById("rem_a_id");
                    var num = document.getElementById("a_q_r_num");
                    num.value = answers[sel_id][1];
                    var text = document.getElementById("a_r_text");
                    text.value = answers[sel_id][2];
                    form_id.value = answers[sel_id][0];
                    break;
                  default:
                }
                break;
              case "resources":
                switch(currentAction){
                  case "add":
                    var form_id = document.getElementById("add_r_id");
                    fillResourceTags("a_r_tag_sel");
                    break;
                  case "modify":
                    var resourceViewNodes = document.getElementById("m_resource_view").childNodes;
                    for(var i=0; i<resourceViewNodes.length; i++){
                      if(resourceViewNodes[i].id == "m_resource_view"){

                      }
                    }
                    var form_id = document.getElementById("mod_r_id");
                    form_id.value = resources[sel_id][0];
                    fillResourceTags("r_tag_sel_m");
                    var tagSelect = document.getElementById("r_tag_sel_m");
                    switch(resources[sel_id][5]){
                      case "basic_chk":
                        tagSelect.value = "Basic Checklist";
                        break;
                      case "site_selection":
                        tagSelect.value = "Site Selection";
                        break;
                      case "use_and_occ":
                        tagSelect.value = "Use and Occupation";
                        break;
                      case "other":
                        tagSelect.value = "Other";
                        break;
                      default:
                    }
                    if(resources[sel_id][4] != ""){
                      var conditionEntry = resources[sel_id][4];
                      var jsonObj = JSON.parse(conditionEntry);
                      var conditions = jsonObj.conditions;
                      for(var i=0; i<conditions.length; i++){
                        if(i == 0){
                          var forQuestion = document.getElementById("for_q_m");
                          forQuestion.value = conditions[i].question;
                          var forAnswer = document.getElementById("for_a_m");
                          forAnswer.value = conditions[i].answer;
                        } else {
                          var element = addResourceCondition();
                          console.log(element.childNodes);
                          var forQuestion = element.childNodes[8];
                          forQuestion.value = conditions[i].question;
                          var forAnswer = element.childNodes[10];
                          forAnswer.value = conditions[i].answer;
                        }
                      }
                    } else {
                      var resourceText = document.getElementById("m_r_text");
                      resourceText.value = resources[sel_id][2];
                      var resourceLink = document.getElementById("m_r_link");
                      resourceLink.value = resources[sel_id][3];
                    }
                    break;
                  case "remove":
                    var resourceView = document.getElementById("r_resource_view");
                    var form_id = document.getElementById("rem_r_id");
                    form_id.value = resources[sel_id][0];
                    fillResourceTags("r_tag_sel_r");
                    var tagSelect = document.getElementById("r_tag_sel_r");
                    switch(resources[sel_id][5]){
                      case "basic_chk":
                        tagSelect.value = document.getElementById("basic_chk").innerHTML;
                        break;
                      case "site_selection":
                        tagSelect.value = document.getElementById("site_selection").innerHTML;
                        break;
                      case "use_and_occ":
                        tagSelect.value = document.getElementById("use_and_occ").innerHTML;
                        break;
                      case "other":
                        tagSelect.value = document.getElementById("other").innerHTML;
                        break;
                      default:
                    }
                    console.log(tagSelect.value);
                    var resourceText = document.getElementById("r_t_r_text");
                    resourceText.value = resources[sel_id][2];
                    var resourceLink = document.getElementById("r_t_r_link");
                    resourceLink.value = resources[sel_id][3];
                    break;
                  default:
                }
                break;
              default:
            }
            updateCol2();
        }

        function updateCol2() {
          var select2 = document.getElementById("col_2");
          select2.innerHTML = '';
          switch (currentView) {
            case "questions":
              for(var i=0; i<answers.length; i++){
                if(questions[sel_id][0] == answers[i][1]){
                  var option = document.createElement("option");
                  option.id = i;
                  option.innerHTML = answers[i][0] + ". " + answers[i][2];
                  select2.appendChild(option);
                }
              }
              break;
            case "answers":
              for(var i=0; i<questions.length; i++){
                if(answers[sel_id][1] == questions[i][0]){
                  var option = document.createElement("option");
                  option.id = i;
                  option.innerHTML = questions[i][0] + ". " + questions[i][1];
                  select2.appendChild(option);
                }
              }
              break;
            case "resources":
              for(var i=0; i<answers.length; i++){
                if(resources[sel_id][1] == answers[i][0]){
                  var option = document.createElement("option");
                  option.id = i;
                  option.innerHTML = answers[i][0] + ". " + answers[i][2];
                  select2.appendChild(option);
                }
              }
              break;
            default:

          }
        }

        function updateCol3() {
          var select3 = document.getElementById("col_3");
          select3.innerHTML = '';
          var select2 = document.getElementById("col_2");
          var options = select2.options;
          console.log(options);
          sel_id2 = options[options.selectedIndex].id;
          var select3 = document.getElementById("col_3");
          select3.innerHTML = '';
          switch (currentView) {
            case "questions":
              for(var i=0; i<resources.length; i++){
                if(answers[sel_id][0] == resources[i][1]){
                  var option = document.createElement("option");
                  option.id = i;
                  if(resources[i][2] != ""){
                    option.innerHTML = resources[i][0] + ". " + resources[i][2];
                  } else {
                    option.innerHTML = resources[i][0] + ". [Resource text determined by condition. Click on this resource and select 'Modify' below to see these conditions.]" ;
                  }
                  select3.appendChild(option);
                }
              }
              break;
            case "answers":
              for(var i=0; i<resources.length; i++){
                if(answers[sel_id][0] == resources[i][1]){
                  var option = document.createElement("option");
                  option.id = i;
                  if(resources[i][2] != ""){
                    option.innerHTML = resources[i][0] + ". " + resources[i][2];
                  } else {
                    option.innerHTML = resources[i][0] + ". [Resource text determined by condition. Click on this resource and select 'Modify' below to see these conditions.]" ;
                  }
                  select3.appendChild(option);
                }
              }
              break;
            case "resources":
              var resAns = document.getElementById("a_ans_id");
              resAns.value = answers[sel_id2][0];
              for(var i=0; i<questions.length; i++){
                if(answers[sel_id2][1] == questions[i][0]){
                  var option = document.createElement("option");
                  option.id = i;
                  option.innerHTML = questions[i][0] + ". " + questions[i][1];
                  select3.appendChild(option);
                }
              }
              break;
            default:

          }
        }

        function fillResourceTags(id){
          var resource_tags = document.getElementById(id);
          resource_tags.innerHTML = '';
          var option1 = document.createElement("option");
          option1.value = "basic_chk";
          option1.innerHTML = "Basic Checklist";
          option1.id = "basic_chk";
          var option2 = document.createElement("option");
          option2.value = "site_selection";
          option2.innerHTML = "Site Selection";
          option2.id = "site_selection";
          var option3 = document.createElement("option");
          option3.value = "use_and_occ";
          option3.innerHTML = "Use and Occupation";
          option3.id = "use_and_occ";
          var option4 = document.createElement("option");
          option4.value = "other";
          option4.innerHTML = "Other";
          option4.id = "other";
          resource_tags.add(option1);
          resource_tags.add(option2);
          resource_tags.add(option3);
          resource_tags.add(option4);
        }

        function addResourceCondition(){
          var resourceView;
          var resourceInterface;
          var tagSelectID;
          switch(currentAction){
            case "add":
              num_resource_conditions++;
              res_condition_counter.value = num_resource_conditions;
              var br1 = document.createElement("br");
              var br2 = document.createElement("br");
              var br3 = document.createElement("br");
              resourceView = document.getElementById("a_resource_view");
              resourceInterface = document.createElement("div");
              var tagDiv = document.createElement("div");
              var textDiv = document.createElement("div");
              var linkDiv = document.createElement("div");
              var forQADiv = document.createElement("div");

              tagDiv.className = "form_element_container";
              textDiv.className = "form_element_container";
              linkDiv.className = "form_element_container";
              forQADiv.className = "form_element_container";

              //var ansIDLabel = document.createElement("label");
              var tagLabel = document.createElement("label");
              var textLabel = document.createElement("label");
              var linkLabel = document.createElement("label");
              var forQLabel = document.createElement("label");
              var forALabel = document.createElement("label");

              //ansIDLabel.innerHTML = "For Answer: ";
              tagLabel.innerHTML = "Tag";
              textLabel.innerHTML = "Text";
              linkLabel.innerHTML = "Link";
              forQLabel.innerHTML = "If Question ";
              forALabel.innerHTML = "Answer Is: ";

              var tagSelector = document.createElement("select");

              //var ansIDInput = document.createElement("input");
              var textInput = document.createElement("input");
              var linkInput = document.createElement("input");
              var forQInput = document.createElement("input");
              var forAInput = document.createElement("input");

              //ansIDInput.type = "number";
              textInput.type = "text";
              linkInput.type = "text";
              forQInput.type = "number";
              forAInput.type = "number";

              //ansIDInput.className = "number";
              tagSelector.className = "tag_select";
              forQInput.className = "number";
              forAInput.className = "number";

              //ansIDInput.id = 'a_ans_id'+num_resource_conditions;
              tagSelector.id = 'a_r_tag_sel'+num_resource_conditions;
              tagSelectID = tagSelector.id;
              textInput.id = 'a_r_text'+num_resource_conditions;
              console.log(textInput.id);
              linkInput.id = 'a_r_link'+num_resource_conditions;
              forQInput.id = 'a_for_q'+num_resource_conditions;
              forAInput.id = 'a_for_a'+num_resource_conditions;

              //ansIDLabel.htmlFor = ansIDInput.id;
              tagLabel.htmlFor = tagSelector.id;
              textLabel.htmlFor = textInput.id;
              linkLabel.htmlFor = linkInput.id;
              forQLabel.htmlFor = forQInput.id;
              forALabel.htmlFor = forAInput.id;

              //ansIDInput.name = "answer_id"+num_resource_conditions;
              tagSelector.name = "resource_tag"+num_resource_conditions;
              textInput.name = "resource_text"+num_resource_conditions;
              linkInput.name = "resource_link"+num_resource_conditions;
              forQInput.name = "for_question"+num_resource_conditions;
              forAInput.name = "for_answer"+num_resource_conditions;

              //tagDiv.appendChild(ansIDLabel);
              //tagDiv.appendChild(ansIDInput);
              tagDiv.appendChild(tagLabel);
              tagDiv.appendChild(tagSelector);
              tagDiv.appendChild(br1);

              textDiv.appendChild(textLabel);
              textDiv.appendChild(textInput);
              textDiv.appendChild(br2);

              linkDiv.appendChild(linkLabel);
              linkDiv.appendChild(linkInput);
              linkDiv.appendChild(br3);

              forQADiv.appendChild(forQLabel);
              forQADiv.appendChild(forQInput);
              forQADiv.appendChild(forALabel);
              forQADiv.appendChild(forAInput);

              resourceInterface.appendChild(tagDiv);
              resourceInterface.appendChild(textDiv);
              resourceInterface.appendChild(linkDiv);
              resourceInterface.appendChild(forQADiv);
              break;
            case "modify":
              resourceView = document.getElementById("m_resource_view");
              resourceInterface = document.getElementById("m_resource_interface");
              break;
            case "remove":
              resourceView = document.getElementById("r_resource_view");
              resourceInterface = document.getElementById("r_resource_interface");
              break;
            default:
          }
          console.log(num_resource_conditions);
          resourceView.appendChild(resourceInterface);
          fillResourceTags(tagSelectID);
          return resourceInterface;
        }
    </script>
  </body>
</html>
