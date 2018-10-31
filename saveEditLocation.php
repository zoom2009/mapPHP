<?php

    echo 'Editlocation.php';
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $title = $_POST['title'];
    $des = $_POST['des'];

    // update data
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "map";

    $sql = '';

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    if(!isset($_FILES['img']) || $_FILES['img']['error'] == 4) { //old img
        echo "use old img"; 
        $sql = "UPDATE location SET lat=$lat, lng=$lng, title='$title', des='$des' WHERE title='$title'";
        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $conn->close();
        header("Location: mapShowing.php");
    } else { // new img
        echo "Is upload new file";
        //check can upload img

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["img"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["img"]["size"] > 1000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["img"]["name"]). " has been uploaded.";
                $sql = "UPDATE location SET lat='$lat' lng='$lng' title='$title' des='$des' imgName='$imgName' WHERE title='$title'";
                if ($conn->query($sql) === TRUE) {
                    echo "Record updated successfully";
                } else {
                    echo "Error updating record: " . $conn->error;
                }
                $conn->close();
                header("Location: mapShowing.php");
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    


    //


    

?>