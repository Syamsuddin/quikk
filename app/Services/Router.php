<?php
namespace App\Services;

use PDO;

class Router
{
    private PDO $db;
    private string $token;
    private array $config;

    public function __construct(PDO $db, string $token, array $config)
    {
        $this->db = $db;
        $this->token = $token;
        $this->config = $config;
    }

    public function resolve(): string
    {
        $stmt = $this->db->prepare("SELECT url_path FROM route_tokens WHERE token = :token");
        $stmt->execute([':token' => $this->token]);
        $path = $stmt->fetchColumn();

        if ($path && file_exists($this->config['pages_path'] . "$path.php")) {
            return $this->config['pages_path'] . "$path.php";
        }

        return $this->config['fallback_404'];
    }

    public function hasAccess(string $userLevel): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM access_rights ar
            JOIN menus m ON ar.menu_code = m.menu_code
            WHERE ar.level = :level AND m.url_token = :token
        ");
        $stmt->execute([
            ':level' => $userLevel,
            ':token' => $this->token
        ]);
        return $stmt->fetchColumn() > 0;
    }
}
