<?php
 namespace OCA\YaDb\Controller;

  use Exception;

  use OCP\IRequest;
  use OCP\AppFramework\Http;
  use OCP\AppFramework\Http\DataResponse;
  use OCP\AppFramework\Controller;
  use OCP\IDbConnection;
  use OCP\IUserManager;
  use OCP\IGroupManager;
  use OCP\IConfig;
  use OCP\IUserSession;




 class DisboDBController extends Controller {


    protected $userId;
    protected $config;
    protected $userSession;
    private $db;
    public $userManager;
    public $groupManager;


     public function __construct($AppName, IConfig $config, IRequest $request, IDbConnection $db, $UserId, IUserManager $user, IGroupManager $group, IUserSession $userSession){
         parent::__construct($AppName, $request);
         $this->userId = $UserId;
         $this->db = $db;
         $this->userManager = $user;
         $this->groupManager = $group;
         $this->config = $config;
         $this->userSession = $userSession;



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
        $timestamp = time();
        return $timestamp;
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



      function isAdmin() {
        $user = $this->userSession->getUser();
        if ($this->groupManager->isInGroup($user->getUID(), 'admin')) {
          return true;
        }
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

      function replycount($uuid) {
        $sql = "SELECT * from *PREFIX*ncdisbo where uuid='".$uuid."' and reply='1'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        $cc = $stmt->rowCount();
        $stmt->closeCursor();
        return $cc;
      }


      function viewcount($id) {
        $sql = "SELECT views from *PREFIX*ncdisbo where id='".$id."'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        $cc = $stmt->fetch();
        $stmt->closeCursor();
        return $cc['views'];
      }

      function ActivityMath($uuid) {
        $sql = "SELECT ts from *PREFIX*ncdisbo where uuid='".$uuid."' order by ts desc";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id, \PDO::PARAM_INT);
        $stmt->execute();
        $cc = $stmt->fetch();
        $stmt->closeCursor();
        $curTS = time() + 3600;
        return $curTS - $cc['ts'];
      }

      function create_topics_row($id, $title, $category, $author, $replies, $views, $activity, $uuid, $ts, $pin) {
        if ($pin == 1) { $pinned = "btn-pinned"; } else { $pinned = ""; }
        if ($this->isAdmin()) {
          $ad = "admin";
          $pin_button = '<button title="pin this Topic to Top" uuid="'. $uuid. '" id="pin-'. $uuid .'" class="btn-pin '. $pinned. '"></button>';
        } else {
          $ad = "member";
          $pin_button = "";
        }
        $replies = $this->replycount($uuid);
        $views = $this->viewcount($id);
        $last_Activity = $this->ActivityMath($uuid);
        $last_Ahrs = $last_Activity / 3600;
        $last_Amin = $last_Activity / 60;
        if ($last_Ahrs > 24 && $last_Ahrs < 168) { $act = round(($last_Ahrs / 24), 0, PHP_ROUND_HALF_UP ) . " days"; }
        if ($last_Ahrs > 168 && $last_Ahrs< 1176 ) {$act = round(($last_Ahrs / 24 / 7), 0, PHP_ROUND_HALF_UP ) . " weeks";  }
        if ($last_Ahrs > 1176 ) {$act = round(($last_Ahrs / 24 / 7 / date('t')), 0, PHP_ROUND_HALF_UP ) . " month";  }
        if ($last_Ahrs < 24) { $act = round($last_Ahrs, 0, PHP_ROUND_HALF_UP) . " hours";}
        if ($last_Amin < 60) { $act = round($last_Amin, 0, PHP_ROUND_HALF_UP) . " minutes";}
        $row = '<div id="db-topic-div-'. $uuid .'" class="db-topic-div"><table class="db-topics-table">
                <tr class="db-topics-row" id="'. $uuid .'">
                <td colspan="5" class="db-topics-row-td"><h3>'. $title . '</h3><br><p style="cursor:pointer;font-size: 0.7em">
                <img class="img-round" src="data:image/png;base64,'. $this->userManager->get($author)->getAvatarImage(32) .'"><br>'. $author .'<br>'. date('Y-m-d H:i:s', $ts) .'</p></td>
                </tr><tr>
                <td colspan="4">
                <p class="topic-footer">Category:'. $category .'</p>
                <p class="topic-footer">views:'. $views .'</p>
                <p class="topic-footer">replies:'. $replies .'</p>
                <p class="topic-footer">activity:'. $act .'</p>
                </td>
                <td style="text-align:right;">
                '. $pin_button .'
                <button title="comment this Topic" dbid="'. $id .'" id="'. $uuid .'" class="btn-reply"></button></td></tr>
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


      /**
       * @NoCSRFRequired
       * @NoAdminRequired
      */
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
         if ($id == "") {$id = 2;}
          $data = $this->get_dbRows($id,$search);
          $ret = "";
          foreach($data as $row) {
            $ret .= $this->create_topics_row($row["id"], $row["title"], $row["category"], $row["user_id"], $row["views"], $replies, $last_action, $row["uuid"], $row["ts"], $row["pinned"]);
          }
          return $ret;
       }


       function plusView($id) {
         $sql = "UPDATE *PREFIX*ncdisbo SET views= views + 1 where id='". $id ."'";
         $stmt = $this->db->prepare($sql);
         $stmt->bindParam(1, $id, \PDO::PARAM_INT);
         $stmt->execute();
         $stmt->closeCursor();
       }

       function get_topic_content($uuid) {
         $sql = "SELECT * FROM *PREFIX*ncdisbo where uuid='". $uuid ."' order by ts asc";
         $stmt = $this->db->prepare($sql);
         $stmt->bindParam(1, $id, \PDO::PARAM_INT);
         $stmt->execute();
         $t_content = $stmt->fetchall();
         $stmt->closeCursor();
         $this->plusView($t_content[0]['id']);

         return $t_content;
       }
     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      *
      * @param string $uuid
      */
     public function showTopic($uuid) {

       $data = $this->get_topic_content($uuid);
       $t_row = "";
       foreach($data as $tdata) {
         if ($tdata["reply"] == '1') { $delid = $tdata["id"]; } else {$delid = $tdata["uuid"];}
         if ($this->isAdmin()) {
           $ad = "admin";
           $del_button = "<button class='btn-del-topic btn' id='". $delid ."'></button>";
         } else {
           $ad = "member";$pin_button = "";
           $del_button = "";
         }
         if($this->userId == $tdata['user_id'] || $this->isAdmin() ) {
           $edit_button = "<button class='btn-edit-topic btn' id='". $tdata["id"] ."'></button>";
         }
         else {
           $edit_button = "";
         }
         $t_row .= "<div id='topic-content-". $delid ."'><table width='100%' border='0'><tr class='db-topics-content-tr'>
                    <td class='db-topics-content-td db-topics-content-left'>
                    <img class='img-round' src='data:image/png;base64,". $this->userManager->get($tdata['user_id'])->getAvatarImage(32) ."'><br>
                    <p style='font-size: 0.7em'>" . $tdata['user_id'] ."</p><br>
                    <p style='font-size: 0.7em'>". date('Y-m-d', $tdata["ts"]) . "<br>". date('H:i:s', $tdata["ts"]) ."</p></td>
                    <td id='db-topics-content-td-". $tdata["id"] ."' class='db-topics-content-td db-topics-content-center' style='vertical-align:top'>
                    <div class='db-content-div' id='db-topics-content-div-". $tdata["id"] ." style='text-align:left;'>". $tdata['content'] ."</td></div>
                    <td class='db-topics-content-td db-topics-content-right' style='text-align:right;'>
                    ". $edit_button ."
                    ". $del_button ."
                    </td>
                    </tr></table></div><div id='trenner-". $delid ."' class='trenner'></div>";
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
      * @param string $content
      * @param string $uuid
      */
     public function replyTopic($uuid, $content) {
        $dt = $this->mytime();
        $sql = "insert into oc_ncdisbo (uuid,content,reply,ts,user_id) values ('". $uuid ."','". $content ."', '1', '". $dt ."','". $this->userId ."')";
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
