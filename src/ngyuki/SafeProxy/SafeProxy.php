<?php
namespace ngyuki\SafeProxy;

/**
 * SafeProxy
 *
 * @author ngyuki
 */
class SafeProxy extends AbstractAssertion
{
    /**
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call($name, array $args)
    {
        // グローバル関数の呼び出し
        return Safe::callArgs($name, $args);
    }

    protected function _chain($callback)
    {
        return new SafeProxyAssertion($this, $callback);
    }
}
