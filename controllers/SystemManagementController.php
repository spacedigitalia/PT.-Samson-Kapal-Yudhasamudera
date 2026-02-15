<?php

class SystemManagementController
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
     * Decode list JSON to array of items with only title.
     * @return array<int, array{title: string}>
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
            $title = trim((string) ($item['title'] ?? ''));
            if ($title !== '') {
                $out[] = ['title' => $title];
            }
        }
        return $out;
    }

    /**
     * @return array<int, array>
     */
    public function getAll(): array
    {
        $list = [];
        $stmt = $this->db->prepare("SELECT id, title, description, `list`, user_id, created_at, updated_at FROM `management_systems` ORDER BY id DESC");
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
        $stmt = $this->db->prepare("SELECT m.id, m.title, m.description, m.`list`, m.user_id, m.created_at, m.updated_at, a.fullname FROM `management_systems` m LEFT JOIN `accounts` a ON m.user_id = a.id WHERE m.id = ? LIMIT 1");
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
        $stmt = $this->db->prepare("SELECT m.id, m.title, m.description, m.`list`, m.user_id, m.created_at, m.updated_at, a.fullname FROM `management_systems` m LEFT JOIN `accounts` a ON m.user_id = a.id ORDER BY m.id DESC LIMIT 1");
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
     * @param array<int, array{title: string}> $list
     */
    public function create(string $title, ?string $description, array $list, ?int $userId): bool
    {
        $listJson = json_encode($list, JSON_UNESCAPED_UNICODE);
        $stmt = $this->db->prepare("INSERT INTO `management_systems` (title, description, `list`, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $title, $description, $listJson, $userId);
        $ok = $stmt->execute();
        if ($ok) {
            $this->log($userId, 'management_system_create', 'Created: ' . $title);
        }
        $stmt->close();
        return (bool) $ok;
    }

    /**
     * @param array<int, array{title: string}> $list
     */
    public function update(int $id, string $title, ?string $description, array $list, ?int $userId): bool
    {
        $listJson = json_encode($list, JSON_UNESCAPED_UNICODE);
        $stmt = $this->db->prepare("UPDATE `management_systems` SET title = ?, description = ?, `list` = ?, user_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('sssii', $title, $description, $listJson, $userId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'management_system_update', 'Updated id ' . $id);
        }
        return (bool) $ok;
    }

    public function delete(int $id, ?int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `management_systems` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'management_system_delete', 'Deleted id ' . $id);
        }
        return (bool) $ok;
    }
}
