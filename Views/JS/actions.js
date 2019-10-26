function login()
{
    if ((typeof (Storage) !== "undefined")) {
        if (sessionStorage.getItem("user_id") === 'null') {
            // alert(sessionStorage.getItem('user_id'));
            document.getElementById("login").children[0].style.display = "visible";
            document.getElementById("registration").children[0].style.display = "visible";
            document.getElementById("logout").children[0].style.display = "none";
        }
        else {
            // alert(sessionStorage.getItem('user_id'));
            document.getElementById("login").children[0].style.display = "none";
            document.getElementById("registration").children[0].style.display = "none";
            document.getElementById("logout").children[0].style.display = "visible";
        }
    }
}