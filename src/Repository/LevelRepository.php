<?php

namespace App\Repository;

use SimpleXMLElement;

class LevelRepository
{
    public function __construct(
        private readonly string $levelDir
    ) {
    }

    public function loadLevel(string $level): ?SimpleXMLElement
    {
        $xml = @file_get_contents($this->levelDir . '/' . $level. '/AKEVcompound.xml');

        if ($xml) {
            return new SimpleXMLElement($xml);
        }
        return null;
    }
}