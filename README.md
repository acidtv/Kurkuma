Kurkuma
=======

RSS Reader

Installation
------------

Clone the source from github.

```sh
$ git clone git://github.com/acidtv/Kurkuma.git
$ cd Kurkuma
```

Install dependencies with composer (http://getcomposer.org/).

```sh
$ composer install
```

Create database.

```sh
$ echo create database kurkuma\; | mysql -u user -p
$ mysql -u user -p kurkuma < sql/kurkuma.sql
```

Set permissions for log and cache directories.

```sh
$ chown www-data:www-data application/{logs,cache}
```

Copy sample config files. After copying them, adjust the vars to fit your needs.

```sh
$ cd application/config
$ for file in `ls`; do cat $file > `echo $file | sed 's/sample\.//g'`; done
```

Updating feeds
--------------

To update your feeds run this:

```sh
$ php index.php --task=feeds:update
```

For a single feed use:

```sh
$ php index.php --task=feeds:update --feed=<feed_id>
```

The updater uses 5 processes by default, you can change that by doing:

```sh
$ php index.php --task=feeds:update --processes=15
```

Importing subscriptions
-----------------------

There's no user interface for importing Google Reader subscriptions yet, but they can be imported from the commandline by doing:

```sh
$ php index.php --task=feeds:import --file=subscriptions.xml --user=<user_id>
```

