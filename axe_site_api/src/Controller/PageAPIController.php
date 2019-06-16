<?php
/**
 * Created by PhpStorm.
 * User: arulan
 */

namespace Drupal\axe_site_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class PageAPIController
 * @package Drupal\axe_site_api\Controller
 */
class PageAPIController extends ControllerBase {

  /**
   * @param $api
   * @param $nid
   * Return the JSON response of node.
   * @return JsonResponse
   */
  public function getPageJson($api, $nid) {

    // Fetching the 'siteapikey' config value.
    $site_config = $this->config('system.site');
    $config_api = $site_config->get('siteapikey');

    // Whether given API key is valid or not.
    if ($config_api === $api) {
      $nodes = \Drupal::entityTypeManager()->getStorage('node')
        ->loadByProperties([
          'type' => 'page',
          'nid' => $nid,
        ]);

      if (!empty($nodes)) {
        foreach ($nodes as $node) {
          return new JsonResponse([
            'Node ID' => $node->id(),
            'Node Description' => $node->get('body')->value,
          ]);
        }
      }
      else {
        return new JsonResponse([
          'Error' => '404',
          'Error Message' => $this->t('Content not Found (or) Content Not Published'),
        ]);
      }
    }
    else {
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
    }
  }

}
