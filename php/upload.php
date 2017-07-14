<?php 
//init
session_start(); 
include  "functions.php";

if(!isset($_SESSION['partID'])) 
    die("LOGIN / REGISTER FIRST!!!");
?>

<?php

//this file does just the uploading routine
//upload routine and listing files of some organiztion.
function main() {
     //error non login
    if(!isset($_SESSION["partName"])){
        die("please login or register first");
    }

    //getting the new upload
    $targetDir = "../uploads/";
    $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 0;

    //check if image file is an actual image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                $uploadOK = 1;

                //storing documents in a database
                $type = $_POST['docType'];
               
                $mysqltime = date("Y-m-d h:i:sa");

                $fileName = $targetFile;
                $fileHandle = fopen($targetFile, "r");
                $fileSize = filesize($targetFile);
                $fileContent = fread($fileHandle, $fileSize);
                $fileContent = addslashes($fileContent);
                $fileContent = signFile($fileContent, querrySomething($_SESSION['partID'], 'part_private_key'));
                fclose($fileHandle);

                //things that has to be done
                if(!get_magic_quotes_gpc())
                {
                    $fileName = addslashes($fileName);
                }

                //building querry to the database
                $dbQuery = "INSERT INTO Posts (part_id, post_type, post_date_uploaded, post_document_content, post_text, post_document_type, post_document_size, post_document_name) VALUES ('" . $_SESSION['partID'] . "', '" . $type ."', '" . $mysqltime ."', '" . $fileContent ."', '" . $_POST['docDescription'] ."', '" . $_FILES["fileToUpload"]["type"] . "', '" . $fileSize . "', '" . $fileName  ."')";
            

                //inserting
                $conn = new mysqli('localhost','boubou','boubou','edel') or die('Error connecting to MySQL server.');
                $result = $conn->query($dbQuery);

                if(!$result) {
                    die("something went wrong" . $conn->error);
                }
            } else {
                echo "the file is an image however was not uploaded";
                $uploadOk = 0;
            }
        }else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    if($uploadOk) {
        echo "<br>Upload was successful!";
    } else {
        echo "<br>Upload failed!";
        
    }

    sleep(3);
    header("Location: https://localhost/new/profile.php"); /* Redirect browser */
        
}

//calling main()
main();
  
?>