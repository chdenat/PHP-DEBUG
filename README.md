# PHP-DEBUG

It offers two methods : 

Debug::log($var) : useful to track variable content in the dedicated error_log file.
For WordPress it logs into **debug.log** if allowed.

Calling it using 

    Debug::log($my_var,args[] //optional);
gives

    [26-Feb-2020 14:17:53 UTC] -----------------------------------------
    $my_var
    --------------------------------------------------------------------
    Array
    (
        [0] => 5
        [1] => 6
    )
    --------------------------------------------------------------------

Debug::echo($var, args[] //optional) : used to display variable content on screen.



