<?php
/*
 * Project:     EQdkp-Plus
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2010-12-05 20:51:33 +0100 (So, 05. Dez 2010) $
 * -----------------------------------------------------------------------
 * @author      $Author: corgan $
 * @copyright   2006-2008 Corgan - Stefan Knaak | Wallenium & the EQdkp-Plus Developer Team
 * @link        http://eqdkp-plus.com
 * @package     eqdkp-plus
 * @version     $Rev: 9346 $
 * 
 * $Id: module.php 9346 2010-12-05 19:51:33Z corgan $
 */

if ( !defined('EQDKP_INC') ){
  header('HTTP/1.0 404 Not Found');exit;
}

$portal_module['weather'] = array(
			'name'           => 'Weather',
			'path'           => 'weather',
			'version'        => '1.0.0',
			'author'         => 'WalleniuM',
			'contact'        => 'http://www.eqdkp-plus.com',
			'description'    => 'Shows the wather and a 2 days forcast',
			'positions'      => array('left1', 'left2', 'right'),
      'signedin'       => '1',
      'custom_tables'	 => array('module_weather'),
      'install'        => array(
			                      'autoenable'        => '0',
			                      'defaultposition'   => 'left2',
			                      'defaultnumber'     => '11',
			                      'customsql'					=> array(
			                      		"CREATE TABLE IF NOT EXISTS __module_weather (
				          								`user_id` varchar(255) NOT NULL default '',
				          								`last_update` varchar(255) NOT NULL default '',
								                  `weather_xml` blob,
								                  PRIMARY KEY  (`user_id`)
										              )",
			                      	),
			                    ),
    );

$portal_settings['weather'] = array(
  'pk_weather_hours'     => array(
        'name'      => 'pk_weather_hours',
        'language'  => 'pk_weather_hours',
        'property'  => 'text',
        'size'      => '3',
      ),
);

if(!function_exists(weather_module))
{
  function weather_module()
  {
  	global $eqdkp, $plang, $pcache, $pm, $db, $eqdkp_root_path, $user, $conf_plus;
  	$image_path = $eqdkp_root_path.'portal/weather/images/';
  	$seconds_to_midnight= ($conf_plus['pk_weather_hours']) ? $conf_plus['pk_weather_hours']*3600 : 6*3600;
  	
  	// Try to select the cached xml..
  	$weatherdata_sql	= $db->query("SELECT * FROM __module_weather WHERE user_id='". $user->data['user_id']."'");
  	$mwdata						= $db->fetch_record($weatherdata_sql);
  	
  	if(($mwdata['last_update']+$seconds_to_midnight) > time()){
  		// use the database cached version
			$xml = simplexml_load_string($mwdata['weather_xml']);
  	}else{
	  	// Load weather data
	  	if($debug == 3){
	  		System_Message('Debug: Fetched Weather Data');
	  	}
	    $weather_sql     = "SELECT country, ZIP_code, town FROM __users WHERE user_id='". $user->data['user_id']."'";
	    $weather_result  = $db->query($weather_sql);
	    $user_data = $db->fetch_record($weather_result);
	
	    if($user_data['country'] && $user_data['ZIP_code'] != 0){
	      // Load the Country states
	      $cfile = $eqdkp_root_path.'pluskernel/include/country_states.php';
	      if (file_exists($cfile)){
	        include_once($cfile);
	        $myCountry = strtolower($country_array[$user_data['country']]);
	      }
	      $myURL1 = 'http://www.google.com/ig/api?weather='.$user_data['ZIP_code'].'+'.$myCountry.'&hl='.strtolower($user->lang['XML_LANG']);
	      $myURL2 = 'http://www.google.com/ig/api?weather='.$user_data['town'].'+'.$myCountry.'&hl='.strtolower($user->lang['XML_LANG']);
	      
	      // parse the weather Data
	      $xml = simplexml_load_string(utf8_encode(file_get_contents($myURL1)));
	      if(!$xml->weather->forecast_information->city['data']){
          $xml = simplexml_load_string(utf8_encode(file_get_contents($myURL2)));
        }
        if($xml){
		      $db->query("DELETE FROM __module_weather WHERE user_id='". $user->data['user_id']."'");
		      $db->query("INSERT INTO __module_weather (user_id, weather_xml, last_update) VALUES ('".$user->data['user_id']."', '".$xml->asXML()."', '".time()."')");
	      }
	    }else{
      	$myOut = $plang['pk_weather_no_data'];
    	}
		}
			
    // parse it!
    if(!($xml)){
      $myOut  = $plang['pk_weather_xml_err'];
    }else{
      $city = (string)$xml->weather->forecast_information->postal_code['data'];
      $wdata = array();
      $wdata['current'] = array(
				"city"            => utf8_decode((string)$xml->weather->forecast_information->city['data']),
				"condition"       => (string)$xml->weather->current_conditions->condition['data'],
				"temp"            => (string)$xml->weather->current_conditions->temp_c['data'],
				"humidity"        => preg_replace('/Â/', '', utf8_decode((string)$xml->weather->current_conditions->humidity['data'])),
				"humidity_array"  => explode(": ",preg_replace('/Â/', '', (string)$xml->weather->current_conditions->humidity['data'])),
				"wind"            => (string)$xml->weather->current_conditions->wind_condition['data'],
				"wind_array"      => explode(": ",(string)$xml->weather->current_conditions->wind_condition['data']),
				"icon"            => "http://www.google.de".$xml->weather->current_conditions->icon['data']
      );
			
      $forecast = array();$i = 0;
      foreach($xml->weather->forecast_conditions as $item) {
        $wdata['future'][$i]["dow"]        = (string)$item->day_of_week['data'];
        $wdata['future'][$i]["low"]        = (string)$item->low['data'];
        $wdata['future'][$i]["high"]       = (string)$item->high['data'];
        $wdata['future'][$i]["icon"]       = "http://www.google.de".((string)$item->icon['data']);
        $wdata['future'][$i]["condition"]  = (string)$item->condition['data'];
        $i++;
      }

      // Generate Output
      $myOut  = '';
      //$myOut  = '<span style="color:red;font-size: 16px;">ALPHA VERSION - NOT FOR USAGE!</span>';
     	$myOut  .= "<table cellpadding='3' cellSpacing='2' width='100%'>";
      $myOut .= "<tr valign='top'>
                    <td>
                      <table cellpadding='3' cellSpacing='2' width='100%'>
                        <tr>
                          <td colspan=2><div class='weather_city'>".$wdata['current']['city']."</div></td>
                        </tr>
                        <tr>
                          <td width='42px'><img src='".$wdata['current']['icon']."' /></td>
                          <td>
                            <div class='weather_temp'><img src='".$image_path."temp_small.png' alt='".$plang['pk_weather_temp']."' title='".$plang['pk_weather_temp'].": ".$wdata['current']["temp"]."°C'>".$wdata['current']["temp"]."°C</div>
                            <div class='weather_humidity'><img src='".$image_path."humidity_small.png' alt='".$wdata['current']["humidity"]."' title='".$wdata['current']["humidity"]."'> ".$wdata['current']["humidity_array"][1]."</div>
                            <div class='weather_wind'><img src='".$image_path."wind_small.png' alt='".$wdata['current']["wind"]."' title='".$wdata['current']["wind"]."'> ".$wdata['current']["wind_array"][1]."</div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>";

      $myOut .= "<tr valign='top' >
                    <td>
                    <table cellpadding='3' cellSpacing='2' width='100%'>
                    <tr>";
      for ($ic = 0; $ic < 3; $ic++){
        $myOut .= "<td align='center'>
                        <img src='".$wdata['future'][$ic]['icon']."' /><br/>
                        ".$wdata['future'][$ic]['dow']."
                    </td>";
      }
     	$myOut .= "</tr></table>
      	          </td></tr></table>";

    }
    return $myOut;
  }
}
?>
