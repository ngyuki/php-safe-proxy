<?php
namespace ngyuki\SafeProxy;

/**
 * メソッドチェインのためのメソッド定義
 *
 * @author ngyuki
 */
abstract class AbstractAssertion
{
    /**
     * @param callable $callback
     * @return AbstractAssertion
     */
    abstract protected function _chain($callback);

    /**
     * @return AbstractAssertion
     */
    public function notEmpty()
    {
        return $this->_chain(function ($val, $name) { Assertion::notEmpty($val, $name); });
    }

    /**
     * @return AbstractAssertion
     */
    public function notFalse()
    {
        return $this->_chain(function ($val, $name) { Assertion::notFalse($val, $name); });
    }

    /**
     * @return AbstractAssertion
     */
    public function notNull()
    {
        return $this->_chain(function ($val, $name) { Assertion::notNull($val, $name); });
    }

    /**
     * @return AbstractAssertion
     */
    public function isResource()
    {
        return $this->_chain(function ($val, $name) { Assertion::isResource($val, $name); });
    }
}
