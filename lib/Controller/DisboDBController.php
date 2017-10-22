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

       public function show($id) {
          return $this->create_categories_dropdown();
       }

     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      *
      * @param int $id
      */
     public function show_topic($id) {
       try {
            return new DataResponse($this->mapper->find($id, $this->userId));
        } catch(Exception $e) {
            return new DataResponse([], Http::STATUS_NOT_FOUND);
        }
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
