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

    static $currentTest              = array();     



    /**
     * Alows the user to write anything they like to write:
     *      test::is(x, y)
     *      test::does(x, y)
     *      test::whatEverYouWantToTypeHere(x, y, z, ...)
     */
    public static function __callStatic($name, $args)
    {
        //Run the function to test
        $function                              = array_shift($args);
        static::$currentTest['functionResult'] = call_user_func_array($function, $args);


        //Save some usefull debuging information
        $debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        static::$currentTest['info'] = array('function'        => $function,
                                             'args'            => $args,
                                             'line'            => $debug[0]['line'],
                                             'file'            => $debug[0]['file']);


        //When the last test has run, print the result from all the testes
        if(!self::$hasRegistredShutdownFunc)
        {
            register_shutdown_function(function() {
                self::printTestResults();
            });

            self::$hasRegistredShutdownFunc = true;
        }


        return new static;
    }


    /**
     * Determine which comparing functions to call / use.
     *
     * test::is(x, y)->equalTo(x)
     * test::is(x, y)->notEqualTo(x)
     * test::is(x, y)->notEqualToStrict(x)
     *
     * Note: If you add something here, also add it to static::invertComparisonOperator();
     */
    public function __call($name, $exceptedResult)
    {
        $exceptedResult             = $exceptedResult[0];
        $currTest                   =& static::$currentTest['info']; //Less to write later. :)
        $currTest['exceptedResult'] = $exceptedResult;


        switch ($name)
        {
            //==
            case 'equalTo':
            case 'assertEqual':
                $currTest['testType'] = '==';
                static::assertWith($exceptedResult, $currTest['testType']);
            break;
            

            //===
            case 'strictEqualTo':
            case 'assertStrictEqual':
                $currTest['testType'] = '===';
                static::assertWith($exceptedResult, $currTest['testType']);
            break;


            //!=
            case 'notEqualTo':
            case 'assertNotEqualTo':
                $currTest['testType'] = '!=';
                static::assertWith($exceptedResult, $currTest['testType']);
            break;


            //!==
            case 'notStrictEqualTo':
            case 'assertNotStrictEqualTo':
                $currTest['testType'] = '!==';
                static::assertWith($exceptedResult, $currTest['testType']);
            break;


            //<
            case 'lessThen':
                $currTest['testType'] = '<';
                static::assertWith($exceptedResult, $currTest['testType']);
            break;


            //>
            case 'biggerThen':
                $currTest['testType'] = '>';
                static::assertWith($exceptedResult, $currTest['testType']);
            break;


            //<=
            case 'lessThenOrEqualTo':
                $currTest['testType'] = '<=';
                static::assertWith($exceptedResult, $currTest['testType']);
            break;


            //>=
            case 'biggerThenOrEqualTo':
                $currTest['testType'] = '>=';
                static::assertWith($exceptedResult, $currTest['testType']);
            break;


            //Operator not supported
            default:
                exit("Couln't find the comparing function '". $name ."'. On line ". __LINE__ ." in the file '".  __FILE__ ."'\n Btw, have a nice day!\n\n");       
            break;
        }
    }




    //-----------------------------------
    // Comparing functions
    //-----------------------------------


    private static function assertWith($exceptedResult, $comparisonOperator)
    {
        //To keep the code DRY I use `assert()` here.
        assert_options(ASSERT_ACTIVE,     true);
        assert_options(ASSERT_WARNING,    false);
        assert_options(ASSERT_BAIL,       false);
        assert_options(ASSERT_QUIET_EVAL, true);

        //Necassary for strict comparing.
        $exceptedResult = static::quoteString($exceptedResult);

        if(assert(static::$currentTest['functionResult'] . $comparisonOperator . $exceptedResult))
        {
            self::$testsSucceded[] = static::$currentTest['info'];
        }
        else
        {
            self::$testsFailed[] = static::$currentTest['info'];
        }
    }





    //You can't redeclare functions in php, so this wont work. And PCNTL aren't available at windows :(
    //
    // EDIT: Wait.. I can get the args used to start the file, and then exec them.
    //
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
    // Helper functions
    //-----------------------------------


    /**
     * @example invertComparisonOperator("===") -> "!=="
     * @example invertComparisonOperator("!=") -> "=="
     */
    private static function invertComparisonOperator($operator)
    {
        switch ($operator)
        {
            case '==':  return '!=';
            case '===': return '!==';

            case '!=':  return '==';
            case '!==': return '===';

            case '>': return '<=';
            case '<': return '>=';

            case '>=': return '<'; 
            case '<=': return '>';


            default:
                exit("Didn't find the invert for ". $operator .". On line: ". __LINE__);
                break;
        }
    }


    /**
     * @example quoteString('hej') --> array('"hej"')
     * @example quoteString(array('16', 16, 'mu')) --> array('"16"', 16, '"mu"')
     */
    private static function quoteString($x)
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
                $testOperator   = self::invertComparisonOperator($the['testType']);
                $args           = implode(",", self::quoteString($the['args']));
                $exceptedResult = self::quoteString($the['exceptedResult']);


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