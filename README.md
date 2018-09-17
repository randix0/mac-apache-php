# Apache/Mysql/PHP config-pack for macOS and install steps for [Brew](https://brew.sh/):

## 0. Install [Brew](https://brew.sh/)
### Tap extra brew repos:
```sh
brew tap homebrew/core
```

## 1. Install git, apache, mysql56 and php-pack:
### 1.1. PHP 5.5:
```sh
brew install httpd24 mysql@5.6 memcached redis php55 --with-thread-safety --with-httpd
```
### 1.2. PHP 5.6:
```sh
brew install httpd24 mysql@5.6 memcached redis php56 --with-thread-safety --with-httpd
```
### 1.3. PHP 7.0:
```sh
brew install httpd24 mysql@5.6 memcached redis php70 --with-thread-safety --with-httpd
```
### 1.4. PHP 7.1:
```sh
brew install httpd24 mysql@5.6 memcached redis php71 --with-thread-safety --with-httpd
```
### 1.5. PHP 7.2:
```sh
brew install httpd24 mysql@5.6 memcached redis php72 --with-thread-safety --with-httpd
```

## 2. Setup MySQL 5.6 server:
```sh
brew link mysql@5.6 --force
mysqld --initialize --user=_mysql
brew services start mysql@5.6
mysql_secure_installation
```

## 3. Clone these configs using the next commands:
```sh
mkdir mac-apache-php
cd mac-apache-php/
git clone https://github.com/randix0/mac-apache-php.git .
```

## 4. Customize configs before deploying:

### 4.1. Set your path in the next files:
- httpd24/extra/httpd-vhosts.conf
- sendmail/sendmail.php

## 5. Deploy configs and load daemons (PHP 5.6 is used for example):
```sh
sudo /usr/sbin/apachectl stop
mkdir -m 777 ~/Sites/_LOGS/ ~/Sites/_MAILS/
touch ~/Sites/_LOGS/access_log.log && touch ~/Sites/_LOGS/error_log.log
mv /usr/local/etc/my.cnf /usr/local/etc/my.cnf.old
cp mysql56/my.cnf /usr/local/etc/my.cnf && brew services restart mysql@5.6
cp xdebug/xdebug-extra.ini /usr/local/etc/php/7.0/conf.d/
chmod +x sendmail/sendmail.php && cp sendmail/sendmail.php /usr/local/bin/
mkdir -p ~/Sites/php.lo/ && touch ~/Sites/php.lo/index.php
cp php70/php.ini /usr/local/etc/php/7.0/
cp php70/xdebug.ini /usr/local/etc/php/7.0/conf.d/
ln -s /usr/local/opt/php\@7.0/bin/php /usr/local/bin/php70
ln -s /usr/local/opt/php\@7.0/bin/phpize /usr/local/bin/phpize70
ln -s /usr/local/opt/php\@7.0/bin/pecl /usr/local/bin/pecl70
pecl70 install xdebug
patch -p0 < httpd24/httpd.conf.patch
cp -r httpd24/extra/ /usr/local/etc/httpd/extra/ && sudo brew services restart httpd
echo "<?php phpinfo();" > ~/Sites/php.lo/index.php
echo "127.0.0.1    php.lo" | sudo tee -a /etc/hosts
curl -I http://php.lo/
```

