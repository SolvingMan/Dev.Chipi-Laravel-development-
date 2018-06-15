$(document).ready(function () {

    $("#chat-form").validate({
        rules: {
            message: {
                required: true
            },
        },
        messages: {
            message: {
                required: "<span class='validation_warning'>" + i18n.general.message_required + "</span>"
            },
        }
    });
    $("#report-chat-form").validate({
        rules: {
            message: {
                required: true
            },
        },
        messages: {
            message: {
                required: "<span class='validation_warning'>" + i18n.general.message_required + "</span>"
            },
        }
    });
});