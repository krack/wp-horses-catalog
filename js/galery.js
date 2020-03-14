$(document).ready(function () {
    $(".gallery .thumbnail img").click(function () {
        $(".gallery .thumbnail img").removeClass("current");
        $(this).addClass("current");
        $(".gallery #zoom").attr("src", $(this).attr("src"));
        $(".gallery #legend").text($(this).next(".legend").text());
       

    });
    $(".gallery .next").click(function(){
        var nextImages = $(".gallery .thumbnail .current").nextAll("img");
        if(nextImages.length > 0){
            nextImages[0].click();
        }
    });
    $(".gallery .previous").click(function(){
        var prevImages = $(".gallery .thumbnail .current").prevAll("img");
        if(prevImages.length > 0){
            prevImages[0].click();
        }
    });

    $(".gallery .thumbnail img").first().click();

});