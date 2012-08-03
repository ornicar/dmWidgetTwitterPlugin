<?php

class dmWidgetTwitterSearchForm extends dmWidgetPluginForm
{
  public function configure()
  {
    $this->widgetSchema['query'] = new sfWidgetFormInputText();
    $this->validatorSchema['query'] = new sfValidatorString(array(
      'required' => true,
      'min_length' => 3
    ));
    
    $this->widgetSchema['nb_tweets'] = new sfWidgetFormInputText();
    $this->validatorSchema['nb_tweets'] = new sfValidatorInteger(array(
      'min' => 0,
      'max' => 200
    ));

    $this->widgetSchema['life_time'] = new sfWidgetFormInputText();
    $this->validatorSchema['life_time'] = new sfValidatorInteger(array(
      'min' => 0
    ));
    $this->widgetSchema->setHelp('life_time', 'Cache life time in seconds');

    if(!$this->getDefault('nb_tweets'))
    {
      $this->setDefault('nb_tweets', 10);
    }

    if(!$this->getDefault('life_time'))
    {
      $this->setDefault('life_time', 3600);
    }
    
    $this->getWidgetSchema()->setHelps(array(
        'query' => 'Get query from: <a href="http://twitter.com/#!/search-advanced" target="_blank">http://twitter.com/#!/search-advanced</a>'          
    ));
    
    parent::configure();
  }
}