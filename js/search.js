$(document).ready(function(){
  var output = $("#output");
  $("#navbar_search").keyup(function(){
    output.show();
    var query = $(this).val();
      $.ajax({
        url: "../includes/search.php",
        method: "POST",
        data: {query:query},
        success:function(data){
          output.html(data);
          console.log(data);
        }
      });
      if(query == ''){
        $(output).hide();
      }
  });
  function showSearchResults(){
    output.show()
  };
  $(document).mouseup(function(e){
    var textInput = $("#navbar_search");

    // If the target of the click isn't the container
    if(!textInput.is(e.target) && textInput.has(e.target).length === 0 || !output.is(e.target) && output.has(e.target).length === 0){
        output.hide();
    }
  });

//smaller navbar
var outputSmall = $("#outputSmall")
$("#navbar_search_small").keyup(function(){
  outputSmall.show();
  var query = $(this).val();
    $.ajax({
      url: "../includes/search.php",
      method: "POST",
      data: {query:query},
      success:function(data){
        outputSmall.html(data);
        console.log(data);
      }
    });
    if(query == ''){
      $(outputSmall).hide();
    }
});
function showSearchResults(){
  outputSmall.show()
};


});
