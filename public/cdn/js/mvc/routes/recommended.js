jQuery(document).ready(function(){
    jQuery('.raty').raty({
        path: Ogniter.CDN_HOST +'img/',
        half: true,
        timeout: 4000,
        score: function() {
			return $(this).data('score');
		},
		click: function(score, evt) {
			var $r = jQuery(this);
			jQuery.post(Ogniter.BASE_URL+'site/vote/website/'+ $r.data('id'), {score: score}, function(res){
				$r.raty('readOnly', true);

				noty({
					type: 'success',
					text: 'Vote sent!'
				});

			});
		}
    });
});