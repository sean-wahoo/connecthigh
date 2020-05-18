
var submitButton = document.getElementById("submitSubjects");

console.log(submitButton);

$('.English').on('change', function(){
  if($('.English:checked').length < 1){
    submitButton.disabled = true;
  }
  if($('.English:checked').length > 0 && $('.englishSub:checked').length < 2){
    submitButton.disabled = true;
  }
});
$('.Math').on('change', function(){
  if($('.Math:checked').length < 1){
    submitButton.disabled = true;
  }
  if($('.Math:checked').length > 0 && $('.mathSub:checked').length < 2){
    submitButton.disabled = true;
  }
});
$('.Science').on('change', function(){
  if($('.Science:checked').length < 1){
    submitButton.disabled = true;
  }
  if($('.Science:checked').length > 0 && $('.scienceSub:checked').length < 2){
    submitButton.disabled = true;
  }
});
$('.SocialStudies').on('change', function(){
  if($('.SocialStudies:checked').length < 1){
    submitButton.disabled = true;
  }
  if($('.sstudies:checked').length > 0 && $('.sstudiesSub:checked').length < 2){
    submitButton.disabled = true;
  }
});


$('.englishSub').on('change', function(){
  console.log("hello");

  if($('.englishSub:checked').length > 2 ){
    this.checked = false;
  }
  else if($('.englishSub:checked').length < 2){
    submitButton.disabled = true;
  }
  else if($('.englishSub:checked').length = 2){
    submitButton.disabled = false;
  };
});
$('.mathSub').on('change', function(){
  console.log("hello");

  if($('.mathSub:checked').length > 2 ){
    this.checked = false;
  }
  else if($('.mathSub:checked').length < 2){
    submitButton.disabled = true;
  }
  else if($('.mathSub:checked').length = 2){
    submitButton.disabled = false;
  };
});
$('.scienceSub').on('change', function(){
  console.log("hello");

  if($('.scienceSub:checked').length > 2 ){
    this.checked = false;
  }
  else if($('.scienceSub:checked').length < 2){
    submitButton.disabled = true;
  }
  else if($('.scienceSub:checked').length = 2){
    submitButton.disabled = false;
  };
});
$('.sstudiesSub').on('change', function(){
  console.log("hello");

  if($('.sstudiesSub:checked').length > 2 ){
    this.checked = false;
  }
  else if($('.sstudiesSub:checked').length < 2){
    submitButton.disabled = true;
  }
  else if($('.sstudiesSub:checked').length = 2){
    submitButton.disabled = false;
  };
});
