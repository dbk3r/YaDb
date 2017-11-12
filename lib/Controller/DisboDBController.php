<?php
 namespace OCA\YaDb\Controller;

  use Exception;

  use OCP\IRequest;
  use OCP\AppFramework\Http;
  use OCP\AppFramework\Http\DataResponse;
  use OCP\AppFramework\Controller;
  use OCP\IDbConnection;
  use OCP\IUserManager;




 class DisboDBController extends Controller {


    protected $userId;
    private $db;
    public $userManager;


     public function __construct($AppName, IRequest $request, IDbConnection $db, $UserId, IUserManager $user){
         parent::__construct($AppName, $request);
         $this->userId = $UserId;
         $this->db = $db;
         $this->userManager = $user;


     }


     function get_dbRow($id) {
       $sql = 'SELECT * FROM *PREFIX*ncdisbo where id="'. $id .'" limit 1';
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(1, $id, \PDO::PARAM_INT);
       $stmt->execute();
       $db_row = $stmt->fetch();
       $stmt->closeCursor();
       return $db_row;
     }


     function get_dbRows($p,$search) {
       $items_per_page = 10;
       $page_number = filter_var($p, FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
       $position = (($page_number-1) * $items_per_page);
       if($search != "") { $searchpattern = "AND (title like '%".$search."%' or content like '%".$search."%')"; } else { $searchpattern=""; }
       $sql = 'SELECT * FROM *PREFIX*ncdisbo where reply=0 '. $searchpattern .' order by pinned DESC , ts desc LIMIT ' . $position . ',' . $items_per_page;
       $stmt = $this->db->prepare($sql);
       #$stmt->bindParam(1, $id, \PDO::PARAM_INT);
       $stmt->execute();
       $db_rows = $stmt->fetchall();
       $stmt->closeCursor();
       return $db_rows;
     }


     function get_categories() {
       $sql = 'SELECT * FROM *PREFIX*ncdisbocat order by category asc';
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(1, $id, \PDO::PARAM_INT);
       $stmt->execute();
       $categories = $stmt->fetchall();
       $stmt->closeCursor();
       return $categories;

     }

     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      */

      function mytime() {
        date_default_timezone_set("Europe/Berlin");
        $datetime = date('Y-m-d H:i:s');
        return $datetime;
      }

      function gen_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
      }

      function create_categories_dropdown() {
        $data = $this->get_categories();
        $ret = "<select id='dbCat' name='Category'>";
        foreach($data as $category) {
          $ret = $ret . "<option vlaue='". $category ."'>" . $category['category'] . "</option>";
        }
        $ret = $ret . "</select>";
        return $ret;
      }

      function create_topics_row($title, $category, $author, $replies, $views, $activity, $uuid, $ts, $pin) {
        if ($pin == 1) { $pinned = "btn-pinned"; } else { $pinned = ""; }
        $row = '<div id="db-topic-div-'. $uuid .'" class="db-topic-div"><table class="db-topics-table">
                <tr class="db-topics-row" id="'. $uuid .'">
                <td class="db-topics-row-td"><h2>'. $title . '</h2><br><p style="cursor:pointer;font-size: 0.7em">
                <img class="img-round" src="data:image/png;base64,'. $this->userManager->get($author)->getAvatarImage(32) .'"><br>'. $author .'<br>'. $ts .'</p></td>
                <td class="db-topics-row-td" style="width:250px">'. $category .'</td>
                <td class="db-topics-row-td" style="width:100px; text-align: center">'. $replies .'</td>
                <td class="db-topics-row-td" style="width:100px; text-align: center">'. $views .'</td>
                <td class="db-topics-row-td" style="width:150px; text-align: center">'. $activity .'</td>
                </tr><tr><td colspan="5" style="text-align:right;">
                <button title="pin this Topic to Top" uuid="'. $uuid. '" id="pin-'. $uuid .'" class="btn-pin '. $pinned. '"></button>
                <button title="comment this Topic" id="reply-'. $uuid .'" class="btn-reply"></button></td></tr>
                <tr><td colspan="5" class="db-topics-content-td">
                <div class="db-topic-content" id="db-topic-content-'. $uuid .'" style="display:none"> </div>
                </td></tr></table></div>';
        return $row;
      }

      /**
       * @NoCSRFRequired
       * @NoAdminRequired
      */
      public function TopicFormHeader($id) {

        $r= "";
        if ($id == "new") {
          $r .= "Subject <input type='text' name='db-new-topic-name' class='db-new-topic-input'> ";
          $r .= "Category ";
          $r .= $this->create_categories_dropdown($id);
        } else {
          $r .=  "<h2>";
          $db_data = $this->get_dbRow($id);
          $r .= $db_data["title"];
          $r .= "  [" . $db_data["category"] . "]";
        }
        $r .= "</h2><input type='hidden' name='action' value=''>";
        $r .= "<input type='hidden' id='nrs-uuid' name='nrs-uuid' value='". $db_data["uuid"] ."'> ";
        $r .= "<input type='hidden' id='nrs-id' name='nrs-id' value='". $id ."'> ";
        $r .= "";
        return $r;
      }

      public function TopicContent($id) {
        $db_data = $this->get_dbRow($id);
        return $db_data["content"];
      }

      /**
       * @NoCSRFRequired
       * @NoAdminRequired
       *
       * @param int $id
       * @param string $search
       */
       public function showall($id,$search) {
          $data = $this->get_dbRows($id,$search);
          $ret = "";
          foreach($data as $row) {
            $ret .= $this->create_topics_row($row["title"], $row["category"], $row["user_id"], $row["views"], $replies, $last_action, $row["uuid"], $row["ts"], $row["pinned"]);
          }
          return $ret;
       }





       function get_topic_content($uuid) {
         $sql = "SELECT * FROM *PREFIX*ncdisbo where uuid='". $uuid ."' order by ts asc";
         $stmt = $this->db->prepare($sql);
         $stmt->bindParam(1, $id, \PDO::PARAM_INT);
         $stmt->execute();
         $t_content = $stmt->fetchall();
         $stmt->closeCursor();
         return $t_content;
       }
     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      *
      * @param int $id
      */
     public function showTopic($uuid) {

       $data = $this->get_topic_content($uuid);
       $t_row = "";
       foreach($data as $tdata) {
         if ($tdata["reply"] == 1) { $delid = $tdata["id"]; } else {$delid = $tdata["uuid"];}
         $t_row .= "<div id='topic-content-". $delid ."'><table width='100%' border='0'><tr class='db-topics-content-tr'>
                    <td class='db-topics-content-td' style='vertical-align:top;width:250px;'>
                    <img class='img-round' src='data:image/png;base64,". $this->userManager->get($tdata['user_id'])->getAvatarImage(32) ."'><br>
                    <p style='font-size: 0.7em'>" . $tdata['user_id'] ."</p><br>
                    <p style='font-size: 0.7em'>". $tdata["ts"]. "</p></td>
                    <td id='db-topics-content-td-". $tdata["id"] ."' class='db-topics-content-td' style='vertical-align:top'>". $tdata['content'] ."</td>
                    <td class='db-topics-content-td' style='vertical-align:top; text-align:right; width:150px;'>
                    <button class='btn-edit-topic btn' id='". $tdata["id"] ."'></button>
                    <button class='btn-del-topic btn' id='". $delid ."'></button>
                    </td>
                    </tr></table></div>";
       }
       return $t_row;
     }

     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      *
      * @param string $title
      * @param string $content
      * @param string $category
      */
     public function createTopic($title, $content, $category) {

          #print ("titel " . $title . "  uuid " . $uuid);
          return $this->add_topic($title,$category,$content,$this->userId);
          #return $uuid . " - " . $title . " - " .  $content ." " . $category  . " " . $this->userId;

     }

     function add_topic($title,$category,$content,$user) {
       $uuid = $this->gen_uuid();
       $dt = $this->mytime();
       $sql = "INSERT INTO oc_ncdisbo (ts,uuid,title,reply,user_id,content,category) VALUES ('". $dt ."','". $uuid ."', '". $title ."',0,'". $user ."','". $content ."','". $category ."')";
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(1, $id, \PDO::PARAM_INT);
       $stmt->execute();
       return $uuid;
     }

     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      *
      * @param string $content
      * @param int $id
      */
     public function saveTopic($id, $content) {
        $dt = $this->mytime();
        $sql = "update oc_ncdisbo set content='". $content ."',ts='". $dt ."' WHERE id='". $id ."'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $sql;
     }


     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      *
      * @param int $pin
      * @param string $uuid
      */
     public function pinTopic($uuid, $pin) {
        $dt = $this->mytime();
        $sql = "update oc_ncdisbo set pinned='". $pin ."' WHERE uuid='". $uuid ."'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $sql;
     }


     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      * @param string $id
      */
     public function deleteTopic($id) {
       if (preg_match('/-/',$id)) {
         $sql = "DELETE from  oc_ncdisbo WHERE uuid='". $id ."'";
       }
       else {
         $sql = "DELETE from  oc_ncdisbo WHERE id='". $id ."'";
       }
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(1, $id, \PDO::PARAM_INT);
       $stmt->execute();
       return $sql;
     }

 }

 ?>
