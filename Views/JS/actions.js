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

function f(x)
{
    var input = "reservation/" + x['type'] + "/" + x['name'] + "/" + x['label'] + "/" + x['begin'];
    document.getElementById('test').setAttribute("href", input);
}

function redirectTo()
{

}

function editorAjax()
{

    var price = document.getElementById('price').value;
    var dataString = 'price=' + price;

    $.ajax({
        type: "post",
        // url: "Editor",
        data: dataString,
        cache: false,
        success: function (html) {
            $('#msg').html(html);
        }
    });

    return false;
}

function ajaxForm()
{

    var price = document.getElementById('price');
    var seat = document.getElementById('seat');
    var discount = document.getElementById('discount');
    var email = document.getElementById('email');
    var dataString = 'price=' + price + '&seat=' + seat + '&discount=' + discount + '&email=' + email;

    $.ajax({
        type: "post",
        url: "CashierController",
        data: dataString,
        cache: false,
        success: function (html) {
            $('#msg').html(html);
        }
    });

    return false;
}


