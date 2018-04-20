
  var questions = json_encode($questions);
  var answers = json_encode($answers);
  var question_flow = json_encode($question_flow);
  var resources = json_encode($resources);

  function getQuestionText(id){
    question_text.innertext = questions[id];
  }

  function getAnswers(){

  }
