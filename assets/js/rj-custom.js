
// menus
function rj_mojo_menu_open_nav() {
	window.rj_mojo_responsiveMenu=true;
	jQuery(".sidenav").addClass('show');
}
function rj_mojo_menu_close_nav() {
	window.rj_mojo_responsiveMenu=false;
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
    jQuery(document).ready(function($) {
        // Initialize slick slider for the first active tab
        $('#v-pills-tabContent .tab-pane.show .slick-slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: true,
            arrows: false,
        });
    
        // Reinitialize slick slider when switching tabs
        $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("data-bs-target"); // Get the target tab pane
            $(target).find('.slick-slider').not('.slick-initialized').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: true,
                arrows: false,
            });
        });
    });

    const child_rate = parseFloat($('#child_rate').val());
    const grown_rate = parseFloat($('#grown_rate').val());
    const total_cost = $('#total_cost');
    let child_cost = 0;
    let grownup_cost = 0;

    // Function to update the total cost
    function updateTotalCost() {
        const total = child_cost + grownup_cost;
        total_cost.val("Rs. " + total);
    }

    document.getElementById('increment').addEventListener('click', function() {
        var input = document.getElementById('no_of_children');
        let value = parseInt(input.value) + 1;
        input.value = value;
        child_cost = value * child_rate;
        updateTotalCost(); // Update the total cost
    });

    document.getElementById('decrement').addEventListener('click', function() {
        var input = document.getElementById('no_of_children');
        var value = parseInt(input.value);
        if (value > 0) {
            value -= 1;
            input.value = value;
            child_cost = value * child_rate;
            updateTotalCost(); // Update the total cost
        }
    });

    // Function to handle increment and decrement for grownups
    function updateValue(button, isIncrement) {
        const input = button.parentElement.querySelector('input[type="number"]');
        let value = parseInt(input.value, 10);
        if (isIncrement) {
            value += 1;
        } else {
            if (value > 0) {
                value -= 1;
            }
        }
        input.value = value;
        grownup_cost = value * grown_rate;
        updateTotalCost(); // Update the total cost
    }

    document.querySelectorAll('.increment').forEach(button => {
        button.addEventListener('click', () => updateValue(button, true));
    });

    document.querySelectorAll('.decrement').forEach(button => {
        button.addEventListener('click', () => updateValue(button, false));
    });

    $('#no_of_children, #no_of_elders').on('change', function() {
        const childrenCount = parseInt($('#no_of_children').val()) || 0;
        const eldersCount = parseInt($('#no_of_elders').val()) || 0;
        
        child_cost = childrenCount * child_rate;
        grownup_cost = eldersCount * grown_rate;
        
        updateTotalCost(); // Update the total cost
    });

    // Initial calculation to set the initial total cost
    updateTotalCost();
});



$(function(){
    var dtToday = new Date();
    
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    
    var maxDate = year + '-' + month + '-' + day;

    // alert(maxDate);
    $('#booking_date').attr('min', maxDate);
});