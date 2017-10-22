<?php
 namespace OCA\YaDb\Controller;

  use Exception;

  use OCP\IRequest;
  use OCP\AppFramework\Http;
  use OCP\AppFramework\Http\DataResponse;
  use OCP\AppFramework\Controller;

  use OCA\YaDb\Db\Disbo;
  use OCA\YaDb\Db\DisboMapper;

 class DisboDBController extends Controller {

    private $mapper;
    private $userId;

     public function __construct($AppName, IRequest $request, DisboMapper $mapper, $UserId ){
         parent::__construct($AppName, $request);
         $this->mapper = $mapper;
         $this->userId = $UserId;

     }

     function mytime() {
       date_default_timezone_set("Europe/Berlin");
       $datetime = date('Y-m-d H:i:s');
       return $datetime;
     }

     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      */

     public function index() {
         return new DataResponse($this->mapper->findAll($this->userId));
     }

     /**
      * @NoCSRFRequired
      * @NoAdminRequired
      *
      * @param int $id
      */
     public function show($id) {
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
