$(document).ready(function () {
    $(".gallery .thumbnail img").click(function () {

        $(".gallery #zoom").attr("src", $(this).attr("src"));
        $(".gallery #legend").text($(this).next(".legend").text());

    });

    $(".gallery .thumbnail img").first().click();

});