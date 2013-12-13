<?php
namespace ngyuki\SafeProxy;

/**
 * Safe
 *
 * @author ngyuki
 */
class Safe
{
    /**
     * @return SafeProxy
     */
    public static function proxy()
    {
        return new SafeProxy();
    }

    /**
     * @param  callable $callback
     * @return mixed
     */
    public static function call($callback)
    {
        $args = func_get_args();
        $callback = array_shift($args);

        return self::callArgs($callback, $args);
    }

    /**
     * @param  callable $callback
     * @param  array $args
     * @return mixed
     */
    public static function callArgs($callback, array $args = array())
    {
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            if (error_reporting() & $errno)
            {
                throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
            }
            return false;
        });

        try
        {
            $val = call_user_func_array($callback, $args);
            restore_error_handler();
        }
        catch (\Exception $ex)
        {
            restore_error_handler();
            throw $ex;
        }

        return $val;
    }
}
