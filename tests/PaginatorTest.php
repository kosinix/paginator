<?php
use Kosinix\Paginator;

class PaginatorTest extends PHPUnit_Framework_TestCase
{
    public function testOnZeroTotal()
    {
        $total = 0;
        $paginator = new Paginator($total);
        
        $this->assertEquals(0, $paginator->get_start_index());
        $this->assertEquals(0, $paginator->get_end_index(), 'end_index should be zero if total is zero');
        $this->assertEquals(0, $paginator->get_previous_page(), 'previous_page should be zero');
    }
    
    
    public function testAgainstValues()
    {
        $total = 23;
        $per_page = 10;
        $current_page = 2;
        $paginator = new Paginator($total, $current_page, $per_page);
        
        $this->assertEquals(10, $paginator->get_start_index());
        $this->assertEquals(19, $paginator->get_end_index());
        $this->assertEquals(1, $paginator->get_previous_page());
        $this->assertEquals(3, $paginator->get_next_page());
    }
    
    public function testClassGettersCompleteness(){
        
        $missing_functions = array();
        $public_functions = array();
        
        $reflection = new ReflectionClass('\Kosinix\Paginator');
        
        // Get class public functions
        foreach( $reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method ) {
            if($method->name !== '__construct'){
                $public_functions[] = $method->name;
            }
        }
        
        // Generate a getter name for each class variables and test existence
        foreach( $reflection->getProperties() as $field ) {
            $getter_name = 'get_'.$field->name; // Format: get_[method name]
            if(!in_array($getter_name, $public_functions)){
                $missing_functions[] = $getter_name;
            }
        }
        sort($missing_functions); // Sort alphabetically
        
        $this->assertCount(0, $missing_functions, 'Missing getter functions from class Paginator: '.print_r($missing_functions, true));
    }
}