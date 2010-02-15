<?php use_helper('Text');

/*
 * A $tweet is an array containing:
 * - from_user:   username of the one who posted the tweet
 * - text:        text of the tweet
 * - created_at:  when the tweet was posted, timestamp
 */

echo _open('ul');

foreach($tweets as $tweet)
{
  echo _tag('li',

    // link to the user page on twitter
    _link('http://twitter.com/'.$tweet['from_user'])
    ->text($tweet['from_user'])
    ->set('.tweet_from_user').

    // render tweet text
    _tag('p.tweet_text', auto_link_text($tweet['text']))
  
  );
}

echo _close('ul');