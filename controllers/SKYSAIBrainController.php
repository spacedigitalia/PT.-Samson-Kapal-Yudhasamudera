<?php

class SKYSAIBrainController
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
     * Decode features JSON: array of { title, description }.
     * @return array<int, array{title: string, description: string}>
     */
    public static function decodeFeatures(?string $json): array
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
            $t = trim((string) ($item['title'] ?? ''));
            $d = trim((string) ($item['description'] ?? ''));
            if ($t !== '' || $d !== '') {
                $out[] = ['title' => $t, 'description' => $d];
            }
        }
        return $out;
    }

    /**
     * Decode list JSON: array of { title } only.
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
        $stmt = $this->db->prepare("SELECT id, title, description, image, features, title_list, `list`, user_id, created_at, updated_at FROM `sky_sai_brain` ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $row['features'] = self::decodeFeatures($row['features'] ?? null);
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
        $stmt = $this->db->prepare("SELECT s.id, s.title, s.description, s.image, s.features, s.title_list, s.`list`, s.user_id, s.created_at, s.updated_at, a.fullname FROM `sky_sai_brain` s LEFT JOIN `accounts` a ON s.user_id = a.id WHERE s.id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$row) {
            return null;
        }
        $row['features'] = self::decodeFeatures($row['features'] ?? null);
        $row['list'] = self::decodeList($row['list'] ?? null);
        return $row;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getFirst(): ?array
    {
        $stmt = $this->db->prepare("SELECT s.id, s.title, s.description, s.image, s.features, s.title_list, s.`list`, s.user_id, s.created_at, s.updated_at, a.fullname FROM `sky_sai_brain` s LEFT JOIN `accounts` a ON s.user_id = a.id ORDER BY s.id DESC LIMIT 1");
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$row) {
            return null;
        }
        $row['features'] = self::decodeFeatures($row['features'] ?? null);
        $row['list'] = self::decodeList($row['list'] ?? null);
        return $row;
    }

    /**
     * @param array<int, array{title: string, description: string}> $features
     * @param array<int, array{title: string}> $list
     */
    public function create(string $title, string $description, string $image, array $features, string $titleList, array $list, ?int $userId): bool
    {
        $featuresJson = json_encode($features, JSON_UNESCAPED_UNICODE);
        $listJson = json_encode($list, JSON_UNESCAPED_UNICODE);
        $stmt = $this->db->prepare("INSERT INTO `sky_sai_brain` (title, description, image, features, title_list, `list`, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssi', $title, $description, $image, $featuresJson, $titleList, $listJson, $userId);
        $ok = $stmt->execute();
        if ($ok) {
            $this->log($userId, 'sky_sai_brain_create', 'Created: ' . $title);
        }
        $stmt->close();
        return (bool) $ok;
    }

    /**
     * @param array<int, array{title: string, description: string}> $features
     * @param array<int, array{title: string}> $list
     */
    public function update(int $id, string $title, string $description, string $image, array $features, string $titleList, array $list, ?int $userId): bool
    {
        $featuresJson = json_encode($features, JSON_UNESCAPED_UNICODE);
        $listJson = json_encode($list, JSON_UNESCAPED_UNICODE);
        $stmt = $this->db->prepare("UPDATE `sky_sai_brain` SET title = ?, description = ?, image = ?, features = ?, title_list = ?, `list` = ?, user_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('ssssssii', $title, $description, $image, $featuresJson, $titleList, $listJson, $userId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'sky_sai_brain_update', 'Updated id ' . $id);
        }
        return (bool) $ok;
    }

    public function delete(int $id, ?int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `sky_sai_brain` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'sky_sai_brain_delete', 'Deleted id ' . $id);
        }
        return (bool) $ok;
    }
}
