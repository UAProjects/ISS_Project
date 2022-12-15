<?php

include "cipher.php";

//check if server reached here by POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  echo "POST Method required";
}
else{
  echo "Encrypting...";
  
  //check for file upload
  if(!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name'])){
    echo "<br>ERROR: no image to encrypt!";
  } else {
    
    //form validation
    if(!isset($_POST['mode'])){
      echo "<br>No Encryption mode selected";
    }
    
    //save a backup of the file inside upload;
    $directory = 'upload/';
    $filename = explode(".", $_FILES['image']['name']);
    $extension = end($filename);
    $fileNewName = $directory. $filename[0] . '_' . round(microtime(true) * 1000) . "." . $extension;
    move_uploaded_file($_FILES['image']['tmp_name'], $fileNewName);
    
    //gets cipher mode from the user selected input 
    $cipher = get_cipher_from_int($_POST['mode']);
    
    //reads the file
    $fileData = file_get_contents($fileNewName);

    //generates an encryption key and iv
    $encryption_key = bin2hex(openssl_random_pseudo_bytes(32/2)); 
    $iv = bin2hex(openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher)/2));

    //encrypts the file
    $encrypted = @openssl_encrypt($fileData, $cipher, $encryption_key, 0, $iv);

    //check if the eencyption is complete
    if(empty($encrypted)){
      echo "<br>Encryption failed";
    }else{
    
      //saves the encrypted file and append the extension to it
      $encryptedFileName = "encrypted/" . $filename[0] . round(microtime(true) * 1000). "." . $extension .".encrypted";
      file_put_contents($encryptedFileName, $encrypted);
      echo "<br>File Encrypted! Keep the following parameter somewhere safe. <br>Cipher:". $cipher ."<br>IV: " . $iv . "<br>Key: " . $encryption_key."<br><a download target='_blank' href='".$encryptedFileName."'>download encrypted file</a>";
    }
  }
}

echo '<br><a href="index.html">back</a>';

?>