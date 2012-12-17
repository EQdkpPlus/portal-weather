<?php
 /*
 * Project:		EQdkp-Plus
 * License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:		2008
 * Date:		$Date: 2012-12-17 13:13:57 +0100 (Mo, 17. Dez 2012) $
 * -----------------------------------------------------------------------
 * @author		$Author: godmod $
 * @copyright	2006-2011 EQdkp-Plus Developer Team
 * @link		http://eqdkp-plus.com
 * @package		eqdkp-plus
 * @version		$Rev: 12604 $
 * 
 * $Id: weather_portal.class.php 12604 2012-12-17 12:13:57Z godmod $
 */

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

class weather_portal extends portal_generic {
	public static function __shortcuts() {
		$shortcuts = array('user', 'pdh', 'pfh', 'core', 'db', 'tpl', 'config', 'puf'	=> 'urlfetcher', 'in');
		return array_merge(parent::$shortcuts, $shortcuts);
	}

	protected $path		= 'weather';
	protected $data		= array(
		'name'			=> 'Weather',
		'version'		=> '2.0.0',
		'author'		=> 'WalleniuM',
		'contact'		=> EQDKP_PROJECT_URL,
		'description'	=> 'Shows the weather and a 2 days forcast',
	);
	protected $positions = array('left1', 'left2', 'right');
	protected $settings	= array(
		'pk_weather_hours'		=> array(
			'name'		=> 'pk_weather_hours',
			'language'	=> 'pk_weather_hours',
			'property'	=> 'text',
			'size'		=> '3',
		),
		'pk_weather_tempformat'		=> array(
			'name'		=> 'pk_weather_tempformat',
			'language'	=> 'pk_weather_tempformat',
			'property'	=> 'dropdown',
			'options'	=> array('C','F'),
		),
	);
	protected $install	= array(
		'autoenable'		=> '1',
		'defaultposition'	=> 'left2',
		'defaultnumber'		=> '11',
	);
	protected $tables	= array('module_weather');
	protected $sqls		= array(
		"CREATE TABLE IF NOT EXISTS __module_weather (
		`user_id` varchar(255) NOT NULL default '',
		`last_update` varchar(255) NOT NULL default '',
		`weather_data` blob,
		PRIMARY KEY (`user_id`)) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;",
	);

	private function getWOEID($zip, $country){
		// use YQL to find the WOEID
		$q = "select woeid from geo.places where text='$zip $country' limit 1";

		// execute the YQL query we just built
		$ch			= curl_init();
		$response	= $this->puf->fetch(sprintf("http://query.yahooapis.com/v1/public/yql?format=json&q=%s", urlencode($q)));
		// if we got something back
		if ($response){
			try {
				$response = json_decode($response, true);				// convert JSON to an array
				return $response['query']['results']['place']['woeid'];	// path to the WOEID value
			} catch(Exception $ex) {
				return 0;												// invalid response, we may have hit the rate limit (see next section)
			}
		}
		return 0;														// we received no response at all
	}

	public function output() {
		if($this->user->data['user_id'] > 0 &&($this->user->data['country'] != '' && $this->user->data['ZIP_code'] != 0)){
			$this->user_data		= $this->pdh->get('user', 'data', array($this->user->data['user_id']));
			$seconds_to_midnight	= ($this->config->get('pk_weather_hours')) ? $this->config->get('pk_weather_hours')*3600 : 6*3600;
			$tempformat				= ($this->config->get('pk_weather_tempformat') == 'F') ? '°F' : '°C';

			// Try to select the cached data
			$weatherdata_sql	= $this->db->query("SELECT * FROM __module_weather WHERE user_id=?", false, $this->user->id);
			$mwdata				= $this->db->fetch_record($weatherdata_sql);

			if(($mwdata['last_update']+$seconds_to_midnight) > time()){
				// use the database cached version
				$weather_data = json_decode($mwdata['weather_data'], true);
			}else{
				// Load weather data
				if(DEBUG == 3){
					$this->core->message($this->user->lang('pk_weather_debfetch'));
				}
	
				if($this->user_data['country'] && $this->user_data['ZIP_code'] != 0){
					// Load the Country states
					if (file_exists($this->root_path.'core/country_states.php')){
						include_once($this->root_path.'core/country_states.php');
						$myCountry = strtolower($country_array[$this->user_data['country']]);
					}

					$woeid				= $this->getWOEID($this->user_data['ZIP_code'],$myCountry);
					$weather_query		= sprintf("SELECT * FROM weather.forecast WHERE woeid='%s' and u='%s'", $woeid, (($this->config->get('pk_weather_tempformat') == 'F') ? 'f' : 'c'));
					
					
					$response	= $this->puf->fetch(sprintf("http://query.yahooapis.com/v1/public/yql?format=json&q=%s", urlencode($weather_query)));
					$data_weather_json	= $response;

					// Save to Database
					if($data_weather_json){
						$this->db->query("DELETE FROM __module_weather WHERE user_id=".$this->user->id);
						$this->db->query("INSERT INTO __module_weather :params", array(
							'user_id'			=> $this->user->id,
							'weather_data'		=> $data_weather_json,
							'last_update'		=> time()
						));
						$weather_data = json_decode($data_weather_json, true);
					}
				}else{
					$myOut = $this->user->lang('pk_weather_no_data');
				}
			}
			// parse it!
			if(empty($weather_data)){
				$myOut  = $this->user->lang('pk_weather_xml_err');
			} elseif(empty($myOut)) {

				// CSS
				$this->tpl->add_css("
					.weatherFeed {
						font-family: Arial, Helvetica, sans-serif;
						font-size: 90%;
						margin: 2em 3em;
						width: 280px;
					}
					.weatherFeed a { color: #888; }
					.weatherFeed a:hover {
						color: #000;
						text-decoration: none;
					}
					.weatherItem {
						padding: 0.8em;
						text-align: right;
					}
					.weatherCity { text-transform: uppercase; }
					.weatherTemp {
						font-size: 2.8em;
						font-weight: bold;
					}
					.weatherDesc, .weatherCity, .weatherForecastDay  { font-weight: bold; }
					.weatherDesc { margin-bottom: 0.4em; }
					.weatherRange, .weatherWind, .weatherLink, .weatherForecastItem { font-size: 0.8em; }
					.weatherLink, .weatherForecastItem {
						margin-top: 0.5em;
						text-align: left;
					}
					.weatherForecastItem {
						padding: 0.5em 0.5em 0.5em 80px;
						background-color: #fff;
						background-position: left center;
					}
					.weatherForecastDay { font-size: 1.1em; }
				");
				$weather_data = $weather_data['query']['results']['channel'];
				foreach($weather_data['item'] as $key => $value){
					$weather_data[$key] = $value;
				}
			
				// Generate Output
				$myOut  = '	<div class="weatherItem" style="background-image: url(http://l.yimg.com/a/i/us/nws/weather/gr/'.$weather_data['condition']['code'].'n.png); background-repeat: no-repeat; background-position: -18px -14px;">
					<div class="weatherCity">'.$weather_data['location']['city'].'</div>
					<div class="weatherTemp">'.$weather_data['condition']['temp'].'°'.$weather_data['units']['temperature'].'</div>
					<div class="weatherDesc">'.(($this->user->lang(array('pk_weather_condition', $weather_data['condition']['code']))) ? $this->user->lang(array('pk_weather_condition', $weather_data['condition']['code'])) : $weather_data['condition']['text']).'</div>
					<div class="weatherRange">'.$this->user->lang('pk_weather_high').': '.$weather_data['forecast'][0]['high'].'°'.$weather_data['units']['temperature'].' '.$this->user->lang('pk_weather_low').': '.$weather_data['forecast'][0]['low'].'°'.$weather_data['units']['temperature'].'</div>
					<div class="weatherWind">'.$this->user->lang('pk_weather_wind_txt').': '.(($this->user->lang(array('pk_weather_wind', $weather_data['wind']['direction']))) ? $this->user->lang(array('pk_weather_wind', $weather_data['wind']['direction'])) : $weather_data['wind']['direction']).' '.$weather_data['wind']['speed'].$weather_data['units']['speed'].'</div>
					<div class="weatherLink"><a href="'.$weather_data['link'].'" target="_self" title="'.$this->user->lang('pk_weather_fulllink').'">'.$this->user->lang('pk_weather_fulllink').'</a></div>
				</div>';

			}
		}else{
			$this->tpl->add_js('
			var APPID = ""; // Your Yahoo Application ID
			var DEG = "c";  // c for celsius, f for fahrenheit

			// Does this browser support geolocation?
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(locationSuccess, locationError);
			}
			else{
				showError("Your browser does not support Geolocation!");
			}'."
			
			function locationSuccess(position) {
					var lat = position.coords.latitude;
					var lon = position.coords.longitude;

					// Yahoo's PlaceFinder API http://developer.yahoo.com/geo/placefinder/
					// We are passing the R gflag for reverse geocoding (coordinates to place name)
					var geoAPI = 'http://where.yahooapis.com/geocode?location='+lat+','+lon+'&flags=J&gflags=R&appid='+APPID;

					// Forming the query for Yahoo's weather forecasting API with YQL
					// http://developer.yahoo.com/weather/

					var wsql = 'select * from weather.forecast where woeid=WID and u=\"'+DEG+'\"',
						weatherYQL = 'http://query.yahooapis.com/v1/public/yql?q='+encodeURIComponent(wsql)+'&format=json&callback=?',
						code, city, results, woeid;

					// Issue a cross-domain AJAX request (CORS) to the GEO service.
					// Not supported in Opera and IE.
					$.getJSON(geoAPI, function(r){

						if(r.ResultSet.Found == 1){

							results = r.ResultSet.Results;
							city = results[0].city;
							code = results[0].statecode || results[0].countrycode;

							// This is the city identifier for the weather API
							woeid = results[0].woeid;

							// Make a weather API request (it is JSONP, so CORS is not an issue):
							$.getJSON(weatherYQL.replace('WID',woeid), function(r){

								if(r.query.count == 1){

									$.post('".$this->root_path."portal/weather/weather_geolocation.php".$this->SID."&link_hash=".$this->user->csrfGetToken('module_weather_geoloc')."', r, function(data) {
									  showError(data);
									});


								}
								else {
									showError(\"Error retrieving weather data!\");
								}
							});

						}

					}).error(function(){
						showError(\"Your browser does not support CORS requests!\");
					});

				}
			
			".'function locationError(error){
				switch(error.code) {
					case error.TIMEOUT:
						showError("A timeout occured! Please try again!");
						break;
					case error.POSITION_UNAVAILABLE:
						showError("We can\'t detect your location. Sorry!");
						break;
					case error.PERMISSION_DENIED:
						showError("Please allow geolocation access for this to work.");
						break;
					case error.UNKNOWN_ERROR:
						showError("An unknown error occured!");
						break;
				}
			}

			function showError(msg){
				$(\'#portalweather_geoloc\').html(msg);
			}

			', "docready");
			
			// CSS
			$this->tpl->add_css("
				.weatherFeed {
					font-family: Arial, Helvetica, sans-serif;
					font-size: 90%;
					margin: 2em 3em;
					width: 280px;
				}
				.weatherFeed a { color: #888; }
				.weatherFeed a:hover {
					color: #000;
					text-decoration: none;
				}
				.weatherItem {
					padding: 0.8em;
					text-align: right;
				}
				.weatherCity { text-transform: uppercase; }
				.weatherTemp {
					font-size: 2.8em;
					font-weight: bold;
				}
				.weatherDesc, .weatherCity, .weatherForecastDay  { font-weight: bold; }
				.weatherDesc { margin-bottom: 0.4em; }
				.weatherRange, .weatherWind, .weatherLink, .weatherForecastItem { font-size: 0.8em; }
				.weatherLink, .weatherForecastItem {
					margin-top: 0.5em;
					text-align: left;
				}
				.weatherForecastItem {
					padding: 0.5em 0.5em 0.5em 80px;
					background-color: #fff;
					background-position: left center;
				}
				.weatherForecastDay { font-size: 1.1em; }
			");
			
			$myOut = "<div id=\"portalweather_geoloc\">".$this->user->lang('pk_weather_loading')."</div>";
		}
		return '<div style="white-space:normal;">'.$myOut.'</div>';
	}
	
	public function geolocation(){
		if ($this->user->checkCsrfGetToken($this->in->get('link_hash'), 'module_weather_geoloc')){

			$arrData = $this->in->getArray('query');
			$weather_data = $arrData['results']['channel'];
			foreach($weather_data['item'] as $key => $value){
				$weather_data[$key] = $value;
			}
			// Generate Output
				$myOut  = '	<div class="weatherItem" style="background-image: url(http://l.yimg.com/a/i/us/nws/weather/gr/'.$weather_data['condition']['code'].'n.png); background-repeat: no-repeat; background-position: -18px -14px;">
								<div class="weatherCity">'.$weather_data['location']['city'].'</div>
								<div class="weatherTemp">'.$weather_data['condition']['temp'].'°'.$weather_data['units']['temperature'].'</div>
								<div class="weatherDesc">'.(($this->user->lang(array('pk_weather_condition', $weather_data['condition']['code']))) ? $this->user->lang(array('pk_weather_condition', $weather_data['condition']['code'])) : $weather_data['condition']['text']).'</div>
								<div class="weatherRange">'.$this->user->lang('pk_weather_high').': '.$weather_data['forecast'][0]['high'].'°'.$weather_data['units']['temperature'].' '.$this->user->lang('pk_weather_low').': '.$weather_data['forecast'][0]['low'].'°'.$weather_data['units']['temperature'].'</div>
								<div class="weatherWind">'.$this->user->lang('pk_weather_wind_txt').': '.(($this->user->lang(array('pk_weather_wind', $weather_data['wind']['direction']))) ? $this->user->lang(array('pk_weather_wind', $weather_data['wind']['direction'])) : $weather_data['wind']['direction']).' '.$weather_data['wind']['speed'].$weather_data['units']['speed'].'</div>
								<div class="weatherLink"><a href="'.$weather_data['link'].'" target="_self" title="'.$this->user->lang('pk_weather_fulllink').'">'.$this->user->lang('pk_weather_fulllink').'</a></div>
							</div>';
				return $myOut;
			
		} else {
			return "access denied";
		}
	}
	
}
if(version_compare(PHP_VERSION, '5.3.0', '<')) registry::add_const('short_weather_portal', weather_portal::__shortcuts());

?>