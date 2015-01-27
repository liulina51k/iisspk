function login_show(id, param) {
    doAjaxGetJSON(id, 'login', param + '_login', rand());
}
function login_on(id, ac) {
    var username = $("#" + id + " input[name='username']").val();
    if (username == '') {
        alert('请填写用户名！');
        return false;
    }
    var password = $("#" + id + " input[name='password']").val();
    if (password == '') {
        alert('请填写密码！');
        return false;
    }
    username = encodeURIComponent(username);
    password = encodeURIComponent(password);
    var param = username + '_' + password;
    if (arguments[2]) {
        param += '_' + arguments[2];
    }
    param = encodeURI(param);
    doAjaxGetJSON(id, 'login', ac + '_on', param, rand());
}
function login_quit(id, ac) {
    doAjaxGetJSON(id, 'login', ac + '_quit', rand());
}
function user_login_pro(id) {
    var username = $("#" + id + " input[name='username']").val();
    if (username == '') {
        alert('请填写用户名！');
        $("#" + id + " input[name='username']").focus();
        return false;
    }
    var password = $("#" + id + " input[name='password']").val();
    if (password == '') {
        alert('请填写密码！');
        $("#" + id + " input[name='password']").focus();
        return false;
    }
    username = encodeURIComponent(username);
    password = encodeURIComponent(password);
    var param = username + '_' + password;
    if ($("#" + id + " input[name='anchor']").length)
    {
        param += '_' + $("#" + id + " input[name='anchor']").val();
    }
    if (arguments[1]) {
        param += '_' + arguments[1];
    }
    param = encodeURI(param);
    doAjaxProJSON(id, 'login', 'pro_login', param, rand());
}
function quit_quit() {
    doAjaxProJSON(rand(), 'login', 'quit_quit', rand());
}
function login_newsheadlogin(id, param) {
    doAjaxGetJSON(id, 'login', 'newshead_login', rand());
}
function login_newheadlogin(id, param) {
    doAjaxGetJSON(id, 'login', 'newhead_login', rand());
}


