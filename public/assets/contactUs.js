jQuery(document).ready(function () {
    if (typeof jsDataSuccess !== 'undefined') {
        $('#modal-contact-success').modal('show');
    }
    $("#department").on("change", function (e) {
        e.preventDefault();
        if ($(this).val() == 0) {
            if ($('.history-empty').length > 0) {
                $('.history-empty').removeClass("hidden");
                $(".hidden-block").addClass("hidden");
            }
            else {
                $(".hidden-block").removeClass("hidden");
                //$("#userID").removeAttr('disabled', 'disabled');
                $("#ordernumber").removeAttr('disabled', 'disabled');
                $(".order-number").removeClass('hidden');
            }
        }
        else if ($(this).val() == 2 || $(this).val() == 4) {
            $(".hidden-block").removeClass("hidden");
            //$("#userID").attr('disabled', 'disabled');
            $("#ordernumber").attr('disabled', 'disabled');
            $(".order-number").addClass('hidden');
            $('.history-empty').addClass("hidden");
        }
        else {
            $(".hidden-block").addClass("hidden");
            $('.history-empty').addClass("hidden");
        }
    });
    $("#contactForm").validate({
        rules: {
            message: {
                required: true
            },
            ordernumber: {
                required: true
            }
        },
        messages: {
            message: {
                required: "<span class='validation_warning'>" + i18n.general.message_required + "</span>"
            },
            ordernumber: {
                required: "<span class='validation_warning'>" + i18n.general.ordernumber_required + "</span>"
            }
        }
    });
    $("#ordernumber").on("change", function (e) {
        var value = $(this).val();
        for (var i = 0; i < jsData.length; i++) {
            if (jsData[i].ordernumber == value) {
                $('#modal-contact-warning').modal('show');
                $('#default-ordernumber').prop('selected', true);
                $('#ordernumber option[value=\"' + value + '\"]').prop('disabled', true);
            }
        }
    });
});
