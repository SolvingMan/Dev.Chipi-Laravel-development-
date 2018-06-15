jQuery(document).ready(function () {

    // if your code broke without a reason - this line is the cause,
    // probably
    // window.history.pushState("", "", location.pathname);

    var images = $('img.category-fixed-size-image');
    for (var i = 0; i < images.length; i++) {
        if (location.href == images[i].src) {
            images[i].src = location.origin + '/images/icon/index1/main_logo.png';
            images[i].style.height = '150px';
            images[i].style.marginTop = '70px';
            images[i].style.marginBottom = '40px';
        }
    }

    $(".submit").click(function () {
        var filters = $('input[type = checkbox]:checked');
        var parameters = "?";
        var filtersArray = [];
        for (var i = 0; i < filters.length; i++) {
            if (filtersArray[filters[i].name]) {
                filtersArray[filters[i].name] += "|" + filters[i].value;
            }
            else {
                filtersArray[filters[i].name] = filters[i].value;
            }
        }
        for (var i in filtersArray) {
            var filterName = i.replace(new RegExp("&", 'g'), "%26");
            var filterValue = filtersArray[i].replace(new RegExp("&", 'g'), "%26");
            parameters += filterName + "=" + filterValue + "&";
        }
        if (location.pathname == "/ebay/search") {
            var keyword = $("#search-input").val();
            parameters += "searchKeywords" + "=" + keyword + "&";
        }
        parameters = parameters.substring(0, parameters.length - 1);
        parameters = parameters.replace(new RegExp(" ", 'g'), "+");
        location.href = location.pathname + parameters;
    });
    $('.filters').click(function (e) {
        e.preventDefault();
        var filterName = $(this).data('name');
        $(".item-hidden[data-filter-name=\"" + filterName + "\"]").toggleClass('hidden');
        $(this).text(function (index, text) {
            return text == 'טען מסננים נוספים' ? 'סגור' : 'טען מסננים נוספים';
        });
    });

    $('.item').click(function (e) {
        if ($('input[type = checkbox]:checked').length == 0) {
            $('.container-fluid').fadeOut(1000);
        }
        else {
            $('.container-fluid').fadeIn(1000);
        }
    });
});