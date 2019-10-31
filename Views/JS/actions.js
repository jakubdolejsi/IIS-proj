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


function searchInList()
{
    let input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        }
        else {
            li[i].style.display = "none";
        }
    }
}

function insertDiv(data)
{
}

function ifChecked()
{
    var checkBox = document.getElementById("myCheck");
    var text = document.getElementById("text");
    if (checkBox.checked == true) {
        text.style.display = "block";
    }
    else {
        text.style.display = "none";
    }
}