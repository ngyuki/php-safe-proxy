<?php
namespace Tests;

use ngyuki\SafeProxy\Safe;
use ngyuki\SafeProxy\ErrorException;

/**
 * @author ngyuki
 */
class SafeTest extends AbstractTestCase
{
    /**
     * @test
     */
    function safe_ok()
    {
        $val = Safe::call(function ($a, $b) { return $a + $b; }, 123, 456);
        assertSame(123 + 456, $val);
    }

    /**
     * @test
     */
    function safe_warning()
    {
        try
        {
            $this->setExpectedErrorException("sprintf(): Too few arguments");
            Safe::call(function () { return sprintf("%02d%03d", 1); });
        }
        catch (ErrorException $ex)
        {
            assertSame(E_WARNING, $ex->getSeverity());
            throw $ex;
        }
    }

    /**
     * @test
     */
    function safe_call_notice()
    {
        try
        {
            $this->setExpectedErrorException("Undefined variable: nothing");

            Safe::call(function () {
                /** @noinspection PhpUndefinedVariableInspection */
                return $nothing;
            });
        }
        catch (ErrorException $ex)
        {
            assertSame(E_NOTICE, $ex->getSeverity());
            throw $ex;
        }
    }

    /**
     * @test
     */
    function error_reporting_zero()
    {
        error_reporting(0);

        $val = Safe::call(function () { return sprintf("%02d%03d", 1); });
        assertFalse($val);
    }

    /**
     * @test
     */
    function error_reporting_ok()
    {
        error_reporting(E_ALL &~ E_WARNING);

        $val = Safe::call(function () { return sprintf("%02d%03d", 1); });
        assertFalse($val);
    }

    /**
     * @test
     */
    function error_reporting_ng()
    {
        error_reporting(E_ALL &~ E_NOTICE);

        $this->setExpectedErrorException();
        Safe::call(function () { return sprintf("%02d%03d", 1); });
    }

    /**
     * @test
     */
    function atMark_call()
    {
        $val = @Safe::call(function () { return sprintf("%02d%03d", 1); });
        assertFalse($val);
    }
}
