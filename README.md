# Zarinpal Transaction Library for Laravel
Just another transaction request library for zarinpal

##add provider
Add provider to providers list in "config/app.php":
just add :
```php
'providers' => [
    ...
    Zarinpal\Laravel\ZarinpalServiceProvider::class,
    ...
]
```
and run
'`php artisan vendor:publish --provider="Zarinpal\Laravel\ZarinpalServiceProvider"`'
to add config file to laravel configs directory.

##usage

###request
```php
use Zarinpal\Drivers\Soap;
use Zarinpal\Zarinpal;

$test = new Zarinpal(config('Zarinpal.merchantID'), new SoapDriver());

$answer = $test->request("http://example.com/verify.php", 1000, 'Payment Description');

//it will redirect to zarinpal to do the transaction or fail and just echo the errors.
if(isset($answer['Authority'])) {
    return $test->redirect($answer['Authority']);
}

return 'There Was an error!';
```

###verify
```php
use Zarinpal\Drivers\Soap;
use Zarinpal\Zarinpal;

$test = new Zarinpal(config('Zarinpal.merchantID'),new SoapDriver(), true);

return $test->verify('OK', 4000);
//'Status'(index) going to be 'success', 'error' or 'canceled'
```

##For Developers
just put 'true' as third parameter of new instance of Zarinpal in both request and verify!

```php
$test = new Zarinpal('XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',new SoapDriver(), true);
```
