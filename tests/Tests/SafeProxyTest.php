<?php
namespace Tests;

use ngyuki\SafeProxy\Safe;
use ngyuki\SafeProxy\ErrorException;

/**
 * @author ngyuki
 */
class SafeProxyTest extends AbstractTestCase
{
    /**
     * @test
     */
    function proxy_ok()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $val = Safe::proxy()->sprintf("%03d-%04d", 3, 4);
        assertSame("003-0004", $val);

        /** @noinspection PhpUndefinedMethodInspection */
        $val = Safe::proxy()->notNull()->notEmpty()->sprintf("%03d-%04d", 3, 4);
        assertSame("003-0004", $val);
    }

    /**
     * @test
     */
    function proxy_warning()
    {
        try
        {
            /** @noinspection PhpUndefinedMethodInspection */
            Safe::proxy()->sprintf("%s");
            $this->fail();
        }
        catch (ErrorException $ex)
        {
            assertContains("sprintf(): Too few arguments", $ex->getMessage());
            assertSame(E_WARNING, $ex->getSeverity());
        }
    }

    /**
     * @test
     */
    function assertion_ok()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $res = Safe::proxy()->notEmpty()->notFalse()->notNull()->isResource()->fopen('php://output', 'w');
        fclose($res);
    }

    /**
     * @test
     */
    function assertion_notEmpty_ng()
    {
        $this->setExpectedAssertionException("sprintf() assertion notEmpty failed.");

        /** @noinspection PhpUndefinedMethodInspection */
        Safe::proxy()->notEmpty()->sprintf("");
    }

    /**
     * @test
     */
    function assertion_notFalse_ng()
    {
        $this->setExpectedAssertionException("is_dir() assertion notFalse failed.");

        /** @noinspection PhpUndefinedMethodInspection */
        Safe::proxy()->notFalse()->is_dir(__FILE__);
    }

    /**
     * @test
     */
    function assertion_notNull_ng()
    {
        $this->setExpectedAssertionException("usleep() assertion notNull failed.");

        /** @noinspection PhpUndefinedMethodInspection */
        Safe::proxy()->notNull()->usleep(1);
    }

    /**
     * @test
     */
    function assertion_isResource_ng()
    {
        $this->setExpectedAssertionException("intval() assertion isResource failed.");

        /** @noinspection PhpUndefinedMethodInspection */
        Safe::proxy()->isResource()->intval(123);
    }
}
