$(document).ready(function(){
  function render(input) {
    let text = input.replace(/^[\u200B\u200C\u200D\u200E\u200F\uFEFF]/, "");
		if( text.startsWith('#MD#\n') ) {
		  return marked.parse( text.substr(5) );
		}
		return "";
  }
  ["bugnote_text", "description", "steps_to_reproduce"].forEach(function(name) {
    let text_src = $('#'+name);
    let text_dest = $('#'+name+'_md');
    if( text_src && text_dest ) {
      text_dest.html( render( text_src.val() ) );
    	text_src.on('input', function (event) {
    	  text_dest.html( render( text_src.val() ) );
      });
    }
  });
});

