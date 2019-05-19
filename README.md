# Zend Translate

## Bilgilendirme

Zend Framework 2 Google Translate v2 REST Api

## Yükleme

```bash
$ composer install
```

module/Application/config/module.config.php.example dosyasını module.config.php olarak değiştirip Google API key girin.

```php
'translate'    => [
	'apiKey'  => 'AIzaSyBabSGLuzycUB-4wqdP8iY2hBOnPPMrb38',
	'baseUrl' => 'https://translation.googleapis.com/language/translate/v2/'
]
```

Son olarak 

```bash
php -S localhost:8080 -t public/ public/index.php
```

Yada

```bash
$ composer run --timeout 0 serve
```