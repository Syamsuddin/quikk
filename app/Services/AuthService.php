<?php
// [UPDATE 27/06/2025 16:00] Tambah fitur keamanan terenkapsulasi (rate limit, CSRF, logout, logging)

namespace App\Services;

use PDO;
use Exception;

class AuthService
{
    private PDO $db;
    private int $maxAttempts = 5;
    private int $lockTime = 300; // dalam detik (5 menit)

    public function __construct(PDO $db)
    {
        $this->db = $db;
        session_start();
        $this->initCsrfToken();
    }

    // Fungsi login
    public function login(string $username, string $password, string $csrfToken): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        if (!$this->validateCsrfToken($csrfToken)) {
            $this->logAttempt($username, 'fail', $ip);
            return false;
        }

        if ($this->isRateLimited($ip)) {
            $this->logAttempt($username, 'blocked', $ip);
            return false;
        }

        if (!$this->validateInput($username, $password)) {
            $this->logAttempt($username, 'fail', $ip);
            return false;
        }

        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'username' => $user['username'],
                'level'    => $user['level']
            ];
            $this->logAttempt($username, 'success', $ip);
            return true;
        } else {
            $this->logAttempt($username, 'fail', $ip);
            return false;
        }
    }

    // Fungsi logout aman
    public function logout(): void
    {
        session_unset();
        session_destroy();
    }

    // Ambil data user yang sedang login
    public function getUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    // Cek apakah user sedang login
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    // Token CSRF: buat jika belum ada
    private function initCsrfToken(): void
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    // Ambil token CSRF
    public function getCsrfToken(): string
    {
        return $_SESSION['csrf_token'];
    }

    // Validasi token CSRF
    private function validateCsrfToken(string $token): bool
    {
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    // Validasi input username dan password
    private function validateInput(string $username, string $password): bool
    {
        return preg_match('/^[a-zA-Z0-9_]{4,30}$/', $username) &&
               strlen($password) >= 6 && strlen($password) <= 255;
    }

    // Log semua upaya login
    private function logAttempt(string $username, string $status, string $ip): void
    {
        $stmt = $this->db->prepare("INSERT INTO login_logs (username, status, ip_address) VALUES (:username, :status, :ip)");
        $stmt->execute([
            ':username' => $username,
            ':status'   => $status,
            ':ip'       => $ip
        ]);
    }

    // Rate limiter sederhana berdasarkan IP
    private function isRateLimited(string $ip): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM login_logs WHERE ip_address = :ip AND status = 'fail' AND timestamp > (NOW() - INTERVAL :lockTime SECOND)");
        $stmt->bindValue(':ip', $ip);
        $stmt->bindValue(':lockTime', $this->lockTime, PDO::PARAM_INT);
        $stmt->execute();
        $failCount = $stmt->fetchColumn();

        return $failCount >= $this->maxAttempts;
    }
}
