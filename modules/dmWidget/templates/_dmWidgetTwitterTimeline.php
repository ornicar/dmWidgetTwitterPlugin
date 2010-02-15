<?php use_helper('Text'); use_helper('Date');

/*
 * A $tweet is an array containing:
 * - text:        text of the tweet
 * - created_at:  when the tweet was posted, timestamp
 */

echo _open('ul');

foreach($tweets as $tweet)
{
  echo _tag('li',

    // render tweet text
    _tag('p.tweet_text', auto_link_text($tweet['text'])).

    // render tweet date
    _tag('p.tweet_date', format_date($tweet['created_at'], 'D'))
  
  );
}

echo _close('ul');