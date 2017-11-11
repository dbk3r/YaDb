<?php
namespace OCA\YaDb\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use \OCP\AppFramework\Http\ContentSecurityPolicy;
use \OCP\Util;


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

		$params = array('user' => $this->userId, 'shareMode' => $shareMode);
		$response = new TemplateResponse('yadisbo', 'index', $params);
		$ocVersion = \OCP\Util::getVersion();
		if ($ocVersion[0] > 8 || ($ocVersion[0] == 8 && $ocVersion[1] >= 1)) {
			$csp = new \OCP\AppFramework\Http\ContentSecurityPolicy();
			$csp->addAllowedImageDomain('data:');
			$csp->addAllowedImageDomain('blob:');
			$csp->addAllowedImageDomain('*');
			$csp->addAllowedFrameDomain('data:');
			$csp->addAllowedFrameDomain('blob:');


			$allowedFrameDomains = array(
				'https://www.youtube.com',
				'https://soundcloud.com',
				'https://w.soundcloud.com',
				'http://berlin-art.work'
			);
			foreach ($allowedFrameDomains as $domain) {
				$csp->addAllowedFrameDomain($domain);
			}

			$csp->addAllowedScriptDomain("'nonce-test'");
			$response->setContentSecurityPolicy($csp);
		}
		return $response;

	}

}
?>
