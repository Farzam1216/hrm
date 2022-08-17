$("input[type='radio']").on('click', function(){
    $("#option-error"+this.getAttribute('question_id')).addClass("hidden");
});

function check(question_id)
{
    if($("#answer"+question_id).val() == ''){
        $("#answer-error"+question_id).removeClass("hidden");
        $("#answer"+question_id).addClass("error");
    } else {
        $("#answer-error"+question_id).addClass("hidden");
        $("#answer"+question_id).removeClass("error");
    }
}

function validate(employee, questions)
{
    var radioCheck, textFieldCheck, count = 0;

    $.each(employee.assigned_form['assigned_questions'], function(index, assignedQuestion){
        $.each(questions, function(index, question){
            if(question.id == assignedQuestion.question_id) {
                var optionSelected;
                if (question.field_type == 'multiple choice button') {
                    $.each(question['options'], function(index, option){
                        if (document.getElementById("answer["+question.id+"]["+option.id+"]")) {
                            if (document.getElementById("answer["+question.id+"]["+option.id+"]").checked) {
                                optionSelected = 'checked';
                            }
                        }
                    });
                    
                    $.each(question['options_history'], function(index, optionHistory){
                        if (document.getElementById("answer["+question.id+"]["+optionHistory.id+"]")) {
                            if (document.getElementById("answer["+question.id+"]["+optionHistory.id+"]").checked) {
                                optionSelected = 'checked';
                            }
                        }
                    });

                    if (optionSelected == null) {
                        $("#option-error"+question.id).removeClass("hidden");
                        $("#focus"+question.id).addClass('error');
                        radioCheck = false;
                    } else {
                        $("#focus"+question.id).removeClass('error');
                    }
                }
                if (question.field_type != 'multiple choice button') {
                    if ($("#answer"+question.id).val() == '') {
                        $("#answer-error"+question.id).removeClass("hidden");
                        $("#answer"+question.id).addClass("error");
                        textFieldCheck = false;
                    }
                }
            }
        });
    });

    $.each($(".error"), function(index,val){
        if (val.getAttribute('name') || val.getAttribute('focus_name')) {
            if (count == 0) {
                val.focus();
                count++;
            }
        }
    });

    if(radioCheck != false && textFieldCheck != false){
        $("#evaluation-form").submit();
    }
}