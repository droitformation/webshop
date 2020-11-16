# Laravel Email Database Log

Database logger for all outgoing emails

# Installation
## Step 1: Composer

Database Log can be installed via [composer](http://getcomposer.org) by running this line in terminal:

```bash
composer require droitformation/databaselog
```

## Step 2: Configuration

Add the following to your config/app.php in the providers array:

```php
'providers' => [
    // ...
    Droitformation\DatabaseLog\DatabaseLogServiceProvider::class,
],
```

## Step 3: Migration
Now, run this in terminal:

```bash
php artisan migrate
```

# Usage
After installation, any email sent by your website will be logged to `email_log` table in the site's database.
