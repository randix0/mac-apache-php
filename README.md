# Apache/Mysql/PHP config-pack for macOS and install steps for [Brew](https://brew.sh/):

## 0. Install [Brew](https://brew.sh/)
### Tap extra brew repos:
```sh
brew tap homebrew/core
```

## 1. Install git, apache, mysql56 and php-pack:
### 1.1. PHP 7.2 (works on Apple Silicone):
```sh
brew install httpd24 redis php@7.2
```

### 1.2. PHP 7.3 (bottle for Apple Silicone does not exist so far):
```sh
brew install httpd24 redis php@7.3
```

### 1.3. PHP 7.3 (for Apple Silicone):
```sh
brew install httpd24 redis
brew tap shivammathur/php
brew install shivammathur/php/php@7.3
```

### 1.4. PHP 7.4 (works on Apple Silicone):
```sh
brew install httpd24 redis php@7.4
```

## 2. Setup MySQL server:
### - MySQL 5.6 server:
```sh
brew install mysql@5.6
mysqld --initialize --user=_mysql
brew services start mysql@5.6
mysql_secure_installation
```

### - MySQL 8 server (for Apple Silicone):
```sh
brew install mysql
brew services start mysql
mysql_secure_installation
```

### - MariaDB 10.4 server:
```sh
brew install mariadb@10.4
brew services start mariadb@10.4
mysql-secure-installation
```

## 3. Install Opensearch
```sh
brew tap isaaceindhoven/opensearch-maintenance
brew install opensearch@1
brew install opensearch
```

## 4. Clone these configs using the next commands:
```sh
mkdir mac-apache-php
cd mac-apache-php/
git clone https://github.com/randix0/mac-apache-php.git .
```

## 5. Customize configs before deploying:

### 5.1. Set your path Mysql engine in the next files:
- httpd24/extra/httpd-vhosts.conf
- sendmail/sendmail.php
- bin/web-start
- bin/web-stop
- bin/web-restart

## 6. Deploy configs (PHP 7.4 is used for example):
```sh
sudo /usr/sbin/apachectl stop
mkdir -m 777 ~/Sites/_LOGS/ ~/Sites/_MAILS/
touch ~/Sites/_LOGS/access_log.log && touch ~/Sites/_LOGS/error_log.log
mv /opt/homebrew/etc/my.cnf /opt/homebrew/etc/my.cnf.old
cp mysql56/my.cnf /opt/homebrew/etc/my.cnf && brew services restart mysql
cp xdebug/xdebug-extra.ini /opt/homebrew/etc/php/7.4/conf.d/
chmod +x sendmail/sendmail.php && cp sendmail/sendmail.php /opt/homebrew/bin/
mv /opt/homebrew/etc/php/7.4/php.ini /opt/homebrew/etc/php/7.4/php.ini.old
ln -s /opt/homebrew/opt/php@7.4/bin/php /opt/homebrew/bin/php74
ln -s /opt/homebrew/opt/php@7.4/bin/phpize /opt/homebrew/bin/phpize74
ln -s /opt/homebrew/opt/php@7.4/bin/pecl /opt/homebrew/bin/pecl74
pecl74 install xdebug
cp php74/php.ini /opt/homebrew/etc/php/7.4/
cp php74/xdebug.ini /opt/homebrew/etc/php/7.4/conf.d/
patch -p0 < httpd24/httpd.conf.patch
cp -r httpd24/extra/ /opt/homebrew/etc/httpd/extra/
mkdir -p ~/Sites/php.lo/ && touch ~/Sites/php.lo/index.php
echo "<?php phpinfo();" > ~/Sites/php.lo/index.php
echo "127.0.0.1    php.lo" | sudo tee -a /etc/hosts
```

## 7. Deploy start/stop/restart scripts to /usr/local/bin and load daemons (PHP 7.3 is used for example):
```sh
chmod +rx ./bin/web-*
cp ./bin/web-* /opt/homebrew/bin/
web-restart
```

## 8. Check if web works:
```sh
curl -I http://php.lo/
```

## 9. Install gpatch to fix the issue when composer install stucks on the patch applying step (https://github.com/cweagans/composer-patches/issues/326)
```sh
brew install gpatch
ln -s /opt/homebrew/bin/gpatch /opt/homebrew/bin/patch
```