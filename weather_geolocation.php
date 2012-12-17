<?php
 /*
 * Project:		EQdkp-Plus
 * License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:		2008
 * Date:		$Date: 2012-10-03 15:24:30 +0200 (Mi, 03. Okt 2012) $
 * -----------------------------------------------------------------------
 * @author		$Author: godmod $
 * @copyright	2006-2011 EQdkp-Plus Developer Team
 * @link		http://eqdkp-plus.com
 * @package		eqdkp-plus
 * @version		$Rev: 12187 $
 * 
 * $Id: weather_portal.class.php 12187 2012-10-03 13:24:30Z godmod $
 */
define('EQDKP_INC', true);

$eqdkp_root_path = './../../';
include_once($eqdkp_root_path.'common.php');
include_once($eqdkp_root_path.'portal/twitter/weather_portal.class.php');
$weather = registry::register('weather_portal');
echo $weather->geolocation();

?>