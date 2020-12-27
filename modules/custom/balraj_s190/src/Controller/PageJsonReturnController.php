<?php

namespace Drupal\balraj_s190\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for JSON Response.
 */
class PageJsonReturnController extends ControllerBase {
	/**
	* This function only returns the JSON representation of page type entities only
 	* @param $nid : node id
 	* @return $jsonResp : Entity information 
 	*/
  public function getJsonResp($nid) {
  	$siteApiKey = \Drupal::config('system.site')->get('siteapikey');

  	if (!empty($nid) && !empty($siteApiKey)) {

  		// Creating Json response of node
			$node_storage = \Drupal::entityTypeManager()->getStorage('node');
			$node = $node_storage->load($nid);
			if ($node->getType() === 'page') {
				$jsonArray = array(
					'type' => $node->getType(),
					'id' => $nid,
					'attributes' => array(
						'title' => $node->title->value,
						'body' => ($node->body->value) ? html_to_obj($node->body->value) : '',	//using helper function to convert HTML to JSON
						'authored_on' => gmdate("Y-m-d\TH:i:s\Z", $node->created->value)
					)
				);
				return new JsonResponse($jsonArray);
			} else {
				return new JsonResponse("Access Denied");
			}
  	} else {
  		return new JsonResponse("Access Denied");
  	}
  }
}