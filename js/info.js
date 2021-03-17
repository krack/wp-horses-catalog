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


    $(".fa-info-circle.bonus").click(function(){
        var image = "<div class=\"bonus-help\">";
        image += "<span class=\"new\">NOUVEAUTE:</span>";

        image += "<p ><b><span class=\"new\">Lors de l’édition 2021,</span> les 5 meilleurs mâles du championnat ce sont vus attribuer une note supplémentaire correspondant à l’ « Impression Générale Etalon » qui leur ont apporté un Bonus s’ajoutant au total des épreuves du Championnat.</b></p>";
        
        image += "<p>Pour attribuer cette note, un comité composé de 3 membres du jury et de 3 membres de la Commission d’approbation du Stud Book a attribué une note « d’appréciation générale étalon » à chacun d’entre eux. Cette note est attribuée selon le barème suivant :</p>";
        image += "<ul>";
        image += "<li>Une note de 20 correspond à un bonus de + 1 point</li>";
        
        image += "<li>Une note de 18 correspond à un bonus de + 0,8 point</li>";
        
        image += "<li>Une note de 16 correspond à un bonus de + 0,6 point</li>";
        
        image += "<li>Une note de 14 correspond à un bonus de + 0,4 point</li>";
        
        image += "<li>Une note de 12 correspond à un bonus de + 0,2 point</li>";
        image += "</ul>";
        image += "<p><b>Ce Bonus a donc été ajouté au total des épreuves du championnat et déterminera le classement du Championnat des 5 premiers !</b></p>";
        
         image += "</div>";

        $("body").append(image);
        $("body").append("<div class=\"background-help\"></div>");
        $(".background-help").click(function(){
            $(".bonus-help").remove();
            $(".background-help").remove();            
        });
        $(".bonus-help").click(function(){
            $(".bonus-help").remove();
            $(".background-help").remove();            
        });
    });
});