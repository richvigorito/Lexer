<?php

namespace rv\Lexer;

use rv\Lexer\ExpressionTree;


class LexicalScanner{

  public static $grammars = array();
  public static $debug = false;

  function __construct(array $grammars = array(),$debug = false)
  {
      self::$grammars = $grammars;
      self::$debug = $debug;
  }


  public static function parse($source, $debug_level = 0)
  {
    try{
      $tokens = explode(' ',$source);       
      $stack = new ExpressionTree ($tokens);

 			if ( $debug_level > 0 ){
				$x =  self::run($stack,$debug_level); 
				print_r($x);
 				for( $i = 1 ; $i < $debug_level; $i++){
 					$x =  self::run($x,$debug_level); 
					print_r($x);
 				}
 				exit;
			} else {
      	return  self::run($stack); 
			}
    } catch (Exception $e){
      print_R(array($e,$stack));
    }
  }

  public static function run($stack, $debug = 0)
  {
    $stack_string = $stack->getParseString();
    $tmp_stack = new ExpressionTree;
    $start = 0;
    while($start < $stack->stringCount()) {
      $no_match = true;
      $length = $stack->stringCount() - $start;
      $first_string = $stack->getFirstNode($start,$length);
       
      while($length && (($start + $length) > 0)) {
        $string = $stack->getParseString($start,$length);
        $result = static::_match($string);
  
        if($result === false){  
          $length--;
        } else {
          $sub_array = $stack->getSubArr($start,$length); 
          $sub_stack = new ExpressionTree($sub_array);
          $tmp_stack->push(array($result['token'] => $sub_stack));
          $start += $length;
          $no_match = false;
          break;
        }
      }

      if($no_match){
        $tmp_stack->push($first_string);
        $start++;
      }
    }
    $line = $tmp_stack->getParseString();


    /*
	recurse until find T_TERM (terminate). 
	if the $line == $stack_string then we will
        be in an infinite loop. in such case, catch this,
	puth the stack in an ERROR key array, hopefully
	we can still use some of the info 
   */
    if($line == 'T_TERM' or $debug) {          
				return $tmp_stack;
    } elseif($line == $stack_string)  {
			$has_term = $tmp_stack->getNode('T_TERM');	
      $err_stack = new ExpressionTree;
			if(!empty($has_term))
      	$err_stack->push(array('ERROR' => $has_term));
			elseif(!empty($sub_stack))
      	$err_stack->push(array('ERROR' => $sub_stack));
			else
      	$err_stack->push(array('ERROR' => $tmp_stack));
      return $err_stack;
    }
    return self::run($tmp_stack);
  }

  protected static function _match($string) {
    foreach(self::$grammars as $pattern => $name){
        if(preg_match($pattern, $string, $matches)) {
            return array(
                'match' => $matches[0],
                'token' => $name,
            );
        }
    }
    return false;
  }
}
