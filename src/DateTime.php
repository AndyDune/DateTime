<?php
/**
 *
 * PHP version >= 7.1
 *
 * @package andydune/datetime
 * @link  https://github.com/AndyDune/DateTime for the canonical source repository
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author Andrey Ryzhov  <info@rznw.ru>
 * @copyright 2018 Andrey Ryzhov
 */


namespace AndyDune\DateTime;
use DateTimeZone;

class DateTime
{

    /**
     * @var null|DateTimeZone
     */
    protected $datetimeZone = null;

    protected $defaultFormat = 'Y-m-d H:i:s';

    /**
     * @var \DateTime
     */
    protected $value;
    /**
     * @param string $time String representation of datetime.
     * @param string $format Format accepted by date(). If not specified, the format is Y-m-d H:i:s.
     * @param \DateTimeZone|string $timezone Optional timezone object.
     *
     */
    public function __construct($time = null, $format = null,  $timezone = null)
    {
        if ($timezone and is_string($timezone)) {
            $timezone = new DateTimeZone($timezone);
        }
        if ($time instanceof \DateTime) {
            $this->value = $time;
            return;
        }

        if ($time !== null && $time !== "")
        {
            if ($format === null)
            {
                $format = $this->defaultFormat;
                if (is_integer($time)) {
                    $time = date($format, $time);
                }
            }

            $this->value = \DateTime::createFromFormat($format, $time, $timezone);
        } else {
             $this->value= new \DateTime(null, $timezone);
        }
        $this->datetimeZone = $this->value->getTimezone();
    }

    /**
     * Set current timezone.
     * It can be string:
     * Europe/Moscow
     * Asia/Novosibirsk
     * Asia/Vladivostok
     *
     * and other from http://php.net/manual/en/timezones.php
     *
     * @param DateTimeZone|string  $zone
     * @return $this
     */
    public function setDateTimeZone($zone)
    {
        if (is_string($zone)) {
            $zone = new DateTimeZone($zone);
        }
        $this->value->setTimezone($zone);
        return $this;
    }


    /**
     * @return DateTimeZone
     */
    public function getTimezone()
    {
        return $this->value->getTimezone();
    }

    /**
     * Performs dates arithmetic.
     *
     * Each duration period is represented by an integer value followed by a period
     * designator. If the duration contains time elements, that portion of the
     * specification is preceded by the letter T.
     * Period Designators: Y - years, M - months, D - days, W - weeks, H - hours,
     * M - minutes, S - seconds.
     * Examples: two days - 2D, two seconds - T2S, six years and five minutes - 6YT5M.
     * The unit types must be entered from the largest scale unit on the left to the
     * smallest scale unit on the right.
     * Use first "-" char for negative periods.
     * OR
     * Relative period.
     * Examples: "+5 weeks", "12 day", "-7 weekdays", '3 months - 5 days'
     *
     * @param string $interval Time interval to add.
     *
     * @return DateTime
     */
    public function add($interval)
    {
        $i = null;
        try
        {
            $intervalTmp = strtoupper($interval);
            $isNegative = false;
            $firstChar = substr($intervalTmp, 0, 1);
            if ($firstChar === "-")
            {
                $isNegative = true;
                $intervalTmp = substr($intervalTmp, 1);
                $firstChar = substr($intervalTmp, 0, 1);
            }

            if ($firstChar !== "P")
            {
                $intervalTmp = "P".$intervalTmp;
            }
            $i = new \DateInterval($intervalTmp);
            if ($isNegative)
            {
                $i->invert = 1;
            }
        }
        catch (\Exception $e)
        {
        }

        if ($i == null)
        {
            $i = \DateInterval::createFromDateString($interval);
        }

        $this->value->add($i);

        return $this;
    }


    /**
     * Returns monday date.
     *
     * @param string $format
     * @return false|string
     */
    public function getDateMonday($format = 'Y-m-d')
    {
        $weekDay = $this->format('N') - 1;
        $time = $this->getTimestamp();
        if ($weekDay) {
            $time -= $weekDay * 24 * 3600;
        }
        return date($format, $time);
    }


    /**
     * Returns sunday date.
     *
     * @param string $format
     * @return false|string
     */
    public function getDateSunday($format = 'Y-m-d')
    {
        $weekDay = 7 - $this->format('N');
        $time = $this->getTimestamp();
        if ($weekDay) {
            $time += $weekDay * 24 * 3600;
        }
        return date($format, $time);
    }

    /**
     * Day of the month without leading zeros
     *
     * @return string
     */
    public function getDay()
    {
        return $this->format('j');
    }

    /**
     * Numeric representation of a month, without leading zeros
     *
     * @return string
     */
    public function getMonth()
    {
        return $this->format('n');
    }

    /**
     * A full numeric representation of a year, 4 digits
     *
     * @return string
     */
    public function getYear()
    {
        return $this->format('Y');
    }

    /**
     * Returns Unix timestamp from date.
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->value->getTimestamp();
    }


    /**
     * Converts a date to the string.
     *
     * @param string $format Format accepted by date().
     * @return string
     */
    public function toString($format = null)
    {
        return $this->format($format);
    }

    /**
     * Converts a date to the string with default culture format setting.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString($this->defaultFormat);
    }


    /**
     * Formats date value to string.
     *
     * @param string $format Format accepted by date().
     *
     * @return string
     */
    public function format($format)
    {
        return $this->value->format($format);
    }

    /**
     * Sets the date and time based on a Unix timestamp.
     *
     * @param int $timestamp Source timestamp.
     *
     * @return static
     */
    public function setTimestamp($timestamp)
    {
        $this->value->setTimestamp($timestamp);
        return $this;
    }
}