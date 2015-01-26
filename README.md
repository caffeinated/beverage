Caffeinated Beverage
====================
Caffeinated Beverage allows the means to easily configure the default paths of your Laravel 5.0 application.

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