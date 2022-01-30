
$(document).ready(function () {
    $("[title]").click(function (e) {
        $(".title-plugin").remove();
        var text = $(this).attr("title");

        var tooltip = "<span class=\"title-plugin\">";

        tooltip += text;
        tooltip += "</span>";

        $("body").append(tooltip);
        $(".title-plugin").css("position", "absolute");
        $(".title-plugin").css("top", e.pageY+"px");
        $(".title-plugin").css("left", e.pageX+"px");
        $(".title-plugin").css("background-color", "black");
        $(".title-plugin").css("color", "white");
        $(".title-plugin").css("max-width", "25%");
        $(".title-plugin").css("padding", "10px");
        e.stopPropagation();
        
    });

    $("body").click(function (e) {
        $(".title-plugin").remove();
    });

});