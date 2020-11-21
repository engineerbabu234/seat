$(document).ready(function(){
      $('.toggle-btn, .close-menu, .Navoverlay').click(function() {
        //console.log("Ganesh");
      // $('body').addClass('toggle-class');
      $("body").toggleClass("toggle-class");
    });
});
/**************/
// $(document).ready(function() {
//     $('#dataTable').DataTable();
// } );

/**loader javascript**/

    var preloader = $('#loader-wrapper');
    var myVar;

    function aakashloader(){
        preloader.css("transition", "all 0.5s");
        preloader.css("visibility", "hidden");
        preloader.css("opacity", "0");
        window.scrollTo(0, 0);
    };

    function loaderfun() {
        myVar = setTimeout(aakashloader, 800);
    }

/****end****/