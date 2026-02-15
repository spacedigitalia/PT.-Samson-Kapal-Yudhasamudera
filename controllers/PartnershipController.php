<?php

class PartnershipController
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
        $stmt = $this->db->prepare("SELECT id, title, quete, image, title_list, `list`, user_id, created_at, updated_at FROM `partnership` ORDER BY id DESC");
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
        $stmt = $this->db->prepare("SELECT p.id, p.title, p.quete, p.image, p.title_list, p.`list`, p.user_id, p.created_at, p.updated_at, a.fullname FROM `partnership` p LEFT JOIN `accounts` a ON p.user_id = a.id WHERE p.id = ? LIMIT 1");
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
        $stmt = $this->db->prepare("SELECT p.id, p.title, p.quete, p.image, p.title_list, p.`list`, p.user_id, p.created_at, p.updated_at, a.fullname FROM `partnership` p LEFT JOIN `accounts` a ON p.user_id = a.id ORDER BY p.id DESC LIMIT 1");
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
    public function create(string $title, string $quete, ?string $image, string $titleList, array $list, ?int $userId): bool
    {
        $listJson = json_encode($list, JSON_UNESCAPED_UNICODE);
        $stmt = $this->db->prepare("INSERT INTO `partnership` (title, quete, image, title_list, `list`, user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssi', $title, $quete, $image, $titleList, $listJson, $userId);
        $ok = $stmt->execute();
        if ($ok) {
            $this->log($userId, 'partnership_create', 'Created: ' . $title);
        }
        $stmt->close();
        return (bool) $ok;
    }

    /**
     * @param array<int, array{title: string}> $list
     */
    public function update(int $id, string $title, string $quete, ?string $image, string $titleList, array $list, ?int $userId): bool
    {
        $listJson = json_encode($list, JSON_UNESCAPED_UNICODE);
        $stmt = $this->db->prepare("UPDATE `partnership` SET title = ?, quete = ?, image = ?, title_list = ?, `list` = ?, user_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('sssssii', $title, $quete, $image, $titleList, $listJson, $userId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'partnership_update', 'Updated id ' . $id);
        }
        return (bool) $ok;
    }

    public function delete(int $id, ?int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `partnership` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'partnership_delete', 'Deleted id ' . $id);
        }
        return (bool) $ok;
    }
}
