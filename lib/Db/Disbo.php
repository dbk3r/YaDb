<?php
namespace OCA\YaDb\Db;

use JsonSerializable;
use OCP\AppFramework\Db\Entity;


class Disbo extends Entity implements JsonSerializable {

    protected $title;
    protected $content;
    protected $userId;
    protected $category;
    protected $ts;
    protected $reply;

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'userId' => $this->userId,
            'ts' => $this->ts,
            'reply' => $this->reply,
            'category' => $this->category,
            'content' => $this->content
        ];
    }
}

?>
