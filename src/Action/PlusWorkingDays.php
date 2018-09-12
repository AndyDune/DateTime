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


namespace AndyDune\DateTime\Action;


use AndyDune\DateTime\DateTime;

class PlusWorkingDays extends AbstractAction
{
    use WorkingDaysTrait;

    protected $plusNoWorkingDayIfItHappenInSaturdayOrSunday = false;

    protected $addWorkingDayIfPublicHolidayInCommonHoliday = false;

    protected $daysPlus = 0;
    protected $daysPlusWorkingWeekDay = 0;
    protected $daysPlusNotWorkingWeekDay = 0;

    public function setIsAddWorkingDayIfPublicHolidayInCommonHoliday($flag = true)
    {
        $this->addWorkingDayIfPublicHolidayInCommonHoliday = $flag;
        return $this;
    }

    public function execute(...$params) : DateTime
    {
        $plusDays = $params[0] ?? 0;
        $doNotFindHolidayInSunday = 5;
        $this->daysPlus = 0;
        $this->daysPlusNotWorkingWeekDay = 0;
        $this->daysPlusWorkingWeekDay = 0;
        do {
            if ($plusDays < 0) {
                $plusDays = 0;
            }
            $go = $plusDays;
            $inNoWorkingDays = $this->isInNoWorkingDays();
            $isInWorkingWeekDays = $this->isInWorkingWeekDays();
            if ($this->isInWorkingDays()) {
                if (!$plusDays) {
                    if (!$isInWorkingWeekDays) {
                        $this->getDateTime()->add('+ 1 day');
                        $this->daysPlus++;
                        $this->daysPlusWorkingWeekDay++;
                        $this->daysPlusNotWorkingWeekDay++;
                        $go = true; continue;
                    }
                    return $this->getDateTime();
                }
                $this->getDateTime()->add('+ 1 day');
                $this->daysPlusWorkingWeekDay++;
                $this->daysPlus++;
                $plusDays--;
                $go = true; continue;
            }

            if ($this->getDateTime()->isSaturday()
                or $this->getDateTime()->isSunday()
            ) {
                if ($this->addWorkingDayIfPublicHolidayInCommonHoliday
                    and $inNoWorkingDays and $doNotFindHolidayInSunday > 4) {
                    $doNotFindHolidayInSunday = 0;
                    $plusDays++;
                }
                $this->getDateTime()->add('+ 1 day');
                $this->daysPlus++;
                $go = true; continue;
            }

            if ($inNoWorkingDays) {
                $this->getDateTime()->add('+ 1 day');
                $this->daysPlus++;
                $go = true; continue;
            }

            if (!$plusDays) {
                if (!$isInWorkingWeekDays) {
                    $this->getDateTime()->add('+ 1 day');
                    $this->daysPlus++;
                    $this->daysPlusWorkingWeekDay++;
                    $this->daysPlusNotWorkingWeekDay++;
                    $go = true; continue;
                }

                return $this->getDateTime();
            }

            $doNotFindHolidayInSunday++;
            $this->getDateTime()->add('+ 1 days');
            $this->daysPlus++;
            $this->daysPlusWorkingWeekDay++; // считаем пропущенные рабочие дни
            $plusDays--;
            $go = true; continue;

            // Это уже повтор кода что выше
            if (!$go and !$isInWorkingWeekDays) {
                $this->getDateTime()->add('+ 1 day');
                $this->daysPlus++;
                $this->daysPlusNotWorkingWeekDay++;
                continue;
            }

        } while ($go);
        return $this->getDateTime();
    }

    /**
     * Returns count of days added before working days was fount.
     *
     * @return int
     */
    public function getDaysPlus() : int
    {
        return $this->daysPlus;
    }

    /**
     * It returns days count working days witch are not working within special working week.
     * Use method setWorkingWeekDays() to point working weekdays
     *
     * @return int
     */
    public function getDaysPlusNotWorkingWeekDay()
    {
        return $this->daysPlusNotWorkingWeekDay;
    }


    /**
     * It returns days count working days .
     *
     * @return int
     */
    public function getDaysPlusWorkingWeekDay()
    {
        return $this->daysPlusWorkingWeekDay;
    }
}