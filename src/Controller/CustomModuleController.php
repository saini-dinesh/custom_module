<?php

/**
 * @file
 * Contains \Drupal\custom_module\Controller\CustomModuleController.
 */

namespace Drupal\custom_module\Controller;

use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

class CustomModuleController extends ControllerBase {

  /**
   * Get Page Info in JSON Presentation
   */
  public function getPageInfo($site_api_key, $page_id) {

    // Get Site API key set in Systam Site Settings page
    $siteapikey = \Drupal::config('siteapikey.configuration')->get('siteapikey');

    // To check if Site API key is not valid
    if ($siteapikey == 'No API Key yet' || $siteapikey == '' || $siteapikey != $site_api_key) {
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
    }

    // Load Node/Page data
    $node = Node::load($page_id);
    $node_type = $node->bundle();

    // To check if Node Type is not valid
    if ($node_type != 'page') {
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
    }

    // JSON Presentation of Page data
    $serializer = \Drupal::service('serializer');
    $json_data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
    return new Response($json_data);
  }

}
