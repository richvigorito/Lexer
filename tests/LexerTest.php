<?php


$dir = dirname(__FILE__);
require_once ($dir.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'LexicalScanner.php');



use rv\Lexer\LexicalScanner;

class LexerTest extends PHPUnit_Framework_TestCase {

  public function testLexicalScanner()
  {
        // test to just see can construct a LexicalScanner w/o error
        $lexer = new rv\Lexer\LexicalScanner;
        $this->assertTrue(1 == true);
  }  


}

