<?php
namespace ngyuki\SafeProxy;

/**
 * SafeException
 *
 * @author ngyuki
 */
interface SafeException
{
    public function getMessage();
    public function getPrevious();
    public function getCode();
    public function getFile();
    public function getLine();
    public function getTrace();
    public function getTraceAsString();
}
