
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


	const text = "E=Mc²";
    const $element = $("#rj-math-formula");
    let index = 0;

    function typeWriter() {
        if (index < text.length) {
            if (text[index] === "²") {
                $element.append("<sup>2</sup>");
            } else {
                $element.append(text.charAt(index));
            }
            index++;
        } else {
            // Reset index and clear content
            index = 0;
            $element.empty();
            // Add a pause before restarting
            setTimeout(function() {
                typeWriter();
            }, 1000); // 1 second pause before restarting
            return;
        }
        
        setTimeout(typeWriter, 500); // Adjust the typing speed here (milliseconds)
    }

    typeWriter();

});

jQuery(document).ready(function($) {
    jQuery('.slick-slider').slick({
        dots: true,
		nav: false,
    });
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
	floatLabel(".floatLabel");
})(jQuery);

