var i = 1;
while ( eval( 'typeof FSDparam' + i ) != 'undefined' ) {
	fsd_bxslider( '.bxslider' + i,  eval( 'FSDparam' + i ) );
	i++;
}

function fsd_bxslider ( selector, Param ) {
	jQuery(document).ready(function(){
	  jQuery( selector ).bxSlider({
		mode: Param.mode,
		slideWidth: Param.width,
		adaptiveHeight: true,
		controls: Param.controls,
		auto: Param.auto,
		autoControls: Param.playcontrol,
		autoControlsCombine: true,
		pause: Param.speed,
		captions: Param.captions,
		pager: Param.pager,
	  });
	});
}