<?php
namespace QTDL\Project;

use PDO;

class History
{
    private ?PDO $db;

    public ?string $MaMuonTra = null;
    public ?string $SoThe = null;
    public ?string $MaNhanVien = null;
    public ?string $NgayMuon = null;
    public ?string $MaSach = null;
    public ?string $GhiChu = null;
    public ?bool $DaTra = null;
    public ?string $NgayTra = null;

    private array $errors = [];

    public function __construct(?PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function getHistory(): array
    {
        $history = [];
        $statement = $this->db->prepare('SELECT MuonTra.*, ChiTietMuonTra.MaSach, ChiTietMuonTra.GhiChu, ChiTietMuonTra.DaTra, ChiTietMuonTra.NgayTra 
                                         FROM MuonTra INNER JOIN ChiTietMuonTra ON MuonTra.MaMuonTra = ChiTietMuonTra.MaMuonTra');
        $statement->execute();
        while ($row = $statement->fetch()) {
            $record = new History($this->db);
            $record->fillFromDB($row);
            $history[] = $record;
        }
        return $history;
    }

    protected function fillFromDB(array $row): void
    {
        $this->MaMuonTra = $row['MaMuonTra'];
        $this->SoThe = $row['SoThe'];
        $this->MaNhanVien = $row['MaNhanVien'];
        $this->NgayMuon = $row['NgayMuon'];
        $this->MaSach = $row['MaSach'];
        $this->GhiChu = $row['GhiChu'];
        $this->DaTra = $row['DaTra'];
        $this->NgayTra = $row['NgayTra'];
    }

    public function save(): bool
    {
        // Lưu lịch sử mượn trả không được hỗ trợ trong trang này
        return false;
    }

    public function delete(): bool
    {
        // Xóa lịch sử mượn trả không được hỗ trợ trong trang này
        return false;
    }

    public function fill(array $data): void
    {
        // Điền dữ liệu từ một mảng, không được hỗ trợ trong trang này
    }

    public function searchHistory(string $keyword): array
    {
        $history = [];
        // Thực hiện truy vấn cơ sở dữ liệu với điều kiện WHERE để lọc kết quả
        $statement = $this->db->prepare('SELECT MuonTra.*, ChiTietMuonTra.MaSach, ChiTietMuonTra.GhiChu, ChiTietMuonTra.DaTra, ChiTietMuonTra.NgayTra 
                                        FROM MuonTra INNER JOIN ChiTietMuonTra ON MuonTra.MaMuonTra = ChiTietMuonTra.MaMuonTra
                                        WHERE SoThe LIKE :keyword OR MaNhanVien LIKE :keyword OR MaSach LIKE :keyword OR GhiChu LIKE :keyword');
        $statement->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $statement->execute();

        while ($row = $statement->fetch()) {
            $record = new History($this->db);
            $record->fillFromDB($row);
            $history[] = $record;
        }

        return $history;
    }
    public function getHistorySorted(string $sort_by): array
    {
        $history = [];
        // Thực hiện truy vấn cơ sở dữ liệu với điều kiện ORDER BY để sắp xếp kết quả
        $statement = $this->db->prepare('SELECT MuonTra.*, ChiTietMuonTra.MaSach, ChiTietMuonTra.GhiChu, ChiTietMuonTra.DaTra, ChiTietMuonTra.NgayTra 
                                        FROM MuonTra INNER JOIN ChiTietMuonTra ON MuonTra.MaMuonTra = ChiTietMuonTra.MaMuonTra
                                        ORDER BY ' . $sort_by);
        $statement->execute();

        while ($row = $statement->fetch()) {
            $record = new History($this->db);
            $record->fillFromDB($row);
            $history[] = $record;
        }

        return $history;
    }


}
?>