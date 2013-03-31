Kurkuma
=======

RSS Reader

Installation
------------

Clone the source from github.

	$ git clone git://github.com/acidtv/Kurkuma.git
	$ cd Kurkuma

Install dependencies with composer (http://getcomposer.org/).

	$ composer install

Create database.

	$ echo create database kurkuma\; | mysql -u user -p
	$ mysql -u user -p kurkuma < sql/kurkuma.sql

Set permissions for log and cache directories.

	$ chown www-data:www-data application/{logs,cache}

Copy sample config files. After copying them, adjust the vars to fit your needs.

	$ cd application/config
	$ for file in `ls`; do cat $file > `echo $file | sed 's/sample\.//g'`; done


