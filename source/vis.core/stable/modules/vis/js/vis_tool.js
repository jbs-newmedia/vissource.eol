$(function() {
	$('.vis_navigation_toggle').click(function(e) {
		var expanded='';
		if ( typeof this.classList[2] !== 'undefined' && this.classList[2]=='expanded') {
			$(this).html('▲');
			compressed=0;

		} else {
			$(this).html('▼');
			compressed=1;
		}
		
		$.ajax({
			type: "POST",
			url: api_url,
			data: {
				action: 'dosettoggle',
				name: this.classList[1],
				compressed: compressed
			}
		}).done(function() {
			$( this ).addClass('done');
		});
		
	  	$(this).toggleClass('expanded');
		$('#'+this.classList[1]).slideToggle('fast');
	});
});