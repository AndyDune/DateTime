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
Or if composer was not installed globally:
```
php composer.phar require andydune/datetime
```
Or edit your `composer.json`:
```
"require" : {
     "andydune/datetime": "^2"
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
use AndyDuneTest\DateTime\DateTime;
$dt = new DateTime();
$dt->getTimestamp; // == time()
``` 

Constructor with integer parameter is unit seconds.
```php
use AndyDuneTest\DateTime\DateTime;
$dt = new DateTime(time() + 3600);
$dt->getTimestamp; // == time() + 3600
``` 

Constructor with string parameter is the same for function `strtotime()`
```php
use AndyDuneTest\DateTime\DateTime;
$dt = new DateTime('2018-04-11'); // default format is mysql datetime
$dt = new DateTime('11.04.2017', 'd.m.Y'); // own format - use string as for date() function
``` 

Constructor with parameter `\Datetime` type
```php
use AndyDuneTest\DateTime\DateTime;
$dt = new DateTime(new \DateTime());
``` 

Format datetime
------------

It has method `format()` to get formated datetiem data as string. Method waits string like date() function.
```php
use AndyDuneTest\DateTime\DateTime;
$dt = new DateTime();
echo $dt->format('Y-m-d'); // 2107-04-12
echo $dt->format('H:i'); // 10:04
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
use AndyDuneTest\DateTime\DateTime;
$dt = new DateTime();
$dt->add('2D'); // two days
$dt->add('T2S'); // two seconds
$dt->add('6YT5M'); // six years and five minutes
``` 


The unit types must be entered from the largest scale unit on the left to the smallest scale unit on the right.
Use first "-" char for negative periods. OR Relative period.

Examples:

```php
use AndyDuneTest\DateTime\DateTime;
$dt = new DateTime();
$dt->add('+5 weeks'); // 5 weeks to future
$dt->add('12 day'); // 12 days to future
$dt->add('-7 weekdays'); // 7 working daye to past
$dt->add('3 months - 5 days'); // 3 months to future and 5 days to past
``` 


Tools
------------

### Bring per day statistics to weeks.

If there is no full weekdays number is source data missing days will be added as average.  

Source json:

```php
$json = '
   {
    "2018-03-01" : 913,
    "2018-03-03" : 913,
    "2018-03-04" : 913,
    
    "2018-03-05" : 910,
    "2018-03-07" : 914,
    "2018-03-08" : 915,
    "2018-03-09" : 915,
    "2018-03-11" : 912,
    
    "2018-03-12" : 869,
    "2018-03-14" : 869,
    "2018-03-16" : 869,
    "2018-03-17" : 864,
}';

```

```php
use AndyDune\DateTime\Tool\Statistics\BringNumberInDayToNumberInWeek;
$data = json_decode($json, true);

$stat = new BringNumberInDayToNumberInWeek($data);
$weeks = $stat->getWeeksWithCalendarDivision();

```

Weeks are:

```json
 {
    "2018-03-04" : 6391,
    "2018-03-11" : 6393,
    "2018-03-18" : 6075
}
```

Strategy pattent
------------
There is great instrument to manipulatin with `DateTime` object without editting existing code in this library.

## Methods

`DateTime::setAction(AbstractAction $action)` - add action for further execution

`DateTime::executeAction(...$params)` - execute actions with any patrams


### To know is working day

```php
use AndyDune\DateTime\Action\IsWorkingDay;
use AndyDune\DateTime\DateTime;

$dt = new DateTime('18-04-2018', 'd-m-Y');
$dt->setAction(new IsWorkingDay())->executeAction(); // true

$dt = new DateTime('22-04-2018', 'd-m-Y');
$dt->setAction(new IsWorkingDay())->executeAction(); // false
```

### To know closest working date after pointed days number

There is an action `AndyDune\DateTime\Action\PlusWorkingDays` for this task.

```php
use AndyDune\DateTime\Action\PlusWorkingDays;
use AndyDune\DateTime\DateTime;

$dt = new DateTime('28-04-2018', 'd-m-Y');
$action = new PlusWorkingDays();
$action->setNoWorkingDays(['1-05', '30-04']);  // set list of official holidays 
$action->setWorkingDays(['28-04']); // set list of working sundays or saturdays
$dt->setAction($action)->executeAction(1); // to know working date after 1 day

$dt->format('d-m-Y'); // '02-05-2018'
$action->getDaysPlus(); // 4 days
```
