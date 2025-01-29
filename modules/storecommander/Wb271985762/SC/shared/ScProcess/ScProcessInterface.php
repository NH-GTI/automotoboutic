<?php

namespace Sc\ScProcess;

interface ScProcessInterface
{
    /**
     * @return string|array
     */
    public function getProcessMessageForIteration($iteration, $countProcessed, $method, $methodArguments);

    /**
     * @param $message
     *
     * @return string
     */
    public function getProcessMessageCompleted($message);

    /*
     * @param $method
     * @param $methodArguments
     * @return ProcessTrait
     */
//    public function run($method, $methodArguments);
}
