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


class PlusWorkingDays extends AbstractAction
{
    use WorkingDaysTrait;

    protected $plusNoWorkingDayIfItHappenInSaturdayOrSunday = false;

    protected $addWorkingDayIfPublicHolidayInCommonHoliday = false;

    protected $daysPlus = 0;

    public function setIsAddWorkingDayIfPublicHolidayInCommonHoliday($flag = true)
    {
        $this->addWorkingDayIfPublicHolidayInCommonHoliday = $flag;
        return $this;
    }

    public function execute(...$params)
    {
        $plusDays = $params[0] ?? 0;
        $doNotFindHolidayInSunday = 5;
        $this->daysPlus = 0;
        do {
            $go = $plusDays;
            $inNoWorkingDays = $this->isInNoWorkingDays();
            if ($this->isInWorkingDays()) {
                if (!$plusDays) {
                    return $this->getDateTime();
                }
                $this->getDateTime()->add('+ 1 day');
                $this->daysPlus++;
                $plusDays--;
                continue;
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
                $go = true;
                continue;
            }

            if ($inNoWorkingDays) {
                $this->getDateTime()->add('+ 1 day');
                $this->daysPlus++;
                $go = true;
                continue;
            }

            if (!$plusDays) {
                return $this->getDateTime();
            }

            $doNotFindHolidayInSunday++;
            $this->getDateTime()->add('+ 1 day');
            $this->daysPlus++;
            $go = $plusDays--;
        } while ($go);
        return $this->getDateTime();
    }

    /**
     * Returns count of days added before working days was fount.
     *
     * @return int
     */
    public function getDaysPlus()
    {
        return $this->daysPlus;
    }

}