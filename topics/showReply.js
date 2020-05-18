var divState = {};
function showReply(a, b) {
  if (document.getElementById) {
      var divid = document.getElementById("reply_form_"+a+"+"+b);
      divState["reply_form_"+a+"+"+b] = (divState["reply_form_"+a+"+"+b]) ? false : true;
      //close others
      for (var div in divState) {
        if (divState[div] && div != "reply_form_"+a+"+"+b) {
          document.getElementById(div).style.display = 'none';
          divState[div] = false;
        }
      }
      divid.style.display = (divid.style.display == 'block' ? 'none' : 'block');
    }
  }
