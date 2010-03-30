<?php

class dmWidgetTwitterTimelineView extends dmWidgetPluginView
{
  
  public function configure()
  {
    parent::configure();
    
    $this->addRequiredVar(array('user', 'nb_tweets', 'life_time'));
  }

  protected function filterViewVars(array $vars = array())
  {
    $vars = parent::filterViewVars($vars);

    $vars['tweets'] = $this->getUserTweets($vars['user'], $vars['nb_tweets'], $vars['life_time']);
    
    return $vars;
  }
  
  protected function doRenderForIndex()
  {
    $tweets = array();

    $viewVars = $this->getViewVars();
    
    foreach($viewVars['tweets'] as $tweet)
    {
      $tweets[] = $tweet['text'];
    }
    
    return $viewVars['user'].' '.implode(', ', $tweets);
  }

  protected function getUserTweets($user, $nb, $lifeTime)
  {
    $cache = $this->getService('cache_manager')->getCache('dm_widget_twitter_timeline');
    $cacheKey = md5($user.$nb);

    if ($cache->has($cacheKey))
    {
      $tweets = $cache->get($cacheKey);
    }
    else
    {
      $tweets = array();
      $api = new TwitterApiClient();
      $collection = $api->getUserTimeline($user, null, null, $nb);
      
      foreach($collection as $tweet)
      {
        $tweets[] = array(
          'text'        => $tweet->text,
          'created_at'  => strtotime($tweet->created_at)
        );
      }

      $tweets = $this->context->getEventDispatcher()->filter(
        new sfEvent($this, 'dm.widget_twitter_timeline.tweets', array('user' => $user)),
        $tweets
      )->getReturnValue();
      
      $cache->set($cacheKey, $tweets, $lifeTime);
    }

    return $tweets;
  }
  
}