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
		'lang_prefix'	=> 'weather_'
	);
	protected static $positions = array('left1', 'left2', 'right');
	protected $settings	= array(
		'tempformat'	=> array(
			'type'		=> 'dropdown',
			'options'	=> array('C' => 'C','F' => 'F'),
		),
		'geolocation'		=> array(
			'type'		=> 'radio',
		),
	);
	protected static $install	= array(
		'autoenable'		=> '1',
		'defaultposition'	=> 'left2',
		'defaultnumber'		=> '11',
	);
	
	protected static $apiLevel = 20;

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
		
		if($this->user->data['user_id'] > 0 &&($this->user->data['country'] != '' && $this->user->data['town'] != '')){
			$this->tpl->add_js('$("#weather").simpleopenweather({template: \'<div style="display: block;" class="toggle_container"><div class="weatherCity"><i class="fa fa-map-marker fa-fw"></i> {{place}}</div><div class="weatherMiddle">{{icon}}<span class="weatherTemp">{{temperature}} '.$temperature_unit.'</span></div><div class="weatherDesc"><i class="fa fa-tag fa-fw"></i> {{sky}}</div><div class="weatherHumidity"><i class="fa fa-tint fa-fw"></i> {{humidity}} %</div><div class="weatherWind"><i class="fa fa-cloud"></i> {{wind.speed}} m/s, {{wind.direction}} °</div>\', lang:"'.$this->user->lang('XML_LANG').'", units: "'.$tempformat.'", iconfont: true});', "docready");
			return '<div id="weather" class="simpleopenweather" data-simpleopenweather-city="'.$this->user->data['town'].', '.$this->user->data['country'].'"></div>';
		}elseif($this->config('geolocation') == '1'){
			$this->tpl->add_js('
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(locationSuccess, locationError);
				}else{
					showError("Your browser does not support Geolocation!");
				}
		
				function locationSuccess(position) {
					$("#weather").simpleopenweather({latitude: position.coords.latitude, longitude: position.coords.longitude, template: \'<div style="display: block;" class="toggle_container"><div class="weatherCity"><i class="fa fa-map-marker fa-fw"></i> {{place}}</div><div class="weatherMiddle">{{icon}}<span class="weatherTemp">{{temperature}} '.$temperature_unit.'</span></div><div class="weatherDesc"><i class="fa fa-tag fa-fw"></i> {{sky}}</div><div class="weatherHumidity"><i class="fa fa-tint fa-fw"></i> {{humidity}} %</div><div class="weatherWind"><i class="fa fa-cloud"></i> {{wind.speed}} m/s, {{wind.direction}} °</div>\', lang:"'.$this->user->lang('XML_LANG').'", units: "'.$tempformat.'", iconfont: true});
				}

				function locationError(error){
					showError(error);
				}
		
				function showError(msg){
					$("#error").html(msg);
				}', "docready");
			return '<div id="weather" class="simpleopenweather"></div>';
		}else{
			return $this->user->lang('weather_no_data');
		}
		
	}

}
?>