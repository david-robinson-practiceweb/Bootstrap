$(document).ready(function() {
  // This is a test of the JSON override
  window.JSON._parse = window.JSON.parse;
  window.JSON.parse = function( data) {
    return window["eval"]("(" + data + ")");
  }
});
