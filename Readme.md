# Yii2 Subscriber

Yii2 subscriber module for sending sms and email campaigns using queues.


## Prerequisites

This extension relies on your email and sms components to be configured properly, as it will
use default configured values for sending. There should be no downside as these default values
are supported by Yii2 out of the box, and are overridden when manually set via code.

You also need an SMS extension/component that works just like Yii's mailer (`Yii::$app->sms->compose()`),
or SMS sending will not work!

My [Yii2 SMS Twilio](https://github.com/wadeshuler/yii2-sms-twilio) extension is a great example. If you are
not using Twilio, you can copy it and roll out your own. Please refer to it's docs for more info.

> *Note:* Any SMS extension that works just like the built-in Yii2 mailer (`Yii::$app->sms->compose()`)
should work, if there are any other than mine...

**Set Mailer Defaults**

Advanced: `common/config/main-local.php`

Basic: `config/web.php` and `config/console.php`

```
'components' => [
    // ....
    'mailer' => [
        // ....
        'messageConfig' => [
            'from' => ['admin@example.com' => 'Company Name'],
            'replyTo' => 'noreply@example.com',
        ],
    ],
],
```

**Set SMS Defaults**

Advanced: `common/config/main-local.php`

Basic: `config/web.php` and `config/console.php`

```
'components' => [
    // ....
    'sms' => [
        // ....
        'messageConfig' => [
            'from' => '+15554441234',
        ],
    ],
],
```

If you notice, both email and sms configs above utilize the `messageConfig` array to set default values.


## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/)

Either run

    composer require --prefer-dist wadeshuler/yii2-subscriber

or add

    "wadeshuler/yii2-subscriber": "~1.0"

to the require section of your application's `composer.json` file.


Add to `backend/config/main.php` (advanced), `config/web.php` (basic):

```
'modules' => [
    // ....
    'ckeditor' => [
        'class' => 'wadeshuler\ckeditor\Module',
    ],
    'subscriber' => [
        'class' => 'wadeshuler\subscriber\Module',
        'emailTemplate' => '@common/mail/templates/new.tpl',    // optional: must create yourself
        'domainUrl' => 'https://example.com',                   // optional: good for setting root url for links/images
    ],
],
```

Add to `console/config/main.php` (advanced), `config/console.php` (basic):

```
'modules' => [
    // ....
    'subscriber' => [
        'class' => 'wadeshuler\subscriber\Module',
        'emailBatchSize' => 250,
        'smsBatchSize' => 250,
        'emailTemplate' => '@common/mail/templates/new.tpl',    // optional: must create yourself
        'domainUrl' => 'https://example.com',                   // optional: good for setting root url for links/images
    ],
],
```


### Migration

```
./yii migrate/up --migrationPath=@wadeshuler/subscriber/migrations/
```


### Cron

You can test the cron processing, or manually run it, via:

    ./yii subscriber/cron/run

Create a new cron job that runs as often as you want to process the queue. I recommend every 5 minutes.

    /path/to/yii subscriber/cron/run


### Replacements

The following tags will be replaced with available info:

    -domainUrl-
    -name-
    -userId-
    -email-

There also is a `-pretext-` tag for email templates only. This is so you can set the first/invisible pretext line
of your HTML email for control of what shows first in the user's email client. *Note:* Your email template will have to be
properly created to work properly. Read here for more info: https://litmus.com/blog/the-ultimate-guide-to-preview-text-support

To use `-domainUrl-`, it must be configured in your app's config (as shown above). It should be the root path to your site, used for all of your links. This is useful for creating your unsubscribe link, inserting images, etc. It is optional.


### Routes

Here are the main routes which you would link to via your sidebar:

 - `subscriber/manage/index`
 - `subscriber/sms-campaign/index`
 - `subscriber/email-campaign/index`
 - `subscriber/sms-queue/index`
 - `subscriber/email-queue/index`

From there, you can find everything else if you need to deep link (ie: link to `/subscriber/manage/create`).

## Donate

Please consider donating if you find my code useful.

[![PayPal Donate](https://i.ibb.co/YcM55mt/paypaldonate.png "Donate")](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=BEAUQFRMDPHT8&source=url)
