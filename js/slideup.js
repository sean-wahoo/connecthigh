$(document).ready(function(){
  $("button").click(function(){
    $("p").velocity({bottom: '200px',}, "1000");
    $("h1").velocity({bottom: '200px'}, "1000");
  });
});
