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
	
	public static $shortcuts = array('puf'	=> 'urlfetcher');
	protected static $path		= 'weather';
	protected static $data		= array(
		'name'			=> 'Weather',
		'version'		=> '4.0.0',
		'author'		=> 'WalleniuM',
		'icon'			=> 'fa-cloud',
		'contact'		=> EQDKP_PROJECT_URL,
		'description'	=> 'Shows the weather',
		'lang_prefix'	=> 'weather_',
		'multiple'		=> true,
	);
	protected static $positions = array('left1', 'left2', 'right');
	
	protected static $install	= array(
		'autoenable'		=> '1',
		'defaultposition'	=> 'left2',
		'defaultnumber'		=> '11',
	);
	
	protected static $apiLevel = 20;
	
	public function get_settings($state){
		$settings	= array(
			'tempformat'	=> array(
				'type'		=> 'dropdown',
				'options'	=> array('C' => 'C','F' => 'F'),
			),
			'geolocation'	=> array(
				'type'		=> 'radio',
			),
			'default_country' => array(
				'type'		=> 'dropdown',
				'options'	=> $this->pdh->get('user', 'country_list'),
			),
			'default_town' => array(
				'type' => 'text',
			),
		);
		
		return $settings;
	}

	public function output() {
		$this->tpl->js_file($this->root_path.'portal/weather/js/jquery.simpleopenweather.js');
		$this->tpl->css_file($this->root_path.'portal/weather/font/font.css');
		
		$this->tpl->add_css("
			.weatherCity {
				border-bottom:2px #66676c solid;
				padding:0px 0px 8px 0px;
				text-transform: uppercase;
				font-size: 1em;
			}
			.weatherMiddle{
				margin-top:4px;
				font-weight:300;
				vertical-align: middle;
			}
			.weatherTemp {
				font-size:32px;
				margin: 0px 0px 0px 10px;
			}
			.weatherDesc, .weatherHumidity, .weatherWind {
				font-weight: bold;
				margin: 0px 0px 8px 0px;
			}
			.weatherDesc {
				margin: 20px 0px 8px 0px;
			}
		");
		
		$temperature_unit		= ($this->config('tempformat') == 'F') ? '°F' : '°C';
		$tempformat				= ($this->config('tempformat') == 'F') ? 'imperial' : 'metric';
		
		$strCountry = $this->config('default_country');
		$strTown = $this->config('default_town');
		
		//Overwrite the Defaults with Userdata
		$strUserCountry = $strUserTown = "";
		if ($this->user->is_signedin()){
			$strUserCountry = $this->pdh->get('user', 'country', array($this->user->id));
			if ($strUserCountry != "") $strCountry = $strUserCountry;
			
			$strUserTown = $this->pdh->get('user', 'profilefield_by_name', array($this->user->id, 'location', true));
			if ($strUserTown != "") $strTown = $strUserTown;
		}

		//User has both in his profile
		if($strUserCountry != "" && $strUserTown != ""){
			$this->tpl->add_js('$("#weather_'.$this->id.'").simpleopenweather({template: \'<div style="display: block;" class="toggle_container"><div class="weatherCity"><i class="fa fa-map-marker fa-fw"></i> {{place}}</div><div class="weatherMiddle">{{icon}}<span class="weatherTemp">{{temperature}} '.$temperature_unit.'</span></div><div class="weatherDesc"><i class="fa fa-tag fa-fw"></i> {{sky}}</div><div class="weatherHumidity"><i class="fa fa-tint fa-fw"></i> {{humidity}} %</div><div class="weatherWind"><i class="fa fa-cloud"></i> {{wind.speed}} m/s, {{wind.direction}} °</div>\', lang:"'.$this->user->lang('XML_LANG').'", units: "'.$tempformat.'", iconfont: true});', "docready");
			return '<div id="weather_'.$this->id.'" class="simpleopenweather" data-simpleopenweather-city="'.$strUserTown.', '.$strUserCountry.'"></div>';
		
		} elseif($this->config('geolocation') == '1'){
			//User has nothing in profile, let's try geolocation
			$js = '
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(locationSuccess, locationError);
				}else{
					showError("Your browser does not support Geolocation!");
				}
		
				function locationSuccess(position) {
					$("#weather_'.$this->id.'").simpleopenweather({latitude: position.coords.latitude, longitude: position.coords.longitude, template: \'<div style="display: block;" class="toggle_container"><div class="weatherCity"><i class="fa fa-map-marker fa-fw"></i> {{place}}</div><div class="weatherMiddle">{{icon}}<span class="weatherTemp">{{temperature}} '.$temperature_unit.'</span></div><div class="weatherDesc"><i class="fa fa-tag fa-fw"></i> {{sky}}</div><div class="weatherHumidity"><i class="fa fa-tint fa-fw"></i> {{humidity}} %</div><div class="weatherWind"><i class="fa fa-cloud"></i> {{wind.speed}} m/s, {{wind.direction}} °</div>\', lang:"'.$this->user->lang('XML_LANG').'", units: "'.$tempformat.'", iconfont: true});
				}		
				function showError(msg){
					$("#error").html(msg);
				}';
			if ($strTown != "" && $strCountry != ""){
				$js .= '
				function locationError(error){
					$("#weather_'.$this->id.'").attr("data-simpleopenweather-city", "'.$strTown.', '.$strCountry.'");	
					$("#weather_'.$this->id.'").simpleopenweather({template: \'<div style="display: block;" class="toggle_container"><div class="weatherCity"><i class="fa fa-map-marker fa-fw"></i> {{place}}</div><div class="weatherMiddle">{{icon}}<span class="weatherTemp">{{temperature}} '.$temperature_unit.'</span></div><div class="weatherDesc"><i class="fa fa-tag fa-fw"></i> {{sky}}</div><div class="weatherHumidity"><i class="fa fa-tint fa-fw"></i> {{humidity}} %</div><div class="weatherWind"><i class="fa fa-cloud"></i> {{wind.speed}} m/s, {{wind.direction}} °</div>\', lang:"'.$this->user->lang('XML_LANG').'", units: "'.$tempformat.'", iconfont: true});
				}
				';
			} else {
				$js .= '
				function locationError(error){
					showError(error);
				}
				';
			}	
			
			$this->tpl->add_js($js, "docready");
			
			return '<div id="weather_'.$this->id.'" class="simpleopenweather"></div>';
		}elseif($strCountry != "" && $strTown != ""){
			//Show the default weather
			$this->tpl->add_js('$("#weather_'.$this->id.'").simpleopenweather({template: \'<div style="display: block;" class="toggle_container"><div class="weatherCity"><i class="fa fa-map-marker fa-fw"></i> {{place}}</div><div class="weatherMiddle">{{icon}}<span class="weatherTemp">{{temperature}} '.$temperature_unit.'</span></div><div class="weatherDesc"><i class="fa fa-tag fa-fw"></i> {{sky}}</div><div class="weatherHumidity"><i class="fa fa-tint fa-fw"></i> {{humidity}} %</div><div class="weatherWind"><i class="fa fa-cloud"></i> {{wind.speed}} m/s, {{wind.direction}} °</div>\', lang:"'.$this->user->lang('XML_LANG').'", units: "'.$tempformat.'", iconfont: true});', "docready");
			return '<div id="weather_'.$this->id.'" class="simpleopenweather" data-simpleopenweather-city="'.$strTown.', '.$strCountry.'"></div>';
			
		}else {
			return $this->user->lang('weather_no_data');
		}
		
	}

}
?>