<?php

require_once('./ExpressionTree.php');

class LexicalScanner{

  public static $grammars = [];
  public static $debug = false;

  function __construct(array $grammars = array(),$debug = false)
  {
      self::$grammars = $grammars;
      self::$debug = $debug;
  }


  public static function parse($source)
  {
    try{
      $tokens = explode(' ',$source);       
      $stack = new ExpressionTree ($tokens);
      return  self::run($stack); 
    } catch (Exception $e){
      print_R([$e,$stack]);
    }
  }

  public static function run($stack)
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
          $tmp_stack->push([$result['token'] => $sub_stack]);
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


    if($line == 'T_EXPR')          return $tmp_stack;

    elseif($line == $stack_string) 
    {
      // @TODO ... better error handling
      print_r([$tmp_stack,'ERROR']);
      exit;
        //throw new Exception ("about to approach infinite recursive loop");
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
