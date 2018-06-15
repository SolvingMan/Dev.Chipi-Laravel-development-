jQuery(document).ready(function () {
    $('.search-button').on('click', function (e) {
        e.preventDefault();
        if ($("#zip-determiner-form").valid()) {
            var location = $('#location').val();
            var street = $('#street').val();
            var number = $('#number').val();
            var entrance = $('#entrance').val();
            var poBox = $('#poBox').val();
            var url = "https://www.israelpost.co.il/zip_data.nsf/SearchZip?OpenAgent&Location=" + location + "&POB=" + poBox
                + "&Street=" + street + "&House=" + number + "&Entrance=" + entrance;
            $.ajax({
                type: "GET",
                url: url,
                success: function (data) {
                    console.log(data);
                    var zipCode = data.replace("<html>\n<head>\n</head>\n<body text=\"#000000\">\nRES8", "");
                    zipCode = zipCode.replace("\n</body>\n</html>", "");
                    console.log(zipCode);
                    if ($.isNumeric(zipCode)) {
                        $(".zip-code-block").html('<h3><span>המיקוד שלך: </span>' + zipCode + '</h3>');
                    } else {
                        $(".zip-code-block").html('<h3><span>נתוני קלט שגויים</span></h3>');
                    }

                }
            });
        } else {
            console.log("false");
        }
    });
    $("#zip-determiner-form").validate({
        rules: {
            location: {
                required: true
            },
            street: {
                required: true,
            },
            number: {
                required: true,
            },
        },
        messages: {
            location: {
                required: "<span class='validation_warning'>" + i18n.general.location_required + "</span>"
            },
            street: {
                required: "<span class='validation_warning'>" + i18n.general.street_required + "</span>"
            },
            number: {
                required: "<span class='validation_warning'>" + i18n.general.number_required + "</span>",
            },
        }
    });
    $("#location").autocomplete({
        minLength: 1,
        source: function (request, response) {
            var location = $("#location").val();
            $.ajax({
                url: "/ajax/getCityForAutocomplete",
                data: {
                    startWith: location,
                },
                success: function (data) {
                    data = JSON.parse(data);
                    response($.each(data, function (index, item) {
                        return item;
                    }));
                }
            });

        }
    });
    $("#street").autocomplete({
        minLength: 1,
        source: function (request, response) {
            var city = $("#location").val();
            var street = $("#street").val();
            $.ajax({
                url: "/ajax/getStreetForAutocomplete",
                data: {
                    startWith: street,
                    city: city,
                },
                success: function (data) {
                    data = JSON.parse(data);
                    response($.each(data, function (index, item) {
                        return item;
                    }));
                }
            });

        }
    });
});
