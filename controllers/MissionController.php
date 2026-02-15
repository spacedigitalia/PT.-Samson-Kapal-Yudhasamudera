<?php

class MissionController
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
     * Decode list JSON to array of [number, title, description].
     * @return array<int, array{number: int|string, title: string, description: string}>
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
                'number'  => $item['number'] ?? 0,
                'title'   => (string) ($item['title'] ?? ''),
                'description' => (string) ($item['description'] ?? ''),
            ];
        }
        return $out;
    }

    /**
     * @return array<int, array> Each row with 'list' as decoded array (number, title, description).
     */
    public function getAll(): array
    {
        $list = [];
        $stmt = $this->db->prepare("SELECT id, title, image, `list`, user_id, created_at, updated_at FROM `missions` ORDER BY id DESC");
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
     * @return array<string, mixed>|null With 'list' as array of { number, title, description }.
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT m.id, m.title, m.image, m.`list`, m.user_id, m.created_at, m.updated_at, a.fullname FROM `missions` m LEFT JOIN `accounts` a ON m.user_id = a.id WHERE m.id = ? LIMIT 1");
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
     * @return array<string, mixed>|null First mission with decoded list.
     */
    public function getFirst(): ?array
    {
        $stmt = $this->db->prepare("SELECT m.id, m.title, m.image, m.`list`, m.user_id, m.created_at, m.updated_at, a.fullname FROM `missions` m LEFT JOIN `accounts` a ON m.user_id = a.id ORDER BY m.id DESC LIMIT 1");
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
     * @param array<int, array{number?: int|string, title?: string, description?: string}> $list
     */
    public function create(string $title, string $image, array $list, ?int $userId): bool
    {
        $listJson = json_encode($list, JSON_UNESCAPED_UNICODE);
        $stmt = $this->db->prepare("INSERT INTO `missions` (title, image, `list`, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $title, $image, $listJson, $userId);
        $ok = $stmt->execute();
        if ($ok) {
            $this->log($userId, 'mission_create', 'Created mission: ' . $title);
        }
        $stmt->close();
        return (bool) $ok;
    }

    /**
     * @param array<int, array{number?: int|string, title?: string, description?: string}> $list
     */
    public function update(int $id, string $title, string $image, array $list, ?int $userId): bool
    {
        $listJson = json_encode($list, JSON_UNESCAPED_UNICODE);
        $stmt = $this->db->prepare("UPDATE `missions` SET title = ?, image = ?, `list` = ?, user_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('sssii', $title, $image, $listJson, $userId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'mission_update', 'Updated mission id ' . $id);
        }
        return (bool) $ok;
    }

    public function delete(int $id, ?int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `missions` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'mission_delete', 'Deleted mission id ' . $id);
        }
        return (bool) $ok;
    }
}
