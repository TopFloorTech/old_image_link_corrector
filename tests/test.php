<?php
/**
 * @file
 * Test file for the Old Image corrector module.
 */

require_once '../old_image_link_corrector.module';

/**
 * Test for base functionality
 */
class LinkExtractorTest extends PHPUnit_Framework_TestCase {
  /**
   * Test Empty String
   */
  public function testEmptyString() {
    $matches = old_image_link_corrector_parse_text('');
    $this->assertEquals(NULL, $matches);
  }

  /**
   * Test one short image link
   */
  public function testOneShortImageLink() {
    $full_link = '/styles/style1/public/insert/test.png';
    $matches = old_image_link_corrector_parse_text($full_link);
    $this->assertEquals(1, count($matches));
    $this->assertEquals($full_link, $matches[0]['full_link']);
    $this->assertEquals('style1', $matches[0]['style_name']);
    $this->assertEquals('public', $matches[0]['scheme']);
    $this->assertEquals('insert/test.png', $matches[0]['path']);
  }

  /**
   * Test one full image linke
   */
  public function testOneFullImageLink() {
    $full_link = '/sites/default/files/styles/style1/public/insert/test.png';
    $matches = old_image_link_corrector_parse_text($full_link);
    $this->assertEquals(1, count($matches));
    $this->assertEquals($full_link, $matches[0]['full_link']);
  }

  /**
   * Test process only link
   */
  public function testProcessOnlyLink() {
    $text = '/sites/default/files/styles/style1/public/insert/test.png';
    $this->assertEquals('REPLACE', _old_image_link_corrector_process($text));
  }

  /**
   * Test process one link whith other text
   */
  public function testProcessOneLinkWhitOtherText() {
    $text = 'img="/sites/default/files/styles/style1/public/insert/test.png"';
    $this->assertEquals('img="REPLACE"', _old_image_link_corrector_process($text));
  }

  /**
   * Test process links with other text
   */
  public function testProcessLinksWhitOtherText() {
    $text = 'text img="/sites/default/files/styles/style1/public/insert/test.png" text img="/sites/default/files/styles/style1/public/insert/test.png" text';
    $this->assertEquals('text img="REPLACE" text img="REPLACE" text', _old_image_link_corrector_process($text));
  }

  /**
   * Test process full url
   */
  public function testProcessFullURL() {
    $text = 'img="http://example.com/sites/default/files/styles/style1/public/insert/test.png"';
    $this->assertEquals('img="REPLACE"', _old_image_link_corrector_process($text));
  }

  /**
   * Test new image link
   */
  public function testNewImageLink() {
    $full_link = 'sites/default/files/styles/style1/public/insert/test.png?itok=asdf';
    $matches = old_image_link_corrector_parse_text($full_link);
    $this->assertNull($matches);
  }
}
/**
 * Mock image_style_url() function.
 */
function image_style_url() {
  return 'REPLACE';
}
