

$(document).ready(function () {
    // if fisrt page (no history), clean
    if(history.length == 2){
        localStorage.removeItem('lastUrlSearch');
    }
    // add on click of cart
    $(".card a").click(function(e){
        localStorage.setItem('lastUrlSearch', window.location.href);
    });
    
    // on return button go on saved data if exist
    $("#return").click(function (e) {
        var lastUrlSearch = localStorage.getItem('lastUrlSearch');
        localStorage.removeItem('lastUrlSearch');
        if(lastUrlSearch){
            window.location.href = lastUrlSearch;
        }else{
            window.location.href =  "/"+$(this).attr("page-title")+"/";
        }
    });
});