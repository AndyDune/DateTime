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

abstract class AbstractAction
{
    /**
     * @var DateTime
     */
    protected $dateTime;

    final public function setDateTime(DateTime $container)
    {
        $this->dateTime = $container;
    }

    /**
     * @return DateTime
     */
    final public function getDateTime()
    {
        return $this->dateTime;
    }

    abstract public function execute(...$params);
}