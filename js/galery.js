$(document).ready(function () {
    $(".gallery .thumbnail img").click(function () {

        $(".gallery #zoom").attr("src", $(this).attr("src"));

    });

    $(".gallery .thumbnail img").first().click();

});