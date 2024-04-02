<?php

namespace Book;

use db_connect;

class sach
{
    private $maSach;
    private $tenSach;
    private $maTG;
    private $maNXB;
    private $maLoai;

    private $connect;

    public function __construct($db_connect)
    {
        $this->connect = $db_connect;
    }

    public function read()
    {
        $query = "SELECT * FROM library_management.sach;";
        $statement = $this->connect->prepare($query);
        $statement->execute();
        return $statement;
    }

    public function delete()
    {
        $query = "DELETE FROM sach WHERE maSach = ?";
        $statement = $this->connect->prepare($query);
        // $statement->execute(['maSach' => $this->maSach]);
        $statement->bindParam(1, $this->maSach);
        return $statement->execute();
    }

    public function create()
    {
        $queryCheckLoai = "SELECT maLoai FROM theloai WHERE maLoai = :maLoai";
        $statementCheckLoai = $this->connect->prepare($queryCheckLoai);
        $statementCheckLoai->execute([':maLoai' => $this->maLoai]);

        $rowCount = $statementCheckLoai->rowCount();
        if ($rowCount === 0) {
            // Nếu maLoai không tồn tại trong bảng theloai, không thêm sách mới
            echo "Error: Không tồn tại maLoai trong bảng theloai /n";
            return false;
        }

        $query = "INSERT INTO sach(maSach, tenSach, maTG, maNXB, maLoai)
                    VALUES (?, ?, ?, ?, ?)";
        $statement = $this->connect->prepare($query);

        $this->maSach = htmlspecialchars($this->maSach);
        $this->tenSach = htmlspecialchars($this->tenSach);
        $this->maTG = htmlspecialchars($this->maTG);
        $this->maNXB = htmlspecialchars($this->maNXB);
        $this->maLoai = htmlspecialchars($this->maLoai);

        if ($statement->execute([
            $this->maSach, $this->tenSach, $this->maTG, $this->maNXB, $this->maLoai
        ])) {
            return true;
        } else {
            printf("Không thể thêm sách");
        }
        print("Error \n" . $statement->error);
        return false;
    }

    public function update()
    {
        $query = "UPDATE sach SET
            tenSach = :tenSach,
            maTG = :maTG,
            maNXB = :maNXB,
            maLoai = :maLoai
            WHERE maSach = :maSach";

        $statement = $this->connect->prepare($query);

        $statement->bindParam(':tenSach', $this->tenSach);
        $statement->bindParam(':maTG', $this->maTG);
        $statement->bindParam(':maNXB', $this->maNXB);
        $statement->bindParam(':maLoai', $this->maLoai);
        $statement->bindParam(':maSach', $this->maSach);


        if ($statement->execute()) {
            return true;
        } else {
            printf("Không thể chỉnh sửa sách");
        }
        print("Error \n" . $statement->error);
        return false;
    }

    public function search()
    {
        $search = $_GET['search'] ?? '';

        if (!empty($search)) {
            $query = 'SELECT maSach,tenSach,maNXB,maTG,maLoai FROM sach WHERE maSach LIKE :search OR tenSach LIKE :search 
            OR maLoai LIKE :search OR maNXB LIKE :search';
            $statement = $this->connect->prepare($query);

            $statement->execute([
                ':search' => '%' . $search . '%'
            ]);

            $results =  $statement->fetchAll();

            return $results;
        }

        return [];
    }

    public function getMaSach()
    {
        return $this->maSach;
    }

    public function setMaSach($maSach)
    {
        $this->maSach = $maSach;
    }

    public function getTenSach()
    {
        return $this->tenSach;
    }

    public function setTenSach($tenSach)
    {
        $this->tenSach = $tenSach;
    }

    public function getMaTG()
    {
        return $this->maTG;
    }

    public function setMaTG($maTG)
    {
        $this->maTG = $maTG;
    }

    public function getMaNXB()
    {
        return $this->maNXB;
    }

    public function setMaNXB($maNXB)
    {
        $this->maNXB = $maNXB;
    }

    public function getMaLoai()
    {
        return $this->maLoai;
    }

    public function setMaLoai($maLoai)
    {
        $this->maLoai = $maLoai;
    }

    public function getBookByMaSach($maSach)
    {
        $query = "SELECT * FROM sach WHERE maSach = ?";
        $statement = $this->connect->prepare($query);
        $statement->execute([$maSach]);
        return $statement->fetch();
    }
}
