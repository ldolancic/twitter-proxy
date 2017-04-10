TwitterProxy sample app
========================

This is a simple Symfony Twitter proxy web app.

Installation
--------------

1) Clone this github repo
```sh
$ git clone https://github.com/ldolancic/twitter-proxy.git
```

2) Install dependencies and specify parameters

Go to apps.twitter.com and create a new Twitter app. Then, on composer update,
enter the app credentials.
```sh
$ composer install
```
3) Create database
```sh
$ php bin/console doctrine:database:create
```

4) Update database schema
```sh
$ php bin/console doctrine:schema:update --force
```

5) Prepare tweets table for fulltext search
```sh
$ php bin/console fulltext-search:prepare-tweets
```