<?php

namespace BookCopy;

use db_connect;

class bansaosach
{
    private $maSach;
    private $maBanSao;
    private $namXB;
    private $ttMuon;
    private $maNXB;

    private $connect;

    public function __construct($db_connect)
    {
        $this->connect = $db_connect;
    }

    public function searchCopy()
    {
        $searchcopy = $_GET['searchcopy'] ?? '';
        if (!empty($searchcopy)) {
            $query = 'SELECT maSach,maBanSao,namXB,ttMuon,maNXB FROM bansaosach WHERE maSach LIKE :searchcopy 
            OR maBanSao LIKE :searchcopy OR maNXB LIKE :searchcopy';
            $statement = $this->connect->prepare($query);

            $statement->execute([
                ':searchcopy' => '%' . $searchcopy . '%'
            ]);

            $resultcopy = $statement->fetchAll();
            return $resultcopy;
        }
        return [];
    }

    public function addCopy()
    {
        $query = "INSERT INTO bansaosach (maSach, maBanSao, namXB, ttMuon, maNXB) 
                VALUES (?, ?, ?, ?, ?)";
        $statement = $this->connect->prepare($query);

        $this->maSach = htmlspecialchars($this->maSach);
        $this->maBanSao = htmlspecialchars($this->maBanSao);
        $this->namXB = htmlspecialchars($this->namXB);
        $this->ttMuon = htmlspecialchars($this->ttMuon);
        $this->maNXB = htmlspecialchars($this->maNXB);

        if ($statement->execute([
            $this->maSach, $this->maBanSao, $this->namXB, $this->ttMuon, $this->maNXB
        ])) {
            return true;
        } else {
            printf("Không thể thêm bản sao");
        }
        print("Error \n" . $statement->error);
        return false;
    }

    public function editCopy()
    {
        $query = "UPDATE bansaosach SET
            namXB = :namXB,
            ttMuon = :ttMuon
            WHERE maBanSao = :maBanSao";
        $statement = $this->connect->prepare($query);

        $statement->bindParam(':namXB', $this->namXB);
        $statement->bindParam(':ttMuon', $this->ttMuon);
        $statement->bindParam(':maBanSao', $this->maBanSao);

        if ($statement->execute()) {
            return true;
        } else {
            printf("Không thể chỉnh sửa bản sao");
        }
        print("Error \n" . $statement->error);
        return false;
    }

    public function deleteCopy()
    {
        $query = "DELETE FROM bansaosach WHERE maBanSao = ?";
        $statement = $this->connect->prepare($query);
        $statement->bindParam(1, $this->maBanSao);
        return $statement->execute();
    }

    public function getAllcopy()
    {
        $query = "SELECT * FROM bansaosach";
        $statement = $this->connect->prepare($query);

        $statement->execute();
        return $statement->fetchAll();
    }

    public function getBookCopy($maBanSao)
    {
        $query = "SELECT * FROM bansaosach WHERE maBanSao = ?";
        $statement = $this->connect->prepare($query);
        $statement->execute([$maBanSao]);
        return $statement->fetch();
    }

    public function getmaSach()
    {
        return $this->maSach;
    }

    public function setmaSach($maSach)
    {
        $this->maSach = $maSach;
    }

    public function getmaBanSao()
    {
        return $this->maBanSao;
    }

    public function setmaBanSao($maBanSao)
    {
        $this->maBanSao = $maBanSao;
    }

    public function getNamXB()
    {
        return $this->namXB;
    }

    public function setNamXB($namXB)
    {
        $this->namXB = $namXB;
    }

    public function getTtMuon()
    {
        return $this->ttMuon;
    }

    public function setTtMuon($ttMuon)
    {
        $this->ttMuon = $ttMuon;
    }

    public function getMaNXB()
    {
        return $this->maNXB;
    }

    public function setMaNXB($maNXB)
    {
        $this->maNXB = $maNXB;
    }
}
