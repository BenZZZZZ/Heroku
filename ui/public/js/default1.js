
var BASEURL = 'http://localhost/BenZ/Test/Test3/NodeJsLearn/templates/',
    URL = window.location.href, ROUTE = URL.replace(BASEURL, "");
$(document).ready(function(){    
    $('#head-carousel, #tournament-carousel').carousel({
        interval: 4000
    })

    $('#head-carousel .item, #tournament-carousel .item').each(function(){
        var next = $(this).next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }
        next.children(':first-child').clone().appendTo($(this));
        for (var i=0;i<2;i++) {
            next=next.next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }

            next.children(':first-child').clone().appendTo($(this));
        }
    });


    $(".nav-tabs a").click(function(){
        $(this).tab('show');
    });
});

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

var getHashIdFromUrl = function getHashIdFromUrl() {
    return window.location.href.split('#')[1];
};