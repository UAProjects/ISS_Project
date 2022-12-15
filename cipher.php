<?php

function get_cipher_from_int($number){
  switch ($number) {
    case '0':
      return "AES-256-CBC";
      break;
    case '1':
      return "AES-192-CBC";
      break;
    case '2':
      return "AES-128-CBC";
      break;
    
    default:
      return "AES-256-CBC";
      break;
  }
}


?>