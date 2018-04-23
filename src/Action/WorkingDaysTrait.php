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


trait WorkingDaysTrait
{
    protected $noWorkingDays = ['1-01', '2-01', '3-01', '7-01', '23-02', '8-03', '9-05'];
    protected $formatNoWorkingDays = 'j-m';
    protected $workingDays = [];
    protected $formatWorkingDays = 'j-m';

    /**
     * Set official no working days for your country.
     *
     * Format:
     * ['j-m', 'j-m', ...]
     *
     * @param $days
     * @param $format
     * @return $this
     */
    public function setNoWorkingDays(array $days, $format = null)
    {
        $this->noWorkingDays = $days;
        if ($format) {
            $this->formatNoWorkingDays = $format;
        }
        return $this;
    }

    /**
     * Set working days as exclusion.
     *
     * @param array $days
     * @param null $format
     * @return $this
     */
    public function setWorkingDays(array $days, $format = null)
    {
        $this->workingDays = $days;
        if ($format) {
            $this->formatWorkingDays = $format;
        }
        return $this;
    }

    protected function isInNoWorkingDays()
    {
        $dayMonth = $this->getDateTime()->format($this->formatNoWorkingDays);
        if (in_array($dayMonth, $this->noWorkingDays)) {
            return true;
        }
        return false;
    }

    protected function isInWorkingDays()
    {
        $dayMonth = $this->getDateTime()->format($this->formatWorkingDays);
        if (in_array($dayMonth, $this->workingDays)) {
            return true;
        }
        return false;
    }

}