<?php
/**
 * Run phpunit --testdox  tests/unitTestFa.php 
 */

require_once(__DIR__. '/../fa.php');

use PHPUnit\Framework\TestCase;

class FaTest extends TestCase
{
    public function testTransitionFunc()
    {
        $this->assertequals(transitionFunc('S0', '0'), 'S0');
        
        $this->assertequals(transitionFunc('S0', '1'), 'S1');
        
        $this->assertequals(transitionFunc('S1', '0'), 'S2');
        
        $this->assertequals(transitionFunc('S1', '1'), 'S0');
        
        $this->assertequals(transitionFunc('S2', '0'), 'S1');
        
        $this->assertequals(transitionFunc('S2', '1'), 'S2');
        
        // Test exception
        $this->expectException(Exception::class);
        transitionFunc('S2', '5');
        
    }
    
    // This will return an fa for testing
    private function getFa() 
    {
        $allStates = [
            'S0' => 0,
            'S1' => 1, 
            'S2' => 2,
        ];
        
        $initialState = 'S0';
        
        $allowableInputAlphabets = range('0','9');

        $acceptableFinalStates = ['S0', 'S1', 'S2'];

        $fa = new fa($allStates, $allowableInputAlphabets, $initialState, $acceptableFinalStates, 'transitionFunc');
        
        return $fa;
    }
    
    // Test when input string is empty
    public function testFaRunWithEmptyInput()
    {
        $fa = $this->getFa();
        // Test error
        $ret = $fa->run('');
        $this->assertEquals($ret['status'], 'error');
    }
    
    // Test for 110, S0
    public function testCase1()
    {
        $fa = $this->getFa();
        // Test error
        $ret = $fa->run('110', 'S0');
        $this->assertEquals($ret['status'], 'success');
    }
    
    
}