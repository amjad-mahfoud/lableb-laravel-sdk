# Lableb Laravel SDK
Based on Lableb Php SDK https://solutions.lableb.com/documentation/tutorials/php-sdk/installation 

## Publish Vendor config
use php artisan vendor:publish --tag=lableb to publish configuration file

## Set LABLEB_TOKEN 
create an set LABLEB_TOKENl in env file to your Lableb token

## Usage

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