<?php

namespace rv\Lexer;

class ExpressionTree {
  
  var $arr ; 

  public function __construct(array $arr=[]){
      $this->arr = $arr;
  }

  public function push($x){
    $this->arr[] = $x;
  }

  public function getNode($node_name)
  {
    foreach ( $this->arr as $k => $v){
      if(is_array($v)){
        if( isset($this->arr[$k][$node_name])) 
            return $this->arr[$k][$node_name];
      }
    }
    return false;
  } 

  public function stringCount(){
    return count($this->arr);
  }

  public function getFirstNode($offset,$length)
  {
    $str =  $this->getParseString($offset,$length);
    $arr =  $this->getSubArr($offset,$length);
    if(strpos($str,'T_') === false)  return $str;
    else                                return $arr[0];
  }


  public function getSubArr($offset,$length){
   return array_slice($this->arr,$offset,$length);
  }

  public function getParseString($offset=0,$length = null,$x=false){
    $length = ( $length !== null ) ? $length : count($this->arr); ; 
    $str = '';
    $idx = 0 ;
    $tmp_array = self::getSubArr($offset,$length);

    foreach($tmp_array as $k => $v){
      if(is_array($v)){
        $keys = array_keys($v);
        $str .= trim(array_pop($keys)) . ' ';
      } else {
        $str .= trim($v) . ' ';
      }
    }
    return trim($str); 
  }
}
