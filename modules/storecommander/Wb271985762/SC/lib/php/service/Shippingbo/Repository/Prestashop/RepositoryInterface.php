<?php

namespace Sc\Service\Shippingbo\Repository\Prestashop;

use DbQuery;

interface RepositoryInterface
{
    public function addPsErrorParts(\DbQuery $dbQuery);

    /**
     * @desc : truncate sbo product buffer table
     *
     * @return $this
     */
    public function clear();

    /**
     * @throws \Exception
     */
    public function getLastSyncedDate();

    /**
     * @desc : insert or update sbo additional references data in buffer table
     *
     * @return false|\PDOStatement
     */
    public function setBufferStatement();

    /**
     * @return DbQuery
     */
    public function getMissingSboQuery($full = false, $page = false);

    /**
     * @return DbQuery
     */
    public function getMissingPsQuery($page = false);

    /**
     * @return string[]
     */
    public function getExportColumns();
}
