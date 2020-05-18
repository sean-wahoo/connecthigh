function getPosts() {
  var id = document.getElementById("profileId").value;
  $.ajax({
    url: "getPosts.php",
    method: "post",
    data: {
      id:id
    },
    success:function(data){
      $("#profilePosts").html(data);
    }
  })
}
$(document).ready(function(){
  getPosts();
})
