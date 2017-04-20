<?php

namespace Indielab\Slack;

use yii\base\Component;
use yii\base\InvalidConfigException;
use Curl\Curl;
use yii\helpers\ArrayHelper;

/**
 * 
 * @author Basil Suter <basil@nadar.io>
 */
class Client extends Component
{
	public $channel = null;
	
	public $token = null;
	
	public $icon = ':scream_cat:';
	
	public $username = 'Slack BOT';
	
	public function init()
	{
		parent::init();
		
		if ($this->channel === null || $this->token === null) {
			throw new InvalidConfigException("The channel and token property must be defined in your config.");
		}
	}
	
	public function danger($message, array $options)
	{
	    $options['color'] = ArrayHelper::remove($options, 'color', 'danger');
	
	    return $this->message($message, $options);
	}
	
	public function warning($message, array $options)
	{
	    $options['color'] = ArrayHelper::remove($options, 'color', 'warning');
	     
	    return $this->message($message, $options);
	}
	
	public function success($message, array $options)
	{
	    $options['color'] = ArrayHelper::remove($options, 'color', 'good');
	    
	    return $this->message($message, $options);
	}
	
	private $_attachments = [];
	
	/**
	 * Prepare message Payload.
	 * 
	 * See options: https://api.slack.com/docs/message-attachments
	 * 
	 * @param string $message
	 * @param array $options
	 * 
	 * + fallback:
	 * + color: A hex code for the message `#36a64f`
	 * + pretext: Optional text that appears above the attachment block
	 * + title
	 * + title_link
	 * + fields: An array with `title`, `value` and `short`
	 * 
	 * @return \Indielab\Slack\Client
	 */
	public function message($message, array $options = [])
	{
	    $options['text'] = $message;
	    
	    $this->_attachments[] = $options;
	    
	    return $this;
	}
	
	public function send()
	{
	    $data = $this->_attachments;
	    $this->_attachments = [];
	    
	    return $this->parseAndSend($data);
	}

	private function parseAndSend(array $attachements)
	{
	    $curl = new Curl();
	    $curl->post('https://slack.com/api/chat.postMessage', [
	        'token' => $this->token,
	        'channel' => $this->channel,
	        'username' => $this->username,
	        'attachments' => json_encode($attachements),
	        'icon_emoji' => $this->icon
	    ]);
	    
	    return $curl->isSuccess();
	}
}