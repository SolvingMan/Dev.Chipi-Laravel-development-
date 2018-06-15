// newsletter form handling
$(document).ready(function () {


    $('#message').modal();
    setTimeout(function () {
        $('#message').modal('hide');
    }, 3000);

    function setCookie (name, value, expires, path, domain, secure) {
        document.cookie = name + "=" + escape(value) +
            ((expires) ? "; expires=" + expires : "") +
            ((path) ? "; path=" + path : "") +
            ((domain) ? "; domain=" + domain : "") +
            ((secure) ? "; secure" : "");
    }

    function getCookie(name) {
        var cookie = " " + document.cookie;
        var search = " " + name + "=";
        var setStr = null;
        var offset = 0;
        var end = 0;
        if (cookie.length > 0) {
            offset = cookie.indexOf(search);
            if (offset != -1) {
                offset += search.length;
                end = cookie.indexOf(";", offset)
                if (end == -1) {
                    end = cookie.length;
                }
                setStr = unescape(cookie.substring(offset, end));
            }
        }
        return(setStr);
    }


    $.validator.addMethod("regex", function (value, element, regexpr) {
        return regexpr.test(value);
    }, "<span class='validation_warning'>" + i18n.general.email_required_format + "</span>");

    $('#newsletter-button').on('click', function (e) {
        e.preventDefault();

        // // check if there something in email field
        if (!$("#newsletter").valid())return;
        var email = $('#newsletter-email-footer').val();
        console.log(email);
        var signed_in_text = i18n.general.signed_in_popup_message;
        var not_signed_in_text = i18n.general.not_signed_in_popup_message;
        $('#newsletter-text').html(signed_in_text);
        $.get("/ajax/checkEmail",
            {"email": email},
            function (data, status) {
                console.log(+data);
                console.log(status);
                if (status === "success" && data == 0) {
                    $('#newsletter-modal').modal();
                    $('#newsletter-email-footer').val('');
                } else {
                    $('#newsletter-text').html(not_signed_in_text);
                    $('#newsletter-modal').modal();
                }
            });
    });
    $("#newsletter").validate({
        rules: {
            email: {
                required: true,
                regex: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            }
        },
        messages: {
            email: {
                required: "<span class='validation_warning'>" + i18n.general.email_required + "</span>",
                email: "<span class='validation_warning'>" + i18n.general.email_required_format + "</span>"
            }
        }
    });
    $("#newsletter-sigin_form").validate({
        rules: {
            email: {
                required: true,
                regex: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            }
        },
        messages: {
            email: {
                required: "<span class='validation_warning'>" + i18n.general.email_required + "</span>",
                email: "<span class='validation_warning'>" + i18n.general.email_required_format + "</span>"
            }
       }//,
        // errorPlacement: function ( error ) {
        //     $( ".modal-footer" ).prepend(error);
        // }
   });


    var usr_id = getCookie('usr_id');
    if(usr_id == null) {
      var cookkie_user = getCookie('user');
      if (cookkie_user == null) {
          var now = new Date();
          var expire = new Date();
          expire.setFullYear(now.getFullYear());
          expire.setMonth(now.getMonth());
          expire.setDate(now.getDate()+1);
          expire.setHours(0);
          expire.setMinutes(0);
          expire.setSeconds(0);
          setCookie("user", "was", expire.toString(), "/");
          setTimeout(function () {
              $('#newsletter-modal_signin').modal();
          }, 10000);
      }
  }

    $('#newsletter-sigin').on('click', function(e) {
        e.preventDefault();
        if (!$("#newsletter-sigin_form").valid())return false;
        var email = $('#newsletter-email').val();
       $.ajax({
            type: "GET",
            url: "/newslatterSignIn.php",
            data: "email=" + email,
            success: function(msg){
                if(msg == 'exist'){
                    $('#msg').text('דוא"ל זה כבר קיים').css({'display':'block', 'color':'red'});
                    $('#newsletter-email').val(' ');
                    setTimeout(function (){
                        $('#msg').css('display','none');
                    },1000);
                    return false;
                }else{
                    location.href = location.origin + '/newslatterSignIn.php';
                }
            }
        });
    });

});
