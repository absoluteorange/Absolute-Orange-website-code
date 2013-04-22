<?php

class OAuth_Provider_Twitter extends OAuth_Provider {

	public $name = 'twitter';
	
	public $uid_key = 'user_id';

	public function url_request_token()
	{
		return 'https://api.twitter.com/oauth/request_token';
	}

	public function url_authorize()
	{
		return 'https://api.twitter.com/oauth/authenticate';
	}

	public function url_access_token()
	{
		return 'https://api.twitter.com/oauth/access_token';
	}
	
	public function get_user_info(OAuth_Consumer $consumer, OAuth_Token $token)
	{		
		// Create a new GET request with the required parameters
		$request = OAuth_Request::forge('resource', 'GET', 'http://api.twitter.com/1.1/users/lookup.json', array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_token' => $token->access_token,
			'user_id' => $token->uid,
		));
		
		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);

		$user = current(json_decode($request->execute()));
		
		// Create a response from the request
		return array(
			'uid' => $token->uid,
			'nickname' => $user->screen_name,
			'name' => $user->name ? $user->name : $user->screen_name,
			'location' => $user->location,
			'image' => $user->profile_image_url,
			'description' => $user->description,
			'urls' => array(
			  'Website' => $user->url,
			  'Twitter' => 'http://twitter.com/'.$user->screen_name,
			),
		);
	}
	
	public function get_user_tweets(OAuth_Consumer $consumer, OAuth_Token $token)
	{		
		// Create a new GET request with the required parameters
		$request = OAuth_Request::forge('resource', 'GET', 'https://api.twitter.com/1.1/statuses/user_timeline.json?count=5&include_entities=true&include_rts=true&screen_name=absolute_orange', array(
			'oauth_consumer_key' => $consumer->key,
			'oauth_nonce'=> time(),
			'oauth_signature_method'=>'HMAC-SHA1',
			'oauth_timestamp'=> time(),
			'oauth_token'=>$token->access_token,
			'oauth_version'=>'1.0'
		));
		// Sign the request using the consumer and token
		$request->sign($this->signature, $consumer, $token);
		return json_decode($request->execute());
	}

} // End Provider_Twitter
