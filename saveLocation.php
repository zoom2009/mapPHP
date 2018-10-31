<?php

    //This page is open is mean all data is set! 
    //used required in html

    //------------------------------------ img manipulation -------------------------------------
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

            //this line is mean image is uploaded and it's already have lat, lng
            //let's save our data to mysql db
            SaveToDB(basename( $_FILES["img"]["name"]));

        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    //------------------------------------ END img manipulation ----------------------------------


    function SaveToDB($imgName) {
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $title = $_POST['title'];
        $des = $_POST['des'];


        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "map";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = "INSERT INTO location (lat, lng, title, des, imgName)
        VALUES ('$lat', '$lng', '$title', '$des', '$imgName')";

        if ($conn->query($sql) === TRUE) {
            echo "<br />@@@ Saved this location successful @@@<br />";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();

        //move url to mapShowing page after save data to db
        header("Location: mapShowing.php");
    }


?>