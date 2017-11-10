<?php
 namespace OCA\YaDb\Controller;

  use Exception;

  use OCP\IRequest;
  use OCP\AppFramework\Http;
  use OCP\AppFramework\Http\DataResponse;
  use OCP\AppFramework\Controller;
  use OCP\IDbConnection;



 class DisboDBController extends Controller {


    protected $userId;
    private $db;


     public function __construct($AppName, IRequest $request, IDbConnection $db, $UserId ){
         parent::__construct($AppName, $request);
         $this->userId = $UserId;
         $this->db = $db;

     }

     function currentUser() {
        $this->$uContainer->registerService('User', function($c) {
            return $c->query('UserSession')->getUser();
        });

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


     function get_dbRows($id) {
       $sql = 'SELECT * FROM *PREFIX*ncdisbo where reply="0" order by ts';
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(1, $id, \PDO::PARAM_INT);
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

        $ret = "<select name='Category'>";

        foreach($data as $category) {
          $ret = $ret . "<option>" . $category['category'] . "</option>";
        }
        $ret = $ret . "</select>";
        return $ret;
      }

      function create_topics_row($title, $category, $author, $replies, $views, $activity, $uuid) {
        $row = '<table class="db-topics-table">
                <tr class="db-topics-row" id="'. $uuid .'">
                <td class="db-topics-row-td">'. $title . '<br><p style="cursor:pointer;font-size: 0.7em">created by:  '. $author .'</p></td>
                <td class="db-topics-row-td" style="width:250px">'. $category .'</td>
                <td class="db-topics-row-td" style="width:100px; text-align: center">'. $replies .'</td>
                <td class="db-topics-row-td" style="width:100px; text-align: center">'. $views .'</td>
                <td class="db-topics-row-td" style="width:150px; text-align: center">'. $activity .'</td>
                </tr><tr><td colspan="5" class="db-topics-content-td">
                <div class="db-topic-content" id="db-topic-content-'. $uuid .'" style="display:none"> </div>
                </td></tr></table>';
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
       */
       public function showall($id) {
          $data = $this->get_dbRows($id);
          $ret = "";
          foreach($data as $row) {
            $ret .= $this->create_topics_row($row["title"], $row["category"], $row["user_id"], $row["views"], $replies, $last_action, $row["uuid"]);
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
         $t_row .= "<table width='100%'><tr class='db-topics-content-tr'>
                    <td class='db-topics-content-td' width='200' style='vertical-align:top'>". $tdata['user_id'] ."<br><p style='font-size: 0.7em'>". $tdata["ts"]. "</p></td>
                    <td class='db-topics-content-td' style='vertical-align:top'>". $tdata['content'] ."</td>
                    <td class='db-topics-content-td' width='100' style='vertical-align:top; text-align:right'><a class='btn-edit-topic' id='". $tdata["id"] ."'> edit</a> | <a  class='btn-del-topic' id='". $tdata["id"] ."'>delete</a> </td>
                    </tr></table>";
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
          $uuid = $this->gen_uuid();
          $dt = $this->mytime();
          #print ("titel " . $title . "  uuid " . $uuid);
          return $this->add_topic($uuid,$title,$category,$content,$this->userId);
          #return $uuid . " - " . $title . " - " .  $content ." " . $category  . " " . $this->userId;

     }

     function add_topic($uuid,$title,$category,$content,$user) {
       $sql = "INSERT INTO oc_ncdisbo (uuid,title,reply,user_id,content,category) VALUES ('". $uuid ."', '". $title ."',0,'". $user ."','". $content ."','". $category ."')";
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(1, $id, \PDO::PARAM_INT);
       $stmt->execute();
       return $uuid;
     }

     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      *
      * @param string $title
      * @param string $content
      * @param string $category
      * @param int $id
      */
     public function update($id, $title, $content, $category) {
        $dt = mytime();

     }

     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      * @param int $id
      */
     public function destroy($id) {
         // empty for now
     }

 }

 ?>
