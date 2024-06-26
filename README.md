# Laravel hCaptcha

Integrate hCaptcha into your Laravel application.

## Installation

Install it with [Composer](https://getcomposer.org):

```sh
composer require esyede/hcaptcha-laravel
```

Publish the configuration file:

```sh
php artisan vendor:publish --provider="Esyede\Laravel\HCaptcha\HCaptchaServiceProvider"
```

## Setup

Register and add your website to the [hCaptcha dashboard](https://dashboard.hcaptcha.com) to obtain site key and secret key.

Then add that to your `.env` file:

```env
HCAPTCHA_SITEKEY=XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
HCAPTCHA_SECRET=0x0000000000000000000000000000000000000000
```

## Usage

### Display

To display the widget:

```blade
{!! HCaptcha::display() !!}
```

You can also set [custom attributes](https://docs.hcaptcha.com/configuration#hcaptcha-container-configuration) on the widget:

```blade
{!! HCaptcha::display(['data-theme' => 'dark']) !!}
```

Or customize the CSS class:

```blade
{!! HCaptcha::display([
    'class' => $errors->has('email') ? 'is-invalid' : '',
]) !!}
```

### Script

To load the hCaptcha javascript resource:

```blade
{!! HCaptcha::script() !!}
```

You can also set the [query parameters](https://docs.hcaptcha.com/configuration):

```blade
{!! HCaptcha::script($locale, $render, $onload, $recaptchacompat) !!}
```

### Validation

To validate the hCaptcha response, use the `hcaptcha` rule:

```php
$request->validate([
    'h-captcha-response' => ['hcaptcha'],
]);
```

*You can leave out the `required` rule, because it is already checked internally.*

#### Custom validation message

Add the following values to your `validation.php` in the language folder:

```php
'custom' => [
    'h-captcha-response' => [
        'hcaptcha' => 'Please verify that you are human.',
    ]
],
```

### Invisible Captcha

You can also use an [invisible captcha](https://docs.hcaptcha.com/invisible) where the user will only be presented with a hCaptcha challenge if that user meets challenge criteria.

The easiest way is to bind a button to hCaptcha:

```blade
{!! HCaptcha::displayButton() !!}
```

This will generate a button with an `h-captcha` class and the site key. But you still need a callback for the button:

```html
<script>
    function onSubmit(token) {
        document.getElementById('my-form').submit();
    }
</script>
```

By default, `onSubmit` is specified as callback, but you can easily change this (along with the text of the button):

```blade
{!! HCaptcha::displayButton('Submit', ['data-callback' => 'myCustomCallback']) !!}
```

You can also set other [custom attributes](https://docs.hcaptcha.com/configuration#hcaptcha-container-configuration), including `class`.

## Usage without Laravel

This package can also be used without Laravel. Here is an example of how it works:

```php
<?php

require_once 'vendor/autoload.php';

use Esyede\Laravel\HCaptcha\HCaptcha;

$sitekey = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';
$secret = '0x0000000000000000000000000000000000000000';
$guzzleOptions = [
    // 'verify' => false,
];
$hCaptcha = new HCaptcha($sitekey, $secret, $guzzleOptions);

if (!empty($_POST)) {
    var_dump($hCaptcha->validate($_POST['h-captcha-response']));
    exit;
}

?>

<form method="POST">
    <?php echo $hCaptcha->display() ?>
    <button type="submit">Submit</button>
</form>

<?php echo $hCaptcha->script() ?>
```

## License

This package is released under the [MIT License](http://www.opensource.org/licenses/mit-license.php).
