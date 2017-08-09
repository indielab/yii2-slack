# Yii 2 Slack Component

Post a message to a Slack Channel. Pedefined functions to enable coloring.

## Setup

Add the `indielab/yii2-slack` package to your composer.json

```sh
composer require indielab/yii2-slack
```

Add the component to your config in the components section:

```php
'components' => [
    // ...
    'slack' => [
        'class' => 'Indielab\Slack\Client',
        'token' => 'xoxp-1234567891-1234567891-1234567891',
        'channel' => 'indielab',
        'username' => 'Slack Bot',
    ]
]
```

Using in your Application:

```php
Yii::$app->slack->message('Just a Message')->send();
```

Send colorized Messages:

```php
Yii::$app->slack->danger('Very dangerous!')->send();
```

+ danger
+ warning
+ success
