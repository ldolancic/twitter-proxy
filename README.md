TwitterProxy sample app
========================

This is a simple Symfony Twitter proxy web app.

Installation
--------------

1) Clone this github repo
```sh
$ git clone https://github.com/ldolancic/twitter-proxy.git
```
If you don't want to use git, you could simply download the zip file of this project.

2) Install dependencies
```sh
$ composer update
```

3) Update database schema
```sh
$ php bin/console doctrine:schema:update --force
```

4) Prepare tweets table for fulltext search
```sh
$ php bin/console fulltext-search:prepare-tweets
```