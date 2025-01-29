<?php

namespace Sc\Service\Lib\Service\Locker\Interfaces;

interface LockerInterface
{
    /**
     * @return bool|\mysqli_result|\PDOStatement
     *
     * @throws \Exception
     */
    public function lock();

    /**
     * @return bool|\mysqli_result|\PDOStatement
     */
    public function release();

    /**
     * @return string
     */
    public function getLockerName();

    /**
     * @return bool
     */
    public function isRegistered();
}
