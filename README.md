Yii 2 Basic - Experience
============================

CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=test',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
```

### Yii 2

You must install Yii 2 (/vendor dir) before use project.

### Migrations

You must apply migrations before use project.