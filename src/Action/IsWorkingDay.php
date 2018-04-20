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


class IsWorkingDay extends AbstractAction
{
    protected $noWorkingDays = [];
    protected $format = 'j-m';

    protected $plusNoWorkingDayIfItHappenInSaturdayOrSunday = false;

    /**
     * Set official no working days for your country.
     *
     * Format:
     * ['d-m', 'd-m', ...]
     *
     * @param $days
     * @param $format
     * @return $this
     */
    public function setNoWorkingDays(array $days, $format = 'j-m')
    {
        $this->noWorkingDays = $days;
        $this->format = $format;
        return $this;
    }

    public function execute(...$params)
    {

        if ($this->getDateTime()->isSaturday()
            or $this->getDateTime()->isSaturday()
            or $this->isInNoWorkingDays()
        ) {
            return false;
        }
        return true;
    }

    protected function isInNoWorkingDays()
    {
        $dayMonth = $this->getDateTime()->format($this->format);
        if (in_array($dayMonth, $this->noWorkingDays)) {
            return true;
        }
        return false;
    }
}