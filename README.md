# DateTime

[![Build Status](https://travis-ci.org/AndyDune/DateTime.svg?branch=master)](https://travis-ci.org/AndyDune/DateTime)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/andydune/datetime.svg?style=flat-square)](https://packagist.org/packages/andydune/datetime)
[![Total Downloads](https://img.shields.io/packagist/dt/andydune/datetime.svg?style=flat-square)](https://packagist.org/packages/andydune/datetime)


Extends DateTime class for add some comfort.

Installation
------------

Installation using composer:

```
composer require andydune/datetime
```
Or if composer didn't install globally:
```
php composer.phar require andydune/datetime
```
Or edit your `composer.json`:
```
"require" : {
     "andydune/datetime": "^1"
}

```
And execute command:
```
php composer.phar update
```

How to create instance
------------

Constructor without parameters set current time value.
```php
namespace AndyDuneTest\DateTime;
$dt = new DateTime();
$dt->getTimestamp; // == time()
``` 

Constructor with integer parameter is unit seconds.
```php
namespace AndyDuneTest\DateTime;
$dt = new DateTime(time() + 3600);
$dt->getTimestamp; // == time() + 3600
``` 

Constructor with string parameter is the same for function `strtotime()`
```php
namespace AndyDuneTest\DateTime;
$dt = new DateTime('2018-04-11'); // default format is mysql datetime
$dt = new DateTime('11.04.2017', 'd.m.Y'); // own format - use string as for date() function
``` 

Constructor with parameter `\Datetime` type
```php
namespace AndyDuneTest\DateTime;
$dt = new DateTime(new \DateTime());
``` 

Dates arithmetic.
------------

Each duration period is represented by an integer value followed by a period designator. 
If the duration contains time elements, that portion of the specification is preceded by the letter T.

Period Designators: 
- Y - years, 
- M - months, 
- D - days, 
- W - weeks, 
- H - hours, 
- M - minutes, 
- S - seconds.

Examples: 

```php
namespace AndyDuneTest\DateTime;
$dt = new DateTime();
$dt->add('2D'); // two days
$dt->add('T2S'); // two seconds
$dt->add('6YT5M'); // six years and five minutes
``` 


The unit types must be entered from the largest scale unit on the left to the smallest scale unit on the right.
Use first "-" char for negative periods. OR Relative period.

Examples:

```php
namespace AndyDuneTest\DateTime;
$dt = new DateTime();
$dt->add('+5 weeks'); // 5 weeks to future
$dt->add('12 day'); // 12 days to future
$dt->add('-7 weekdays'); // 7 working daye to past
$dt->add('3 months - 5 days'); // 3 months to future and 5 days to past
``` 

