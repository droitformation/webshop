# Configurations

Just for internal use

Configurations through database for Laravel

~~~
"designPond/config-registry": "0.1"
~~~

```php
'providers' => array(
    'DesignPond\Registry\RegistryServiceProvider',
)
```

```php
'aliases' => array(
    'Registry' => DesignPond\Registry\Facades\Registry::class,
)
```

## Change Log
#### v0.1.0

- Inital