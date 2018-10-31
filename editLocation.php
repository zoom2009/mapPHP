<?php    

    $latArray = array();
    $lngArray = array();
    $titleArray = array();
    $desArray = array();
    $imgNameArray = array();

    getAllDataFromDB($latArray, $lngArray, $titleArray, $desArray, $imgNameArray);
    echoAllData($latArray, $lngArray, $titleArray, $desArray, $imgNameArray);
    // $latArray, $lngArray, $desArray, $imgNameArray

    function getAllDataFromDB(&$latArray, &$lngArray, &$titleArray, &$desArray, &$imgNameArray) {

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

        $sql = "SELECT * FROM location";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                //echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
                array_push($latArray, $row["lat"]);
                array_push($lngArray, $row["lng"]);
                array_push($titleArray, $row["title"]);
                array_push($desArray, $row["des"]);
                array_push($imgNameArray, $row["imgName"]);
            }
        } else {
            echo "0 results";
        }
        $conn->close();

    }

    function echoAllData($latArray, $lngArray, $titleArray, $desArray, $imgNameArray) {
        
        $panLocationString = '';

        for($i=0;$i<count($latArray);$i++) {
            // echo "lat : ".$latArray[$i];
            // echo ", lng : ".$lngArray[$i];
            // echo ", title : ".$titleArray[$i];
            // echo ", des : ".$desArray[$i];
            // echo ", imgName : ".$imgNameArray[$i];
            // echo "<br />";

            $panLocationString .= "<a onclick='showEdit($latArray[$i],$lngArray[$i],`$titleArray[$i]`,`$desArray[$i]`,`$imgNameArray[$i]`);' href='#pan-location' 
                class='badge badge-pill badge-success mr-2' >
                $titleArray[$i]</a><br />";
        }


        echo "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='utf-8'>
                <title>Map Showing Page</title>
                <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' crossorigin='anonymous'>
                <style>
                    #pan-location {
                        margin-top: 20px;  
                        margin-left: 20px;
                    }
                    html, body {
                        background-color: #e9e9e9;
                        height: 100%;
                        width: 100%;
                        margin: 0;
                        padding: 0;
                    }
                    #edit-popup {
                        display: none;
                    }
                </style>
            </head>
            <body>
            <div class='container'>
                <h2 class='text-muted ml-5 mt-4'>Select one for edit location.</h2>
                <ul id='pan-location'>
                    $panLocationString
                </ul>
            </div>
            <div id='edit-popup' class='container bg-dark text-white' style='overflow: auto;border-radius: 20px; position: fixed; width:80%; top:0; left:0; right:0; bottom:0; margin: auto; height: 300px; background-color: #fff;'>
                <div onclick='document.getElementById(`edit-popup`).style.display = `none`; ' >
                    <img 
                        style='cursor: pointer; position: absolute; width: 50px; height: 50px; right: 0; top: 0; padding: 10px;'
                        src='./cancel.png'>
                </div>
    
                <div class='container my-4 pb-4'>
                <h2 id='tt'>Title</h2>
                <form method='POST' action='./saveEditLocation.php' enctype='multipart/form-data'>
                    <div class='form-group'>
                        <label for='lat'>Latitude</label>
                        <input type='number' step='any' name='lat' class='form-control' id='lat' placeholder='Enter your latitude' required>
                    </div>
                    <div class='form-group'>
                        <label for='lng'>Longitude</label>
                        <input type='number' step='any' name='lng' class='form-control' id='lng' placeholder='Enter your Longitude' required>
                    </div>
                    <div class='form-group'>
                        <label for='title'>Title</label>
                        <input type='text' name='title' class='form-control' id='title' placeholder='Enter your Marker title' required readonly='readonly'>
                    </div>
                    <div class='form-group'>
                        <label for='des'>Description for this location</label>
                        <textarea name='des' class='form-control' id='des' rows='3' required></textarea>
                      </div>

                      <img class='img-thumbnail' id='img' src='' style='width:250px;'>
                    <div class='form-group'>
                        <label for='img'>Picture for this location</label>
                        <input type='file' name='img' class='form-control-file' id='img'>
                    </div>
                    
                    <button type='submit' name='submit' class='btn btn-primary'>Save</button>
                </form>
            </div>

                
                
            </div>

            </body>
            <script>
                function showEdit(lat, lng, title, des, imgName) {
                    document.getElementById('edit-popup').style.display = 'block';
                    document.getElementById('lat').value = ''+lat;
                    document.getElementById('lng').value = ''+lng;
                    document.getElementById('title').value = title;
                    document.getElementById('des').value = des;
                    document.getElementById('tt').innerHTML = title;
                    document.getElementById('img').src = './uploads/'+imgName;
                }
            </script>
        ";


    }
?>