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
