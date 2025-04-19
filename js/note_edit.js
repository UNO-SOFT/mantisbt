$(document).ready(function(){
  function render(input) {
    let text = input.replace(/^[\u200B\u200C\u200D\u200E\u200F\uFEFF]/, "");
		if( text.startsWith('#MD#\n') ) {
		  return marked.parse( text.substr(5) );
		}
		return "";
  }
  ["bugnote_text", "description", "steps_to_reproduce"].forEach(function(name) {
    const ta = document.getElementById(name);
    if( ta.value == '#MD#' || ta.value.startsWith( '#MD#\n' ) ) {
      const easyMDE = new EasyMDE({
        element: ta,
        hideIcons: ["image", "upload-image"],
        spellChecker: false,
      });
    }
  });
});

