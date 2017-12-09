<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Waypoints in directions</title>
    <style>
      #right-panel {
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }

      #right-panel select, #right-panel input {
        font-size: 15px;
      }

      #right-panel select {
        width: 100%;
      }

      #right-panel i {
        font-size: 12px;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        float: left;
        width: 70%;
        height: 80%;
      }
      #right-panel {
        margin: 20px;
        border-width: 2px;
        width: 20%;
        height: 400px;
        float: left;
        text-align: left;
        padding-top: 0;
      }
      #directions-panel {
        margin-top: 10px;
        background-color: #FFEE77;
        padding: 10px;
        overflow: scroll;
        height: 174px;
      }
	  table, th, td {
		border: 1px solid black;
		border-collapse: collapse;
		}
		th, td {
			padding: 5px;
			text-align: centre;    
		}
    </style>


    <script src="where.js"></script>
  </head>
  <body>
    <div id="map"></div>
    <div id="right-panel">
    <div>
    <b>Start:</b>
    <form >
      <input type="text" id="start" name="Location">
    </form>
    <br>
    <b>Waypoints:</b> <br>
    <i>(Ctrl+Click multiple selection)</i> <br>
    <select multiple id="waypoints">
      <?php
        $file_handle = fopen("where.txt", "r");
		$line=fread($file_handle,filesize("where.txt"));
		$arr=explode(",",$line);
		$i=0;
		while ($i<3) {
			echo '<option value="'.$arr[$i].'">'.$arr[$i].'</option>';
			$i=$i+1;
			}
		fclose($file_handle);
      ?>
    </select>
    <br>
    <b>End:</b>
    <select id="end">
      <option value="LNMIIT Postbox, Sumel, Beermalpura at Mukandpura, RajasthanS">LNMIIT Main Gate</option>
    </select>
    <br>
      <input type="submit" id="submit">
    </div>
    <div id="directions-panel"></div>
    </div>
    <script>
      function initMap() {
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 17,
          center: {lat:26.9355884,lng:75.9215958}
        });
        i = 0;
        
        var markers = [];
        
        for ( pos in myData ) {
            i = i + 1;
        
            var row = myData[pos];
            window.console && console.log(row);
            // if ( i < 3 ) { alert(row); }
            
            var newLatlng = new google.maps.LatLng(row[0], row[1]);
            
            var marker = new google.maps.Marker({
            
                position: newLatlng,
                map: map,
                title: row[2]
            
            });
            
            markers.push(marker);
        }

        directionsDisplay.setMap(map);

        document.getElementById('submit').addEventListener('click', function() {
          calculateAndDisplayRoute(directionsService, directionsDisplay);
        });
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay) {
         var waypts = [];
        var checkboxArray = document.getElementById('waypoints');
        for (var i = 0; i < checkboxArray.length; i++) {
          if (checkboxArray.options[i].selected) {
            waypts.push({
              location: checkboxArray[i].value,
              stopover: true
            });
          }
        }

        directionsService.route({
          origin: document.getElementById('start').value,
          destination: document.getElementById('end').value,
          waypoints: waypts,
          optimizeWaypoints: true,
          travelMode: 'WALKING'
        }, function(response, status) {
          if (status === 'OK') {
            directionsDisplay.setDirections(response);
            var route = response.routes[0];
            var summaryPanel = document.getElementById('directions-panel');
            summaryPanel.innerHTML = '';
            // For each route, display summary information.
            for (var i = 0; i < route.legs.length; i++) {
              var routeSegment = i + 1;
              summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
                  '</b><br>';
              summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
              summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
              summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
            }
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyARwtsO6BezzlE6FO_am6F8bbKS3aIJYrs&callback=initMap">
    </script>
    <table style="width:50%">
  <tr>
    <th>Dustbin Location</th>
    <th colspan="2">Amount</th>
  </tr>
  
  <?php
		$file_handle1 = fopen("where.txt", "r");
		$line1=fread($file_handle1,filesize("where.txt"));
		$arr1=explode(",",$line1);
    $file_handle = fopen("myfile.txt", "r");
    $line=fread($file_handle,filesize("myfile.txt"));
    $arr=explode(",",$line);
    $i=0;
    while ($i<3) {
		
      echo'<tr><td>'.$arr1[$i].'</td><td> '.$arr[$i].'</td></tr>';
      $i=$i+1;
      }
    fclose($file_handle);
	fclose($file_handle1);
  ?>
  </table>
  </body>
</html>