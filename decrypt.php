<?php

include "cipher.php";

//check if server reached here by post
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  echo "POST Method required";
}
else{
  echo "Encrypting...";

  //check for file upload
  if(!file_exists($_FILES['enc']['tmp_name']) || !is_uploaded_file($_FILES['enc']['tmp_name'])){
    echo "<br>ERROR: no file to decrypt!";
  } else {

    //form validation
    if(!isset($_POST['key']) || !isset($_POST['iv']) || !isset($_POST['mode'])){
      echo "<br>Mode, key or iv are missing";
    }
    else{

      //gets cipher mode from the user selected input 
      $cipher = get_cipher_from_int($_POST['mode']);

      //seperates file name from extensions
      $filename = explode(".", $_FILES['enc']['name']);

      //reads the file
      $fileData = file_get_contents($_FILES['enc']['tmp_name']);

      //gets key and iv from form
      $encryption_key = $_POST['key'];
      $iv = $_POST['iv'];

      //decrypts the file
      $decrypted = @openssl_decrypt($fileData, $cipher, $encryption_key, 0, $iv);
      
      //check if the decrypt is complete
      if(empty($decrypted)){
        echo "<br>Mode, Key or IV are incorrect";
      }else{

        //saves the decrypted file and append the extension to it
        $decryptedFileName = "decrypted/" . $filename[0] .".". $filename[1];
        file_put_contents($decryptedFileName, $decrypted);
        echo "<br>File Decrypted <br><a download target='_blank' href='".$decryptedFileName."'>download decrypted file</a>";
      }
    }
  }
}

echo '<br><a href="index.html">back</a>';

?>