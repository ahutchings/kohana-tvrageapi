<?php defined('SYSPATH') or die('No direct script access.');

class TVRageAPI
{
	const API_URL = 'http://services.tvrage.com/feeds/';

	protected static $_instance;

	/**
	 * Returns an instance of the TVRageAPI class.
	 *
	 * @return  TVRageAPI
	 */
	public static function instance()
	{
		if ( ! isset(self::$_instance))
		{
			self::$_instance = new TVRageAPI;
		}

		return self::$_instance;
	}

	public function search($showname)
	{
		$params['show'] = $showname;

		return $this->_request(__FUNCTION__, $params);
	}

	public function full_search($showname)
	{
		$params['show'] = $showname;

		return $this->_request(__FUNCTION__, $params);
	}

	public function showinfo($showid)
	{
		$params['sid'] = $showid;

		return $this->_request(__FUNCTION__, $params);
	}

	public function episode_list($showid)
	{
		$params['sid'] = $showid;

		return $this->_request(__FUNCTION__, $params);
	}

	public function episodeinfo($showname, $season, $episode, $exact = TRUE)
	{
		$params = array(
			'show' => $showname,
			'ep'   => $season.'x'.$episode
			);

		if ($exact)
		{
			$params['exact'] = '1';
		}

		return $this->_request(__FUNCTION__, $params);
	}

	public function full_show_info($showid)
	{
		$params['sid'] = $showid;

		return $this->_request(__FUNCTION__, $params);
	}

	/**
	 * Makes an API request and returns the response.
	 *
	 * @param   string  method to call
	 * @param   array   query parameters
	 * @return  array
	 */
	protected function _request($method, array $params = NULL)
	{
		// Build the API URL
		$endpoint = self::API_URL.$method.'.php?'.http_build_query($params, NULL, '&');

		try
		{
			// Make an API request
			$response = Request::factory($endpoint, HTTP_Cache::factory('sqlite'))
				->execute()
				->body();
		}
		catch (Kohana_Exception $e)
		{
			throw new TVRageAPI_Exception('API :method request failed, API may be offline',
				array(':method' => $method));
		}

		return new SimpleXMLElement($response);
	}
}
