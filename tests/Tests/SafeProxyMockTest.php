<?php
namespace Tests;

use ngyuki\SafeProxy\SafeProxy;

/**
 * @author ngyuki
 */
class SafeProxyMockTest extends AbstractTestCase
{
    /**
     * @test
     */
    function ok_()
    {
        $proxy = $this->getMock(get_class(new SafeProxy), ['sprintf']);
        $proxy->expects(any())->method('sprintf')->will(returnValue("abc"));

        /** @var $proxy SafeProxy */
        /** @noinspection PhpUndefinedMethodInspection */
        {
            assertSame("abc", $proxy->sprintf("%s"));
            assertSame("abc", $proxy->notNull()->sprintf("%s"));
            assertSame("abc", $proxy->notEmpty()->notFalse()->sprintf("%s"));

            $this->setExpectedAssertionException("isResource");
            $proxy->isResource()->sprintf("%s");
        }
    }
}
