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


        if($leftSideValue === $exceptedResult) //Success
        {
            self::$testsSucceded[] = array('function'       => $function,
                                           'args'           => $args,
                                           'exceptedResult' => $exceptedResult);
        }
        else //Failed
        {
            self::$testsFailed[] = array('function'       => $function,
                                         'args'           => $args,
                                         'exceptedResult' => $exceptedResult);
        }




        if(!self::$hasRegistredShutdownFunc) {
            register_shutdown_function(function() {

                $testsFailed   = count(self::$testsFailed);
                $testsSucceded = count(self::$testsSucceded);
                $totalTestes   = $testsFailed + $testsSucceded;

                if($testsFailed > 0)
                {
                    echo $testsFailed . " testes of ". $totalTestes ." testes failed!\n\n";

                    echo "--Log--\n";

                    //Loopa ut vad som failade
                    //square(4) !== 8. (on line XXX in file YYY)
                    //square(4) !== "16". 
                    //Note !== istället för ===
                }
                else
                {
                    echo "All ". $totalTestes ." succeseded!\n";
                }
            });


            self::$hasRegistredShutdownFunc = true;
        }
    }


    static function set($key, $value)
    {

    }



    static function watch($file)
    {
        //har $file uppdaterats?
            //include(__SELF__) (rekursiv loop)
        //
            //Vänta i 1 sekund, kolla igen
    }
}


?>