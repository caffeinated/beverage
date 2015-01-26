Caffeinated Beverage
====================
**Notice:** This may turn into a general support package with added functionality (mulling over creating a base repository interface for instance). The idea is that "beverage" is an ambiguous term implying that you can customize your "drink" however you like. A caffeinated beverage shares some common attributes regardless of the type of beverage you want: a cup, espresso, water, etc.

Caffeinated Beverage allows the means to easily configure the default paths of your Laravel 5.0 application. This means you can take the default Laravel framework structure:

```
laravel5/
|-- app/
|-- bootstrap/
|-- config/
|-- database/
|-- public/
|-- resources/
|-- storage/
|-- tests/
```

And configure it into something like:

```
laravel5/
|-- acme/
|-- bootstrap/
|-- system/
	|-- config/
	|-- database/
	|-- resources/
	|-- storage/
	|-- tests/
```

Installation
------------
Begin by installing the package through Composer. The best way to do this is through your terminal via Composer itself: 

```
composer require caffeinated/beverage
```

Usage
-----
First off, you'll need to use the Caffeinated Beverage Application instance in place of Laravel's Illuminate Foundation Application. Simply replace it within the `bootstrap/app.php` file like so:

```php
$app = new Caffeinated\Beverage\Application(
	realpath(__DIR__.'/../')
);
```

### Publish and Edit Config File
Simply copy the provided config file to your config path within a `caffeinated` subdirectory:

```
config/caffeinated/paths.php
```

From here, feel free to configure any paths that you'd like!

### Setting a Custom Config Path
If you'd like to also move your config directory, provide the path as the second parameter when creating a new instance of the Caffeinated Beverage Application:

```php
$app = new Caffeinated\Beverage\Application(
	realpath(__DIR__.'/../'),
	realpath(__DIR__.'/../system/config/')   // Your custom config path
);
```