<?php

namespace QTDL\Project;

use PDO;

class Reader
{
    private ?PDO $db;

    public ?string $MaDocGia;
    public string $TenDocGia;
    public string $DiaChi;
    public string $SoThe;
    private array $errors = [];

    public function __construct(?PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function getId(): string
    {
        return $this->MaDocGia;
    }

    public function getReaders(): array
    {
        $readers = [];
        $statement = $this->db->prepare('SELECT * FROM docgia');
        $statement->execute();
        while ($row = $statement->fetch()) { // Sử dụng fetch() thay vì fetchAll()
            $reader = new Reader($this->db);
            $reader->fillFromDB($row);
            $readers[] = $reader;
        }
        return $readers;
    }


    protected function fillFromDB(array $row): void
    {
        // Kiểm tra xem các khóa có tồn tại trong mảng $row không trước khi truy cập vào chúng
        $this->MaDocGia = $row['MaDocGia'] ?? null;
        $this->TenDocGia = $row['TenDocGia'] ?? '';
        $this->DiaChi = $row['DiaChi'] ?? '';
        $this->SoThe = $row['SoThe'] ?? '';
    }

    private function isMaDocGiaExists(string $maDocGia): bool
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM DocGia WHERE MaDocGia = :MaDocGia');
        $statement->execute(['MaDocGia' => $maDocGia]);
        $count = $statement->fetchColumn();
        return $count > 0;
    }

    private function generateMaDocGia(): string
    {
        $prefix = 'DG';
        $random_number = mt_rand(1000, 9999);
        return $prefix . $random_number;
    }

    public function save(): bool
    {
        $result = false;
        if ($this->MaDocGia !== null) {
            $statement = $this->db->prepare(
                'UPDATE DocGia SET TenDocGia = :TenDocGia, DiaChi = :DiaChi, SoThe = :SoThe WHERE MaDocGia = :MaDocGia'
            );
            $result = $statement->execute([
                'TenDocGia' => $this->TenDocGia,
                'DiaChi' => $this->DiaChi,
                'SoThe' => $this->SoThe,
                'MaDocGia' => $this->MaDocGia
            ]);
        } else {
            if ($this->MaDocGia === null) {
                do {
                    $this->MaDocGia = $this->generateMaDocGia();
                } while ($this->isMaDocGiaExists($this->MaDocGia));
            }
            $statement = $this->db->prepare(
                'INSERT INTO DocGia (MaDocGia, TenDocGia, DiaChi, SoThe) VALUES (:MaDocGia, :TenDocGia, :DiaChi, :SoThe)'
            );
            $result = $statement->execute([
                'TenDocGia' => $this->TenDocGia,
                'DiaChi' => $this->DiaChi,
                'SoThe' => $this->SoThe,
                'MaDocGia' => $this->MaDocGia
            ]);
        }
        return $result;
    }

    public function find(string $MaDocGia): ?Reader
    {
        $statement = $this->db->prepare('SELECT * FROM DocGia WHERE MaDocGia = :MaDocGia');
        $statement->execute(['MaDocGia' => $MaDocGia]);
        if ($row = $statement->fetch()) {
            $this->fillFromDB($row);
            return $this;
        }
        return null;
    }
    public function update(array $data): bool
    {
        // Không gọi fill ở đây
        $this->MaDocGia = $data['MaDocGia'] ?? null;
        $this->TenDocGia = $data['TenDocGia'] ?? '';
        $this->DiaChi = $data['DiaChi'] ?? '';
        $this->SoThe = $data['SoThe'] ?? '';

        if ($this->validate()) {
            return $this->save();
        }
        return false;
    }

    // public function update(array $data): bool
    // {
    //     $this->fill($data);
    //     if ($this->validate()) {
    //         return $this->save();
    //     }
    //     return false;
    // }

    public function delete(): bool
    {
        // Kiểm tra xem $this->MaDocGia có giá trị không
        if ($this->MaDocGia !== null && $this->MaDocGia !== '') {
            // Kiểm tra xem $this->db có tồn tại không
            if ($this->db) {
                $statement = $this->db->prepare('DELETE FROM DocGia WHERE MaDocGia = :MaDocGia');
                return $statement->execute(['MaDocGia' => $this->MaDocGia]);
            } else {
                // Xử lý trường hợp $this->db không tồn tại
                return false;
            }
        } else {
            // Xử lý trường hợp $this->MaDocGia không có giá trị hợp lệ
            return false;
        }
    }


    public function fill(array $data): void
    {
        // Sử dụng isset hoặc ?? để kiểm tra xem các phần tử trong mảng $data có tồn tại không
        $this->MaDocGia = $data['MaDocGia'] ?? null;
        $this->TenDocGia = $data['TenDocGia'] ?? '';
        $this->DiaChi = $data['DiaChi'] ?? '';
        $this->SoThe = $data['SoThe'] ?? '';
    }

    public function validate(): bool
    {
        $valid = true;

        if (empty($this->TenDocGia)) {
            $this->errors['TenDocGia'] = 'Tên độc giả không hợp lệ.';
            $valid = false;
        }

        return $valid;
    }
    public function searchReaders(string $keyword): array
    {
        $searchResults = [];
        $statement = $this->db->prepare('SELECT * FROM DocGia WHERE TenDocGia LIKE :keyword OR DiaChi LIKE :keyword OR SoThe LIKE :keyword');
        $statement->execute(['keyword' => '%' . $keyword . '%']);
        while ($row = $statement->fetch()) {
            $reader = new Reader($this->db);
            $reader->fillFromDB($row);
            $searchResults[] = $reader;
        }
        return $searchResults;
    }
    public function getReadersSorted(string $sort_by): array
    {
        $readers = [];
        $statement = $this->db->prepare('SELECT * FROM DocGia ORDER BY ' . $sort_by);
        $statement->execute();
        while ($row = $statement->fetch()) {
            $reader = new Reader($this->db);
            $reader->fillFromDB($row);
            $readers[] = $reader;
        }
        return $readers;
    }

    public function getTheThuVienByMa($ma_the)
    {
        // Chuẩn bị câu lệnh gọi procedure
        $sql = "CALL HienThiDanhSachTheThuVienTheoMa(:ma_the)";

        // Chuẩn bị và thực thi statement
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ma_the', $ma_the);
        $stmt->execute();

        // Lấy kết quả trả về từ procedure
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
