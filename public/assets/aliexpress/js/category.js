$(document).ready(function () {
    var images = $('img.category-fixed-size-image');
    for (var i = 0; i < images.length; i++) {
        if (location.href == images[i].src) {
            images[i].src = location.origin + '/images/icon/index1/main_logo.png';
            images[i].style.height = '150px';
            images[i].style.marginTop = '45px';
        }
    }
});
