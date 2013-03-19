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
 * $Id: german.php 12591 2012-12-15 11:24:26Z godmod $
 */

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

$lang = array(
	'weather'					=> 'Wetter',
	'weather_desc'				=> 'Zeigt das aktuelle Wetter und eine 2 Tages Vorhersage',
	'weather_name'				=> 'Wetter',
	'pk_weather_no_data'		=> 'Es wurde kein Land oder keine PLZ in deinem Userprofil gesetzt. Die Wetterdaten können so nicht abgerufen werden.',
	'pk_weather_xml_err'		=> 'Wetterdaten konnten nicht abgerufen werden. Bitte versuche es später erneut...',
	'pk_weather_hours'			=> 'Stunden zum cachen der Wetterdaten (Standard: 6)',
	'pk_weather_tempformat'		=> 'Format der Temperatur (Standard: Celcius)',
	'pk_weather_debfetch'		=> 'Debug: Fetched Weather Data',
	'pk_weather_loading'	=> 'Lade Wetterdaten...',
	'pk_weather_wind_txt'		=> 'Wind',
	'pk_weather_high'			=> 'Hoch',
	'pk_weather_low'			=> 'Tief',
	'pk_weather_fulllink'		=> 'Volle Vorhersage',
	'pk_weather_wind'			=> array(
		'N'		=> 'N',
		'NE'	=> 'NO',
		'NW'	=> 'NW',
		'E'		=> 'O',
		'S'		=> 'S',
		'SW'	=> 'SW',
		'SE'	=> 'SO',
		'W'		=> 'W'
	),
	'pk_weather_condition'		=> array(
		0		=> 'Tornado',
		1		=> 'Tropischer Sturm',
		2		=> 'Orkan',
		3		=> 'Heftiges Gewitter',
		4		=> 'Gewitter',
		5		=> 'Regen und Schnee',
		6		=> 'Regen und Eisregen',
		7		=> 'Schnee und Eisregen',
		8		=> 'Gefrierender Nieselregen',
		9		=> 'Nieselregen',
		10		=> 'Gefrierender Regen',
		11		=> 'Schauer',
		12		=> 'Schauer',
		13		=> 'Schneeschauer',
		14		=> 'Leichte Schneeschauer',
		15		=> 'Stürmiger Schneefall',
		16		=> 'Schnee',
		17		=> 'Hagel',
		18		=> 'Eisregen',
		19		=> 'Staub',
		20		=> 'Neblig',
		21		=> 'Dunst',
		22		=> 'Staubig',
		23		=> 'Stürmisch',
		24		=> 'Windig',
		25		=> 'Kalt',
		26		=> 'Bewölkt',
		27		=> 'Größtenteils bewölkt',	// night
		28		=> 'Größtenteils bewölkt',	// day
		29		=> 'Teilweise bewölkt',		// night
		30		=> 'Teilweise bewölkt',		// day
		31		=> 'Klar',					// night
		32		=> 'Sonnig',
		33		=> 'Schön',					// night
		34		=> 'Schön',					// day
		35		=> 'Regen und Hagel',
		36		=> 'Heiß',
		37		=> 'Einzelne Gewitter',
		38		=> 'Vereinzelte Gewitter',
		39		=> 'Vereinzelte Gewitter',
		40		=> 'Vereinzelte Schauer',
		41		=> 'Starker Schneefall',
		42		=> 'Vereinzelte Schneeschauer',
		43		=> 'Starker Schneefall',
		44		=> 'Teilweise bewölkt',
		45		=> 'Später Schauer',
		46		=> 'Schneeschauer',
		47		=> 'Einzelne Gewitterschauer',
		3200	=> 'Nicht verfügbar'
	),
);
?>