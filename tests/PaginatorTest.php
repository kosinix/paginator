<?php
use Kosinix\Paginator;

class PaginatorTest extends PHPUnit_Framework_TestCase
{
    public function testOnZeroTotal()
    {
        $total = 0;
        $current_page = 1;
        $per_page = 10;
        $paginator = new Paginator($total, $current_page, $per_page);
        
        $this->assertEquals(0, $paginator->getStartIndex());
        $this->assertEquals(0, $paginator->getEndIndex(), 'End index should be zero if total is zero');
    }
    
    
    public function testAgainstValues()
    {
        $total = 23;
        $current_page = 2;
        $per_page = 10;
        $paginator = new Paginator($total, $current_page, $per_page);
        
        $this->assertEquals(10, $paginator->getStartIndex());
        $this->assertEquals(19, $paginator->getEndIndex());
        $this->assertEquals(1, $paginator->getCurrentPage()-1);
        $this->assertEquals(3, $paginator->getCurrentPage()+1);
    }
}