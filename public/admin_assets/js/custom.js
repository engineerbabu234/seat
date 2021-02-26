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


 function showPageSpinner() {
           $('#loader-wrapper').css("visibility", "visible"); 
          $('#loader-wrapper').css("opacity", "1");         
      }
       
      function hidePageSpinner() {
         $('#loader-wrapper').css("visibility", "hidden"); 
          $('#loader-wrapper').css("opacity", "0"); 
          
      }

  function success_alert(messages) {
     title='Success';
        message=messages;
        myalert(title,message)
        function myalert(title,msg){
            $.alert(msg, {
            title: title,
            closeTime: 3000, 
            });
        }
  }

  function error_alert(messages) {
       title='Error';
          message=messages;
          myalert(title,message)
          function myalert(title,msg){
          $.alert(msg, {
          title: title,
          closeTime: 3000,
          type:'danger', 
          });
    }
  }
      
/****end****/