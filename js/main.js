<<<<<<< HEAD
var session_report;

function setSessionReport(jsonObj){
  session_report = jsonObj;
}

function getSessionReport(){
  return session_report;
}
=======

  var questions = json_encode($questions);
  var answers = json_encode($answers);
  var question_flow = json_encode($question_flow);
  var resources = json_encode($resources);

  function getQuestionText(id){
    question_text.innertext = questions[id];
  }

  function getAnswers(){

  }
>>>>>>> 4dd49e257e064e8df9e5851e84faf3900ffa3bae
