<?php

class CompanyKpisController
{
    private mysqli $db;
    private ?AuthController $auth;

    public function __construct(mysqli $db, ?AuthController $auth = null)
    {
        $this->db = $db;
        $this->auth = $auth;
    }

    private function log(?int $userId, string $action, ?string $description = null): void
    {
        if ($this->auth) {
            $this->auth->log($userId, $action, $description);
        }
    }

    /**
     * Decode list JSON: array of { value, title, description }. Value can be e.g. "99.8%".
     * @return array<int, array{value: string, title: string, description: string}>
     */
    public static function decodeList(?string $json): array
    {
        if ($json === null || $json === '') {
            return [];
        }
        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            return [];
        }
        $out = [];
        foreach ($decoded as $item) {
            $out[] = [
                'value'       => (string) ($item['value'] ?? ''),
                'title'       => (string) ($item['title'] ?? ''),
                'description' => (string) ($item['description'] ?? ''),
            ];
        }
        return $out;
    }

    /**
     * @return array<int, array>
     */
    public function getAll(): array
    {
        $list = [];
        $stmt = $this->db->prepare("SELECT id, title, description, `list`, user_id, created_at, updated_at FROM `company_kpis` ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $row['list'] = self::decodeList($row['list'] ?? null);
            $list[] = $row;
        }
        $stmt->close();
        return $list;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT c.id, c.title, c.description, c.`list`, c.user_id, c.created_at, c.updated_at, a.fullname FROM `company_kpis` c LEFT JOIN `accounts` a ON c.user_id = a.id WHERE c.id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$row) {
            return null;
        }
        $row['list'] = self::decodeList($row['list'] ?? null);
        return $row;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getFirst(): ?array
    {
        $stmt = $this->db->prepare("SELECT c.id, c.title, c.description, c.`list`, c.user_id, c.created_at, c.updated_at, a.fullname FROM `company_kpis` c LEFT JOIN `accounts` a ON c.user_id = a.id ORDER BY c.id DESC LIMIT 1");
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$row) {
            return null;
        }
        $row['list'] = self::decodeList($row['list'] ?? null);
        return $row;
    }

    /**
     * @param array<int, array{value: string, title: string, description: string}> $list
     */
    public function create(string $title, string $description, array $list, ?int $userId): bool
    {
        $listJson = json_encode($list, JSON_UNESCAPED_UNICODE);
        $stmt = $this->db->prepare("INSERT INTO `company_kpis` (title, description, `list`, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $title, $description, $listJson, $userId);
        $ok = $stmt->execute();
        if ($ok) {
            $this->log($userId, 'company_kpis_create', 'Created: ' . $title);
        }
        $stmt->close();
        return (bool) $ok;
    }

    /**
     * @param array<int, array{value: string, title: string, description: string}> $list
     */
    public function update(int $id, string $title, string $description, array $list, ?int $userId): bool
    {
        $listJson = json_encode($list, JSON_UNESCAPED_UNICODE);
        $stmt = $this->db->prepare("UPDATE `company_kpis` SET title = ?, description = ?, `list` = ?, user_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('sssii', $title, $description, $listJson, $userId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'company_kpis_update', 'Updated id ' . $id);
        }
        return (bool) $ok;
    }

    public function delete(int $id, ?int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `company_kpis` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'company_kpis_delete', 'Deleted id ' . $id);
        }
        return (bool) $ok;
    }
}
