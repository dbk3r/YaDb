<?php
namespace OCA\YaDb\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;


class PageController extends Controller {


	public $mapper;
	public static $userId;

	public function __construct($AppName, IRequest $request,  $UserId){
		parent::__construct($AppName, $request);
		$this->mapper = $mapper;
		$this->userId = $UserId;

	}


	/**
	 * @NoCSRFRequired
	 * @NoAdminRequired
	 */

	public function index() {
		return new TemplateResponse('yadisbo', 'index');  // templates/index.php
	}

}
?>
