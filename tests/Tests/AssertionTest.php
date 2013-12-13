<?php
namespace Tests;

use ngyuki\SafeProxy\Assertion;

/**
 * @author ngyuki
 */
class AssertionTest extends AbstractTestCase
{
    /**
     * @test
     * @dataProvider data_notEmpty_ng
     */
    function notEmpty_ng($value)
    {
        $this->setExpectedAssertionException("hoge() assertion notEmpty failed.");
        Assertion::notEmpty($value, "hoge");
    }

    /**
     * @test
     * @dataProvider data_notEmpty_ok
     */
    function notEmpty_ok($value)
    {
        Assertion::notEmpty($value, "hoge");
    }

    function data_notEmpty_ng()
    {
        return array(
            array(null),
            array(false),
            array(0),
            array(""),
            array("0"),
            array(array()),
        );
    }

    function data_notEmpty_ok()
    {
        return array(
            array(true),
            array(+1),
            array(-1),
            array("00"),
            array("1"),
            array("false"),
            array(array(0)),
            array(STDIN),
            array(new \stdClass()),
        );
    }

    /**
     * @test
     * @dataProvider data_notFalse_ng
     */
    function notFalse_ng($value)
    {
        $this->setExpectedAssertionException("hoge() assertion notFalse failed.");
        Assertion::notFalse($value, "hoge");
    }

    /**
     * @test
     * @dataProvider data_notFalse_ok
     */
    function notFalse_ok($value)
    {
        Assertion::notFalse($value);
    }

    function data_notFalse_ng()
    {
        return array(
            array(false),
        );
    }

    function data_notFalse_ok()
    {
        return array(
            array(null),
            array(true),
            array(0),
            array(""),
            array("0"),
            array(array()),
            array(STDIN),
            array(new \stdClass()),
        );
    }

    /**
     * @test
     * @dataProvider data_notNull_ng
     */
    function notNull_ng($value)
    {
        $this->setExpectedAssertionException("hoge() assertion notNull failed.");
        Assertion::notNull($value, "hoge");
    }

    /**
     * @test
     * @dataProvider data_notNull_ok
     */
    function notNull_ok($value)
    {
        Assertion::notNull($value);
    }

    function data_notNull_ng()
    {
        return array(
            array(null),
        );
    }

    function data_notNull_ok()
    {
        return array(
            array(false),
            array(true),
            array(0),
            array(""),
            array("0"),
            array(array()),
            array(STDIN),
            array(new \stdClass()),
        );
    }

    /**
     * @test
     * @dataProvider data_isResource_ng
     */
    function isResource_ng($value)
    {
        $this->setExpectedAssertionException("assertion isResource failed.");
        Assertion::isResource($value);
    }

    /**
     * @test
     * @dataProvider data_isResource_ok
     */
    function isResource_ok($value)
    {
        Assertion::isResource($value);
    }

    function data_isResource_ng()
    {
        return array(
            array(null),
            array(false),
            array(0),
            array(""),
            array("0"),
            array(array()),
            array(new \stdClass()),
        );
    }

    function data_isResource_ok()
    {
        return array(
            array(STDIN),
        );
    }
}
