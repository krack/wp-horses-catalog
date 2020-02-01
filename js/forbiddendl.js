$(document).ready(function () {
    $("img").mousedown(function (event) {
        console.log("Marina nue" + event.which);
        if (event.which === 3) {
            event.preventDefault();
            event.stopPropagation();
        }

    });


});