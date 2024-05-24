var dropdownElementList = [].slice.call(
  document.querySelectorAll(".dropdown-toggle")
);
var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
  return new bootstrap.Dropdown(dropdownToggleEl);
});

// Function to update language and redirect
function updateLanguage(lang) {
  // Store the selected language in localStorage
  localStorage.setItem("lang", lang);

  // Check if the lang parameter is already present in the URL
  if (window.location.href.includes("lang=")) {
    // If present, replace the lang parameter with the new language
    window.location.href = replaceLangParameter(window.location.href, lang);
  } else {
    // If not present, add the lang parameter to the URL
    window.location.href = addLangParameter(window.location.href, lang);
  }
}

// Function to add the lang parameter to a URL
function addLangParameter(url, lang) {
  var separator = url.includes("?") ? "&" : "?";
  return url + separator + "lang=" + lang;
}

// Function to replace the lang parameter in a URL
function replaceLangParameter(url, lang) {
  var regex = new RegExp("([?&])lang=[^&]*");
  return url.replace(regex, "$1lang=" + lang);
}

// Check if the lang parameter is present in the URL
if (!window.location.href.includes("lang=")) {
  // If not, add the lang parameter using the value stored in localStorage
  var lang = localStorage.getItem("lang");
  if (lang) {
    window.location.href = addLangParameter(window.location.href, lang);
  }
}

// Function to display the question and its answers
function showQuestion(question) {
  var questionDiv = document.getElementById("question");
  questionDiv.textContent = "Question: " + question.question;

  var answersDiv = document.getElementById("answers");
  answersDiv.innerHTML = ""; // Clear previous answers

  // Loop through each answer and append it to answersDiv
  question.answers.forEach(function (answer) {
    addAnswer(answersDiv, answer);
  });

  // Show the question container
  document.getElementById("question-cont").style.display = "grid";
  // hide error message if shown before
  document.getElementById("error-message").style.display = "none";
}

// Function to add an answer to the answersDiv
function addAnswer(answersDiv, answer) {
  var newAnswerDiv = document.createElement("div");
  newAnswerDiv.classList.add("col-md-3", "answer"); // Use Bootstrap classes for styling
  newAnswerDiv.dataset.answerId = answer.id; // Store the answer ID for voting

  // Create a span element to display the answer and count
  var answerSpan = document.createElement("span");
  answerSpan.textContent =
    "Answer: " + answer.answer + " (Count: " + answer.count + ")";
  newAnswerDiv.appendChild(answerSpan);

  // Add click event listener for voting
  newAnswerDiv.addEventListener("click", voteForAnswer);

  answersDiv.appendChild(newAnswerDiv);
}

// Function to handle voting for an answer
function voteForAnswer(event) {
  var answerId = event.target.dataset.answerId;

  // Make AJAX request to vote for the answer
  $.ajax({
    url: "/questions/vote.php",
    method: "POST",
    data: { answerId: answerId },
    dataType: "json",
    success: function (response) {
      if (response.success) {
        // Update the count displayed on the page
        var answerSpan = event.target.querySelector("span");
        if (answerSpan) {
          var currentCountText =
            answerSpan.textContent.match(/Count: (\d+)/)[1];
          var currentCount = parseInt(currentCountText);
          answerSpan.textContent = answerSpan.textContent.replace(
            /Count: \d+/,
            "Count: " + (currentCount + 1)
          );

          // Clear any previous error message
          var errorMessageElement = document.getElementById("error-message");
          errorMessageElement.textContent = "";
          errorMessageElement.style.display = "none";
        }
      } else {
        // Display the error message
        var errorMessageElement = document.getElementById("error-message");
        errorMessageElement.textContent = response.error;
        errorMessageElement.style.display = "block";
      }
    },
    error: function (xhr, status, error) {
      // Handle AJAX error
      var errorMessage = status + ": " + error;
      var errorMessageElement = document.getElementById("error-message");
      errorMessageElement.textContent = errorMessage;
      errorMessageElement.style.display = "block";
    },
  });
}
