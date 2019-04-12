<?php
/**
 * To run this, php testFa.php
 */
require_once 'fa.php';

/**
 * Test fa for given input and initial state. 
 * 
 * @param string $input
 * @param string $initialState
 * @param boolean $debug
 * 
 * @return string
 */
function testFa($input, $initialState, $debug = false) {
    
    $allStates = [
        'S0' => 0,
        'S1' => 1, 
        'S2' => 2,
    ];

    $allowableInputAlphabets = range('0','9');

    $acceptableFinalStates = ['S0', 'S1', 'S2'];

    $fa = new fa($allStates, $allowableInputAlphabets, $initialState, $acceptableFinalStates, 'transitionFunc');
    $fa->setDebug($debug);

    $result = $fa->run($input);
    
    if(!empty($result['status']) && $result['status'] == 'success') {
        $ret = "output for state {$result['state']} = " . $result['value'];
    } else {
        $ret = $result['message'];
    }
    
    return $ret;
}

$result = testFa('110', 'S0');
echo $result;
echo PHP_EOL;

$result = testFa('1010', 'S0');
echo $result;
echo PHP_EOL;
