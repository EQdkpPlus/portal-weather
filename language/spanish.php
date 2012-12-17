<?php

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: Spanish	
//Created by EQdkp Plus Translation Tool on  2010-07-09 15:10
//File: module_weather
//Source-Language: english

$alang = array( 
"weather" => "Tiempo",
"pk_weather_no_data" => "No has indicado País o CP en tu perfil de usuario. El Tiempo sólo se mostrará si indicaste esta información en tu perfil.",
"pk_weather_xml_err" => "No se pueden obtener los datos del tiempo. Inténtalo más tarde...",
"pk_weather_temp" => "Temperatura",
"pk_weather_hours" => "Horas de almacenamiento de los datos del tiempo en caché (por defecto: 6)",
 );
$plang = array_merge($plang, $alang);
?>