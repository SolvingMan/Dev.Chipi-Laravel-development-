window.fbAsyncInit = function () {
    FB.init({
        appId: '418846555167177',
        xfbml: true,
        version: 'v2.9'
    });
    FB.AppEvents.logPageView();
};

function checkLoginState() {
    FB.getLoginStatus(function (response) {
        statusChangeCallback(response);
    });
}
function statusChangeCallback(response) {
    if (response.status === 'connected') {
        loginFB();
    } else {
        console.log('Please log ' + 'into this app.');
    }
}
function loginFB() {
    FB.api('/me', {fields: ['last_name', 'first_name', 'email']}, function (response) {
        var data = {};
        data.id = response.id;
        data.email = response.email;
        data.firstName = response.first_name;
        data.lastName = response.last_name;
        $.ajax({
            url: 'auth/loginfb',
            type: 'POST',
            data: data,
            success: function (data) {
                console.log(data);
                location.replace('/auth/redirect');
            }
        })
    });
}