<?php
namespace App\Services;

use PDO;

class MenuManager
{
    private PDO $db;
    private string $userLevel;

    public function __construct(PDO $db, string $userLevel)
    {
        $this->db = $db;
        $this->userLevel = $userLevel;
    }

    public function getMenu(): array
    {
        $stmt = $this->db->prepare("
            SELECT m.menu_name, t.url_path
            FROM menus m
            JOIN access_rights a ON m.menu_code = a.menu_code
            JOIN route_tokens t ON t.token = m.url_token
            WHERE a.level = :level AND m.is_active = 1
            ORDER BY m.order_num ASC
        ");
        $stmt->execute([':level' => $this->userLevel]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
