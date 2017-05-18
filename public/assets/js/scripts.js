$(document).ready(function(){

	// Locale Switch
	$('.locale-select ul li a').click(function(e){
		var current = $(this).attr('href');
		$(current).prop('checked',true);
		
		if ( current == "#state" ){
			$('.switch').addClass('state');
		} else {
			$('.switch').removeClass('state');
		}
		
		$('.locale-select ul li a').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	
	
	// Back Btn
	$('.back').click(function(e){
		history.back();
		e.preventDefault();
	});
	
	
	// Committee Toggle
	$('.committees h3').click(function(e){
		if ( $('.committees ul').is(':visible') ){
			$('.committees ul').hide();
			$(this).children('span').html('show <i class="icon-angle-down"></i>');
		} else {
			$('.committees ul').show();
			$(this).children('span').html('hide <i class="icon-angle-up"></i>');
		}
	});
	
});