<?php
namespace App\Database;

class SeederSentinel
{
    private string $lockFile;

    public function __construct()
    {
        $this->lockFile = __DIR__ . '/seeder.lock';
    }

    public function hasRun(): bool
    {
        return file_exists($this->lockFile);
    }

    public function markAsRun(): void
    {
        file_put_contents($this->lockFile, date('Y-m-d H:i:s'));
    }
}
