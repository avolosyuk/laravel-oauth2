<?php

namespace OAuth2\Provider;

use OAuth2\Provider;
use OAuth2\Token_Access;

class Twitter extends Provider
{
	public $name = 'twitter';

	public $uid_key = 'uid';

	public $scope = array('email', 'read_stream');

	public function url_authorize()
	{
		return 'https://api.twitter.com/oauth/authorize';
	}

	public function url_access_token()
	{
		return 'https://api.twitter.com/oauth/access_token';
	}

	public function get_user_info(Token_Access $token)
	{
		$url = 'https://api.twitter.com/1/users/show.json?'.http_build_query(array(
			'access_token' => $token->access_token,
		));



		$user = json_decode(file_get_contents($url));

		print_r($user);

		// Create a response from the request
		return array(
			'uid' => $user->id,
			'nickname' => $user->username,
			'name' => $user->name,
			'email' => $user->email,
			'location' => $user->hometown->name,
			// 'description' => $user->bio,
			'image' => 'https://graph.facebook.com/me/picture?type=normal&access_token='.$token->access_token,
			'urls' => array(
			  'Facebook' => $user->link,
			),
		);
	}
}
