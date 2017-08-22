Ogniter.FlightTimesFormCtrl = can.Control({
	init: function(){

		jQuery(this.element).validationEngine();

	},
	notNaN: function(value){
		var r = parseInt(value, 10);
		if(isNaN(r)){
			return 0;
		}
		return r;
	}
});