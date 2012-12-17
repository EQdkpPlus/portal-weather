<?php
 /*
 * Project:		EQdkp-Plus
 * License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:		2008
 * Date:		$Date: 2012-12-15 12:24:26 +0100 (Sa, 15. Dez 2012) $
 * -----------------------------------------------------------------------
 * @author		$Author: godmod $
 * @copyright	2006-2011 EQdkp-Plus Developer Team
 * @link		http://eqdkp-plus.com
 * @package		eqdkp-plus
 * @version		$Rev: 12591 $
 * 
 * $Id: english.php 12591 2012-12-15 11:24:26Z godmod $
 */

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

$lang = array(
	'weather'					=> 'Weather',
	'weather_desc'				=> 'Shows the weather and a 2 days forcast',
	'weather_name'				=> 'Weather',
	'pk_weather_no_data'		=> 'No Country or ZIP set in your user profile. Weather will only show information if you\'ve set up your Profile information.',
	'pk_weather_xml_err'		=> 'Weather Data could not be fetched. Try again later...',
	'pk_weather_hours'			=> 'Hours to cache the weather data (default: 6)',
	'pk_weather_tempformat'		=> 'Temperature Format (default: Celcius)',
	'pk_weather_debfetch'		=> 'Debug: Fetched Weather Data',
	'pk_weather_loading'		=> 'Loading weather data...',
	'pk_weather_wind_txt'		=> 'Wind',
	'pk_weather_high'			=> 'High',
	'pk_weather_low'			=> 'Low',
	'pk_weather_fulllink'		=> 'Full forecast',
	'pk_weather_wind'			=> array(
		'N'		=> 'N',
		'NE'	=> 'NE',
		'NW'	=> 'NW',
		'E'		=> 'E',
		'S'		=> 'S',
		'SW'	=> 'SW',
		'SE'	=> 'SE',
		'W'		=> 'W'
	),
	'pk_weather_condition'		=> array(
		0		=> 'Tornado',
		1		=> 'Tropical storm',
		2		=> 'Hurricane',
		3		=> 'Severe thunderstorms',
		4		=> 'Thunderstorms',
		5		=> 'Mixed rain and snow',
		6		=> 'Mixed rain and sleet',
		7		=> 'Mixed snow and sleet',
		8		=> 'Freezing drizzle',
		9		=> 'Drizzle',
		10		=> 'Freezing rain',
		11		=> 'Showers',
		12		=> 'Showers',
		13		=> 'Snow flurries',
		14		=> 'Light snow showers',
		15		=> 'Blowing snow',
		16		=> 'Snow',
		17		=> 'Hail',
		18		=> 'Sleet',
		19		=> 'Dust',
		20		=> 'Foggy',
		21		=> 'Haze',
		22		=> 'Smoky',
		23		=> 'Blustery',
		24		=> 'Sindy',
		25		=> 'Cold',
		26		=> 'Cloudy',
		27		=> 'Mostly cloudy',		// night
		28		=> 'Mostly cloudy',		// day
		29		=> 'Partly cloudy',		// night
		30		=> 'Partly cloudy',		// day
		31		=> 'Clear',				// night
		32		=> 'Sunny',
		33		=> 'Fair',				// night
		34		=> 'Fair',				// day
		35		=> 'Mixed rain and hail',
		36		=> 'Hot',
		37		=> 'Isolated thunderstorms',
		38		=> 'Scattered thunderstorms',
		39		=> 'Scattered thunderstorms',
		40		=> 'Scattered showers',
		41		=> 'Heavy snow',
		42		=> 'Scattered snow showers',
		43		=> 'Heavy snow',
		44		=> 'Partly cloudy',
		45		=> 'Thundershowers',
		46		=> 'Snow showers',
		47		=> 'Isolated thundershowers',
		3200	=> 'Not available'
	),
);
?>