$(document).ready(function () {
    $(".fa-info-circle.mother").click(function(){
        var image = "<div class=\"mother-help\">";

        image += "<img src=\"/wp-content/plugins/horses-catalog/css/images/help_mothers.jpg\" />"
         image += "</div>";

        $("body").append(image);
        $("body").append("<div class=\"background-help\"></div>");
        $(".mother-help").click(function(){
            $(".mother-help").remove();
            $(".background-help").remove();            
        });
    });
});