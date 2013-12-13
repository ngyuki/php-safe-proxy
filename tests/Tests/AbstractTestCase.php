<?php
namespace Tests;

use ngyuki\SafeProxy\AssertionException;
use ngyuki\SafeProxy\ErrorException;

/**
 * @author ngyuki
 */
abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->iniSet('error_reporting', -1);
    }

    /**
     * アサーション例外を Expected する
     */
    protected function setExpectedAssertionException($msg = null)
    {
        $this->setExpectedException(get_class(new AssertionException), $msg, 0);
    }

    /**
     * PHPエラー例外を Expected する
     */
    protected function setExpectedErrorException($msg = null)
    {
        $this->setExpectedException(get_class(new ErrorException), $msg, 0);
    }
}
