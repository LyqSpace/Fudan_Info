function logout() {
    var d = new Date();
    d.setTime(d.getTime()-1);
    document.cookie = "login_serial=; expires=" + d.toUTCString();
    window.location.href = "login.html";
}