<?php

namespace Indielab\Slack;

use Curl\Curl;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Slack Message Client.
 * 
 * @author Basil Suter <basil@nadar.io>
 */
class Client extends Component
{
    /**
     * @var string
     */
    public $channel = null;
    
    /**
     * @var string
     */
    public $token = null;
    
    /**
     * @var string
     */
    public $icon = ':scream_cat:';
    
    /**
     * @var string
     */
    public $username = 'Slack BOT';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if ($this->channel === null || $this->token === null) {
            throw new InvalidConfigException("The channel and token property must be defined in your config.");
        }
    }
    
    /**
     * 
     * @param unknown $message
     * @param array $options
     * @return \Indielab\Slack\Client
     */
    public function danger($message, array $options = [])
    {
        $options['color'] = ArrayHelper::remove($options, 'color', 'danger');
    
        return $this->message($message, $options = []);
    }
    
    /**
     * 
     * @param unknown $message
     * @param array $options
     * @return \Indielab\Slack\Client
     */
    public function warning($message, array $options = [])
    {
        $options['color'] = ArrayHelper::remove($options, 'color', 'warning');
         
        return $this->message($message, $options);
    }
    
    /**
     * 
     * @param unknown $message
     * @param array $options
     * @return \Indielab\Slack\Client
     */
    public function success($message, array $options = [])
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
    
    /**
     * 
     * @return boolean
     */
    public function send()
    {
        $data = $this->_attachments;
        $this->_attachments = [];
        
        return $this->parseAndSend($data);
    }

    /**
     * 
     * @param array $attachements
     * @return boolean
     */
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
