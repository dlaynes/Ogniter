Ogniter.FlightTimesFormCtrl = can.Control({
	init: function(){

		jQuery(this.element).validationEngine();
		jQuery('#start_date').val( new Date().toString('yyyy/M/d HH:mm:ss'));

	},
	notNaN: function(value){
		var r = parseInt(value, 10);
		if(isNaN(r)){
			return 0;
		}
		return r;
	},
	'#start_date blur': function(element, event){
		var $el = jQuery(element);
		if(!$el.val()){
			$el.val( new Date().toString('yyyy/M/d HH:mm:ss'));
		}
	},
	'#calc_times click': function(element, event){

		this.uni_speed = this.notNaN( jQuery('#uni_speed').val() );

		event.preventDefault();
		if(!jQuery(this.element).validationEngine('validate')){
			return;
		}

		var start_date = Date.parse(jQuery('#start_date').val() );
		if(start_date===null){
			jQuery('#start_date').validationEngine('showPrompt', this.options.invalidDateTime, '', true);
			return;
		}

		//Mostrar un loader??
		var result_fleet = [],
			self = this,
			motors = [self.notNaN( jQuery('#combustion_drive_tech').val() ),self.notNaN( jQuery('#impulse_drive_tech').val() ), self.notNaN( jQuery('#hyperspacial_drive_tech').val() ) ],
			slowest = null, consumptionTotal,
			total_ships = 0,
			total_capacity = 0;

		//buscamos la nave mas lenta
		jQuery('.resource-fleet').each(function(){
			var $fleet = jQuery(this), number = self.notNaN($fleet.val() ), cap = $fleet.data('capacity');
			if(!number){
				return;
			}
			var resource_id = $fleet.data('resource-id'),
				motor = $fleet.data('motor'),
				speed = $fleet.data('speed'),
				consumption = $fleet.data('consumption'),
				motor3 = $fleet.data('motor3'),
				motor2 = $fleet.data('motor2'), result_speed = null, result_consumption = null;
			if(motor3){
				var speed3 = $fleet.data('speed3'),
					consumption3 = $fleet.data('consumption3');
				switch(motor3){
					case 115:
						if(motors[0] > $fleet.data('motor3-level')){
							result_speed = self.calcSpeed(motors[0], speed3, 1);
							result_consumption = consumption3;
						}
						break;
					case 117:
						if(motors[1] > $fleet.data('motor3-level')){
							result_speed = self.calcSpeed(motors[1], speed3, 2);
							result_consumption = consumption3;
						}
						break;
					case 118:
						if(motors[2] > $fleet.data('motor3-level')){
							result_speed = self.calcSpeed(motors[2], speed3, 3);
							result_consumption = consumption3;
						}
						break;
				}
			}
			if(motor2 && !result_speed ){
				var speed2 = $fleet.data('speed2'),
					consumption2 = $fleet.data('consumption2');
				switch(motor2){
					case 115:
						if(motors[0] > $fleet.data('motor2-level')){
							result_speed = self.calcSpeed(motors[0], speed2, 1);
							result_consumption = consumption2;
						}
						break;
					case 117:
						if(motors[1] > $fleet.data('motor2-level')){
							result_speed = self.calcSpeed(motors[1], speed2, 2);
							result_consumption = consumption2;
						}
						break;
					case 118:
						if(motors[2] > $fleet.data('motor2-level')){
							result_speed = self.calcSpeed(motors[2], speed2, 3);
							result_consumption = consumption2;
						}
						break;
				}
			}
			if(result_speed===null){
				switch(motor){
					case 115:
						result_speed = self.calcSpeed(motors[0], speed, 1);
						result_consumption = consumption;
						break;
					case 117:
						result_speed = self.calcSpeed(motors[1], speed, 2);
						result_consumption = consumption;
						break;
					case 118:
						result_speed = self.calcSpeed(motors[2], speed, 3);
						result_consumption = consumption;
						break;
				}
			}

			//verificamos que la velocidad sea la minima
			if(slowest===null){
				slowest = result_speed;
			} else{
				slowest = Math.min(slowest, result_speed);
			}
			result_fleet.push({speed: result_speed, amount: number, consumption: result_consumption});

			jQuery('#capacity_'+resource_id).text( self.addCommas(number*cap ) );
			jQuery('#speed_'+resource_id).text( self.addCommas(result_speed ) );

			total_ships += number;
			total_capacity += number*cap;
		});

		if(slowest===null){
			jQuery('#resource_202').validationEngine('showPrompt', this.options.mustAddAShip, '', true);
			return;
		}
		var distance = self.calcDistance(self.notNaN( jQuery('#from_galaxy').val() ),self.notNaN( jQuery('#from_system').val() ), self.notNaN( jQuery('#from_position').val() ),
				self.notNaN( jQuery('#to_galaxy').val() ),self.notNaN( jQuery('#to_system').val() ), self.notNaN( jQuery('#to_position').val() )),
			fleet_speed = self.notNaN( jQuery('#fleet_speed').val() ),
			duration = 10 + 35000/fleet_speed * Math.sqrt(10*distance / slowest) ,
			duration_sp =  Math.round( duration/self.uni_speed   )*10 / 10, //1 decimal
			sum_consumption = 0;

		//calculamos el uso de deuterio
		for(var i=0; i<result_fleet.length; i++){
			var ship = result_fleet[i];
			sum_consumption += self.calcConsumption(distance, duration, ship.speed, ship.amount, ship.consumption);
		}

		var arriving_date = start_date.clone(),
			end_date = start_date.clone();

		arriving_date.add({seconds: duration_sp});
		end_date.add({seconds: duration_sp*2});

		jQuery('#duration_desc').text(self.prettyTime(duration_sp));
		jQuery('#distance_desc').text(self.addCommas( distance ) );
		jQuery('#start_time_desc').text(start_date.toString('yyyy/M/d HH:mm:ss'));
		jQuery('#arriving_time_desc').text(arriving_date.toString('yyyy/M/d HH:mm:ss'));
		jQuery('#end_time_desc').text(end_date.toString('yyyy/M/d HH:mm:ss') );

		jQuery('#total_ships').text(self.addCommas(total_ships));
		jQuery('#total_capacity').text(self.addCommas(total_capacity));

		jQuery('#deuterium_usage_desc').text( self.addCommas(sum_consumption) );

	},
	calcSpeed: function(motorLevel, speed, factor){
		return speed * (1 + motorLevel*factor/10 );
	},
	calcDistance: function(fromGalaxy,fromSystem, fromPosition, toGalaxy, toSystem, toPosition){
		if(fromGalaxy==toGalaxy){
			if(fromSystem==toSystem){
				if(fromPosition==toPosition){
					return 5;
				} else{
					return 1000 + 5*Math.abs(fromPosition-toPosition);
				}
			} else{
				return 2700 + 95*Math.abs(fromSystem-toSystem);
			}
		} else{
			return 20000 * Math.abs(fromGalaxy-toGalaxy);
		}
	},
	/*
	calcNewConsumption: function(consumptionBase, distance, speed_perc, ships){
		return 1 + Math.round( consumptionBase *( 1000*distance / 35000000 ) * speed_perc ) * ships;
	}, */
	calcConsumption: function(distance, duration, speed, amount, consumption){
		var av = (35000 / (duration - 10) ) * Math.sqrt(distance * 10 / speed);
		return Math.round( (amount * consumption * distance )/35000 * Math.pow(av/ 10 + 1, 2 ) );
	},
	prettyTime: function(seconds){
		var hours = Math.floor(seconds/3600),
			divisor_for_minutes = seconds % 3600,
			minutes = Math.floor(divisor_for_minutes / 60),
			divisor_for_seconds = divisor_for_minutes % 60;

		seconds = Math.ceil(divisor_for_seconds);
		var res = '';
		if(hours){
			res += hours +'h ';
		}
		if(minutes){
			res += minutes+'m ';
		}
		if(seconds){
			res += seconds + 's';
		}
		return res;
	},
	addCommas: function(nStr)
	{
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}
});
