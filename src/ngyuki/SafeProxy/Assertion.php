<?php
namespace ngyuki\SafeProxy;

/**
 * シンプルなアサーション
 *
 * @author ngyuki
 */
class Assertion
{
    /**
     * @param mixed $val
     * @throws AssertionException
     */
    public static function notEmpty($val, $name = "unknown")
    {
        if (empty($val))
        {
            throw new AssertionException("$name() assertion notEmpty failed.");
        }
    }

    /**
     * @param mixed $val
     * @param string $name
     * @throws AssertionException
     */
    public static function notFalse($val, $name = "unknown")
    {
        if ($val === false)
        {
            throw new AssertionException("$name() assertion notFalse failed.");
        }
    }

    /**
     * @param mixed $val
     * @param string $name
     * @throws AssertionException
     */
    public static function notNull($val, $name = "unknown")
    {
        if ($val === null)
        {
            throw new AssertionException("$name() assertion notNull failed.");
        }
    }

    /**
     * @param mixed $val
     * @param string $name
     * @throws AssertionException
     */
    public static function isResource($val, $name = "unknown")
    {
        if (!is_resource($val))
        {
            throw new AssertionException("$name() assertion isResource failed.");
        }
    }
}
