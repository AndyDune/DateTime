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


namespace AndyDuneTest\DateTime;
use AndyDune\DateTime\DateTime;
use PHPUnit\Framework\TestCase;


class DateTimeTest extends TestCase
{
    public function testBase()
    {
        $time = time() - 60;
        $dt = new DateTime($time);
        $this->assertEquals($dt->getTimestamp(), $time);

        $dt = new DateTime();
        $ts1 = $dt->getTimestamp();
        $dt->add('-2 minutes');
        $this->assertEquals($dt->getTimestamp(), $ts1 - 2 * 60);

        $dt = new DateTime();
        $ts1 = $dt->getTimestamp();
        $dt->add('+5 minute');
        $this->assertEquals($dt->getTimestamp(), $ts1 + 5 * 60);

        $dt = new DateTime();
        $ts1 = $dt->getTimestamp();
        $dt->add('-T2M');
        $this->assertEquals($dt->getTimestamp(), $ts1 - 2 * 60);


        $dt = new DateTime();
        $ts1 = $dt->getTimestamp();
        $dt->add('2 hours');
        $this->assertEquals($dt->getTimestamp(), $ts1 + 2 * 3600);

        $dt = new DateTime();
        $ts1 = $dt->getTimestamp();
        $dt->add('T2H');
        $this->assertEquals($dt->getTimestamp(), $ts1 + 2 * 3600);

        $dt = new DateTime();
        $ts1 = $dt->getTimestamp();
        $dt->add('-1day');
        $this->assertEquals($dt->getTimestamp(), $ts1 - 24 * 3600);

        $dt = new DateTime();
        $ts1 = $dt->getTimestamp();
        $dt->add('-2 days');
        $this->assertEquals($dt->getTimestamp(), $ts1 - 2 * 24 * 3600);

        $dt = new DateTime();
        $dt->add('-7 days');
        $mondayDate = $dt->getDateMonday();
        $sundayDate = $dt->getDateSunday();
        $interval = (strtotime($sundayDate) - strtotime($mondayDate)) / (3600 * 24);
        $this->assertEquals(6, $interval);



        $origin = new \DateTime();
        $origin->add(new \DateInterval('PT1H'));
        $time = time();
        $dt = new DateTime($origin);
        $this->assertEquals($dt->getTimestamp(), $time + 1 * 3600);

    }

}