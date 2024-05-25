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
  questionDiv.textContent = question.question;

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
  answerSpan.textContent = answer.answer + " (Count: " + answer.count + ")";
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

function fetchQuestion(code) {
  if (!code.match(/^\d{5}$/)) {
    console.error("Invalid code format");
    return;
  }

  // Make AJAX request to fetch data from PHP script
  $.ajax({
    url: "/questions/get_question.php",
    method: "GET",
    data: { code: code },
    dataType: "json",
    success: function (response) {
      if (response.error) {
        console.error(response.error);
        return;
      }
      question = response.question;
      showQuestion(question);
    },
    error: function (xhr, status, error) {
      // Handle AJAX error
      console.error(status + ": " + error);
    },
  });
}

function getCodeFromUrl() {
  const url = new URL(window.location.href);
  const pathSegments = url.pathname.split("/"); // Split the path into segments
  const potentialCode = pathSegments[pathSegments.length - 1]; // Get the last segment

  // Check if the last segment is a valid code
  if (potentialCode.match(/^\d{5}$/)) {
    return potentialCode; // Return the code if it's valid
  }

  // If the last segment is not a valid code, check query parameters
  const urlParams = new URLSearchParams(url.search);
  const code = urlParams.get("code");

  // Return the code if it's valid, otherwise return null
  return code && code.match(/^\d{5}$/) ? code : null;
}

function clearCodeFromUrl() {
  const url = new URL(window.location.href);
  const pathSegments = url.pathname.split("/"); // Split the path into segments
  const newPath = pathSegments.slice(0, -1).join("/") + "/"; // Remove the last segment (the code)
  history.replaceState(null, null, newPath + url.search); // Update the URL without the code
}
