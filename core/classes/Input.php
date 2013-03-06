<?php
/**
 * Input class
 *
 * The input class allows you to access HTTP parameters, load server variables
 * and user agent details.
 *
 * @category  Core
 * @name Input
 */
class Input{
	
	public static function protocol(){
		return strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
               === FALSE ? 'http' : 'https';
	}
	
	/**
	 * Get the real ip address of the user.  Even if they are using a proxy.
	 *
	 * @param	string	the default to return on failure
	 * @param	bool	exclude private and reserved IPs
	 * @return  string  the real ip address of the user
	 */
	public static function real_ip($default = '0.0.0.0', $exclude_reserved = false)
	{
		$server_keys = array('HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');

		foreach ($server_keys as $key)
		{
			if ( ! isset($_SERVER["$key"]))
			{
				continue;
			}

			$ips = explode(',', $_SERVER[$key]);
			array_walk($ips, function (&$ip) {
				$ip = trim($ip);
			});

			$ips = array_filter($ips, function($ip) use($exclude_reserved) {
				return filter_var($ip, FILTER_VALIDATE_IP, $exclude_reserved ? FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE : null);
			});

			if ($ips)
			{
				return reset($ips);
			}
		}
		
		return $default;
	}
	
	/**
	 * Return's whether this is an AJAX request or not
	 *
	 * @return  bool
	 */
	public static function is_ajax()
	{
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
	}
	
}

