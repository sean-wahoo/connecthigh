<?php
include '../includes/dbh.inc.php';
$chatbox = "<div id='chat_box' class='p-0'>";
if(isset($_POST['chat_username'])){
    $username = $_POST["session_username"];
    $username2 = $_POST['chat_username'];
    $id2 = $_POST['chat_id'];
    $sessionid = $_POST['session_id'];
    $sql1 = "SELECT id FROM users WHERE username = '$username'";
    $query1 = mysqli_query($conn, $sql1);
    $result1 = mysqli_fetch_assoc($query1);
    $from_user_id = implode(',',$result1);
    $sql2 = "SELECT id FROM users WHERE username = '$username2'";
    $query2 = mysqli_query($conn, $sql2);
    $result2 = mysqli_fetch_assoc($query2);
    $to_user_id = implode(',',$result2);
    $sqlNOTIF = "SELECT to_user_id, from_user_id FROM chat_message WHERE to_user_id = '$sessionid' AND from_user_id = '$id2'";
    $queryNOTIF = mysqli_query($conn, $sqlNOTIF);
    if(mysqli_num_rows($queryNOTIF) == '0'){
    }
    else{
      while($row = mysqli_fetch_assoc($queryNOTIF)){
        if($row['to_user_id'] == $sessionid && $row['from_user_id'] == $id2){
          $sqlUpdateMessageStatus = "UPDATE chat_message SET status = '1' WHERE to_user_id = '$sessionid' AND from_user_id = '$id2'";
          $queryUpdateMessageStatus = mysqli_query($conn, $sqlUpdateMessageStatus);
          $sqlRemoveNotification = "DELETE FROM notifications WHERE user_id = '$sessionid' AND from_id = '$id2' AND about = '2' AND description = 'message_sent'";
          $queryRemoveNotification = mysqli_query($conn, $sqlRemoveNotification);
        }
      }
    }
    $chatbox .="<div id='name_area' class='mt-0 sticky-top green-gradient text-center'style='z-index: 1;'><h1 style='z-index:0;color:white;'>" . $_POST['chat_username'] . "</h1>";
    $chatbox .="</div>";
    $chatbox .="<div id='messages_area' class='mb-3' ><ul>";
    $chatbox .="</ul></div>";
    $chatbox .="<div id='message_bar' class='green-gradient text-center sticky-bottom'>";
    $chatbox .="<input type='textarea' class='hidden_values_chat_messages d-inline-block align-middle w-75' id='message_bar_field' name ='chat_message' maxlength='127' class='form-control align-middle' >";
    $chatbox .="<button type='submit' class='hidden_values_chat_messages d-inline-block align-middle' id='send_chat' onclick='sendMessage(), sendPost()' name='message_send' ><i id='submit_button_icon' class='fas fa-arrow-circle-up fa-2x'></i></button>";
    $chatbox .="</div>";
    $chatbox .="<input type='hidden' class='hidden_values_chat_messages' id='to_user_id' value='" . $to_user_id . "' name='to_user_id' />";
    $chatbox .="<input type='hidden' class='hidden_values_chat_messages' id='from_user_id' value='" . $from_user_id . "' name='from_user_id' />";
    $chatbox .="<input type='hidden' class='hidden_values_chat_messages' id ='read_status' value='0' name='read_status' />";
    $chatbox .="<form action='../includes/message_update.inc.php' method='post' name='forPHP' id='forPHP'>";
    $chatbox .="<input type='hidden' name='id2' value='" . $id2 . "'/>";
    $chatbox .="<input type='hidden' name='session_id' value='" . $sessionid . "'/>";
    $chatbox .="</form>";
    $chatbox .='<script>
                  var messages = document.getElementById("messages_area");
                  var input = document.getElementById("message_bar_field");
                  function goToBottom(){
                    messages.scrollTop = messages.scrollHeight;
                  };
                  input.addEventListener("keyup", function(event){
                    if (event.keyCode === 13){
                      event.preventDefault();
                      document.getElementById("send_chat").click();
                    }
                  });
                function sendMessage(){
                var chatVars = document.getElementsByClassName("hidden_values_chat_messages");
                var formData = new FormData();
                for(var i=0; i<chatVars.length; i++){
                  formData.append(chatVars[i].name, chatVars[i].value);
                }
                var xmlHttp = new XMLHttpRequest();
                    xmlHttp.onreadystatechange = function(){
                      if(xmlHttp.readyState == 4 && xmlHttp.status == 200){
                      }
                    };
                xmlHttp.open("post", "../includes/chat_messenger.inc.php");
                xmlHttp.send(formData);
                document.getElementById("message_bar_field").value = "";
                goToBottom();
              }
                </script>
                <script>
                  function sendPost(){
                    $.ajax({
                      url:"../includes/message_update.inc.php",
                      type:"post",
                      data:{id2:'.$id2.',
                            session_id:'.$sessionid.'},
                      success:function(data){
                        $("#messages_area").html(data);
                      }
                    });
                  };
                  var check = setInterval(sendPost, 1000);
                </script>';

}
$chatbox .= "</div>";

?>

<?php echo $chatbox; ?>
