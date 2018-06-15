var math = {};

math.round = function (number, precision) {
    var factor = Math.pow(10, precision);
    var tempNumber = number * factor;
    var roundedTempNumber = Math.round(tempNumber);
    return roundedTempNumber / factor;
};

// example of api response
function ge() {
    var res = {
        "ResponseCode": "0",
        "Description": "Low Profile Code Found",
        "terminalnumber": "1000",
        "lowprofilecode": "b2f222e0-3d3b-48cd-980e-4437ccfd5f6b",
        "Operation": "1",
        "ProssesEndOK": "0",
        "DealRespone": "0",
        "DealResponse": "0",
        "InternalDealNumber": "17998642",
        "CardValidityYear": "2017",
        "CardValidityMonth": "9 ",
        "CardOwnerID": "040617649",
        "NumOfPayments": "1",
        "ExtShvaParams_CardNumber5": "5701",
        "ExtShvaParams_Status1": "0",
        "ExtShvaParams_Sulac25": "1",
        "ExtShvaParams_JParameter29": "0",
        "ExtShvaParams_Tokef30": "0917",
        "ExtShvaParams_Sum36": "14400",
        "ExtShvaParams_SumStars52": "00000000",
        "ExtShvaParams_ApprovalNumber71": "0000000",
        "ExtShvaParams_FirstPaymentSum78": "00000000",
        "ExtShvaParams_ConstPayment86": "00000000",
        "ExtShvaParams_NumberOfPayments94": "00",
        "ExtShvaParams_AbroadCard119": "1",
        "ExtShvaParams_CardTypeCode60": "1",
        "ExtShvaParams_Mutag24": "1",
        "ExtShvaParams_CardOwnerName": "DMYTRO CHERKASHYN",
        "ExtShvaParams_CardToken": "771bebcf-2a36-48e9-be35-2b11b8e05ebb",
        "ExtShvaParams_CardHolderIdentityNumber": "040617649",
        "ExtShvaParams_CreditType63": "1",
        "ExtShvaParams_DealType61": "01",
        "ExtShvaParams_ChargType66": "50",
        "ExtShvaParams_TerminalNumber": "1000",
        "ExtShvaParams_BinId": "0",
        "CardOwnerEmail": "testsite@test.co.il",
        "CardOwnerName": "DMYTRO CHERKASHYN",
        "CardOwnerPhone": "039436100",
        "ReturnValue": "144,,",
        "CoinId": "1",
        "OperationResponse": "0",
        "OperationResponseText": "OK"
    }
}
// waiting bstrap snippet
var waitingDialog = waitingDialog || (function ($) {
        'use strict';

        // Creating modal dialog's DOM
        var $dialog = $(
            '<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
            '<div class="modal-dialog modal-m">' +
            '<div class="modal-content">' +
            '<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
            '<div class="modal-body">' +
            '<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
            '</div>' +
            '</div></div></div>');

        return {
            /**
             * Opens our dialog
             * @param message Custom message
             * @param options Custom options:
             *                  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
             *                  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
             */
            show: function (message, options) {
                // Assigning defaults
                if (typeof options === 'undefined') {
                    options = {};
                }
                if (typeof message === 'undefined') {
                    message = 'Loading';
                }
                var settings = $.extend({
                    dialogSize: 'm',
                    progressType: '',
                    onHide: null // This callback runs after the dialog was hidden
                }, options);

                // Configuring dialog
                $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
                $dialog.find('.progress-bar').attr('class', 'progress-bar');
                if (settings.progressType) {
                    $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
                }
                $dialog.find('h3').text(message);
                // Adding callbacks
                if (typeof settings.onHide === 'function') {
                    $dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
                        settings.onHide.call($dialog);
                    });
                }
                // Opening dialog
                $dialog.modal();
            },
            /**
             * Closes dialog
             */
            hide: function () {
                $dialog.modal('hide');
            }
        };

    })(jQuery);

$(document).ready(function () {
    var $validator = $("#myform").validate();

    // $('.checkout-discount').hide();
    // $('.total-price-with-discount').hide();
    $('.checkout-error').hide();

    var billingFormOptions = {
        rules: {
            name: {required: true},
            surname: {required: true},
            email: {
                required: true,
                regex: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            },
            city: {required: true},
            street: {required: true},
            building_number: {required: true},
            apartment_number: {
                required: true,
                number: true
            },
            postal_code: {
                required: true,
                number:true,
                maxlength: 7,
                minlength:5,
            },
            telephone: {required: true}
        },
        messages: {
            name: {
                required: "<span class='validation_warning'>" + i18n.general.name_required + "</span>"
            },
            surname: {
                required: "<span class='validation_warning'>" + i18n.general.surname_required + "</span>"
            },
            email: {
                required: "<span class='validation_warning'>" + i18n.general.name_required + "</span>",
                email: "<span class='validation_warning'>" + i18n.general.email_required_format + "</span>"
            },
            city: {
                required: "<span class='validation_warning'>" + i18n.general.city_required + "</span>"
            },
            street: {
                required: "<span class='validation_warning'>" + i18n.general.street_required + "</span>"
            },
            building_number: {
                required: "<span class='validation_warning'>" + i18n.general.building_number_required + "</span>"

            },
            apartment_number: {
                required: "<span class='validation_warning'>" + i18n.general.apartment_number_required + "</span>",
                number: "<span class='validation_warning'>" + i18n.general.apartment_number_number_only + "</span>"
            },
            postal_code: {
                required: "<span class='validation_warning'>" + i18n.general.postal_code_required + "</span>",
                number: "<span class='validation_warning'>" + i18n.general.postal_code_number + "</span>",
                maxlength: "<span class='validation_warning'>" + i18n.general.postal_code_maxlength + "</span>",
                minlength: "<span class='validation_warning'>" + i18n.general.postal_code_minlength + "</span>"
            },
            telephone: {
                required: "<span class='validation_warning'>" + i18n.general.telephone_required + "</span>"
            }
        }
    };
    window.validateForm = $("#billingForm").validate(billingFormOptions);

    // var paymentFormOptions = {
    //     rules: {
    //         card_number: {
    //             required: true,
    //             creditcard: true
    //         },
    //         security_code: {required: true},
    //         expiry_month: {required: true},
    //         expiry_year: {required: true},
    //         name: {required: true},
    //         surname: {required: true}
    //     },
    //     messages: {
    //         card_number: {
    //             required: "<span class='validation_warning'>" + i18n.general.card_number_required + "</span>",
    //             creditcard: "<span class='validation_warning'>" + i18n.general.card_number_invalid + "</span>"
    //         },
    //         security_code: {
    //             required: "<span class='validation_warning'>" + i18n.general.security_code_required + "</span>"
    //         },
    //         expiry_month: {
    //             required: "<span class='validation_warning'>" + i18n.general.please_fill_field + "</span>"
    //         },
    //         expiry_year: {
    //             required: "<span class='validation_warning'>" + i18n.general.please_fill_field + "</span>"
    //         },
    //         name: {
    //             required: "<span class='validation_warning'>" + i18n.general.name_required + "</span>"
    //         },
    //         surname: {
    //             required: "<span class='validation_warning'>" + i18n.general.surname_required + "</span>"
    //         }
    //     }
    // };
    // window.validateForm = $("#paymentForm").validate(paymentFormOptions);

    // Smart Wizard
    $('#smartwizard').smartWizard({
        selected: 0,
        enableFinishButton: false,
        autoAdjustHeight: true,
        theme: 'arrows',
        transitionEffect: 'fade',
        anchorSettings: {
            anchorClickable: true,
            enableAllAnchors: true
        },
        toolbarSettings: {
            showNextButton: false,
            showPreviousButton: false,
            toolbarPosition: 'bottom',
            toolbarExtraButtons: [
                {
                    label: i18n.general.finish_an_order,
                    css: 'btn btn-default btn-next',
                    onClick: function () {
                        $('#smartwizard').smartWizard("next");
                    }
                }
            ]
        }
    });


    function updateUser(data) {
        $.post("/ajax/updateUser",
            {"data": data},
            function (data, status) {
            })
    }

    function updateNotice() {
        var noticeText = $("#notice").val();
        $.get("/ajax/storeNotice",
            {"notice": noticeText},
            function (data, status) {
            })
    }

    // function updateCoupon() {
    //     var couponId = $("#coupon").val();
    //     console.log("selected coupon");
    //     console.log(couponId);
    //     // $.get("/ajax/storeNotice",
    //     //     {"notice": noticeText},
    //     //     function (data, status) {
    //     //         console.log(data);
    //     //         console.log(status);
    //     //     })
    // }
    //
    // // update coupon on enter page
    // updateCoupon();
    //
    // // update coupon on leave blur
    // $("#submit-coupon").on('click', function () {
    //     var couponCode = $("#coupon").val();
    //     $.get("/ajax/storeCouponCode",
    //         {"couponCode": couponCode},
    //         function (data, status) {
    //             var price = $('.total-price-checkout span').text();
    //             if (data.errorCode == "200") {
    //                 var discount = math.round(price * (data.discount / 100), 1);
    //                 price = price - discount;
    //                 loadIframe(data.discount / 100);
    //                 $('.total-price-with-discount span').html(price + ' ' + '<i class="fa fa-ils"></i>');
    //                 $('.total-price-with-discount-table span').html(price + ' ' + '<i class="fa fa-ils"></i>');
    //                 $('.checkout-discount span').html(discount + '- ' + '<i class="fa fa-ils"></i>');
    //                 $('.checkout-discount-table span').html(discount + '- ' + '<i class="fa fa-ils"></i>');
    //                 $('.checkout-error').hide();
    //                 $('.checkout-discount').show();
    //                 $('.total-price-with-discount').show();
    //                 $('.hidden-td').show();
    //             }
    //             if (data.errorCode == "404") {
    //                 $('.checkout-discount').hide();
    //                 $('.total-price-with-discount').hide();
    //                 $('.checkout-error').show();
    //                 $('.hidden-td').hide();
    //                 loadIframe();
    //             }
    //
    //             console.log(data);
    //             console.log(price);
    //         })
    // });

    function loadIframe(discount) {
        discount = discount || 0;
        $.get("/ajax/loadIframe",
            {"discount": discount},
            function (data, status) {
                if (status === "success") {
                    $("#payment-iframe").attr('src', data['ResponseURL']);
                }
                else {
                    location.reload();
                }
            })
    }

    $('#payment-iframe').load(function () {
        waitingDialog.hide();
    });

    // next/previous step events handler
    $("#smartwizard").on("leaveStep", function (e, anchorObject, stepNumber, stepDirection) {
        var result = true;

        if (stepNumber === 0) {
            result = $("#billingForm").valid();
            if (result) {
                // get data from form
                var formData = $("#billingForm").serialize();

                // update user in DB on leaving step 1
                updateUser(formData);

                // put user notice in session storage
                updateNotice();

                // modal wait dialog popup
                waitingDialog.show(i18n.general.loading);

                // background payment iframe loading
                loadIframe();

                // hide buttons on page 2
                $(".btn-prev").addClass("hidden");
                $(".btn-next").addClass("hidden");

                // scroll to the top
                window.scrollTo(0, 0);
            } else {
                var block = $("#smartwizard"),
                    pos = block.find('.error').offset().top-20;
                $('body,html').animate({scrollTop: pos}, 500);
            }

        }
        else if (stepNumber === 1) {
            // get finish button back
            $(".btn-prev").removeClass("hidden");
            $(".btn-next").removeClass("hidden");
        }

        return result;
    });

// External Button Events
    $("#reset-btn").on("click", function () {
        // Reset wizard
        alert("reset");
        $('#smartwizard').smartWizard("reset");
        return true;
    });

    $("#prev-btn").on("click", function () {
        // Navigate previous
        $('#smartwizard').smartWizard("prev");
        return true;
    });


    $("#billingForm input").trigger("blur");

    $('#smartwizard').smartWizard("reset");

    $(".sw-btn-next").on('click', function () {
        nextBtnClick();
    });
    $(".zip-code-button").on('click', function (e) {
        e.preventDefault();
        window.open("/main/zipDeterminer","Zip Determiner","width=700,height=550");
    });

    /*$("#smartwizard").on('.btn-next','click', function(){
        var block = $("#smartwizard");
        if (block.find('error')){
            var pos = block.find('error').offset().top;
            $('body,html').animate({scrollTop: pos}, 1500);

        }
    });*/
});