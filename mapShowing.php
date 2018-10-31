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

        $locationString = '';
        $desString = '';
        $markerString = '';
        $infoWindowString = '';
        $panLocationString = '';


        //Create string for create map to html and js
        for($i=0;$i<count($latArray);$i++) {
            // echo "lat : ".$latArray[$i];
            // echo ", lng : ".$lngArray[$i];
            // echo ", title : ".$titleArray[$i];
            // echo ", des : ".$desArray[$i];
            // echo ", imgName : ".$imgNameArray[$i];
            // echo "<br />";


            
            $panLocationString .= "<a href='#pan-location' class='badge badge-pill badge-success mr-2' onclick='panMap($latArray[$i], $lngArray[$i]);'; >$titleArray[$i]</a>";

            $locationString .= 'let loc'.$i.' = {lat: '.$latArray[$i].', lng: '.$lngArray[$i].'};';
            $desString .= "
                let des$i = `<div class='card' style='width: 18rem;'>
                <div class='card-header'>
                    $titleArray[$i]
                </div>
                    <img style='width: 17rem;' class='py-2 px-1 m-auto' src='./uploads/$imgNameArray[$i]'>
                    <div class='card-body'>
                        <p class='card-text'>$desArray[$i]</p>
                    </div>
                </div>`;
                
                ";

            $markerString .= 'let marker'.$i.' = new google.maps.Marker({
                position: loc'.$i.',
                map: map,
                title: "'.$titleArray[$i].'"
            });
            
            marker'.$i.'.addListener("click", function() {
                infowindow'.$i.'.open(map, marker'.$i.');
            });
            ';

            $infoWindowString .= 'let infowindow'.$i.' = new google.maps.InfoWindow({
                content: des'.$i.'
            });';

        }

        //render map
        echo '
        
        <!DOCTYPE html>
        <html>
          <head>
            <meta charset="utf-8">
            <title>Map Showing Page</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
            <style>
              #map {
                height: 500px;
                width: 90%;
                border-radius: 20px;
                margin: 40px auto;
              }
              #pan-location {
                margin-top: 20px;  
                margin-left: 20px;
              }
              html, body {
                background-color: #e9e9e9;
                height: 100%;
                margin: 0;
                padding: 0;
              }
            </style>
          </head>
        <body>
          
          <h2 class="text-muted ml-5 mt-4">Select some for pan to their location.</h2>
          <ul id="pan-location">
            '.$panLocationString.'
          </ul>
          <div id="map"></div>
          <a href="./edit.html">
          <div style="position: fixed; top: 0; right: 0; padding: 20px;"> 
            <img src="https://mbtskoudsalg.com/images/icon-png-circle-6.png" style="width: 70px; height: 70px;">
          </div>
          </a>
          <script>
            var map;
            function initMap() {
                '.$locationString.'
                '.$desString.'
                map = new google.maps.Map(document.getElementById("map"), {
                zoom: 4,
                center: loc0
                });
                '.$infoWindowString.'
                '.$markerString.'

            }
            function panMap(lat, lng) {
                //alert(""+lat+" , "+lng);
                map.panTo(new google.maps.LatLng( lat, lng ));
            }

            </script>

            <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4cg9Sa_KzdascoWO95mtMrJiNC42Gr4A&callback=initMap">
            </script>

        </body>
        </html>
        ';

    }


?>