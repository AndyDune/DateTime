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
namespace AndyDuneTest\DateTime\Tool\Statistics;
use AndyDune\DateTime\DateTime;
use AndyDune\DateTime\Tool\Statistics\BringNumberInDayToNumberInWeek;
use PHPUnit\Framework\TestCase;


class BringNumberInDayToNumberInWeekTest extends TestCase
{
    public function testBase()
    {
        $json = '
        {
     "2018-03-01" : 913,
    "2018-03-03" : 913,
    "2018-03-04" : 913,
    
    "2018-03-05" : 910,
    "2018-03-07" : 914,
    "2018-03-08" : 915,
    "2018-03-09" : 915,
    "2018-03-11" : 912,
    
    "2018-03-12" : 869,
    "2018-03-14" : 869,
    "2018-03-16" : 869,
    "2018-03-17" : 864,
    
    
    "2018-03-20" : 861,
    "2018-03-21" : 860,
    "2018-03-22" : 863,
    "2018-03-23" : 863,
    "2018-03-25" : 861,
    "2018-03-26" : 859,
    "2018-03-27" : 858,
    "2018-03-28" : 858,
    "2018-03-29" : 859,
    "2018-03-30" : 861,
    "2018-03-31" : 858,
    "2018-04-01" : 856,
    "2018-04-02" : 854,
    "2018-04-03" : 852,
    "2018-04-04" : 848,
    "2018-04-05" : 848,
    "2018-04-06" : 849,
    "2018-04-07" : 850,
    "2018-04-08" : 850,
    "2018-04-09" : 851,
    "2018-04-10" : 848,
    "2018-04-11" : 848,
    "2018-04-12" : 850,
    "2018-04-13" : 849,
    "2018-04-14" : 847,
    "2018-04-15" : 846,
    "2018-04-16" : 847,
    "2018-04-17" : 846,
    "2018-04-18" : 847
}
';
        $data = json_decode($json, true);

        $stat = new BringNumberInDayToNumberInWeek($data);
        $weeks = $stat->getWeeksWithCalendarDivision();

        $this->assertCount(8, $weeks);
        // Sundays is key for key of week
        $this->assertTrue(array_key_exists('2018-04-22', $weeks));
        $this->assertTrue(array_key_exists('2018-04-15', $weeks));
        $this->assertTrue(array_key_exists('2018-04-08', $weeks));
        $this->assertTrue(array_key_exists('2018-04-01', $weeks));
        $this->assertTrue(array_key_exists('2018-03-25', $weeks));
    }

}