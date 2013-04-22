<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Twitter {
	
	public function extractTweets($tweets) {
    	$myTweets = array();
    	foreach ($tweets as $tweet):
			$myTweets[] = array(
			'text' => $tweet->text,
			'date' => $tweet->created_at,
			'entities' => $tweet->entities
			);
		endforeach;
		return $myTweets;
    }
    
    public function formatTweets($myTweets){
    	$formattedTweets['items'] = array();
    	foreach ($myTweets as $tweet):
	    	//add href links to tweets
    		if (!empty($tweet['entities']->hashtags)):
    			foreach ($tweet['entities']->hashtags as $tag):
    				$tweetTag = $tag->text;
    				$tweet['text'] = str_replace('#'.$tweetTag, '<a href="http://twitter.com/?searchq=#'.$tweetTag.'&src=hash" title="#'.$tweetTag.'">#'.$tweetTag.'</a>', $tweet['text']);
    			endforeach;
    		endif;
    		if (!empty($tweet['entities']->user_mentions)):
    			foreach ($tweet['entities']->user_mentions as $user):
    				$tweetUser = $user->screen_name;
    				$tweet['text'] = str_replace('@'.$tweetUser, '<a href="http://twitter.com/'.$tweetUser.'" title="@'.$tweetUser.'">@'.$tweetUser.'</a>', $tweet['text']);
    			endforeach;
    		endif;
    		if (!empty($tweet['entities']->urls)):
    			foreach ($tweet['entities']->urls as $url):
    				$tweetUrl = $url->url;
    				$tweet['text'] = str_replace($tweetUrl, '<a href="'.$tweetUrl.'" title="'.$tweetUrl.'">'.$tweetUrl.'</a>', $tweet['text']);
    			endforeach;
    		endif;
	    	//format the date
	    	$date = date('jS F, o', strtotime($tweet['date']));
	    	//format the tweet
	    	$tweet = "<a href='http://twitter.com/absolute_orange' title='@absolute_orange'>Absolute Orange</a> tweeted on ".$date.": ".$tweet['text'];
	    	$formattedTweets['items'][] = array(
	    		'tweet' => $tweet
	    	);
	    endforeach;
	    return $formattedTweets;
    }
}