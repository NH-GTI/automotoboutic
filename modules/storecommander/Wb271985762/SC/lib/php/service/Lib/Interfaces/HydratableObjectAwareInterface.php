<?php

namespace Sc\Service\Lib\Interfaces;

use Exception;

interface HydratableObjectAwareInterface
{
    /**
     * @return mixed
     */
    public function getRepository();

    /**
     * @param (int)  $id
     * @param string $primaryKey
     *
     * @return void
     *
     * @throws Exception
     */
    public function hydrateObject($id, $primaryKey = 'id');
}
