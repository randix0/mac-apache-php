# Apache/Mysql/PHP config-pack for macOS and install steps for [Brew](https://brew.sh/):

## 0. Install [Brew](https://brew.sh/)
### Tap extra brew repos:
```sh
brew tap homebrew/core
```

## 1. Install git, apache, mysql56 and php-pack:
### 1.1. PHP 7.2:
```sh
brew install httpd24 memcached redis php@7.2
```

### 1.2. PHP 7.3:
```sh
brew install httpd24 memcached redis php@7.3
```

### 1.3. PHP 7.4:
```sh
brew install httpd24 memcached redis php@7.4
```

## 2. Setup MySQL server:
### - MySQL 5.6 server:
```sh
brew install mysql@5.6
mysqld --initialize --user=_mysql
brew services start mysql@5.6
mysql_secure_installation
```
### - MariaDB 10.4 server:
```sh
brew install mariadb@10.4
brew services start mariadb@10.4
mysql-secure-installation
```

## 3. Clone these configs using the next commands:
```sh
mkdir mac-apache-php
cd mac-apache-php/
git clone https://github.com/randix0/mac-apache-php.git .
```

## 4. Customize configs before deploying:

### 4.1. Set your path Mysql engine in the next files:
- httpd24/extra/httpd-vhosts.conf
- sendmail/sendmail.php
- bin/web-start
- bin/web-stop
- bin/web-restart

## 5. Deploy configs (PHP 7.3 is used for example):
```sh
sudo /usr/sbin/apachectl stop
mkdir -m 777 ~/Sites/_LOGS/ ~/Sites/_MAILS/
touch ~/Sites/_LOGS/access_log.log && touch ~/Sites/_LOGS/error_log.log
mv /usr/local/etc/my.cnf /usr/local/etc/my.cnf.old
cp mysql56/my.cnf /usr/local/etc/my.cnf && brew services restart mariadb@10.4
cp xdebug/xdebug-extra.ini /usr/local/etc/php/7.3/conf.d/
chmod +x sendmail/sendmail.php && cp sendmail/sendmail.php /usr/local/bin/
mv /usr/local/etc/php/7.3/php.ini /usr/local/etc/php/7.3/php.ini.old
ln -s /usr/local/opt/php\@7.3/bin/php /usr/local/bin/php73
ln -s /usr/local/opt/php\@7.3/bin/phpize /usr/local/bin/phpize73
ln -s /usr/local/opt/php\@7.3/bin/pecl /usr/local/bin/pecl73
pecl73 install xdebug
cp php73/php.ini /usr/local/etc/php/7.3/
cp php73/xdebug.ini /usr/local/etc/php/7.3/conf.d/
patch -p0 < httpd24/httpd.conf.patch
cp -r httpd24/extra/ /usr/local/etc/httpd/extra/
mkdir -p ~/Sites/php.lo/ && touch ~/Sites/php.lo/index.php
echo "<?php phpinfo();" > ~/Sites/php.lo/index.php
echo "127.0.0.1    php.lo" | sudo tee -a /etc/hosts
```

## 6. Deploy start/stop/restart scripts to /usr/local/bin and load daemons (PHP 7.3 is used for example):
```sh
chmod +rx ./bin/web-*
cp ./bin/web-* /usr/local/bin/
web-start
```

## 7. Check if web works:
```sh
curl -I http://php.lo/
```
