<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
  
    
  <!-- </body> -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Dashboard</h1>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
              <div class="card">
                
                <div class="card-wrap">
                  <div class="card-header">
                    <div class="container-fluid">
                    <div class="row">
	<div class="col-md-12">
		<h2>Lokasi Petugas</h2>
		<div style="height: 800px;" id="map"></div>
        <script>
          //global array to store our markers
        var markersArray = [];
        var map;
        function load() {
            map = new google.maps.Map(document.getElementById("map"), {
                center : new google.maps.LatLng(-2.0566440354721145, 120.62327289188507),
                zoom : 5,
                mapTypeId : 'hybrid'
            });
            var infoWindow = new google.maps.InfoWindow;

            // your first call to get & process inital data

            downloadUrl("<?php echo base_url() ?>api/all_lokasi", processXML);
        }

        function processXML(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName("marker");
            //clear markers before you start drawing new ones
            resetMarkers(markersArray)
            for(var i = 0; i < markers.length; i++) {
                var host = markers[i].getAttribute("id");
                var type = markers[i].getAttribute("nama");
                var bearing = markers[i].getAttribute("alamatfromapp");
                var lastupdate = "<?php echo get_waktu() ?>"; //markers[i].getAttribute("lastupdate");
                var point = new google.maps.LatLng(parseFloat(markers[i].getAttribute("latitude")), parseFloat(markers[i].getAttribute("longitude")));
                var html = "<b>" + "Host: </b>" + host + "<br><b>"+ "Petugas: </b>" + type + "<br>" + "<b>Lokasi Terakhir: </b>" + bearing + "<br>" + "<br>" + "<b>Update Terakhir: </b>" + lastupdate + "<br>";
                console.log(point+" "+html);
                // var icon = customIcons[type] || {};
                var infoWindow = new google.maps.InfoWindow;
                var imagePetugas = {
                  url:"<?php echo base_url() ?>image/sales.png",
                  scaledSize: new google.maps.Size(50, 50), 
                }
                var marker = new google.maps.Marker({
                // label: type,
                
                icon: imagePetugas,
                map : map,
                position : point,
            });
                //store marker object in a new array
                markersArray.push(marker);
                bindInfoWindow(marker, map, infoWindow, html);


            }
                // set timeout after you finished processing & displaying the first lot of markers. Rember that requests on the server can take some time to complete. SO you want to make another one
                // only when the first one is completed.
                setTimeout(function() {
                    downloadUrl("<?php echo base_url() ?>api/all_lokasi", processXML);
                }, 5000);
        }

    //clear existing markers from the map
    function resetMarkers(arr){
        for (var i=0;i<arr.length; i++){
            arr[i].setMap(null);
        }
        //reset the main marker array for the next call
        arr=[];
    }
    var infoWindow = new google.maps.InfoWindow;
        function bindInfoWindow(marker, map, infoWindow, html) {
            google.maps.event.addListener(marker, 'click', function() {
                infoWindow.setContent(html);
                infoWindow.open(map, marker);
            });
        }

        function downloadUrl(url, callback) {
            var request = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest;

            request.onreadystatechange = function() {
                if(request.readyState == 4) {
                    request.onreadystatechange = doNothing;
                    callback(request, request.status);
                }
            };

            request.open('GET', url, true);
            request.send(null);
        }
        function doNothing() {}

        </script>
        <script defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAAyfmnFvhRQqjFSW7euy935Pm8gVq9GE0&callback=load">
        </script>
      </div>
                            </div>
                          </div>
                    </div>
                    </div>
                   
                  </div>
                  <div class="card-body">
                  <div style="height: 730px;min-width:100%" id="map"></div>
                  </div>
                </div>
              </div>
            </div> 
           
          </div>  
        </section>
      </div>

     