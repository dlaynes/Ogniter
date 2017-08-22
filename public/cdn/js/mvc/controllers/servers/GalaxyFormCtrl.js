/**/

Ogniter.GalaxyFormCtrl = can.Control({
	init: function(){
		var self = this;
		if(location.hash && location.hash != '#'){
			var alliance = location.hash.replace('#','.');
			jQuery(alliance).closest('tr').addClass('info');
		}
		jQuery('.moon').each(function(){
			$a = jQuery(this);
			$a.popover({title: self.options.destroy_moon_title,
				hideOnHTMLClick: false,
				content: "Finally, I can speak!"});
		});
	}
});
