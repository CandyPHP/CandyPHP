<?php

namespace Candy;

class Str{
  function __construct($string=''){
    $this->str = $string;
    $this->any = false;
  }

  public function is($val){
    $any = $this->any;
    $this->any = false;
    if(!is_array($val)) $val = [$val];
    $result = !$any;
    if(in_array('json',$val)){
      json_decode($this->str);
      if($any) $result = $result || json_last_error() === JSON_ERROR_NONE;
      else     $result = $result && json_last_error() === JSON_ERROR_NONE;
    }
    if(in_array('md5',$val)){
      if($any) $result = $result || ((bool) preg_match('/^[a-f0-9A-F]{32}$/', $this->str));
      else     $result = $result && ((bool) preg_match('/^[a-f0-9A-F]{32}$/', $this->str));
    }
    if(in_array('numeric',$val)){
      if($any) $result = $result || is_numeric($this->str);
      else     $result = $result && is_numeric($this->str);
    }
    return $result;
  }

  public function isAny($val){
    $this->any = true;
    return $this->is($val);
  }

  public function contains($val){
    $any = $this->any;
    $this->any = false;
    if(!is_array($val)) $val = [$val];
    $result = !$any;
    foreach($val as $key){
      if($any) $result = $result || (strpos($this->str, $key) !== false);
      else     $result = $result && (strpos($this->str, $key) !== false);
    }
    return $result;
  }

  public function containsAny($val){
    $this->any = true;
    return $this->contains($val);
  }

  public function replace($arr){
    return \str_replace(array_keys($arr),$arr,$this->str);
  }

  public function isBegin($var){
    $str = $this->str;
    return substr($str,0,strlen($var)) == $var;
  }

  public function isEnd($var){
    $str = $this->str;
    return substr($str,0 - strlen($var)) == $var;
  }

  public function slug(){
    $str = $this->str;
    $str = preg_replace('~[^\pL\d]+~u', '-', $str);
    $str = iconv('utf-8', 'us-ascii//TRANSLIT', $str);
    $str = preg_replace('~[^-\w]+~', '', $str);
    $str = trim($str, '-');
    $str = preg_replace('~-+~', '-', $str);
    $str = strtolower($str);
    if(empty($str)) return '';
    return $str;
  }

  public function format($format){
    $str = $this->str;
    $result = '';
    $letter = 0;
    for ($i=0; $i < strlen($format); $i++) {
      if(substr($format,$i,1)=='?'){
        $result .= substr($str,$letter,1);
        $letter = $letter + 1;
      }elseif(substr($format,$i,1)=='*'){
        $result .= substr($str,$letter);
        $letter = $letter + strlen(substr($str,$letter));
      }else{
        $result .= substr($format,$i,1);
      }
    }
    return $result;
  }
}
