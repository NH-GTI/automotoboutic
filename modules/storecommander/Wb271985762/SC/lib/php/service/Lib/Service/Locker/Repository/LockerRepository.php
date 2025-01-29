<?php

namespace Sc\Service\Lib\Service\Locker\Repository;

use DateTimeImmutable;
use DateTimeZone;
use Db;
use DbQuery;
use PDO;
use Sc\Service\Lib\Service\Locker\Entity\Locker;
use Sc\Service\Model\ServiceLockerModel;
use Sc\Service\Service;

class LockerRepository
{
    /**
     * @return bool|\mysqli_result|\PDOStatement
     */
    public function update(Locker $serviceLocker)
    {
        $pdo = Db::getInstance()->getLink();

        return $pdo->query('UPDATE '._DB_PREFIX_.$this->getTableName().' SET 
        status=\''.$serviceLocker->getStatus().'\', 
        detail=\''.json_encode($serviceLocker->getDetail()).'\'  
        WHERE id_service_locker=\''.$serviceLocker->getId().'\'');
    }

    /**
     * @return mixed
     */
    private function getTableName()
    {
        return (new ServiceLockerModel())->getTableName();
    }

    /**
     * @return DbQuery
     */
    public function getAllQuery()
    {
        $dbQuery = new DbQuery();
        $dbQuery
            ->select('*')
            ->from($this->getTableName(), 'service_locker')
            ->where('id_service = :id_service')
        ;

        return $dbQuery;
    }

    /**
     * @return array<int,mixed>
     *
     * @throws \Exception
     */
    public function getAll(Service $service)
    {
        $pdo = Db::getInstance()->getLink();
        $stmt = $pdo->prepare($this->getAllQuery());
        $stmt->execute([
            ':id_service' => $service->getServiceId(),
        ]);
        $lockers = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $lockerCollection = [];
        foreach ($lockers as $locker)
        {
            $lockerCollection[] = new Locker($service, $locker['locker_name']);
        }

        return $lockerCollection;
    }

    /**
     * @return DbQuery
     */
    public function getOneByIdQuery()
    {
        $dbQuery = $this->getAllQuery();
        $dbQuery->where('service_locker.locker_name = :id');

        return $dbQuery;
    }

    /**
     * @return bool
     *
     * @throws \Exception
     */
    public function add(Locker $locker, Service $service)
    {
        $now = new DateTimeImmutable('now', new DateTimeZone($locker->getTimeZone()));
        $sql = 'INSERT INTO '._DB_PREFIX_.$this->getTableName().' (`id_service`, `locker_name`, `locker_group`, `created_at`) VALUES(:id_service,:locker_name,:locker_group, :created_at)';
        $pdo = Db::getInstance()->getLink();
        $stmt = $pdo->prepare($sql);
        $params = [
            ':id_service' => $service->getServiceId(),
            ':locker_name' => $locker->getLockerName(),
            ':locker_group' => $locker->getLockerGroup(),
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ];

        return $stmt->execute($params);
    }

    /**
     * @param Service $service
     *
     * @return bool
     */
    public function remove(Locker $locker, $service)
    {
        $sql = 'DELETE FROM '._DB_PREFIX_.$this->getTableName().' WHERE `id_service` = :id_service AND `locker_name` = :locker_name';
        $pdo = Db::getInstance()->getLink();
        $stmt = $pdo->prepare($sql);

        $params = [
            ':id_service' => $service->getServiceId(),
            ':locker_name' => $locker->getLockerName(),
        ];

        return $stmt->execute($params);
    }
}
