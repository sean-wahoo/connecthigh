function handleServerResponse() {
    if (this.readyState == 4 && this.status == 200) {
        if (this.responseText == "accept_ok") {
            return document.getElementById('friend_request_feed').innerHTML = "<b>Request accepted!< /b>";
        } else if (this.responseText == "reject_ok") {
            return document.getElementById('friend_request_feed').innerHTML = "<b>Ignored!</b>";
        } else if (this.responseText == "YIKES") {
            return document.getElementById('friend_request_feed').innerHTML = "<b>ERROR</b>";
        }
        console.log(this.responseText)
    }
}

function friendReqHandler(action, reqid, user1, elem) {
    var ajax = new XMLHttpRequest();
    document.getElementById(elem).innerHTML = 'Accepted!';
    if (ajax) {
        ajax.open("POST", "../friends/friend_system.par.php");
        ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax.onreadystatechange = handleServerResponse;
    }
    ajax.open("POST", "../friends/friend_system.par.php", true);
    ajax.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send("action=" + action + "&reqid=" + reqid + "&user1=" + user1);
}
