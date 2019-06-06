# Shopgate Connect Integration SDK

[![Build Status](https://travis-ci.org/shopgate/connect-integration-sdk-php.svg?branch=master)](https://travis-ci.org/shopgate/connect-integration-sdk-php)
[![Coverage Status](https://coveralls.io/repos/github/shopgate/connect-integration-sdk-php/badge.svg?branch=master)](https://coveralls.io/github/shopgate/connect-integration-sdk-php?branch=master)
[![GitHub license](http://dmlc.github.io/img/apache2.svg)](LICENSE.md)
[![Semver](http://img.shields.io/SemVer/2.0.0.png?color=blue)](http://semver.org/spec/v2.0.0.html)

The Shopgate Connect Integration SDK is a compilation of classes to manage the communication between your shop system and Shopgate Connect.

Create a developer account at https://developer.shopgate.com

## Requirements
* PHP 5.6 and above

## Getting Started
#### Via Composer
```composer require shopgate/connect-integration-sdk-php```


#### Usage
Example for calling our service in order to create, update or delete a category:
```php
<?php
use Shopgate\ConnectSdk\ShopgateSdk;
use Shopgate\ConnectSdk\DTO\Catalog\Category as CategoryDto;

$config = [
    'merchantCode'  => 'EE1',
    'clientId'      => 'xxx',
    'clientSecret'  => 'xxx'
];

$client = new ShopgateSdk($config);

// create new category
$categoryPayload = new CategoryDto();
$categoryPayload->setCode('pants')->setName('Denim Pants')->setSequenceId(1);
$client->catalog->addCategories([$categoryPayload]);
// update category with constructor input example
$updateDto = new CategoryDto(['name' => 'Skirts']);
$client->catalog->updateCategory('pants', $updateDto);
// delete category
$client->catalog->deleteCategory('pants');
// get categories
$categories = $client->catalog->getCategories(['limit' => 5]);

// update category sync
$updateDto = new CategoryDto(['name' => 'Skirts']);
$client->catalog->updateCategory('4', $updateDto, ['requestType' => 'direct']);
```

Example for calling our service in order to create, update or delete a simple product:
```php
<?php
use Shopgate\ConnectSdk\ShopgateSdk;
use Shopgate\ConnectSdk\DTO\Catalog\Product as ProductDto;
use Shopgate\ConnectSdk\DTO\Catalog\Product\Price as PriceDto;

$config = [
    'merchantCode'  => 'EE1',
    'clientId'      => 'xxx',
    'clientSecret'  => 'xxx'
];

$client = new ShopgateSdk($config);
// create new price
$price = new PriceDto();
$price->setPrice(90)->setSalePrice(84.99)->setCurrencyCode(PriceDto::CURRENCY_CODE_EUR);
// create new product
$productPayload = new ProductDto();
$productPayload->setCode('42')
               ->setCatalogCode('my_catalog')
               ->setName('Blue Jeans regular')
               ->setStatus(ProductDto::STATUS_ACTIVE)
               ->setModelType(ProductDto::MODEL_TYPE_STANDARD)
               ->setIsInventoryManaged(true)
               ->setPrice($price);
$client->catalog->addProducts([$productPayload]);
// update product with constructor input example
$updateDto = new ProductDto(['name' => 'Blue Jeans fancy']);
$client->catalog->updateProduct('42', $updateDto);
// delete product
$client->catalog->deleteProduct('42');

// update product sync
$updateDto = new ProductDto(['status' => ProductDto::STATUS_INACTIVE]);
$client->catalog->updateProduct('42', $updateDto, ['requestType' => 'direct']);
```

#### Config

* __clientId__ (string) - oAuth2 client ID
* __clientSecret__ (string) - oAuth2 client secret
* __merchantCode__ (string) - the following are template system related, the default _base_uri_ provided takes in variables in __{merchantCode}__ format. These params just replace these variables. This way you do not need to rewrite the base_uri, but just provide the correct variables to replace the template components.
* __base_uri__ (string, default: _https://{service}.shopgate{env}.services/v{ver}/merchants/{merchantCode}/_) - if rewriting, make sure to add a forward slash at end as the calls will append paths
* __ver__ (integer, default: 1) - template variable, can be substituted on a per call level in the meta array parameters
* __env__ (string, default: '') - template variable, can also be 'dev' or 'pg'

## Changelog

See [CHANGELOG.md](CHANGELOG.md) file for more information.

## Contributing

See [CONTRIBUTING.md](docs/CONTRIBUTING.md) file for more information.

## About Shopgate

Shopgate is the leading mobile commerce platform.

## License

The Shopgate Connect Integration SDK is available under the Apache License, Version 2.0.

See the [LICENSE.md](LICENSE.md) file for more information.

[Guzzle]:http://docs.guzzlephp.org/en/stable/request-options.html
