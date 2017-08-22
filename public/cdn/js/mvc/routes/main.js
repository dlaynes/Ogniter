jQuery(document).ready(function(){
	$("input:checkbox, input:radio, input:file").not('[data-no-uniform="true"]').uniform();

	$('.carousel').carousel();
	$('.home-carousel a').colorbox({rel:'home-carousel-a', transition:"elastic", maxWidth:"95%", maxHeight:"95%"});
});