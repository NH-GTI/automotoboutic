<?php

namespace Sc\Service\Lib\Service\Locker\Entity;

use DateTimeImmutable;
use DateTimeZone;
use Employee;
use Sc\Service\Lib\Interfaces\HydratableObjectAwareInterface;
use Sc\Service\Lib\Service\Locker\Interfaces\LockerInterface;
use Sc\Service\Lib\Service\Locker\Repository\LockerRepository;
use Sc\Service\Lib\Traits\EntityHydratableTrait;
use Sc\Service\Service;

class Locker implements LockerInterface, HydratableObjectAwareInterface
{
    use EntityHydratableTrait;

    const DEFAULT_LOCKER_GROUP = 'default';
    const STATUS_LOCKED = 'locked';
    const STATUS_RELEASED = 'released';
    /** @var int */
    private $idServiceLocker = null;
    /** @var int */
    private $idService = null;
    /** @var string */
    private $lockerName = null;
    /** @var string */
    private $status = null;
    /** @var string */
    private $lockerGroup = null;
    /** @var string */
    private $detail = '';
    /** @var string */
    private $timeZone = 'UTC';
    /** @var DateTimeImmutable|null */
    private $createdAt = null;
    /**
     * @var mixed
     */
    private $service;

    /**
     * @param int|null $lockerId
     *
     * @throws \Exception
     */
    public function __construct(Service $service, $lockerId = null)
    {
        $this->setService($service);
        if ($lockerId)
        {
            $this->hydrateObject($lockerId, 'locker_name');
        }
    }

    /**
     * @return bool|\mysqli_result|\PDOStatement
     *
     * @throws \Exception
     */
    public function lock()
    {
        $this->getService()->getLogger()->debug('[PROCESS LOCKER] '.$this->getLockerName().' locked');
        $now = new DateTimeImmutable('now', new DateTimeZone($this->getTimeZone()));

        return $this
            ->setStatus(self::STATUS_LOCKED)
            ->setDetail([
                'started_at' => $now->format('Y-m-d H:i:s'),
                'user_id' => \SC_Agent::getInstance()->id_employee,
            ])
            ->save();
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Service $service
     *
     * @return Locker;
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return string
     */
    public function getLockerName()
    {
        return $this->lockerName;
    }

    /**
     * @param string $lockerName
     *
     * @return Locker
     */
    public function setLockerName($lockerName)
    {
        $this->lockerName = $lockerName;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * @param string $timeZone
     *
     * @return Locker
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    /**
     * @return bool|\mysqli_result|\PDOStatement
     */
    public function save()
    {
        return $this->getRepository()->update($this);
    }

    public function getRepository()
    {
        return new LockerRepository();
    }

    /**
     * @return bool|\mysqli_result|\PDOStatement
     */
    public function release()
    {
        $this->getService()->getLogger()->debug('[PROCESS LOCKER] '.$this->getLockerName().' released');

        return $this
            ->setStatus(self::STATUS_RELEASED)
            ->setDetail([])
            ->save();
    }

    /**
     * @return string
     */
    public function getLockerGroup()
    {
        if (!$this->lockerGroup)
        {
            $this->lockerGroup = self::DEFAULT_LOCKER_GROUP;
        }

        return $this->lockerGroup;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return Locker
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param int $slowProcessTime: in seconds -> switches 'isToSlow' output to true if greater
     *
     * @return array<string, mixed>
     *
     * @throws \Exception
     */
    public function getRunningProcessInformation($slowProcessTime = 30)
    {
        $message = 'Process started on %s by %s is already running';
        $info = $this->getDetail();
        $params = ['undefined', 'undefined'];
        $ownerName = null;
        $isToSlow = false;
        $isOwner = false;
        if ($info)
        {
            foreach ($info as $key => $value)
            {
                switch ($key){
                    case 'started_at':
                        $startedAt = (new DateTimeImmutable())->createFromFormat('Y-m-d H:i:s', $value, new DateTimeZone($this->getTimeZone()));
                        $value = $this->getService()->getLocaleDate($startedAt, 'dd/MM/yyyy H:mm:ss');
                        $now = new DateTimeImmutable('now', new DateTimeZone($this->getTimeZone()));
                        if ($startedAt && (($now->getTimestamp() - $startedAt->getTimestamp()) > $slowProcessTime))
                        {
                            $isToSlow = true;
                        }
                        break;
                    case 'user_id':
                        $isOwner = (\SC_Agent::getInstance()->id_employee === $value);
                        $employee = new Employee((int) $value);
                        $value = $ownerName = $employee->firstname.' '.$employee->lastname;
                        break;
                }
                $info[$key] = $value;
            }
            $params = array_values($info);
        }

        return [
            'canKill' => $isOwner && $isToSlow,
            'ownerName' => $ownerName,
            'message' => _l($message, null, $params),
        ];
    }

    /**
     * @return array<string,mixed>
     */
    public function getDetail()
    {
        return json_decode($this->detail, true);
    }

    /**
     * @param array<string,mixed> $detail
     *
     * @return Locker
     */
    public function setDetail($detail = [])
    {
        $this->detail = json_encode($detail) ?: '';

        return $this;
    }

    /**
     * @return bool
     */
    public function isRegistered()
    {
        return (bool) $this->getId();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->getIdServiceLocker();
    }

    /**
     * @return int|null
     */
    public function getIdServiceLocker()
    {
        return $this->idServiceLocker;
    }

    /**
     * @return int|null
     */
    public function getIdService()
    {
        return $this->idService;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
