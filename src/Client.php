<?php

namespace Indielab\Slack;

use yii\base\Component;
use yii\base\InvalidConfigException;

class Client extends Component
{
	public $channel = null;
	
	public $token = null;
	
	public function init()
	{
		parent::init();
		
		if ($this->channel === null || $this->token === null) {
			throw new InvalidConfigException("The channel and token property must be defined in your config.");
		}
	}
	
	public function postMessage($message)
	{
		
	}
}