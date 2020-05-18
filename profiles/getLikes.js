function getLikes() {
  var id = document.getElementById("profileId").value;
  $.ajax({
    url: "getLikes.php",
    method: "post",
    data: {
      id:id
    },
    success:function(data){
      $("#profileLikes").html(data);
    }
  })
}
$(document).ready(function(){
  getLikes();
})
