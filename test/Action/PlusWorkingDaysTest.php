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
        $result = $dt->setAction(new PlusWorkingDays())->executeAction()->format('d-m-Y');
        $this->assertEquals('18-04-2018', $result);

        $dt = new DateTime('18-04-2018', 'd-m-Y');
        $result = $dt->setAction(new PlusWorkingDays())->executeAction(1)->format('d-m-Y');
        $this->assertEquals('19-04-2018', $result);

        $dt = new DateTime('18-04-2018', 'd-m-Y');
        $result = $dt->setAction(new PlusWorkingDays())->executeAction(2)->format('d-m-Y');
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
        $this->assertEquals('03-05-2018', $result);


        $dt = new DateTime('20-04-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setNoWorkingDays(['28-04', '29-04']);
        $result = $dt->setAction($action)->executeAction(8)->format('d-m-Y');
        $this->assertEquals('03-05-2018', $result);
    }

    public function testWorkingDays()
    {
        $dt = new DateTime('27-04-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setNoWorkingDays(['1-05']);
        $action->setWorkingDays(['28-04']);
        $result = $dt->setAction($action)->executeAction(1)->format('d-m-Y');
        $this->assertEquals('28-04-2018', $result);

        $dt = new DateTime('28-04-2018', 'd-m-Y');
        $action = new PlusWorkingDays();
        $action->setNoWorkingDays(['1-05', '30-04']);
        $action->setWorkingDays(['28-04']);
        $result = $dt->setAction($action)->executeAction(1)->format('d-m-Y');
        $this->assertEquals('02-05-2018', $result);


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
    }
}