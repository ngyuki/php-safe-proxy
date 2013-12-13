<?php
namespace ngyuki\SafeProxy;

/**
 * SafeProxyAssertion
 *
 * @author ngyuki
 */
class SafeProxyAssertion extends AbstractAssertion
{
    /**
     * @var SafeProxy
     */
    private $_proxy;

    /**
     * @var array
     */
    private $_assertions = array();

    /**
     * @param SafeProxy $proxy
     * @param callable  $callback
     */
    public function __construct(SafeProxy $proxy, $callback)
    {
        $this->_proxy = $proxy;
        $this->_assertions[] = $callback;
    }

    /**
     * @param mixed $val
     * @return mixed
     */
    private function _assertion($val, $name)
    {
        foreach ($this->_assertions as $assertion)
        {
            $assertion($val, $name);
        }

        return $val;
    }

    /**
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call($name, array $args)
    {
        // マジックメソッドを直接呼ぶとモックのメソッドが呼ばれないため通常のメソッド呼び出しにする
        $val = call_user_func_array(array($this->_proxy, $name), $args);
        return $this->_assertion($val, $name);
    }

    protected function _chain($callback)
    {
        $obj = clone $this;
        $obj->_assertions[] = $callback;
        return $obj;
    }
}
