<div class="container shadow" id='question-cont' style="display: none;">
    <div class="row">
        <div class="column" id='question'>

        </div>
    </div>
    <div class="row" id='answers'>

    </div>
</div>


<script>
    function showQuestion(question) {
        var questionDiv = document.getElementById('question')
        questionDiv.textContent = question.question;
        var answersDiv = document.getElementById('answers');
        question.answers.forEach(answer => {
            addAnswer(answersDiv, answer);
        });
        document.getElementById('question-cont').style.display = 'grid';
    }

    function addAnswer(answersDiv, answer) {
        const newAnswerDiv = document.createElement('div');
        newAnswerDiv.classList.add('col');
        newAnswerDiv.textContent = answer.answer;
        answersDiv.appendChild(newAnswerDiv);
    }
</script>