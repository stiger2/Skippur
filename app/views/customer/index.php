<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <link href="https://fonts.googleapis.com/css2?family=Exo+2&family=Roboto:wght@300&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.3.0/dist/leaflet.markercluster.js"></script>
    <title>Welcome</title>

    <style>
        body {
            background: rgb(80, 117, 160);
            background: linear-gradient(167deg, rgba(80, 117, 160, 1) 0%, rgba(104, 255, 200, 1) 100%);
        }

        #siteLocationMap {
            margin: 0 auto;
            height: 600px;
            width: 850px;
            border-radius: 10px;
        }

        h2,
        h3 {
            font-family: 'Roboto', sans-serif;
            text-align: center;
            color: #092024;
        }

        nav {
            text-align: center;
        }

        #searchInput {
            width: 300px;
            margin: 0 auto;
            text-align: center;
        }

        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        /* The Close Button */
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="/customer/index/">
            <img src="/images/skippur_logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Skippur
        </a>
        <a href="/customer/profile">Profile</a>
        <a href="/customer/messages">Messages</a>
        <a href="/customer/myAppointments">My Appointments</a>
        <a href='/home/logout'>Logout</a>
    </nav>
    </br>
    <h2 style="text-align: center;">Find a Business</h2>
    <div id='searchInput'>
        <input class="form-control mr-sm-2" id='searchBox' type="textbox" placeholder="Enter your address ..." />
        <br>
        <button class="btn btn-primary" onclick="search()">Search</button>
        <button id="myBtn" class="btn btn-secondary">Advanced Search</button>
    </div>
    <br>
    <h3>Sites</h3>

    <div id="siteLocationMap"></div>
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <form method="post" action="/customer/search">
                <center><label for="cars">Service Industry: </label>
                <select id="cars" name="something">
                    <?php
                    $industries = $this->model('service_industries')->getIndustryCategories();
                    foreach ($industries as $industry) {
                        echo "<option value='$industry->industry_category_name'>$industry->industry_category_name</option>";
                    }
                    ?>
                </select>
                <button name="submit" class="btn btn-primary" action="submit">Submit</button></center>
            </form>
        </div>
    </div>

    </div>

    <script>
        var sitesJSON = JSON.parse('<?php echo json_encode($data["sites"]); ?>');
        var sites = jsonToArr(sitesJSON);
        var markers = L.markerClusterGroup();


        var mymap = L.map('siteLocationMap').setView([0, 0], 1);
        const attribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
        const tileUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        const tiles = L.tileLayer(tileUrl, {
            attribution
        });
        tiles.addTo(mymap);

        function jsonToArr(jsonData) {
            result = [];

            for (var i in jsonData)
                result.push([i, jsonData[i]]);

            return result;
        }

        function search() {
            // get search box value
            var userAddress = document.getElementById("searchBox").value.replace(/\s+/g, '+');

            //make request
            let requestUrl = 'https://nominatim.openstreetmap.org/search?q=' + userAddress + '&format=json';
            let request = new XMLHttpRequest();
            request.open('GET', requestUrl, false);
            request.onload = function() {
                const resp = JSON.parse(request.response);
                mymap.setView([resp[0].lat, resp[0].lon], 16);
            }
            request.send();

            markers.clearLayers();
            bindMarkers();
            markers.addTo(mymap);
        }


        function bindMarkers() {
            sites.forEach(site => {
                var marker = L.marker([site[1].site_latitude, site[1].site_longitude]);
                marker.bindPopup(
                    "<b>" + site[1].business_name + "</b>" +
                    "<br><b>Business: </b>" + site[1].business_domain +
                    "<br><b>Address: </b>" + site[1].site_address +
                    "<br><b>Postal Code: </b>" + site[1].site_postal_code +
                    "<br><b>Phone: </b>" + site[1].site_phone_number +
                    "<br>" + '<a href="/customer/calender/' + site[1].site_id + '">Visit Page</a>');
                markers.addLayer(marker);
            });
        }
    </script>
    <script>
        // Get the modal
        var modal = document.getElementById("myModal");
        var x = document.getElementById("siteLocationMap");
        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
            x.style.display = "none";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
            x.style.display = "block";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                x.style.display = "block";
            }
        }
    </script>



</body>

</html>