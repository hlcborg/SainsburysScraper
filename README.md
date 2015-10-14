Sainsbury's Scarper Test
===================

This is a test tool console application that scrape some products from Sainsbury's online shop.
Then returns a formatted JSON array containing several fields.
The application was developed on top of a Yii2 full stack PHP framework.

The files related to the scrape actions are:

SainsburysScraper/_protected/console/controllers/FruitScraperController.php
and
SainsburysScraper/_protected/console/models/Fruit.php


Dependencies
------------

This project needs curl option installed and activated in LAMP stack assuming Ubuntu
To install curl:
> sudo apt-get install php5-curl

After installing libcurl you should restart the web server :
> sudo service apache2 restart


Installation
-------------------

To install and execute, just get the code:

> git clone https://github.com/hlcborg/SainsburysScraper.git

or download .zip file and extract into root directory of webserver.
Then enter the directory

> cd SainsburysScraper/_protected

and run:

> $./yii fruit-scraper/init


Testing
-------------------

Although there is a test file related with this project in this directory:

> SainsburysScraper/_protected/tests/codeception/unit/FruitScraperTest.php

is not working because of a bug/problem from phpunit module with this framework version.