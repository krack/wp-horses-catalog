

$(document).ready(function () {
    $("#return").click(function (e) {
        if(history.length > 2){
            window.history.back();
        }else{
            window.location.href =  "/etalons/";
        }
    });
});