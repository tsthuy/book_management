<?php
namespace QTDL\Project;

use PDO;

class ReaderCard
{
    private ?PDO $db;

    public ?string $SoThe = null;
    public ?string $NgayBatDau = null;
    public ?string $NgayHetHan = null;
    public ?string $GhiChu = null;

    private array $errors = [];

    public function __construct(?PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function getId(): string 
    {
        return $this->SoThe;
    }
    public function getReaderCards(): array
    {
        $readerCards = [];
        $statement = $this->db->prepare('SELECT * FROM TheThuVien');
        $statement->execute();
        while ($row = $statement->fetch()) {
            $readerCard = new ReaderCard($this->db);
            $readerCard->fillFromDB($row);
            $readerCards[] = $readerCard;
        }
        return $readerCards;
    }

    protected function fillFromDB(array $row): void
    {
        $this->SoThe = $row['SoThe'];
        $this->NgayBatDau = $row['NgayBatDau'];
        $this->NgayHetHan = $row['NgayHetHan'];
        $this->GhiChu = $row['GhiChu'];
    }
    private function generateRandomSoThe(): string
    {
        $prefix = 'STV'; // Prefix cho số thẻ thư viện
        $randomNumber = mt_rand(1000000, 9999999); // Số ngẫu nhiên từ 1000 đến 9999
        return $prefix . $randomNumber;
    }
    private function isSoTheExists(string $soThe): bool
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM TheThuVien WHERE SoThe = :SoThe');
        $statement->execute(['SoThe' => $soThe]);
        $count = $statement->fetchColumn();
        return $count > 0; // Trả về true nếu số thẻ đã tồn tại, ngược lại trả về false
    }

    public function validate(): bool
    {
        $valid = true;
        
        // Kiểm tra hợp lệ của Ngày bắt đầu
        if (empty($this->NgayBatDau)) {
            $this->errors['NgayBatDau'] = 'Ngày bắt đầu không được để trống.';
            $valid = false;
        }
        
        // Kiểm tra hợp lệ của Ngày hết hạn
        if (empty($this->NgayHetHan)) {
            $this->errors['NgayHetHan'] = 'Ngày hết hạn không được để trống.';
            $valid = false;
        }

        // Kiểm tra hợp lệ của Ghi chú (nếu có)
        if (strlen($this->GhiChu) > 255) {
            $this->errors['GhiChu'] = 'Ghi chú không được vượt quá 255 ký tự.';
            $valid = false;
        }

        return $valid;
    }

    public function save(): bool
    {
        $result = false;
        if ($this->SoThe !== null) {
            $statement = $this->db->prepare(
                'UPDATE TheThuVien SET NgayBatDau = :NgayBatDau, NgayHetHan = :NgayHetHan, GhiChu = :GhiChu WHERE SoThe = :SoThe'
            );
            $result = $statement->execute([
                'NgayBatDau' => $this->NgayBatDau,
                'NgayHetHan' => $this->NgayHetHan,
                'GhiChu' => $this->GhiChu,
                'SoThe' => $this->SoThe
            ]);
        } else {
            if ($this->SoThe === null) {
                // Nếu Số thẻ chưa được thiết lập, sinh một số thẻ ngẫu nhiên và kiểm tra xem có trùng lặp không
                do {
                    $this->SoThe = $this->generateRandomSoThe();
                } while ($this->isSoTheExists($this->SoThe)); // Lặp lại cho đến khi không còn trùng lặp
            }
        
            // Tiếp tục thực hiện lưu dữ liệu vào cơ sở dữ liệu
            $statement = $this->db->prepare(
                'INSERT INTO TheThuVien (SoThe, NgayBatDau, NgayHetHan, GhiChu) VALUES (:SoThe, :NgayBatDau, :NgayHetHan, :GhiChu)'
            );
            $result = $statement->execute([
                'SoThe' => $this->SoThe,
                'NgayBatDau' => $this->NgayBatDau,
                'NgayHetHan' => $this->NgayHetHan,
                'GhiChu' => $this->GhiChu
            ]);
        }
        return $result;
    }

    public function find(string $SoThe): ?ReaderCard
    {
        $statement = $this->db->prepare('SELECT * FROM TheThuVien WHERE SoThe = :SoThe');
        $statement->execute(['SoThe' => $SoThe]);
        if ($row = $statement->fetch()) {
            $this->fillFromDB($row);
            return $this;
        }
        return null;
    }

    public function update(array $data): bool
    {
        $this->fill($data);
        if ($this->validate()) {
            return $this->save();
        }
        return false;
    }

    public function delete(): bool
    {
        if ($this->SoThe) {

            $statement = $this->db->prepare('DELETE FROM TheThuVien WHERE SoThe = :SoThe');
            $result = $statement->execute(['SoThe' => $this->SoThe]);
            return $result;
        }
        return false;
    }


    public function fill(array $data): void
    {
        $this->SoThe = $data['SoThe'] ?? $this->SoThe;
        $this->NgayBatDau = $data['NgayBatDau'] ?? $this->NgayBatDau;
        $this->NgayHetHan = $data['NgayHetHan'] ?? $this->NgayHetHan;
        $this->GhiChu = $data['GhiChu'] ?? $this->GhiChu;
    }

    public function getValidationErrors(): array
    {
        return $this->errors;
    }
    public function getReaderCardsSorted(string $sort_by): array
    {
        $readerCards = [];
        $statement = $this->db->prepare('SELECT * FROM TheThuVien ORDER BY ' . $sort_by);
        $statement->execute();
        while ($row = $statement->fetch()) {
            $readerCard = new ReaderCard($this->db);
            $readerCard->fillFromDB($row);
            $readerCards[] = $readerCard;
        }
        return $readerCards;
    }


    public function searchReaderCards(string $keyword): array
    {
        $readerCards = [];
        $statement = $this->db->prepare('SELECT * FROM TheThuVien WHERE SoThe LIKE :keyword OR NgayBatDau LIKE :keyword OR NgayHetHan LIKE :keyword OR GhiChu LIKE :keyword');
        $statement->execute(['keyword' => '%' . $keyword . '%']);
        while ($row = $statement->fetch()) {
            $readerCard = new ReaderCard($this->db);
            $readerCard->fillFromDB($row);
            $readerCards[] = $readerCard;
        }
        return $readerCards;
    }
}
?>
