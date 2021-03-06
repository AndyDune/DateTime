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

namespace AndyDuneTest\Action;

use AndyDune\DateTime\Action\IsWorkingDay;
use AndyDune\DateTime\Action\PlusWorkingDays;
use AndyDune\DateTime\DateTime;
use PHPUnit\Framework\TestCase;


class PlusWorkingDaysTest extends TestCase
{
    public function testIt()
    {

        $dt = new DateTime('18-04-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $result = $dt->setAction($action)->executeAction()->format('d-m-Y');
        $this->assertEquals(0, $action->getDaysPlus());
        $this->assertEquals('18-04-2018', $result);

        $dt = new DateTime('18-04-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $result = $dt->setAction($action)->executeAction(1)->format('d-m-Y');
        $this->assertEquals(1, $action->getDaysPlus());
        $this->assertEquals('19-04-2018', $result);

        $dt = new DateTime('18-04-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $result = $dt->setAction($action)->executeAction(2)->format('d-m-Y');
        $this->assertEquals(2, $action->getDaysPlus());
        $this->assertEquals('20-04-2018', $result);

        $dt = new DateTime('18-04-2018', 'd-m-Y');
        $result = $dt->setAction(new PlusWorkingDays())->executeAction(3)->format('d-m-Y');
        $this->assertEquals('23-04-2018', $result);


        $dt = new DateTime('21-04-2018', 'd-m-Y');
        $result = $dt->setAction(new PlusWorkingDays())->executeAction()->format('d-m-Y');
        $this->assertEquals('23-04-2018', $result);

        $dt = new DateTime('21-04-2018', 'd-m-Y');
        $result = $dt->setAction(new PlusWorkingDays())->executeAction(1)->format('d-m-Y');
        $this->assertEquals('24-04-2018', $result);


        $dt = new DateTime('20-04-2018', 'd-m-Y');
        $result = $dt->setAction(new PlusWorkingDays())->executeAction(6)->format('d-m-Y');
        $this->assertEquals('30-04-2018', $result);

        $dt = new DateTime('20-04-2018', 'd-m-Y');
        $result = $dt->setAction(new PlusWorkingDays())->executeAction(8)->format('d-m-Y');
        $this->assertEquals('02-05-2018', $result);


        $dt = new DateTime('20-04-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setNoWorkingDays(['1-05']);
        $result = $dt->setAction($action)->executeAction(8)->format('d-m-Y');
        $this->assertEquals('03-05-2018', $result);


        $dt = new DateTime('20-04-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setNoWorkingDays(['28-04']);
        $result = $dt->setAction($action)->executeAction(8)->format('d-m-Y');
        $this->assertEquals('02-05-2018', $result);
        $this->assertEquals(12, $action->getDaysPlus());

        $dt = new DateTime('20-04-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setIsAddWorkingDayIfPublicHolidayInCommonHoliday(true);
        $action->setNoWorkingDays(['28-04']);
        $result = $dt->setAction($action)->executeAction(8)->format('d-m-Y');
        $this->assertEquals('03-05-2018', $result);
        $this->assertEquals(13, $action->getDaysPlus());

        $dt = new DateTime('20-04-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setIsAddWorkingDayIfPublicHolidayInCommonHoliday();
        $action->setNoWorkingDays(['28-04', '29-04']);
        $result = $dt->setAction($action)->executeAction(8)->format('d-m-Y');
        $this->assertEquals('03-05-2018', $result);
        $this->assertEquals(13, $action->getDaysPlus());
    }

    public function testWorkingDays()
    {
        $dt = new DateTime('27-04-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setNoWorkingDays(['1-05']);
        $action->setWorkingDays(['28-04']);
        $result = $dt->setAction($action)->executeAction(1)->format('d-m-Y');
        $this->assertEquals('28-04-2018', $result);
        $this->assertEquals(1, $action->getDaysPlus());

        $dt = new DateTime('28-04-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setNoWorkingDays(['1-05', '30-04']);
        $action->setWorkingDays(['28-04']);
        $result = $dt->setAction($action)->executeAction(1)->format('d-m-Y');
        $this->assertEquals('02-05-2018', $result);
        $this->assertEquals(4, $action->getDaysPlus());
    }

    public function testActionIsWorkingDay()
    {
        $dt = new DateTime('18-04-2018', 'd-m-Y');
        $this->assertTrue($dt->setAction(new IsWorkingDay())->executeAction());

        $dt = new DateTime('21-04-2018', 'd-m-Y');
        $this->assertFalse($dt->setAction(new IsWorkingDay())->executeAction());


        $dt = new DateTime('20-04-2018', 'd-m-Y');
        $action = new IsWorkingDay();
        $action->setNoWorkingDays(['28-04', '29-04']);
        $this->assertTrue($dt->setAction($action)->executeAction());

        $dt = new DateTime('20-04-2018', 'd-m-Y');
        $action = new IsWorkingDay();
        $action->setNoWorkingDays(['20-04', '29-04']);
        $this->assertFalse($dt->setAction($action)->executeAction());


        $dt = new DateTime('28-04-2018', 'd-m-Y');
        $action = new IsWorkingDay();
        $this->assertFalse($dt->setAction($action)->executeAction());

        $action = new IsWorkingDay();
        $action->setNoWorkingDays(['1-05']);
        $action->setWorkingDays(['28-04']);
        $this->assertTrue($dt->setAction($action)->executeAction());

        $dt = new DateTime('01-05-2018', 'd-m-Y');
        $action = new IsWorkingDay();
        $action->setNoWorkingDays(['1-05']);
        $action->setWorkingDays(['28-04']);
        $this->assertFalse($dt->setAction($action)->executeAction());
    }

    public function testWeekWorkingDay()
    {
        $dt = new DateTime('25-06-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction();

        $this->assertEquals(0, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(0, $action->getDaysPlus());


        $dt = new DateTime('26-06-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction();

        $this->assertEquals(4, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(6, $action->getDaysPlus());


        $dt = new DateTime('25-06-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setNoWorkingDays(['25-06']);
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction();

        $this->assertEquals(4, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(7, $action->getDaysPlus());


        $dt = new DateTime('26-06-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setNoWorkingDays(['2-07']);
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction();

        $this->assertEquals(8, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(13, $action->getDaysPlus());

    }

    public function testWeekWorkingDaySeptember()
    {
        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction(0);

        $this->assertEquals(2, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(4, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([2]);
        $dt->setAction($action)->executeAction(0);

        $this->assertEquals(3, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(5, $action->getDaysPlus());


        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([3]);
        $dt->setAction($action)->executeAction(0);

        $this->assertEquals(4, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(6, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([4]);
        $dt->setAction($action)->executeAction(0);

        $this->assertEquals(0, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(0, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([5]);
        $dt->setAction($action)->executeAction(0);

        $this->assertEquals(1, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(1, $action->getDaysPlus());


        // 1 день плюс
        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction(1);

        $this->assertEquals(2, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(4, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([2]);
        $dt->setAction($action)->executeAction(1);

        $this->assertEquals(3, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(5, $action->getDaysPlus());


        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([3]);
        $dt->setAction($action)->executeAction(1);

        $this->assertEquals(4, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(6, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([4]);
        $dt->setAction($action)->executeAction(1);

        $this->assertEquals(5, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(7, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([5]);
        $dt->setAction($action)->executeAction(1);

        $this->assertEquals(1, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(1, $action->getDaysPlus());


        // 2 день плюс
        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction(2);

        $this->assertEquals(2, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(4, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([2]);
        $dt->setAction($action)->executeAction(2);

        $this->assertEquals(3, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(5, $action->getDaysPlus());


        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([3]);
        $dt->setAction($action)->executeAction(2);

        $this->assertEquals(4, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(6, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([4]);
        $dt->setAction($action)->executeAction(2);

        $this->assertEquals(5, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(7, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([5]);
        $dt->setAction($action)->executeAction(2);

        $this->assertEquals(6, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(8, $action->getDaysPlus());

        // 3 день плюс
        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction(3);

        $this->assertEquals(7, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(11, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([2]);
        $dt->setAction($action)->executeAction(3);

        $this->assertEquals(3, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(5, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([3]);
        $dt->setAction($action)->executeAction(3);

        $this->assertEquals(4, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(6, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([4]);
        $dt->setAction($action)->executeAction(3);

        $this->assertEquals(5, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(7, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([5]);
        $dt->setAction($action)->executeAction(3);

        $this->assertEquals(6, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(8, $action->getDaysPlus());


        // 4 день плюс
        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction(4);

        $this->assertEquals(7, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(11, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([2]);
        $dt->setAction($action)->executeAction(4);

        $this->assertEquals(8, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(12, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([3]);
        $dt->setAction($action)->executeAction(4);

        $this->assertEquals(4, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(6, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([4]);
        $dt->setAction($action)->executeAction(4);

        $this->assertEquals(5, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(7, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([5]);
        $dt->setAction($action)->executeAction(4);

        $this->assertEquals(6, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(8, $action->getDaysPlus());



        // 5 день плюс
        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction(5);

        $this->assertEquals(7, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(11, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([2]);
        $dt->setAction($action)->executeAction(5);

        $this->assertEquals(8, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(12, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([3]);
        $dt->setAction($action)->executeAction(5);

        $this->assertEquals(9, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(13, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([4]);
        $dt->setAction($action)->executeAction(5);

        $this->assertEquals(5, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(7, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([5]);
        $dt->setAction($action)->executeAction(5);

        $this->assertEquals(6, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(8, $action->getDaysPlus());


        // 6 день плюс
        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction(6);

        $this->assertEquals(7, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(11, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([2]);
        $dt->setAction($action)->executeAction(6);

        $this->assertEquals(8, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(12, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([3]);
        $dt->setAction($action)->executeAction(6);

        $this->assertEquals(9, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(13, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([4]);
        $dt->setAction($action)->executeAction(6);

        $this->assertEquals(10, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(14, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([5]);
        $dt->setAction($action)->executeAction(6);

        $this->assertEquals(6, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(8, $action->getDaysPlus());



        // 7 день плюс
        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction(7);

        $this->assertEquals(7, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(11, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([2]);
        $dt->setAction($action)->executeAction(7);

        $this->assertEquals(8, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(12, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([3]);
        $dt->setAction($action)->executeAction(7);

        $this->assertEquals(9, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(13, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([4]);
        $dt->setAction($action)->executeAction(7);

        $this->assertEquals(10, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(14, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([5]);
        $dt->setAction($action)->executeAction(7);

        $this->assertEquals(11, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(15, $action->getDaysPlus());



        // 8 день плюс
        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction(8);

        $this->assertEquals(12, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(18, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([2]);
        $dt->setAction($action)->executeAction(8);

        $this->assertEquals(8, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(12, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([3]);
        $dt->setAction($action)->executeAction(8);

        $this->assertEquals(9, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(13, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([4]);
        $dt->setAction($action)->executeAction(8);

        $this->assertEquals(10, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(14, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([5]);
        $dt->setAction($action)->executeAction(8);

        $this->assertEquals(11, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(15, $action->getDaysPlus());


        // 9 день плюс
        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([1]);
        $dt->setAction($action)->executeAction(9);

        $this->assertEquals(12, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(18, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([2]);
        $dt->setAction($action)->executeAction(9);

        $this->assertEquals(13, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(19, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([3]);
        $dt->setAction($action)->executeAction(9);

        $this->assertEquals(9, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(13, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([4]);
        $dt->setAction($action)->executeAction(9);

        $this->assertEquals(10, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(14, $action->getDaysPlus());

        $dt = new DateTime('06-09-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setWorkingWeekDays([5]);
        $dt->setAction($action)->executeAction(9);

        $this->assertEquals(11, $action->getDaysPlusWorkingWeekDay());
        $this->assertEquals(15, $action->getDaysPlus());
    }

}