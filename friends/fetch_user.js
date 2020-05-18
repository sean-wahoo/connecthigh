$(document).ready(function(){
  fetch_user();
  setInterval(function(){
    update_last_activity();
    fetch_user();
  }, 5000);

  function fetch_user(){
    $.ajax({
      url:"fetch_user.inc.php",
      method:"POST",
      success:function(data){
        $('#friendsList').html(data);
      }
    })
  }

  function update_last_activity() {
    $.ajax({
      url:"update_last_activity.php",
      success:function() {

      }
    })
  }
});
