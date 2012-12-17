<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-04-17 04:56:53 +0900 (2009-04-17, ��) $
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
  'weather'             => '����',
  'pk_weather_no_data'  => '������ �����ȣ�� �����ʿ� �������� �ʾҽ��ϴ�. ������ ����� �������� �������� ���� �۵��մϴ�.',
  'pk_weather_xml_err'  => '���� ������ �޾ƿ��� ���߽��ϴ�. ���� �ٽ� �õ��Ͻʽÿ�...',
  'pk_weather_temp'     => '���',
  'pk_weather_hours'		=> '���� ������ �޾ƿ� �ð� (default: 6)',
));
?>
