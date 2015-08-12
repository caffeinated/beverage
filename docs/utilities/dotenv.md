<!---
title: Dotenv
subtitle: Utilities
author: Robin Radic and Shea Lewis
-->

The `Dotenv` class extends the `Dotenv` from `vlucas` and adds a method to retreive the `.env` variables as array.

### Example
```php
$vars = \Caffeinated\Beverage\Dotenv::getEnvFile(base_path());
```
