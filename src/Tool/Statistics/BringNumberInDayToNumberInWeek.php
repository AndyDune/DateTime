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


namespace AndyDune\DateTime\Tool\Statistics;


use AndyDune\ArrayContainer\ArraysAccumulator;
use AndyDune\DateTime\DateTime;

class BringNumberInDayToNumberInWeek
{
    protected $days = [];

    protected $format = 'Y-m-d';

    protected $fillMissingDayWithIntermediateValue = true;

    public function __construct($days = [])
    {
        $this->days = $days;
    }

    public function getPreparedDays()
    {
        $days = $this->days;
        ksort($days);
        return $days;
    }

    public function getWeeksWithCalendarDivision()
    {
        $days = $this->getPreparedDays();

        $weeksContainer = new ArraysAccumulator();
        foreach ($days as $day => $count) {
            $dateTime = new DateTime($day, $this->format);
            $weeksContainer->add($dateTime->getDateSunday(), $count);
        }

        $weeksCount = [];
        $weeks = $weeksContainer->getArrayCopy();
        foreach ($weeks as $sunday => $countInDay) {
            $countInWeek = array_reduce($countInDay, function ($carry, $current) {
                return $carry + $current;
            }, 0);

            if (!$this->fillMissingDayWithIntermediateValue) {
                $weeksCount[$sunday] = $countInWeek;
                continue;
            }

            $daysInWeek = count($countInDay);
            $addDays = 7 - $daysInWeek;
            if ($addDays) {
                $countInWeek += $addDays * ($countInWeek / $daysInWeek);
            }
            $weeksCount[$sunday] = ceil($countInWeek);
        }
        return $weeksCount;
    }

}