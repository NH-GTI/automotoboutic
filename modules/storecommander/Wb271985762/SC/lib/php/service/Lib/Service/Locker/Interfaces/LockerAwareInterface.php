<?php

namespace Sc\Service\Lib\Service\Locker\Interfaces;

use Sc\Service\Lib\Service\Locker\Entity\Locker;

interface LockerAwareInterface
{
    /**
     * @return void
     */
    public function addLocker(LockerInterface $locker = null);

    /**
     * @param string|null $name
     *
     * @return array<int,mixed>|Locker
     *
     * @throws \Exception
     */
    public function getLocker($name);
}
