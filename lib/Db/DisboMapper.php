<?php
namespace OCA\YaDb\Db;

use OCP\IDbConnection;
use OCP\AppFramework\Db\Mapper;

class DisboMapper extends Mapper {


    public function __construct(IDbConnection $db) {
        parent::__construct($db, 'ncdisbo', '\OCA\YaDb\Db\Disbo');

    }

    public function find($id, $userId) {
        $sql = 'SELECT * FROM *PREFIX*ncdisbo WHERE id = ? AND user_id = ?';
        return $this->findEntity($sql, [$id, $userId]);
    }

    public function findAll($userId) {
        $sql = 'SELECT * FROM *PREFIX*ncdisbo WHERE user_id = ?';
        return $userId;
        #return $this->findEntities($sql, [$userId]);
    }

}


?>
