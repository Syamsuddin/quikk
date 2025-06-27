<?php
namespace App\Services;

use PDO;

class ModuleRegistrar
{
    private PDO $db;
    private string $moduleName;
    private array $config;

    public function __construct(PDO $db, string $moduleName, array $config)
    {
        $this->db = $db;
        $this->moduleName = $moduleName;
        $this->config = $config;
    }

    public function register(): void
    {
        if (!$this->isRegistered()) {
            $this->importSQL();
            $this->insertMenus();
            $this->insertAccessRights();
            $this->markRegistered();
        }
    }

    private function importSQL(): void
    {
        $sqlFile = $this->config['module_sql_path'] . $this->moduleName . '.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $this->db->exec($sql);
        }
    }

    private function insertMenus(): void {}
    private function insertAccessRights(): void {}

    private function isRegistered(): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM " . $this->config['modules_table'] . " WHERE name = :name");
        $stmt->execute([':name' => $this->moduleName]);
        return $stmt->fetchColumn() > 0;
    }

    private function markRegistered(): void
    {
        $stmt = $this->db->prepare("INSERT INTO " . $this->config['modules_table'] . " (name, installed_at) VALUES (:name, NOW())");
        $stmt->execute([':name' => $this->moduleName]);
    }
}
