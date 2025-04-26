function hookInitMDE() {
  function mayInitMDE(ta) {
    if( ta.value == "#MD#" || ta.value.startsWith( '#MD#\n' ) ) {
      const easyMDE = new EasyMDE({
        element: ta,
        hideIcons: ["image", "upload-image"],
        spellChecker: false,
      });
      return true;
    }
    return false;
  }
  ["bugnote_text", "description", "steps_to_reproduce"].forEach(function(name) {
    const ta = document.getElementById(name);
    if( !mayInitMDE(ta) ){
      function mayInit() {
        if( mayInitMDE(ta) ) {
          ta.removeEventListener("keyup", mayInit)
        }
      }
      ta.addEventListener("keyup", mayInit);
    }
  });
}

if (document.readyState !== 'loading') {
  hookInitMDE();
} else {
  document.addEventListener('DOMContentLoaded', hookInitMDE);
}

