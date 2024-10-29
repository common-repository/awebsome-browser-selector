<?php
/*
	Plugin Name: Awebsome! Browser Selector
	Plugin URI: http://plugins.awebsome.com
	Description: Empower your CSS selectors. Write specific CSS code for each Platform/Browser/Version the right way.
	Version: 2.2
	Author: Raul Illana <r@awebsome.com>
	Author URI: http://raulillana.com
	License: GPLv2
*/
if( !class_exists('Awebsome_Browser_Selector') ):
/**
 * Awebsome Browser Selector
 * 
 * @since 2.0
 */
class Awebsome_Browser_Selector
{
	/**
	 * PHP5 Constructor
	 */
	public function __construct()
	{
		add_filter('body_class', array(&$this, 'add_body_classes'));
		
		// BuddyPress support
		add_action('bp_include', array(&$this, 'bp_activate'));
	}
	
	/**
	 * Only load code that needs BuddyPress to run once BP is loaded and initialized
	 * 
	 * @since 2.0
	 */
	public function bp_activate()
	{
		require(dirname(__FILE__) .'/'. __FILE__);
	}
	
	/**
	 * Parses the user agent string into browser, version and platform
	 * 
	 * @author Jesse G. Donat <donatj@gmail.com>
	 * @link https://github.com/donatj/PhpUserAgent
	 * @param string $ua
	 * @return array an array with browser, version and platform keys
	 * 
	 * @since 2.0
	 */
	public function parse_UA_string($ua = null)
	{
		if( is_null($ua) ) $ua = $_SERVER['HTTP_USER_AGENT'];
		
		$data = array(
			'platform' => '',
			'browser'  => '',
			'version'  => '',
		);
		
		if( preg_match('/\((.*?)\)/im', $ua, $regs) )
		{
			/*
			(?P<platform>Android|CrOS|iPhone|iPad|Linux|Macintosh|Windows\ Phone\ OS|Windows|Silk|linux-gnu|BlackBerry|Xbox)
			(?:\ [^;]*)?
			(?:;|$)
			*/
			preg_match_all('/(?P<platform>Android|CrOS|iPhone|iPad|Linux|Macintosh|Windows\ Phone\ OS|Windows|Silk|linux-gnu|BlackBerry|Nintendo\ Wii|Xbox)(?:\ [^;]*)?(?:;|$)/imx', $regs[1], $result, PREG_PATTERN_ORDER);
		
			$priority = array('Android', 'Xbox');
			
			$result['platform'] = array_unique($result['platform']);
			
			if( count($result['platform']) > 1 )
			{
				if( $keys = array_intersect($priority, $result['platform']) ) $data['platform'] = reset($keys);
				else $data['platform'] = $result['platform'][0];
			}
			elseif( isset($result['platform'][0]) ) $data['platform'] = $result['platform'][0];
		}
		
		if( $data['platform'] == 'linux-gnu' ) $data['platform'] = 'Linux';
		if( $data['platform'] == 'CrOS' ) $data['platform'] = 'Chrome OS';
	
		/*
		(?<browser>Camino|Kindle|Kindle\ Fire\ Build|Firefox|Safari|MSIE|AppleWebKit|Chrome|IEMobile|Opera|Silk|Lynx|Version|Wget|curl|PLAYSTATION\ \d+)
		(?:;?)
		(?:(?:[/\ ])(?<version>[0-9.]+)|/(?:[A-Z]*))
		*/
		preg_match_all('%(?P<browser>Camino|Kindle|Kindle\ Fire\ Build|Firefox|Safari|MSIE|AppleWebKit|Chrome|IEMobile|Opera|Silk|Lynx|Version|Wget|curl|PLAYSTATION\ \d+)(?:;?)(?:(?:[/ ])(?P<version>[0-9.]+)|/(?:[A-Z]*))%x', 
			$ua, $result, PREG_PATTERN_ORDER);
		
		$key = 0;
		
		$data['browser'] = $result['browser'][0];
		$data['version'] = $result['version'][0];
		
		if( ($key = array_search('Kindle Fire Build', $result['browser'])) !== false || ($key = array_search('Silk', $result['browser'])) !== false )
		{
			$data['browser']  = $result['browser'][$key] == 'Silk' ? 'Silk' : 'Kindle';
			$data['platform'] = 'Kindle Fire';
			
			if( !($data['version']  = $result['version'][$key]) ) $data['version'] = $result['version'][array_search( 'Version', $result['browser'] )];
		}
		elseif( ($key = array_search('Kindle', $result['browser'])) !== false )
		{
			$data['browser']  = $result['browser'][$key];
			$data['platform'] = 'Kindle';
			$data['version']  = $result['version'][$key];
		}
		elseif( $result['browser'][0] == 'AppleWebKit' )
		{
			if( ( $data['platform'] == 'Android' && !($key = 0) ) || $key = array_search('Chrome', $result['browser']) )
			{
				$data['browser'] = 'Chrome';
				
				if( ($vkey = array_search('Version', $result['browser'])) !== false ) $key = $vkey;
			}
			elseif( $data['platform'] == 'BlackBerry' )
			{
				$data['browser'] = 'BlackBerry Browser';
				
				if( ($vkey = array_search('Version', $result['browser'])) !== false ) $key = $vkey;
			}
			elseif( $key = array_search('Safari', $result['browser']) )
			{
				$data['browser'] = 'Safari';
				
				if( ($vkey = array_search('Version', $result['browser'])) !== false ) $key = $vkey;
			}
			
			$data['version'] = $result['version'][$key];
		}
		elseif( ($key = array_search('Opera', $result['browser'])) !== false )
		{
			$data['browser'] = $result['browser'][$key];
			$data['version'] = $result['version'][$key];
			
			if( ($key = array_search('Version', $result['browser'])) !== false ) $data['version'] = $result['version'][$key];
		}
		elseif( $result['browser'][0] == 'MSIE' )
		{
			if( $key = array_search('IEMobile', $result['browser']) ) $data['browser'] = 'IEMobile';
			else
			{
				$data['browser'] = 'MSIE';
				$key = 0;
			}
			
			$data['version'] = $result['version'][$key];
		}
		elseif( $key = array_search('PLAYSTATION 3', $result['browser']) !== false )
		{
			$data['platform'] = 'PLAYSTATION 3';
			$data['browser']  = 'NetFront';
		}
		
		return $data;
	}
	
	/**
	 * Converts the parsed User Agent string to CSS classes
	 * 
	 * @param $data array Server User Agent parsed
	 * @return string     Parsed CSS classes (platform + browser + version)
	 * 
	 * @since 2.0
	 */
	public function parse_UA_to_classes($data)
	{
		$css['platform'] = self::filter_platform($data['platform']);
		$css['browser']  = self::filter_browser($data['browser']);
		$css['version']  = self::filter_version($data['version']);
		
		return join(' ', $css);
	}
	
	/**
	 * Filters the Platform CSS string
	 * 
	 * @param $platform string Server User Agent Platform parsed
	 * @return string          CSS Platform class
	 * 
	 * @since 2.0
	 */
	public function filter_platform($platform)
	{
		$p = '';
		
		# Android|CrOS|iPhone|iPad|Linux|Macintosh|Windows\ Phone\ OS|Windows|Silk|linux-gnu|BlackBerry|Xbox
		switch($platform)
		{
			// desktop
			case 'Windows'   : $p = 'win';  break;
			case 'Linux'     : $p = 'lnx';  break;
			case 'Macintosh' : $p = 'mac';  break;
			case 'ChromeOS'  : $p = 'cros'; break;
			
			// mobile
			case 'Android'          : $p = 'android';    break;
			case 'iPhone'           : $p = 'iphone';     break;
			case 'iPad'             : $p = 'ipad';       break;
			case 'Windows Phone OS' : $p = 'winphone';   break;
			case 'Kindle'           : $p = 'kindle';     break;
			case 'Kindle Fire'      : $p = 'kindlefire'; break;
			case 'BlackBerry'       : $p = 'blackberry'; break;
			
			// consoles
			case 'Xbox'          : $p = 'xbox'; break;
			case 'PLAYSTATION 3' : $p = 'ps3';  break;
			case 'Nintendo Wii'  : $p = 'wii'; break;
			
			default : break;
		}
		
		return $p;
	}
	
	/**
	 * Filters the Browser CSS string
	 * 
	 * @param $browser string Server User Agent Browser parsed
	 * @return string         CSS Browser class
	 * 
	 * @since 2.0
	 */
	public function filter_browser($browser)
	{
		$b = '';
		
		# Camino|Kindle|Kindle\ Fire\ Build|Firefox|Safari|MSIE|AppleWebKit|Chrome|IEMobile|Opera|Silk|Lynx|Version|Wget|curl|PLAYSTATION\
		switch($browser)
		{
			case 'Camino'            : $b = 'camino';   break;
			case 'Kindle'            : $b = 'kindle';   break;
			case 'Firefox'           : $b = 'firefox';  break;
			case 'Safari'            : $b = 'safari';   break;
			case 'Internet Explorer' : $b = 'ie';       break;
			case 'IEMobile'          : $b = 'iemobile'; break;
			case 'Chrome'            : $b = 'chrome';   break;
			case 'Opera'             : $b = 'opera';    break;
			case 'Silk'              : $b = 'silk';     break;
			case 'Lynx'              : $b = 'lynx';     break;
			case 'Wget'              : $b = 'wget';     break;
			case 'Curl'              : $b = 'curl';     break;
			
			default : break;
		}
		
		return $b;
	}
	
	/**
	 * Filters the Version CSS string
	 * 
	 * @param $browser string Server User Agent Version parsed
	 * @return string         CSS Version class
	 * 
	 * @since 2.0
	 */
	public function filter_version($version)
	{
		$v = explode('.', $version);
		
		return !empty($v[0]) ? 'v'. $v[0] : '';
	}
	
	/**
	 * Callback function for the body_class filter
	 * 
	 * @param $classes array Body tag classes
	 * @return array         Body tag classes + parsed UA classes
	 * 
	 * @since 2.0
	 */
	function add_body_classes($classes)
	{
		$classes[] = self::parse_UA_to_classes( self::parse_UA_string() );
		
		return $classes;
	}
} // class
endif;

/**
 * Enable the plugin
 */
new Awebsome_Browser_Selector;
?>