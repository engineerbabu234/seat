/*document.getElementById('swal').onclick = function(){

	swal({
	  title: "We Booked Your Seat",
	  icon: "success",
	  button: "OK"
	});
};

document.getElementById('cancel').onclick = function(){

	swal({
	  title: "We Booked Your Seat",
	  icon: "success",
	  button: "OK"
	});
};*/

/*******subscriber-slider*******/

  var preloader = $('#loader-wrapper');
    var myVar;

    function nxsolloader(){
        preloader.css("transition", "all 0.5s");
        preloader.css("visibility", "hidden");
        preloader.css("opacity", "0");
        window.scrollTo(0, 0);
    };

    function loaderfun() {
        myVar = setTimeout(nxsolloader, 800);
    }

     function showPageSpinner() {
           $('#loader-wrapper').css("visibility", "visible"); 
          $('#loader-wrapper').css("opacity", "1");         
      }
       
      function hidePageSpinner() {
         $('#loader-wrapper').css("visibility", "hidden"); 
          $('#loader-wrapper').css("opacity", "0"); 
          
      }

      $(function () {
          $('[data-toggle="tooltip"]').tooltip();
        })

$('.slider-box .owl-carousel').owlCarousel({

    effect: 'fade',

    speed: 800,

    loop:true,

    responsive:{

        0:{

            items:1

        },

        450:{

            items:1

        },            

        768:{

            items:1

        },

        1200:{

            items:1

        }

    },

    autoplay: {

        delay: 3500,

        disableOnInteraction: false,

    },

});


$(document).on('click','.success_modal_close',function(){
$('#success_modal_text').html('');
$('#success_modal').hide(); 
$(".overlay-layer").toggleClass('hidden');
});

$(document).on('click','.warning_modal_close',function(){
    $('#warning_modal_text').html('');
    $('#warning_modal').hide();
    $(".overlay-layer").toggleClass('hidden');
     
});


$(document).on('click','.error_modal_close',function(){
    $('#error_modal_text').html('');
    $('#error_modal').hide();   
    $('body').removeClass( "modal-open" );
});



/***********/