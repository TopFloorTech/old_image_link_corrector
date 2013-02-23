<?php
require_once '../old_image_link_corrector.module';
class LinkExtractorTest extends PHPUnit_Framework_TestCase
{
  public function testEmptyString()
  {
    $matches = old_image_link_corrector_parse_text('');
    $this->assertEquals(NULL, $matches);
  }
  public function testOneShortImageLink()
  {
    $full_link = '/styles/style1/public/insert/test.png';
    $matches = old_image_link_corrector_parse_text($full_link);
    $this->assertEquals(1, count($matches));
    $this->assertEquals($full_link, $matches[0]['full_link']);
    $this->assertEquals('style1', $matches[0]['style_name']);
    $this->assertEquals('public', $matches[0]['scheme']);
    $this->assertEquals('insert/test.png', $matches[0]['path']);
  }
  public function testOneFullImageLink()
  {
    $full_link = '/sites/default/files/styles/style1/public/insert/test.png';
    $matches = old_image_link_corrector_parse_text($full_link);
    $this->assertEquals(1, count($matches));
    $this->assertEquals($full_link, $matches[0]['full_link']);
  }
  public function testProcessOnlyLink() {
    $text = '/sites/default/files/styles/style1/public/insert/test.png';
    $this->assertEquals('REPLACE', _old_image_link_corrector_process($text));
  }
  public function testProcessOneLinkWhitOtherText() {
    $text = 'img="/sites/default/files/styles/style1/public/insert/test.png"';
    $this->assertEquals('img="REPLACE"', _old_image_link_corrector_process($text));
  }
  public function testProcessLinksWhitOtherText() {
    $text = 'text img="/sites/default/files/styles/style1/public/insert/test.png" text img="/sites/default/files/styles/style1/public/insert/test.png" text';
    $this->assertEquals('text img="REPLACE" text img="REPLACE" text', _old_image_link_corrector_process($text));
  }
  public function testProcessFullURL() {
    $text = 'img="http://example.com/sites/default/files/styles/style1/public/insert/test.png"';
    $this->assertEquals('img="REPLACE"', _old_image_link_corrector_process($text));
  }
  public function testNewImageLink()
  {
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
