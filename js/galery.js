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

$(document).ready(function () {
    $("#video .thumbnail img").click(function () {
        var currentVideo = $("#video video.current").get(0)
        $("#video .thumbnail img").removeClass("current");
        $("#video video").removeClass("current");
        if(currentVideo){
            currentVideo.pause();
            currentVideo.load();
        }
        $(this).addClass("current");

        var idVideo = $(this).attr("video");
        $("#video .video_"+idVideo).addClass("current");

        $("#video #legend").text($(this).next(".legend").text());
       

    });
    $("#video .next").click(function(){
        var nextImages = $("#video .thumbnail .current").nextAll("img");
        if(nextImages.length > 0){
            nextImages[0].click();
        }
    });
    $("#video .previous").click(function(){
        var prevImages = $("#video .thumbnail .current").prevAll("img");
        if(prevImages.length > 0){
            prevImages[0].click();
        }
    });

    $("#video .thumbnail img").first().click();

});