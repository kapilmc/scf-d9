<?php

namespace Drupal\Tests\field_collection_to_paragraphs\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Verify that the stie still works after the module is enabled.
 *
 * @group Form
 */
class SiteStillWorks extends BrowserTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  protected static $modules = ['field_collection_to_paragraphs'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Load the homepage.
   */
  public function testHomepage() {
    // Load the homepage.
    $this->drupalGet('');

    // Confirm an appropriate HTTP response was provided, specifically a 200
    // status.
    $this->assertSession()->statusCodeEquals(200);

    // By default there's nothing visible on the site except for a login form.
    $this->assertSession()->pageTextContains('Log in');
    $this->assertSession()->pageTextContains('Enter your Drupal username');
    $this->assertSession()->pageTextContains('Enter the password that accompanies your username');
  }

}
