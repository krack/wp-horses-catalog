$(window).scroll(function () {
    var headerTop = $(".detail-card > h1").offset().top + $(".detail-card > h1").outerHeight();

    if ($(window).scrollTop() > headerTop) {
        //when the header reaches the top of the window change position to fixed
        $("#horse-menu").addClass("fixed-menu");
        if ($("#wpadminbar").length > 0) {
            $("#horse-menu").addClass("with-admin-menu");
        }
    } else {
        //put position back to relative
        $("#horse-menu").removeClass("fixed-menu");
        $("#horse-menu").removeClass("with-admin-menu");
    }
});