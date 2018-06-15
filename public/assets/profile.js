jQuery(document).ready(function () {
    console.log(jsData);
    for (var i = 0; i < jsData.length; i++) {
        for (var j=0;j<jsData[i].products.length;j++)
        {
            console.log($("#reportForm"+i+"-"+j));
            $("#reportForm"+i+"-"+j).validate({
                rules: {
                    message: {
                        required: true
                    },
                    reason: {
                        required: true
                    },
                    photo: {
                        required: true
                    }
                },
                messages: {
                    message: {
                        required: "<span class='validation_warning'>" + i18n.general.message_required + "</span>"
                    },
                    photo: {
                        required: "<span class='validation_warning'>" + i18n.general.ordernumber_required + "</span>"
                    },
                    reason :{
                        required: "<span class='validation_warning'>" + i18n.general.ordernumber_required + "</span>"
                    },
                }
            });
        }
    }

    $("input[type=radio][name=reason]").on("change", function (e) {
        e.preventDefault();
        if ($(this).val() == 2) {
            $('.photo-block').removeClass("hidden");
            $(".photo").removeAttr('disabled', 'disabled');
        }
        if ($(this).val() == 1) {
            $('.photo-block').addClass("hidden");
            $('.photo').attr('disabled', 'disabled');
        }

    });
});
