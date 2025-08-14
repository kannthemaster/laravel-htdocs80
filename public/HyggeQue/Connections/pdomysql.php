<?php
class pdomysql
{
    const db_host = '192.168.1.250:3307'; //IP เครื่อง Server ของ HOSxP [แนะนำใช้ Server สำรองครับ]
    const db_name = 'clinic';  //ชื่อ Database
    const db_user = 'hyggequeue'; //User MySQL เข้า Database
    const db_password = 'hyggequeue'; //Password MySQL เข้า Database
   
    private $pdo = null;

    public function __construct()
    {
        $conStr = sprintf("mysql:host=%s;dbname=%s;charset=UTF8", self::db_host, self::db_name);
        try {
            $this->pdo = new PDO($conStr, self::db_user, self::db_password);
            $this->pdo->exec("SET NAMES UTF8 ");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function selectAll($sql,$data){
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function selectOne($sql,$data){
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        $rows = $stmt->fetch(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function insertData($sql,$data){
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function insertDataLastId($sql,$data){
        $stmt = $this->pdo->prepare($sql);
         $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function updateData($sql,$data){
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
    public function deleteData($sql,$data){
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }


    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        $this->pdo = null;
    }
}

?>
