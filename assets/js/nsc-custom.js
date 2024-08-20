// menus
function rj_bookmarks_menu_open_nav() {
	window.rj_bookmarks_responsiveMenu=true;
	jQuery(".sidenav").addClass('show');
}
function rj_bookmarks_menu_close_nav() {
	window.rj_bookmarks_responsiveMenu=false;
 	jQuery(".sidenav").removeClass('show');
}

// owl 
jQuery(document).ready(function($) {
    function initializeOwlCarousel() {
        if ($(window).width() < 768) {
            $('#rj-about-owl').owlCarousel({
                items: 1,
                loop: true,
                margin: 10,
                nav: true,
				
                autoplay: true,
                autoplayTimeout: 9000,
				dot: true,
				autoHeight:true,
				autoplayHoverPause: true
            });
        } 
    }
    initializeOwlCarousel();
    $(window).resize(function() {
        initializeOwlCarousel();
    });

	// Sticky Header
	$(window).scroll(function(){
		var sticky = $('.header-sticky'),
			scroll = $(window).scrollTop();

		if (scroll >= 100) sticky.addClass('header-fixed');
		else sticky.removeClass('header-fixed');
	});
	
});

// booking form
  document.getElementById('visit-form').addEventListener('submit', function(e) {
	e.preventDefault();
	alert('Booking submitted! We will get back to you soon.');
  });

  (function($){
	function floatLabel(inputType){
		$(inputType).each(function(){
			var $this = $(this);
			// on focus add cladd active to label
			$this.focus(function(){
				$this.next().addClass("active");
			});
			//on blur check field and remove class if needed
			$this.blur(function(){
				if($this.val() === '' || $this.val() === 'blank'){
					$this.next().removeClass();
				}
			});
		});
	}
	// just add a class of "floatLabel to the input field!"
	floatLabel(".floatLabel");
})(jQuery);



/*
* Hey Folks, feel free to use this Code
* You can find some more Animated Icons in my Collection
* https://codepen.io/collection/XyyOGm/
*/



