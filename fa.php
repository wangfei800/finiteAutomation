<?php

/* 
 * Finite automation machine, 5-tuple. 
 * 
 */
class fa 
{
    protected $debug = false;
    
    protected $allStates, $allowableInputAlphabets, $initialState, $acceptableFinalStates, $transitionFunc;
    
    public function setDebug($debog) {
        $this->debug = (bool)$debog;
    }
    
    /**
     * 
     * @param array $allStates
     * @param array $allowableInputAlphabets
     * @param array $initialState
     * @param array $acceptableFinalStates
     * @param callback $transitionFunc
     */
    public function __construct($allStates, $allowableInputAlphabets, $initialState, 
            $acceptableFinalStates, $transitionFunc) 
    {
        $this->allStates = $allStates;
        $this->allowableInputAlphabets = $allowableInputAlphabets;
        $this->initialState = $initialState;
        $this->acceptableFinalStates = $acceptableFinalStates;
        
        if (!is_callable($transitionFunc)) {
            die("transitionFunc is not callable");
        }
        
        $this->transitionFunc = $transitionFunc;
        
    }
    
    /**
     * Run the finite automation machine with given input.
     * 
     * @param string $inputString
     * @return array
     * @throws Exception
     */
    public function run($inputString)
    {
        try{
            if (empty($inputString)) {
                throw new Exception('Input cannot be empty');
            }
            
            $inputArray = str_split($inputString);
            
            $currentState = $this->initialState;

            foreach($inputArray as $index => $currentVar) {
                if ($this->debug) {
                    echo "currentState: $currentState, current value: $currentVar" . PHP_EOL . '<br/>';
                }
                
                // Check if the var is valid
                if (!in_array($currentVar, $this->allowableInputAlphabets)) {
                    throw new Exception("Index: $index, '$currentVar' is not valid.");
                }

                $nextState = call_user_func($this->transitionFunc, $currentState, $currentVar);
                
                $currentState = $nextState;
            }
            
            // Finish loops, check if states is final/acceptable
            if (!in_array($currentState, $this->acceptableFinalStates)) {
                return [
                    'status' => 'error',
                    'message' => 'Note: this will never happen with our above machine as all state can be final state',
                ];
            } else {
                return [
                    'status' => 'success',
                    'state' => $currentState,
                    'value' => $this->allStates[$currentState],
                ];
            }
            
        } catch (Exception $ex) {
            return [
                    'status' => 'error',
                    'message' => $ex->getMessage()
                ];
        }
    }
}

/**
 * Transition function, used for states transition. 
 * 
 * @staticvar array $transitionMatrix
 * @param string $state
 * @param string $var
 * 
 * @return string new state
 * @throws Exception
 */
function transitionFunc ($state, $var) {
    static $transitionMatrix = [
        'S0' => [
            0 => 'S0',
            1 => 'S1',
        ],
        'S1' => [
            0 => 'S2',
            1 => 'S0',
        ],
        'S2' => [
            0 => 'S1',
            1 => 'S2',
        ],

    ];

    if (!isset($transitionMatrix[$state][$var])) {
        throw new Exception("Cannot transition from state: $state on: $var");
    } else {
        return $transitionMatrix[$state][$var];
    }

};



