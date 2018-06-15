// checkout form validation
// $(document).ready(function () {
//     window.validateForm = $("#billingForm").validate({
//         rules: {
//             name: {
//                 required: true
//             },
//             email: {
//                 required: true,
//                 regex: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
//             },
//             city: {
//                 required: true
//             },
//             street: {
//                 required: true
//             },
//             building_number: {
//                 required: true
//             },
//             postal_code: {
//                 required: true
//             }
//         },
//         messages: {
//             name: {
//                 required: "<span class='validation_warning'>" + i18n.general.email_required + "</span>"
//             },
//             email: {
//                 required: "<span class='validation_warning'>" + i18n.general.name_required + "</span>",
//                 email: "<span class='validation_warning'>" + i18n.general.email_required_format + "</span>"
//             },
//             city: {
//                 required: "<span class='validation_warning'>" + i18n.general.city_required + "</span>"
//             },
//             street: {
//                 required: "<span class='validation_warning'>" + i18n.general.street_required + "</span>"
//             },
//             building_number: {
//                 required: "<span class='validation_warning'>" + i18n.general.building_number_required + "</span>"
//             },
//             postal_code: {
//                 required: "<span class='validation_warning'>" + i18n.general.postal_code_required + "</span>"
//             }
//         }
//     });
// });


//auth page validations
$(document).ready(function () {
    $.validator.addMethod("regex", function (value, element, regexpr) {
        return regexpr.test(value);
    }, "<span class='validation_warning'>" + i18n.general.email_required_format + "</span>");

    $("#commentForm").validate({
        rules: {
            name: {
                required: true
            },
            surname: "required",
            password: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            email: {
                required: true,
                regex: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            }
        },
        messages: {
            name: {
                required: "<span class='validation_warning'>" + i18n.general.name_required + "</span>"
            },
            surname: {
                required: "<span class='validation_warning'>" + i18n.general.surname_required + "</span>"
            },
            email: {
                required: "<span class='validation_warning'>" + i18n.general.email_required + "</span>",
                email: "<span class='validation_warning'>" + i18n.general.email_required_format + "</span>"
            },
            password: {
                minlength: "<span class='validation_warning'>" + i18n.general.password_6_symbols_minimum + "</span>",
                maxlength: "<span class='validation_warning'>Password has 20 symbols maximum length</span>",
                required: "<span class='validation_warning'>" + i18n.general.please_specify_password + "</span>",
                password: "<span class='validation_warning'>Password must be between 6 and 20 symbols length</span>"
            }
        }
    });

    $('#loginForm').validate({
        rules: {
            password: {
                required: true
            },
            email: {
                required: true,
                regex: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            }
        },
        messages: {
            email: {
                required: "<span class='validation_warning'>" + i18n.general.email_required + "</span>",
                email: "<span class='validation_warning'>" + i18n.general.email_required_format + "</span>"
            },
            password: {
                required: "<span class='validation_warning'>" + i18n.general.please_specify_password + "</span>",
            }
        }
    });

    $('#forgotPassForm').validate({
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

    $('#register_button').addClass('unavailable');

    $('#check_rules').click(function () {
        if ($(this).is(':checked')) {
            $('#register_button').removeAttr('disabled');
        } else {
            $('#register_button').attr('disabled', 'disabled');
        }

        $('#register_button').toggleClass('unavailable');
    });

    $('#forgotPass').on('click', function (e) {
        // e.preventDefault();
    });
    $(".login-button").click(function () {
        $("body").prepend('<div id="preloader"> <div class="loader"></div> </div>');
    });


});