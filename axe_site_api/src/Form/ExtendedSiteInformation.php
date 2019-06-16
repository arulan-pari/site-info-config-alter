<?php
/**
 * Created by PhpStorm.
 * User: arulan
 */

namespace Drupal\axe_site_api\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;

/**
 * Class ExtendedSiteInformation
 * @package Drupal\axe_site_api\Form
 */
class ExtendedSiteInformation extends SiteInformationForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $site_config = $this->config('system.site');
    $form = parent::buildForm($form, $form_state);
    $form['site_information']['siteapikey'] = [
      '#type' => 'textfield',
      '#title' => t('Site API Key'),
      '#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
      '#description' => t("Custom field to set the API Key"),
    ];

    return $form;
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   * Store the 'siteapikey' under the 'system.site'.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('system.site')
      ->set('siteapikey', $form_state->getValue('siteapikey'))
      ->save();
    parent::submitForm($form, $form_state);
    \Drupal::messenger()->addMessage(t('The site API Key has been saved with that value.'), 'status');
  }
}