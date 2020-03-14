$(document).ready(function () {
    $(".fa-info-circle").click(function(){
        var image = "<div class=\"mother-help\">";
         image += "</div>";

        $("body").append(image);
        $(".mother-help").click(function(){
            $(".mother-help").remove();
        });
    });
});