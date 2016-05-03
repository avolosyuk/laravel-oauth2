<?php

namespace OAuth2\Provider;

use OAuth2\Provider;
use OAuth2\Token_Access;

class Facebook extends Provider
{
	public $name = 'facebook';

	public $uid_key = 'uid';

	public $scope = array('email', 'read_stream');

	public function url_authorize()
	{
		return 'https://www.facebook.com/dialog/oauth';
	}

	public function url_access_token()
	{
		return 'https://graph.facebook.com/oauth/access_token';
	}

	public function get_user_info(Token_Access $token)
	{
		$url = 'https://graph.facebook.com/me?'.http_build_query(array(
				'access_token' => $token->access_token,
				'fields' => 'name,first_name,last_name,hometown,link,gender'
			));

		$user = json_decode(file_get_contents($url));
		$location = isset($user->hometown) && isset($user->hometown->name) ? $user->hometown->name : '';

		// Create a response from the request
		return array(
			'uid' => $user->id,
			'name' => $user->name,
			'email' => isset($user->email) ? $user->email : '',
			'location' => $location,
			// 'description' => $user->bio,
			'image' => 'https://graph.facebook.com/me/picture?type=normal&access_token='.$token->access_token,
			'urls' => array(
				'Facebook' => $user->link,
			),
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'sex' => isset($user->gender) ? $user->gender : "",
			'bdate' => '',
			'city' => $location
		);
	}
}
