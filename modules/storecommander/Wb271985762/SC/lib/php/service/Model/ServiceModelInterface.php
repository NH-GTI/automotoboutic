<?php

namespace Sc\Service\Model;

interface ServiceModelInterface
{
    public static function getDefinition();

    public static function createTablesIfNeeded();

    public static function migrateDb($currentVersion, $targetVersion);
}
