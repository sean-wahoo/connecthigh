function getNotifications(){
    var username = "<?php echo $_SESSION['username']; ?>";
    var id = "<?php echo $_SESSION['id']; ?>";

    $.ajax({
      type: 'post',
      url: 'includes/notifications.inc.php',
      data: {
        username:username,
        id:id
      },
      success: function(response){
        $("#divnotifications").html(response);
      }
    });
  };
setInterval(getNotifications(), 1000);
function updateDiv() {
  $("#actualNotifications").load('includes/notifications.inc.php');
};

function getButton(){
  var id = "<?php echo $_SESSION['id']?>";
  $.ajax({
    type: 'post',
    url: 'includes/notificationButton.php',
    data: {
      id:id,
    },
    success: function(response){
      $("#button").html(response);
    }
  });
};
$(document).ready(function(){
  getButton();
});

$("#button").click(function() {
  $("#divnotifications").toggle();
});
