<?php

namespace Sc\Service\Lib\Traits;

use Db;
use Exception;
use PDO;
use Sc\Service\Model\ServiceModelInterface;

trait EntityHydratableTrait
{
    /**
     * @param int|string $entityId
     * @param string     $primaryKey
     *
     * @return void
     *
     * @throws Exception
     */
    public function hydrateObject($entityId, $primaryKey = 'id')
    {
        $stmt = Db::getInstance()->getLink()->prepare($this->getRepository()->getOneByIdQuery());
        $params = [
            ':id' => $entityId,
        ];
        if ((bool) strpos($stmt->queryString, ':id_service') && method_exists($this, 'getService'))
        {
            $params[':id_service'] = (int) $this->getService()->getServiceId();
        }
        $stmt->execute($params);

        if (!$stmt->rowCount())
        {
            return;
        }

        foreach ($stmt->fetch(PDO::FETCH_ASSOC) as $property => $value)
        {
            $property = lcfirst(str_replace('_', '', ucwords($property, '_')));
            $property = ($property === $primaryKey) ? $primaryKey : $property;
            /* @var ServiceModelInterface $repositoryClass */
            if (!property_exists($this, $property))
            {
                throw new Exception('Missing property '.$property.' in '.get_class($this));
            }
            $this->$property = $value;
        }
    }
}
