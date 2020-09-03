# PHP-Mysql-database-class
This lightweight database class is written with PHP and uses the PDO extension, it uses prepared statements to properly secure your queries, no need to worry about SQL injection attacks.

#installation
To use this class, first import mysqliDb.php file into your file
```php
require_once ('MysqliDb.php');
```

SELECT Query
============

```php
chp::data(TABLE_NAME_WITHOUT_PREFIX, WHERE_IN_ARRAY, ORDER_BY_ID_BOOLEAN, SYNC_ASC_DESC, EXTRA_SQL);
```

Example Code:

```php
chp::data(
    'users',
    array(
        'email' => 'info@codehelppro.com'
    ),
    false,
    'ASC'
);
```

Insert Query
============

```php
chp::insert(TABLE_NAME_WITHOUT_PREFIX, DATA_IN_ARRAY);
```

Example Code:

```php
chp::insert(
    'users',
    array(
        'name' => 'Suresh Chand',
        'email' => 'info@codehelppro.com'
     )
);
```

Update Query
===========

```php
chp::update(TABLE_NAME_WITHOUT_PREFIX, DATA_IN_ARRAY, WHERE_IN_ARRAY);
```

Example Code:

```php
chp::update(
    'users',
    array(
        'name' => 'Suresh Chand',
        'email' => 'info@codehelppro.com'
    ),
    array(
        'auth' => 1
    )
);
```

Delete Query
============

```php
chp::delete(TABLE_NAME_WITHOUT_PREFIX, WHERE_IN_ARRAY);
```

Example Code:

```php
chp::insert(
    'users',
    array(
        'auth' => 1
     )
);
```

Last ID of Table
============

```php
chp::lastID(TABLE_NAME_WITHOUT_PREFIX);
```

Example Code:

```php
chp::lastID('users');
```

Total number of data
============

```php
chp::num(TABLE_NAME_WITHOUT_PREFIX, WHERE_IN_ARRAY);
```

Example Code:

```php
chp::num(
    'users',
    array(
        'email' => 'info@codehelppro.com'
    )
);
```
