<?php

namespace Sc\Service\Lib\Service\Locker\Traits;

use Sc\Service\Lib\Service\Locker\Entity\Locker;
use Sc\Service\Lib\Service\Locker\Interfaces\LockerInterface;
use Sc\Service\Lib\Service\Locker\Repository\LockerRepository;

trait LockerTrait
{
    private $locker = [];

    /**
     * @return void
     */
    public function addLocker(LockerInterface $locker = null)
    {
        if (!$locker->isRegistered())
        {
            $locker->getRepository()->add($locker, $this);
        }
    }

    public function removeLocker(LockerInterface $locker = null)
    {
        $locker->getRepository()->remove($locker, $this);
    }

    /**
     * @param string $name
     *
     * @return Locker|false
     *
     * @throws \Exception
     */
    public function getLocker($name)
    {
        return new Locker($this, $name);
    }

    /**
     * @return mixed[]
     *
     * @throws \Exception
     */
    public function getAllLockers()
    {
        return (new LockerRepository())->getAll($this);
    }
}
