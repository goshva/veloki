<?php
include_once 'config.php';
// Create a class Stock
class Stock extends Config
{
    // Fetch all or a single user from database
    public function fetch($id = 0)
    {
        $sql = 'SELECT * FROM stock';
        if ($id != 0) {
            $sql .= ' WHERE id = :id';
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $rows = $stmt->fetchAll();
        return $rows;
    }

    // Insert a order in the database
    public function insert($tel, $name, $items,$itemsStr, $money, $sign, $comment, $timein) {
        $sql = 'INSERT INTO `stock` (tel, name, items, money, sign, comment, timein) VALUES (:tel, :name, :itemsStr, :money, :sign, :comment, :timein)';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['tel' => $tel,
            'name' => $name,
            'itemsStr' => $itemsStr,
            'money' => $money,
            'sign' => $sign,
            'comment' => $comment,
            'timein' => $timein]);
        //Update bike status
        $bike = new Bikes();
        $id = $this->conn->lastInsertId();
        foreach ($items as $item) {
            $bike->updateStatus(1,$item, $id);
        }
        return true;
    }
    public function delete($id) {
        $sql = 'DELETE FROM `stock` WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $bike = new Bikes();
        $bike->cleanupBikes($id);
        return true;
    }
    // Update an order in the database
    public function update($id, $tel, $name, $items, $money, $sign, $comment) {
        //$stmt = $this->conn->prepare("UPDATE `stock` SET  `tel` = :tel, `name` = :name, `items` = :itemsStr, `money` = :money, `sign` = :sign, `comment` = :comment WHERE `id` = :id;");
        $stmt = $this->conn->prepare("UPDATE `stock` SET  `tel` = :tel, `name` = :name, `items` = :items, `money` = :money, `sign` = :sign, `comment` = :comment WHERE `id` = :id;");
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':tel', $tel);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':items', $items);
        $stmt->bindValue(':money', $money);
        $stmt->bindValue(':sign', $sign);
        $stmt->bindValue(':comment', $comment);
        //$stmt->execute(['id' => $id,'tel' => $tel,'name' => $name,'items' => $itemsStr,'money' => $money,'sign' => $sign,'comment' => $comment,]);
        $stmt->execute();

        return true;
    }
}
// Create a class Bikes
class Bikes extends Config {
    // Fetch all or a single user from database
    public function fetch($id = 0) {
        $sql = 'SELECT * FROM equip';
        if ($id != 0) {
            $sql .= ' WHERE id = :id';
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $rows = $stmt->fetchAll();
        return $rows;
    }
    public function updateStatus($status,$item,$id)
    {
        $sql = 'UPDATE `equip` SET  `status` = :status, `order` = :id WHERE `name` = :item';
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute(['status' => $status, 'item' => $item, 'id' => $id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }

    }
    public function cleanupBikes($id)
    {
        $sql = 'UPDATE `equip` SET  `status` = 0, `order` = 0 WHERE `order` = :id';
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }

    }
    // Insert a bike in the database
    public function insert($name, $class) {
        $sql = 'INSERT INTO `equip` (name, class) VALUES (:name, :class)';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['name' => $name, 'class' => $class]);
        return true;
    }

    // Update an bike in the database
    public function update($id, $name, $class, $status, $order) {
        $stmt = $this->conn->prepare("UPDATE `equip` SET  `name` = :name, `class` = :class, `status` = :status, `order` = :order WHERE `id` = :id;");
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':class', $class);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':order', $order);
        $stmt->execute();

        return true;
    }

    // Delete an bike from database
    public function delete($id) {
        $sql = 'DELETE FROM `equip` WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return true;
    }
}

?>