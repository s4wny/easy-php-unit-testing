<?php

/**
 *
 * 
 * @author Sony? aka Sawny <4morefun.net>
 */
class test
{

    static $hasRegistredShutdownFunc = false;
    static $testsFailed              = array();
    static $testsSucceded            = array();


    public function __construct()
    {
    }


    /**
     * AssertEqual
     *
     * @todo assertEqual strict
     */
    static function assertEqual()
    {
        $args           = func_get_args();
        $function       = array_shift($args);
        $exceptedResult = array_pop($args);
        $leftSideValue  = call_user_func_array($function, $args);

        
        $debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $tmp   = array('function'        => $function,
                       'args'            => $args,
                       'exceptedResult'  => $exceptedResult,
                       'testType'        => '===',
                       'line'            => $debug[0]['line'],
                       'file'            => $debug[0]['file']);


        //Assertion
        if($leftSideValue == $exceptedResult) { //Success
            self::$testsSucceded[] = $tmp;     
        }
        else { //Failed
            self::$testsFailed[] = $tmp;
        }

        unset($tmp, $debug);


        //When last test has runed, run this:
        if(!self::$hasRegistredShutdownFunc)
        {
            register_shutdown_function(function() {
                self::printTestResults();
            });

            self::$hasRegistredShutdownFunc = true;
        }
    }


    //You can't redeclare functions in php, so this wont work. And PCNTL aren't available at windows :(
    //static function watch($file, $functionToRun)
    //{
    //    $functionToRun();
    //    self::printTestResults();
    //
    //    $lastEdited = filemtime($file);
    //    
    //    while(1)
    //    {
    //        clearstatcache();
    //    
    //        if(filemtime($file) > $lastEdited)
    //        {
    //            $lastEdited = filemtime($file);
    //
    //
    //            $functionToRun();
    //            self::printTestResults();
    //
    //        }
    //    
    //        sleep(1);
    //    }
    //}





    //-----------------------------------
    // Private functions
    //-----------------------------------


    /**
     * @example invertLogicOperator("===") -> "!=="
     * @example invertLogicOperator("!=") -> "=="
     */
    private static function invertLogicOperator($operator)
    {
        switch ($operator)
        {
            case '==':  return '!=';
            case '===': return '!==';

            case '!=':  return '==';
            case '!==': return '===';

            default:
                exit("Didn't find the invert for ". $operator .". On line: ". __LINE__);
                break;
        }
    }


    /**
     * @example addTypesTo('hej') --> array('"hej"')
     * @example addTypesTo(array('16', 16, 'mu')) --> array('"16"', 16, '"mu"')
     */
    private static function addTypesTo($x)
    {
        if(is_array($x))
        {
            array_walk($x, function(&$value, &$key) {
                if(is_string($value)) {
                    $value = '"'. $value .'"';
                }
            });
        }
        elseif(is_string($x))
        {
            $x = '"'. $x .'"';
        }

        return $x;
    }


    /**
     *
     */
    private static function printTestResults()
    {
        $testsFailed   = count(self::$testsFailed);
        $testsSucceded = count(self::$testsSucceded);
        $totalTestes   = $testsFailed + $testsSucceded;

        //Some testes failed
        if($testsFailed > 0)
        {
            echo $testsFailed . " testes of ". $totalTestes ." testes failed!\n\n";

            echo "---- Log ----\n\n";


            foreach(self::$testsFailed as $the)
            {
                $testOperator   = self::invertLogicOperator($the['testType']);
                $args           = implode(",", self::addTypesTo($the['args']));
                $exceptedResult = self::addTypesTo($the['exceptedResult']);


                //echo square(4) !== "16"
                echo $the['function'] ."(". $args .") ". $testOperator ." ". $exceptedResult;

                echo ". On line: ". $the['line'] .chr(10);
            }
        }
        else
        {
            echo "All ". $totalTestes ." succeseded!\n";
        }


        echo "\n------------------------------\n\n\n";


        self::$testsSucceded = array();
        self::$testsFailed   = array();
    }
}


?>