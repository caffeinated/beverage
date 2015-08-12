<!---
title: Service Provider
author: Robin Radic and Shea Lewis
--->

The service provider can be extended and will provide a high level of abstraction.
All properties and methods have docblock documentation explaining how and what for its used.

### Basic Example
```php
class MyServiceProvider extends ServiceProvider {
    
    // Mandatory property to define the path to the directory. 
    // All other paths are relative to this and by default expects to be in the src directory
    protected $dir = __DIR__;

    protected $configFiles = [ 'my.package' ]
    
    public function boot(){
        // When overriding the boot method, make sure to call the super method.
        // returns the Application instance
        $app = parent::boot(); 
    }
        
    public function register(){
        // When overriding the register method, make sure to call the super method.
        // returns the Application instance
        $app = parent::register(); 
    }
}
```
