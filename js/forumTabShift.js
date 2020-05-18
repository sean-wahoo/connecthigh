function changeTab(evt, category) {
  var i, forumtab, tablinks;

  forumtab = document.getElementsByClassName("forumtab");
  for (i = 0; i < forumtab.length; i++){
    forumtab[i].style.display = "none";
  }

  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++){
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(category).style.display = "block";
  evt.currentTarget.className += " active";
}
function userChangeTab(evt, category) {
  var i, usertab, usertablinks;

  usertab = document.getElementsByClassName("usertabs");
  for (i = 0; i < usertab.length; i++){
    usertab[i].style.display = "none";
  }

  usertablinks = document.getElementsByClassName("usertablinks");
  for (i = 0; i < usertablinks.length; i++){
    usertablinks[i].className = usertablinks[i].className.replace(" active", "");
  }
  document.getElementById(category).style.display = "block";
  evt.currentTarget.className += " active";
}
