<?php

namespace OAuth2\Provider;

use OAuth2\Provider;
use OAuth2\Token_Access;

class Odnoklassniki extends Provider
{
    public $name = 'odnoklassniki';

    public $uid_key = 'uid';

    public $scope = array('email', 'read_stream');

    protected $method = 'POST';

    protected $key = '';

    public function __construct(array $options = array())
    {
        parent::__construct($options);
        isset($options['key']) and $this->key = $options['key'];
    }

    public function url_authorize()
    {
        return 'https://connect.ok.ru/oauth/authorize';
    }

    public function url_access_token()
    {
        return 'https://api.ok.ru/oauth/token.do';
    }

    public function get_user_info(Token_Access $token)
    {
        $sig = md5(sprintf(
            'application_key=%smethod=users.getCurrentUser%s',
            $this->key,
            md5($token->access_token . $this->client_secret)
        ));

        $url = 'http://api.ok.ru/fb.do?method=users.getCurrentUser&'.http_build_query(array(
                'access_token' => $token->access_token,
                'application_key' => $this->key,
                'sig' => $sig
            ));

        $user = json_decode(file_get_contents($url));

        // Create a response from the request
        return array(
            'uid' => $user->uid,
            'name' => $user->name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'sex' => isset($user->gender) ? $user->gender : "",
            'bdate' => isset($user->birthday) ? $user->birthday : '',
            'city' => isset($user->city) ? $user->city : ''
        );
    }
}
