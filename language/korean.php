<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-04-17 04:56:53 +0900 (2009-04-17, 금) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 4613 $
 * 
 * $Id: english.php 4613 2009-04-16 19:56:53Z wallenium $
 */

if ( !defined('EQDKP_INC') ){
    header('HTTP/1.0 404 Not Found');exit;
}


$plang = array_merge($plang, array(
  'weather'             => '날씨',
  'pk_weather_no_data'  => '국가나 우편번호가 프로필에 설정되지 않았습니다. 날씨는 사용자 프로필을 설정했을 때만 작동합니다.',
  'pk_weather_xml_err'  => '날씨 정보를 받아오지 못했습니다. 이후 다시 시도하십시오...',
  'pk_weather_temp'     => '기온',
  'pk_weather_hours'		=> '날씨 정보를 받아올 시간 (default: 6)',
));
?>
