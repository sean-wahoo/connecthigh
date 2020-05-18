function updateMessageRead() {
  var update = "nice job";
  var xhr = new XMLHttpRequest;
  xhr.open("POST","../includes/message_update.inc.php");
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onload = function() {
    
      alert(xhr.responseText);

  }
  xhr.send(encodeURI('update=' + update));
}
