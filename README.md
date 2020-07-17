# WordPress migrate URL script

Another WordPress migrate URL script.

Sadly WordPress put the domain name everywhere instead of using relative URLs etc.

I was somehow not happy about the existing scripts, so here you are another one.

Database tables involved:

* `posts` ( `post_content` and `post_excerpt` )
* `options`
* `postmeta`

Yes, the `options` and `postmeta` tables contains serialized data. Also that will be migrated.

## Installation

```
sudo apt install php-cli
git clone https://gitpull.it/source/suckless-php.git
git clone https://gitpull.it/source/wp-migrate-url.git
```

Yes, it's based on my awesome and dummy [suckless-php](https://gitpull.it/source/suckless-php/).

## Configuration

Copy the file `load-example.php` as `load.php`.

Fill `load.php` with fill your database credentials.

## Usage

```
./migrate.php http://old.url/ http://new.url/
```

## Stupid questions

* Why is this script in PHP? Because WordPress is in PHP and because PHP is not that bad. I wrote this script in 3 minutes.

## License

Copyright (c) 2019-2029 [Valerio Bozzolan](http://boz.reyboz.it/)

This is a **Free** as in **Freedom** project. It comes with ABSOLUTELY NO WARRANTY. You are welcome to redistribute it under the terms of the **GNU Affero General Public License v3+**.
