(function( $ ){
	$.fn.simpleopenweather = function(options){
		var defaults = {
			template	: '<div class="simpleopenweather-place"> {{icon}} {{place}}: {{sky}} </div><div><span class="simpleopenweather-temperature">Temp: {{temperature.current}} ºC</span><span class="simpleopenweather-humidity"> Humidity: {{humidity}}%</span></div><div>Temp min/max: <span class="simpleopenweather-temperature-min">{{temperature.min}} ºC </span> /<span class="simpleopenweather-temperature-max">{{temperature.max}} ºC </span></div><div>Wind: <span class="simpleopenweather-wind-speed">{{wind.speed}} m/s</span> <span class="simpleopenweather-wind-direction">{{wind.direction}}º</span></div><div><span class="simpleopenweather-cloudiness">Cloudiness: {{cloudiness}}% </span> <span class="simpleopenweather-pressure">Pressure: {{pressure}} hPa</span></div>',
			noweather	: '<p>no weather report was found for that place!</p>',
			error		: '<p>something went wrong!</p>',
			latitude	: 0,
			longitude	: 0,
			units		: 'imperial',
			lang		: 'en',
			iconset		: 'http://openweathermap.org/img/w/',
			iconfont	: false,
			appid		: '' 
		}
		var settings = $.extend(defaults, options);

		return this.each(function() {
			var item = $(this);
			var openweathermap_url = "http://api.openweathermap.org/data/2.5/weather";
			var icons = {"01d": "B", "01n": "C", "02d": "H", "02n": "I", "03d": "N", "03n": "N", "04d": "Y", "04n": "Y", "09d": "Q", "09n": "Q", "10d": "R", "10n": "R", "11d": "0", "11n": "0", "13d": "W", "13n": "W", "50d": "J", "50n": "K"};

			if(item.attr("data-simpleopenweather-city")){
				openweathermap_url += "?q=" + item.attr("data-simpleopenweather-city");
			} else if(item.attr("data-simpleopenweather-coord")){
				latlon = item.attr("data-simpleopenweather-coord").split(',');
				openweathermap_url += "?lat="+latlon[0]+"&lon="+latlon[1]+"&cnt=1";
			} else if(settings.latitude != 0 && settings.longitude != 0){
				openweathermap_url += "?lat="+settings.latitude+"&lon="+settings.longitude+"&cnt=1";
			}

			if(settings.lang != ''){
				openweathermap_url += "&lang="+settings.lang;
			}

			if(settings.units == 'metric'){
				openweathermap_url += "&units="+'metric';
			} else{
				openweathermap_url += "&units="+'imperial';
			}
			if(settings.appid != ''){
				openweathermap_url += "&APPID="+settings.appid;
			}

			console.log(openweathermap_url);

			$.ajax({
				type : "GET",
				dataType : "jsonp",
				url : openweathermap_url,
				success : function(weather){
					if(!weather){
						item.html(settings.noweather);
						return;
					}

					if(settings.iconfont){
						var icon_name	= icons[weather.weather[0].icon];
					}else{
						var icon_name	= settings.iconset+weather.weather[0].icon+".png";
					}

					var info = {
						temp 		: {
										'current' : weather.main.temp.toFixed(1),
										'min' : weather.main.temp_min.toFixed(1),
										'max' :weather.main.temp_max.toFixed(1),
									},
						humidity 	: weather.main.humidity,
						pressure	: weather.main.pressure,
						wind		: {
										'speed': weather.wind.speed.toFixed(1),
										'direction': weather.wind.deg.toFixed(1),
									},
						cloudiness 	: weather.clouds ? weather.clouds.all : "N/A",
						sky 		: weather.weather[0].description,
						icon 		: icon_name,
						place 		: weather.name
					};

					item.html(settings.template.replace(/{{place}}/ig, info.place)
											 	.replace(/{{temperature}}/ig, info.temp.current)
											 	.replace(/{{temperature.current}}/ig, info.temp.current)
											 	.replace(/{{temperature.min}}/ig, info.temp.min)
											 	.replace(/{{temperature.max}}/ig, info.temp.max)
											 	.replace(/{{humidity}}/ig, info.humidity)
											 	.replace(/{{pressure}}/ig, info.pressure)
											 	.replace(/{{wind.speed}}/ig, info.wind.speed)
											 	.replace(/{{wind.direction}}/ig, info.wind.direction)
											 	.replace(/{{cloudiness}}/ig, info.cloudiness)
												.replace(/{{icon}}/ig, ((settings.iconfont) ? '<i class="meteocon">'+info.icon+'</i>' : '<img src="'+info.icon+'"></img>'))
											 	.replace(/{{sky}}/ig, info.sky));
				},
				error : function(){
					item.html(settings.error);
				}
			});
		});
	}
})( jQuery );