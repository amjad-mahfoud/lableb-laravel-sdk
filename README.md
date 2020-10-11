# Lableb Laravel SDK
Based on Lableb Php SDK https://solutions.lableb.com/documentation/tutorials/php-sdk/installation 

### Require Package:
use ```composer require amjad/lableb``` to require the package

### Publish Vendor config:
use ```php artisan vendor:publish --tag=lableb``` to publish configuration file

### Set LABLEB_TOKEN: 
create an set ```LABLEB_TOKEN``` in env file to your Lableb token

### Usage:

```php
    use Amjad\Lableb\LablebSDK;
    $sdk = new LablebSDK("project");

    $params = [
        'q' => 'الذكاء',
        'cat' => 'Technology',
        'filter' => [
          'meta_sa' => ['Technology']
        ],
        'limit' => 10
      ];
  
    $response = $sdk->search('collection', $params);
```