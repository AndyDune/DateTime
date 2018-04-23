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
    use WorkingDaysTrait;

    public function execute(...$params)
    {
        if ($this->isInWorkingDays()) {
            return true;
        }

        if ($this->getDateTime()->isSaturday()
            or $this->getDateTime()->isSaturday()
            or $this->isInNoWorkingDays()
        ) {
            return false;
        }
        return true;
    }

}