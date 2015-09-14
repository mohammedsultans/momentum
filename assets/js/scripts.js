

/*
| ----------------------------------------------------------------------------------
| TABLE OF CONTENT
| ----------------------------------------------------------------------------------
|	1. Home Slider
    2. Sticky Header 
|	3. Product qty 
|	4. Animate elements when they are in viewport
|	5. Flex Slider
|	6. Animate  viewport
|	7. Menu Mega Bootstrap
|	8. Contact us form validation     
|	9. Contact us form submit    
*/

jQuery(function ($) {

    "use strict";


		

    function header() {
		
		
		
		
		

     var windowHeight = $(window).height();
     var windowWidth = $(window).width();
	 
	 
	    if (windowWidth <  1200 ) {
			
			
	/////////////////////////////////////
    //  Disable Mobile Animated
    /////////////////////////////////////
			
			
			$("html").removeClass("noIE");
			
			
		}
		
	 
	 
	 
	 /////////////////////////////////////
    //  Sticky Header 
    /////////////////////////////////////
	 
	 
	   if (windowHeight > 850) {
	 
	 
	 
	  if ($('#header').length) {
        $(window).on('scroll', function () {
            var winH = $(window).scrollTop();
            var $pageHeader = $('#header');
            if (winH > 60) {
                $pageHeader.addClass('sticky');
            } else {
                $pageHeader.removeClass('sticky');
            }
        });
    }


 };
 
 
 	 /////////////////////////////////////
    //  Home slider
    /////////////////////////////////////
   

        var slider = $(".home-slider .slides");
        var slider2 = $(".home-slider .slides li");




        if (windowHeight > 450) {

            slider.css("max-height", windowHeight - 200);
            slider2.css("max-height", windowHeight - 200);

        };




        $('.scroll_down').click(function (event) {
            event.preventDefault();

            $('html, body').animate({
                scrollTop: windowHeight - 300
            }, 300);
        });


    };




    header();


    $(window).resize(function () {
        header();
    });




    /////////////////////////////////////
    // Qty
    /////////////////////////////////////



    jQuery(".minus_btn").click(function () {
        var inputEl = jQuery(this).parent().children().next();
        var qty = inputEl.val();
        if (jQuery(this).parent().hasClass("minus_btn"))
            qty++;
        else
            qty--;
        if (qty < 0)
            qty = 0;
        inputEl.val(qty);
    })


    jQuery(".plus_btn").click(function () {
        var inputEl = jQuery(this).parent().children().next();
        var qty = inputEl.val();
        if (jQuery(this).hasClass("plus_btn"))
            qty++;
        else
            qty--;
        if (qty < 0)
            qty = 0;
        inputEl.val(qty);
    })


    /////////////////////////////////////
    //  animate elements when they are in viewport
    /////////////////////////////////////



    $('.noIE .animated').waypoint(function () {
        var animation = $(this).data('animation');
        $(this).addClass('animation-done').addClass(animation);
    }, {
        triggerOnce: true,
        offset: '70%'
    });




    /////////////////////////////////////
    //  FLEX SLIDER
    /////////////////////////////////////
	
	
	
	

    $('.home-slider').flexslider({
        animation: 'fade', //String: Select your animation type, "fade" or "slide"
        //Boolean: Create navigation for previous/next navigation? (true/false)
        slideshowSpeed: 7000, //Integer: Set the speed of the slideshow cycling, in milliseconds
        animationSpeed: 600, //Integer: Set the speed of animations, in milliseconds
        pauseOnHover: false, //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
        prevText: "", //String: Set the text for the "previous" directionNav item
        nextText: "" //String: Set the text for the "next" directionNav item
    });

    $(".flex-control-nav").prepend('<a class="prev" href="/"><span class="fa fa-chevron-left"></span></a>');
    $(".flex-control-nav").append('<a class="next" href="/"><span class="fa fa-chevron-right"></span></a>');


    $(".flex-control-nav .prev").click(function () {
        $(".flex-prev").trigger('click');
    });
    $(".flex-control-nav .next").click(function () {
        $(".flex-next").trigger('click');
    });



    $(".flex-control-nav").wrap("<div class='container'></div>");
	
			
    /*PRODUCT FLEXSLIDER*/



        $('#carousel').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 128,
            itemMargin: 4,
            asNavFor: '#slider-product'
        });

        $('#slider-product').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            sync: "#carousel"
        });
		
		
				/*CATEGORY PRODUCT*/
				
	$('.category-slider').flexslider({
        animation: 'fade', //String: Select your animation type, "fade" or "slide"
        //Boolean: Create navigation for previous/next navigation? (true/false)
        slideshowSpeed: 7000, //Integer: Set the speed of the slideshow cycling, in milliseconds
        animationSpeed: 600, //Integer: Set the speed of animations, in milliseconds
        pauseOnHover: false, //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
        prevText: "", //String: Set the text for the "previous" directionNav item
        nextText: "" //String: Set the text for the "next" directionNav item
    });
		
		

  
  
		
		/*CAROUSEL PRODUCT*/ 
		
		
		
		function carouselBx () {
		
		 var windowWidth = $(window).width();
	
	
if (windowWidth >550) {

  var bxcarousel = $('.bxcarousel li').length;

            if (bxcarousel > 3) {

  $('.bxcarousel').bxSlider({
    slideWidth: 265,
    minSlides: 4,
    maxSlides: 4,
    slideMargin: 25,
	   nextText: '',
        prevText: ''
  });
  


    $('.bx-next').html(' <i class="fa fa-chevron-right"></i>')
    $('.bx-prev').html(' <i class="fa fa-chevron-left"></i>')
	
			}

}


else{
	

	
	  var bxcarousel = $('.bxcarousel li').length;

     

$('.bxcarousel').bxSlider({
    slideWidth: 265,
    minSlides: 1,
    maxSlides:1,
    slideMargin: 25,
	   nextText: '',
        prevText: ''
  });
  
  


    $('.bx-next').html(' <i class="fa fa-chevron-right"></i>')
    $('.bx-prev').html(' <i class="fa fa-chevron-left"></i>')
	
	
	
	}
	
	
	
}

	
	
	
	 carouselBx();


    $(window).resize(function() {
        carouselBx();
    });


    /////////////////////////////////////
    //  Menu Mega Bootstrap
    /////////////////////////////////////



    $("#nav .navbar-nav > li .dropdown-toggle").click(function () {

        $(this).parent().toggleClass("open-nav")

    });




    $('input').iCheck({
        checkboxClass: 'icheckbox_minimal',
        radioClass: 'iradio_minimal',
        increaseArea: '20%' // optional
    });








    /////////////////////////////////////
    //  Contact us form validation 
    /////////////////////////////////////




    $('#contact-form').on('submit', function (e) {
        e.preventDefault();
        // we clear error messages
        $(this).find('.error').removeClass('error');
        $(this).find('.err_msg').fadeOut(200);

        // validate form
        var validation = validate_contact(e);

        for (var i = 0; i < validation.length; i++) {
            $(validation[i]).addClass('error');
        }

        if (validation.length) {
            $('body, html').animate({
                'scrollTop': $(validation[0]).offset().top - 100
            }, 'easeInCube', function () {
                $(this).select();
            });
            return false;
        } else {
            submit_form(e);
            return true;
        }
    });

    function validate_contact(e) {
        var $form = $(e.target);
        var rule, val, bad_fields = new Array();
        $form.find('input, textarea').each(function () {
            rule = $(this).data('validate');
            if (!rule) return;

            val = $(this).val();
            if (!val.match(rule)) {
                bad_fields.push(this);
            }
        });
        return bad_fields;
    }




    /////////////////////////////////////
    //  Contact us form submit     
    /////////////////////////////////////




    function submit_form(e) {
        var $form = $(e.target),
            $btn = $form.find('button'),
            btn_text = $btn.text();
        $.ajax({
            url: 'inc/phpmailer/contact.php',
            data: $form.serialize(),
            dataType: 'json',
            type: 'POST',
            beforeSend: function () {
                $('#contact_fail .alert-inner').empty();
                $('#contact_fail').hide();
                $btn.attr('disabled', 'disabled').addClass('btn-disabled').css('cursor', 'not-allowed').text('Sending...');
            },
            success: function (result) {
                if (typeof result.success == 'undefined') {
                    // form is not valid, display errors
                    for (var x in result) {
                        $('#contact_fail .alert-inner').append('<p>' + result[x] + '</p>');
                    }
                    $('#contact_fail').fadeIn();
                } else {
                    // form sent successfully and without errors
                    $('#contact_success').fadeIn(700, function () {
                        var $this = $(this);
                        setTimeout(function () {
                            $this.fadeOut();
                        }, 5000);
                    });
                }
            },
            complete: function () {
                $btn.removeAttr('disabled', 'disabled').removeClass('btn-disabled').css('cursor', 'pointer').html(btn_text);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                switch (jqXHR.status) {
                case 404:
                    alert("We're Sorry... The file you are looking for is not found :(");
                    break;
                case 500, 200:
                    $('#contact_fail .alert-inner').append("<p>Oops, something went wrong and we couldn't send your message :(</p>");
                    $('#contact_fail').fadeIn();
                    break;
                default:
                    console.log(jqXHR, textStatus, errorThrown);
                }
            }
        });
    }

    function get_max($el) {
        /* Get max height */
        var max = 0;
        $el.each(function () {
            var this_h = $(this).outerHeight();
            if (this_h > max) max = this_h;
        });
        return max;
    }




    $(".sidebar h5").click(function (e) {
        e.preventDefault();

        $(this).parent().find(".tgl_c").slideToggle(300);
        if ($(this).hasClass("active")) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }

    });
	
	

	

/*------------------------------------------------------------------
  Debug top button 13.08  <prestazilla@gmail.com>
-------------------------------------------------------------------*/




$(document).mouseup(function (e) {
    var container = $(".menubtn");
    if (container.has(e.target).length === 0){
       $(".menubtn").removeClass('menu_cshow') ;  
    }
});



	 $(".menubtn > a").click(function(){
		 
		 if ( $(this).parent().hasClass("menu_cshow") ) {

	 $(".menu_cshow").removeClass('menu_cshow') ;  	 
     $(this).parent().removeClass('menu_cshow') ; 

	}
	
	else{
		
     $(".menu_cshow").removeClass('menu_cshow') ;  	 
     $(this).parent().addClass('menu_cshow') ; 
		
		}

		 
		 });
		 
		 
  /*------------------------------------------------------------------
     Select
-------------------------------------------------------------------*/


    if ($('.catelog_c').length > 0) {
		

  	$(".selbx").selectbox();
	
	}
	
	
		 
/*------------------------------------------------------------------
  Debug top button 13.08  <prestazilla@gmail.com>
-------------------------------------------------------------------*/


		 

    $('input').iCheck({
        checkboxClass: 'icheckbox_minimal',
        radioClass: 'iradio_minimal',
        increaseArea: '20%' // optional
    });

});