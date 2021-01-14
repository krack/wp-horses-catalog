var originTop = $(".fixe-part").offset().top;
var fixePartHeight = $(".fixe-part").outerHeight();
$(window).scroll(function () {

    if ($(window).scrollTop() > originTop) {
        //when the header reaches the top of the window change position to fixed
        if ($(".fixe-replace").length == 0) {
            $("<div>").insertBefore(".fixe-part").addClass("fixe-replace").height(fixePartHeight);
        }
        $(".fixe-part").addClass("fixed");
        if ($("#wpadminbar").length > 0) {
            $(".fixe-part").addClass("with-admin-menu");
        }
    } else {
        //put position back to relative
        $(".fixe-replace").remove();
        $(".fixe-part").removeClass("fixed");
        $(".fixe-part").removeClass("with-admin-menu");
    }
});

$(document).ready(function () {
    $("a[href^='#'").click(function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var offset = $(href).offset().top - fixePartHeight;
        if ($("#wpadminbar").length > 0) {
            offset -= $("#wpadminbar").outerHeight();
        }
        $(window).scrollTop(offset);
    });
});