jQuery(document).ready(function(){
  jQuery('.bxslider').bxSlider({
	mode: FSDparam.mode,
	slideWidth: FSDparam.width,
	adaptiveHeight: true,
	controls: FSDparam.controls,
	auto: FSDparam.auto,
	autoControls: FSDparam.playcontrol,
	autoControlsCombine: true,
	pause: FSDparam.speed,
	captions: FSDparam.captions,
	pager: FSDparam.pager,
  });
});


