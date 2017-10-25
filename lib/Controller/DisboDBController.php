<?php
 namespace OCA\YaDb\Controller;

  use Exception;

  use OCP\IRequest;
  use OCP\AppFramework\Http;
  use OCP\AppFramework\Http\DataResponse;
  use OCP\AppFramework\Controller;

  use OCP\IDbConnection;

 class DisboDBController extends Controller {


    private $userId;
    private $db;

     public function __construct($AppName, IRequest $request, IDbConnection $db, $UserId ){
         parent::__construct($AppName, $request);

         $this->userId = $UserId;
         $this->db = $db;

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
                <td class="db-topics-row-td">'. $title . '</td>
                <td class="db-topics-row-td" style="width:250px">'. $category .'</td>
                <td class="db-topics-row-td" style="width:250px">'. $author .'</td>
                <td class="db-topics-row-td" style="width:100px; text-align: center">'. $replies .'</td>
                <td class="db-topics-row-td" style="width:100px; text-align: center">'. $views .'</td>
                <td class="db-topics-row-td" style="width:150px; text-align: center">'. $activity .'</td>
                </tr><tr><td colspan="6">
                <div class="db-topic-content" id="db-topic-content-'. $uuid .'" style="display:none"></div>
                </td></tr></table>';
        return $row;
      }

      /**
       * @NoCSRFRequired
       * @NoAdminRequired
      */
      public function NewReplyTopic($id) {

        $r= "";
        if ($id == "new") {

          $r .= "Subject <input type='text' name='db-new-topic-name' class='db-new-topic-input'> ";
          $r .= "Category ";
          $r .= $this->create_categories_dropdown($id);
        } else {
          $r .=  "Subject: ";

          $r .= "<input type='hidden' name='uuid' value='". $id ."'> ";
        }
        $r .= "<br><br>";
        return $r;

      }
      
      /**
       * @NoCSRFRequired
       * @NoAdminRequired
       *
       * @param int $id
       */
       public function show($id) {
          return $this->create_topics_row("title", "Music", "Denis", "0", "1", "10 Minutes", "uuid-4353453-34535-34534");
       }


       function get_topic_content($uuid) {
         $sql = 'SELECT * FROM *PREFIX*ncdisbo where uuid="'. $uuid .'" order by ts asc';
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
     public function showTopic($id) {

       $data = $this->get_topic_content($id);
       $response = "<h3>". $data[0]['title'] ."</h3><br>from: ". $data[0]['user_id'] ."<br>". $data[0][ts];
       foreach($data as $topicdata) {
         $response = $response . "<option>" . $topicdata['title'] . "</option>";
       }
       return $response;

     }
     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      */
     public function showall() {
         // empty for now
     }
     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      *
      * @param string $title
      * @param string $content
      * @param string $category
      */
     public function create($title, $content, $category) {
          $uuid = gen_uuid();
          $dt = mytime();

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
