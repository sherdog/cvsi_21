
 
$(document).ready(function() {
	var $sideBar = $('#documenter_sidebar');
	$sideBar.affix();
	
	$('#documenter_content img').click(function() {
		if ($(this).hasClass("selected")) {
			 $(this).removeClass('selected').addClass('unselected');
		} else {
			 $(this).removeClass('unselected').addClass('selected');
		}    
    });
	
	$('.thumb-video').magnificPopup({
	  type: 'iframe',
	  iframe: {
		patterns: {
		   youtube: {
			  index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
			  id: 'v=', // String that splits URL in a two parts, second part should be %id%
			  src: '//www.youtube.com/embed/%id%?autoplay=1'
				},
			}
		}
		
	});
	
	searchFunction = function() {
		var input, filter, ul, li, a, i;
		input = document.getElementById("searchInput");
		filter = input.value.toUpperCase();
		ul = document.getElementById("documenter_sidebar");
		li = ul.getElementsByTagName("li");
		for (i = 0; i < li.length; i++) {
			a = li[i].getElementsByTagName("a")[0];
			if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
				li[i].style.display = "";
			} else {
				li[i].style.display = "none";

			}
		}
	};
	
	/* Back To Top - Slidebar
	 ========================================================*/
	/*Back to top */
	$(".back-to-top").addClass("hidden-top");
		$(window).scroll(function () {
		if ($(this).scrollTop() === 0) {
			$(".back-to-top").addClass("hidden-top")
		} else {
			$(".back-to-top").removeClass("hidden-top")
		}
	});

	$('.back-to-top').click(function () {
		$('body,html').animate({scrollTop:0}, 1200);
		return false;
	});	
	
	// Hide tooltip when clicking on it
   /* var hasTooltip = $("[data-toggle='tooltip']").tooltip();
	hasTooltip.on('click', function () {
			$(this).tooltip('hide')
	});*/
	
	
	
});

