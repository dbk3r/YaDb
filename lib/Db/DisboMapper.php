<?php
namespace OCA\YaDb\Db;

use OCP\IDbConnection;
use OCP\AppFramework\Db\Mapper;


class DisboMapper extends Mapper {

    public function __construct(IDbConnection $db) {
        parent::__construct($db, 'ncdisbo', '\OCA\YaDb\Db\Disbo');

    }

    function get_categories() {
      $sql = 'SELECT * FROM *PREFIX*ncdisbocat order by category asc';
      return $this->findEntities($sql);

    }

    
    public function show($id) {
        return $this->get_categories();
    }

    public function show_topic($uuid) {
        $sql = 'SELECT * FROM *PREFIX*ncdisbo WHERE uuid = ?';
        return $this->findEntities($sql, [$uuid]);
    }

}


?>
