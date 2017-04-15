# Laravel SMS Sender for Azerbaijani SMS providers

SMS sender currently supports 2 providers:
  - Mobis (mobis.az)
  - MSM (msm.az)

### Installation

Run Composer command:

```composer
composer require orkhanahmadov/laravel-az-sms-sender
```

Add this line to your provider list (`app/config.app`):

```php
Orkhanahmadov\LaravelAzSmsSender\LaravelAzSmsSenderServiceProvider::class,
```

Add this line to your aliases list (`app/config.app`):

```php
'SmsSender' => Orkhanahmadov\LaravelAzSmsSender\Facade\SmsSender::class,
```

Lastly you need to add following lines to your `.env` file and fill their values:
```
SMS_API_PROVIDER=
SMS_API_USER=
SMS_API_PASSWORD=
SMS_API_SENDER_NAME=
SMS_API_USE_DB=
```
  - SMS_API_PROVIDER - SMS provider name, `msm` or `mobis`
  - SMS_API_USER - Username given by provider
  - SMS_API_PASSWORD - Password given by provider
  - SMS_API_SENDER_NAME - Sender name given by provider
  - SMS_API_USE_DB - Defines if all sent sms messages should be saved in DB table or not, `true` or `false`

If `SMS_API_USE_DB` is set to `true` the you need to migrate required tables to your database with artisan command:
```
php artisan migrate
```

### Usage
To send SMS message anywhere in your app you can call:
```php
SmsSender::send($number, $message);
```
  - $number - recipient's phone number
  - $message - SMS message

To send bulk message to multiple numbers you need to give array of numbers to $number argument, like:
```php
SmsSender::send([994502223344, 994505558866, ..., ...], "This is bulk message for all numbers");
```

To send individual messages to different numbers you need to give array of numbers and messages to $number where key is number and value is message. Method will ignore $message argument so it can be skipped.

```php
SmsSender::send([
    994552223344 => "This is message for 994552223344",
    994507775533 => "This is message for 994507775533",
    ...
]);
```
### Config
You can dump config files to your root `config` directory with artisan command:
```
php artisan vendor:publish
```
This command will create `az-sms-sender` folder inside your root `config` directory and dump all config files.

License
----

MIT

### Todo

 - Report method to check sent SMS statuses (automatic status check maybe?)
 - Balance method to check current balance
 - Better exception handling
