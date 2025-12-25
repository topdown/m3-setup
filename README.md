# Localhost Setup - Macbook Pro M3


 ## ⚠️ CRITICAL WARNING ⚠️

>**You MUST ensure HomeBrew is NOT installed under Rosetta mode!**
>
>Failure to do so will cause critical issues with your development environment and can break your entire setup.

---

## Base info
```bash
# Brew paths
/opt/homebrew/bin/httpd
/opt/homebrew/bin/php

ps aux | grep mongod

/usr/local/mongodb/bin/mongod --config /usr/local/etc/mongod.conf

systemLog:
  destination: file
  path: /usr/local/var/log/mongodb/mongo.log
  logAppend: true
storage:
  dbPath: /usr/local/var/mongodb

```

## Terminal
```bash
code /Users/jeffrey/.zshrc
```


## Apache


```bash
brew services stop httpd

brew services start httpd

brew services restart httpd

code /opt/homebrew/etc/httpd/httpd.conf

# Test config
/opt/homebrew/bin/httpd -t 
```


## PHP
```bash

brew services stop php

brew services start php

brew services restart php

# Find your php.ini location
php --ini

code /opt/homebrew/etc/php/8.5/php.ini

```

### PHP Modules
```bash
pecl install mongodb

# Find your php.ini location
php --ini

# Edit config and add:
extension=mongodb.so

pecl install xdebug

# Edit config and add:

extension=xdebug.so
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_host=127.0.0.1
xdebug.client_port=9003

# Test
php -m | grep -i mongo
php -m | grep -i xdebug

```

### Test Mongo

```php
<?php
try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $databases = $client->listDatabases();
    echo "Connected! Databases: ";
    print_r($databases);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```


## MongoDB

Currently not brew installed. 

```bash
## Start
/usr/local/mongodb/bin/mongod --config /usr/local/etc/mongod.conf

# Stop (from another terminal)
/usr/local/mongodb/bin/mongosh
# Then in mongosh:
db.adminCommand({ shutdown: 1 })

# Or force kill it
pkill mongod
```

### Create a simpler alias - add to your ~/.zshrc:

```bash
alias mongo-start="/usr/local/mongodb/bin/mongod --config /usr/local/etc/mongod.conf &"
alias mongo-stop="pkill mongod"
alias mongo-shell="/usr/local/mongodb/bin/mongosh"

```


## MySQL

```bash

brew install mysql
brew services start mysql

# Verify it's running
mysql --version
mysql -u root

```

## Redis

```bash

brew install redis
brew services start redis

# Verify it's running
redis-cli ping
# Should return: PONG

```

### Install PHP extensions for MySQL and Redis

```bash

# Both are already built into most PHP installations though. Check:
# For MySQLi (modern replacement)
#pecl install mysqli
# Or for PDO MySQL
#pecl install pdo_mysql
# Check installed
php -m | grep -i mysql
php -m | grep -i pdo

# Redis extension
pecl install redis

```

__Check / Edit INI config__

```bash
extension=redis.so
```

__Restart Apache__

```bash
brew services restart httpd
```


__Verify PHP can connect:__

*Install Composer packages*

```bash
# In your project directory
composer require mongodb/mongodb
composer require predis/predis
```

```php
<?php

require 'vendor/autoload.php';

echo "=== Database Connections ===<br /><br />";

// MySQL (MySQLi)
try {
    $mysqli = new mysqli("localhost", "root", "");
    echo "✓ MySQL Connected<br />";
    $mysqli->close();
} catch (Exception $e) {
    echo "✗ MySQL Error: " . $e->getMessage() . "<br />";
}

// MongoDB
try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $client->listDatabases();
    echo "✓ MongoDB Connected<br />";
} catch (Exception $e) {
    echo "✗ MongoDB Error: " . $e->getMessage() . "<br />";
}

// Redis (using Predis)
try {
    $redis = new Predis\Client('tcp://localhost:6379');
    $redis->ping();
    echo "✓ Redis Connected<br />";
} catch (Exception $e) {
    echo "✗ Redis Error: " . $e->getMessage() . "<br />";
}


```