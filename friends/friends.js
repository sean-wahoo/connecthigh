function friendToggle(type,user1,elem) {
  if(type == 'unfriend'){
    var confUnfriend = confirm('Are you sure you want to unfriend this user?')
    if(confUnfriend != true){
      return false;
    }
  }
  var ajax = ajaxObj("POST", "../friends/friend_system.par.php");
  ajax.onreadystatechange = function() {
    if(ajaxReturn(ajax) == true) {
      if(ajax.responseText == "friend_request_sent"){
        document.getElementById("profileButtons").innerHTML = 'Friend request sent!';
      }else if(ajax.responseText == "unfriend_ok"){
        document.getElementById("profileButtons").innerHTML = 'Unfriended!';
      }else {
        alert(ajax.responseText);
        document.getElementById("profileButtons").innerHTML = ajax.responseText;
        console.log(ajax.responseText);
      }
    }
  }
  ajax.send("type="+type+"&user1="+user1);
}
function blockToggle(type,blockee,elem) {
  var conf = confirm("Are you sure you want to block <?php echo $u;?>? They won't be able to view your profile and you won't be able to view theirs.");
  if(conf != true){
    return false;
  }
  var elem = document.getElementById(elem);
  elem.innerHTML = 'please wait...';
  var ajax = ajaxObj("POST", "../parsers/block_system.par.php");
  ajax.onreadystatechange = function() {
    if(ajaxReturn(ajax) == true) {
      if (ajax.responseText == "blocked_ok") {
        elem.innerHTML = '<button onclick="blockToggle(\'unblock\',<?php echo $u; ?>\',\'blockBtn\')">Unblock User</button>';
      }else if(ajax.responseText == "unblocked_ok") {
        elem.innerHTML = '<button onclick="blockToggle(\'block\',<?php echo $u; ?>\',\'blockBtn\')">Block User</button>';
    } else {
        alert(ajax.responseText);
        elem.innerHTML = 'Try again later';
    }
  }
}
ajax.send("type="+type+"&blockee="+blockee);
}
