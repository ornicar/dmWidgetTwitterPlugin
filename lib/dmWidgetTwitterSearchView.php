<?php

class dmWidgetTwitterSearchView extends dmWidgetPluginView
{
  
  public function configure()
  {
    parent::configure();
    
    $this->addRequiredVar(array('query', 'nb_tweets', 'life_time'));
  }

  protected function filterViewVars(array $vars = array())
  {
    $vars = parent::filterViewVars($vars);

    $vars['tweets'] = $this->searchTweets($vars['query'], $vars['nb_tweets'], $vars['life_time']);
    
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
    
    return $vars['query'].' '.implode(', ', $tweets);
  }

  protected function searchTweets($query, $nb, $lifeTime)
  {
    $cache = $this->getService('cache_manager')->getCache('dm_widget_twitter_search');
    $cacheKey = md5($query.$nb);

    if ($cache->has($cacheKey))
    {
      $tweets = $cache->get($cacheKey);
    }
    else
    {
      $tweets = array();
      $api = new TwitterApiClient();
      $collection = $api->search($query, array('max' => $nb));
      
      foreach($collection as $tweet)
      {
        $tweets[] = array(
          'from_user'   => $tweet->from_user,
          'text'        => $tweet->text,
          'created_at'  => strtotime($tweet->created_at)
        );
      }

      $tweets = $this->context->getEventDispatcher()->filter(
        new sfEvent($this, 'dm.widget_twitter_search.tweets', array('query' => $query)),
        $tweets
      )->getReturnValue();
      
      $cache->set($cacheKey, $tweets, $lifeTime);
    }

    return $tweets;
  }
  
}