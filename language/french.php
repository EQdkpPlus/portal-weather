<?php

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: French	
//Created by EQdkp Plus Translation Tool on  2010-07-09 13:55
//File: module_weather
//Source-Language: english

$alang = array( 
"weather" => "Météo",
"pk_weather_no_data" => "Aucun pays ou code postal est entré dans votre profil. La météo ne s'affichera que si vous avez paramétré ces informations",
"pk_weather_xml_err" => "Les données de météo ne sont pas accessibles. Veuillez réessayer plus tard...",
"pk_weather_temp" => "Température",
"pk_weather_hours" => "Temps de rafraîchissement des données en heures(défaut = 6)",
 );
$plang = array_merge($plang, $alang);
?>