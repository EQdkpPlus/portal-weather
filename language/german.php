<?php
/*	Project:	EQdkp-Plus
 *	Package:	Weather Portal Module
 *	Link:		http://eqdkp-plus.eu
 *
 *	Copyright (C) 2006-2015 EQdkp-Plus Developer Team
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Affero General Public License as published
 *	by the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Affero General Public License for more details.
 *
 *	You should have received a copy of the GNU Affero General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

$lang = array(
	'weather'				=> 'Wetter',
	'weather_desc'			=> 'Zeigt das aktuelle Wetter',
	'weather_name'			=> 'Wetter',
	'weather_no_data'		=> 'Es wurde kein Land oder keine Stadt in deinem Userprofil gesetzt. Die Wetterdaten können so nicht abgerufen werden.',
	'weather_f_tempformat'	=> 'Format der Temperatur (Standard: Celcius)',
	'weather_f_geolocation'	=> 'Geolocation-Option des Browsers verwenden wenn keine Ortsdaten im Profil hinterlegt sind?',
	'weather_f_default_country' => 'Standard-Land',
	'weather_f_default_town' => 'Standard-Stadt',
);
?>