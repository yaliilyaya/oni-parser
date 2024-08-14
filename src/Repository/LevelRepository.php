<?php

namespace App\Repository;

use SimpleXMLElement;
use Symfony\Component\Filesystem\Filesystem;

class LevelRepository
{
    public function __construct(
        private readonly string $levelDir,
        private readonly string $unloadingDir,
        private readonly Filesystem $filesystem,
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

    public function saveSource(string $level, string $type, array $data)
    {
        $this->filesystem->mkdir($this->unloadingDir . '/' . $level );

        file_put_contents($this->unloadingDir . '/' . $level . '/' . $type . '.json', json_encode($data));
    }
}