<?php

if (!defined('EQDKP_INC')) {
	die('You cannot access this file directly.');
}

//Language: French	
//Created by EQdkp Plus Translation Tool on  2010-07-09 13:55
//File: module_weather
//Source-Language: english

$alang = array( 
"weather" => "M�t�o",
"pk_weather_no_data" => "Aucun pays ou code postal est entr� dans votre profil. La m�t�o ne s'affichera que si vous avez param�tr� ces informations",
"pk_weather_xml_err" => "Les donn�es de m�t�o ne sont pas accessibles. Veuillez r�essayer plus tard...",
"pk_weather_temp" => "Temp�rature",
"pk_weather_hours" => "Temps de rafra�chissement des donn�es en heures(d�faut = 6)",
 );
$plang = array_merge($plang, $alang);
?>