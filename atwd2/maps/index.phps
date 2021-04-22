<!-- Get the Google Maps API key into the JS -->
<?php include_once('googleMapsKey.php');?>

<!-- Specify the DOCTYPE and create the HTML tag with language attribute -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ATIWD2 - Task 4</title>
        <!-- Link external sources (stylesheets, fonts, icons, AJAX) -->
        <link href="stylesheet.css" rel="stylesheet"/>
        <link href="https://unpkg.com/mono-icons@1.2.4/iconfont/icons.css" rel="stylesheet"/>
        <link rel="preconnect" href="https://fonts.gstatic.com"/>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    </head>
    <body>
        <div class="top-menu-bar">
            <!-- Top Menu Bar Title -->
            <div class="top-menu-bar-title">
                <span class="title">ATIWD2 - Task 4</span>
            </div>

            <!-- Top menu bar timeline (allows the user to page through the data by month) -->
            <div class="top-menu-bar-timeline">
                <div class="navigation-arrow left" onclick="previousMonth()"><i class="mi-chevron-left"></i></div>
                <div class="month selected" id="month-1" onclick="selectMonth(1)">Jan</div>
                <i class="mi-remove"></i>
                <div class="month" id="month-2" onclick="selectMonth(2)">Feb</div>
                <i class="mi-remove"></i>
                <div class="month" id="month-3" onclick="selectMonth(3)">Mar</div>
                <i class="mi-remove"></i>
                <div class="month" id="month-4" onclick="selectMonth(4)">Apr</div>
                <i class="mi-remove"></i>
                <div class="month" id="month-5" onclick="selectMonth(5)">May</div>
                <i class="mi-remove"></i>
                <div class="month" id="month-6" onclick="selectMonth(6)">Jun</div>
                <i class="mi-remove"></i>
                <div class="month" id="month-7" onclick="selectMonth(7)">Jul</div>
                <i class="mi-remove"></i>
                <div class="month" id="month-8" onclick="selectMonth(8)">Aug</div>
                <i class="mi-remove"></i>
                <div class="month" id="month-9" onclick="selectMonth(9)">Sep</div>
                <i class="mi-remove"></i>
                <div class="month" id="month-10" onclick="selectMonth(10)">Oct</div>
                <i class="mi-remove"></i>
                <div class="month" id="month-11" onclick="selectMonth(11)">Nov</div>
                <i class="mi-remove"></i>
                <div class="month" id="month-12" onclick="selectMonth(12)">Dec</div>
                <div class="navigation-arrow right" onclick="nextMonth()"><i class="mi-chevron-right"></i></div>
            </div>
        </div>
        
        <!-- Submenu (Provides extra options for the user, such as toggling the heatmap and selecting the pollutant) -->
        <div class="submenu">
            <div class="submenu-item">
                <label for="pollutant">Pollutant</label>
                <select name="pollutant" class="dropdown" onchange="initMap()">
                    <option value="nox" selected>Nitrogen Oxide (NOx)</option>
                    <option value="no">Nitrogen Monoxide (NO)</option>
                    <option value="no2">Nitrogen Dioxide (NO2)</option>
                </select>
            </div>
            <div class="submenu-item">
                <label for="toggle-heatmap">Toggle Heatmap</label>
                <input type="checkbox" checked name="toggle-heatmap" class="checkbox" onchange="initMap()"/>
            </div>
        </div>

        <!-- Loading message for while the data is being loaded -->
        <div class="loading" id="msg">
            Loading data and maps...
        </div>

        <!-- The Google Map element -->
        <div style="height:100%; width:100%;">
            <div id="map" class="invisible"></div>
        </div>

        <!-- Load the Google Maps CDN after the rest of the DOM -->
        <script async src="https://maps.googleapis.com/maps/api/js?key=<?php echo $g_maps_api_key;?>&libraries=visualization&callback=initMap"></script>

        <!-- The rest of the JavaScript for the site (after the DOM has rendered) -->
        <script type="text/javascript">
            // When the window has loaded, run the getData() function
            window.addEventListener("load", () => getData())
            
            // Initialise the jsonData variable
            var jsonData = ''

            // Define the SVG Marker for the map, with parameters such as the hexadecimal colour, the scale of the marker and the opacity
            function svgMarker(hex, scale, opacity) {
                return {
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: hex,
                    fillOpacity: opacity,
                    strokeWeight: 0,
                    rotation: 0,
                    scale: 10 * scale,
                    anchor: new google.maps.Point(0, 0),
                }
            }

            // Initialise the Google Map
            function initMap() {
                // Center the map on Bristol
                const center = new google.maps.LatLng(51.4545, -2.5879)

                // Create the map on the map element with options
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 13,
                    center: center,
                })
                
                // Get the current selected month from the timeline
                var selected_month = document.getElementsByClassName('selected')[0]

                // Get the month string from the element (e.g. Jan, Feb, etc.)
                var selected_month_string = selected_month.innerText

                // Initialise the heatmap_data array
                var heatmap_data = []

                // Get the pollutant to be displayed
                var pollutant = document.getElementsByName('pollutant')[0].value

                // Get the value of whether the heatmap should be displayed or not
                var toggle_heatmap = document.getElementsByName('toggle-heatmap')[0].checked

                // Loop through each site in the jsonData
                for (var site_id in jsonData[selected_month_string]) {
                    // If we are displaying the heatmap
                    if (toggle_heatmap) {
                        // Push the location (lat and long) and the weight (pollutant levels) to the heatmap_data array
                        heatmap_data.push({
                            location: 
                                new google.maps.LatLng(Number(jsonData[selected_month_string][site_id].lat), Number(jsonData[selected_month_string][site_id].long)),
                            weight: 
                                jsonData[selected_month_string][site_id]['data'][pollutant]
                        })

                        // Create invisible markers that will act as hover-points for the cursor in order to display tooltips with the site name and data value
                        new google.maps.Marker({
                            position: { lat: Number(jsonData[selected_month_string][site_id].lat), lng: Number(jsonData[selected_month_string][site_id].long) },
                            icon: svgMarker('#A67BE0', 2, 0),
                            map: map,
                            title: jsonData[selected_month_string][site_id].name + " | Average level: " + Math.round(jsonData[selected_month_string][site_id]['data'][pollutant] * 100) / 100 + "μg",
                        })
                    } else {
                        // Create visible markers to show the sites location, name and data value
                        new google.maps.Marker({
                            position: { lat: Number(jsonData[selected_month_string][site_id].lat), lng: Number(jsonData[selected_month_string][site_id].long) },
                            icon: svgMarker('#A67BE0', 1, 0.85),
                            map: map,
                            title: jsonData[selected_month_string][site_id].name + " | Average level: " + Math.round(jsonData[selected_month_string][site_id]['data'][pollutant] * 100) / 100 + "μg",
                        })
                    }
                }

                // Add the heatmap layer to the map with options
                var heatmap = new google.maps.visualization.HeatmapLayer({
                    data: heatmap_data,
                    map: map,
                    radius: 50
                })
            }

            // Handle the clicking of a month on the timeline
            function selectMonth(month) {
                // Get the element that has been clicked
                var month_to_select = document.getElementById('month-' + month)

                // If the month has already been selected, do nothing
                if (month_to_select.classList.contains('selected')) {
                    return
                } else {
                    // Get the element that previously had the selected class
                    var previously_seleced_month = document.getElementsByClassName('selected')

                    // Remove the selected class from the previously selected month
                    for (var i = 0; i < previously_seleced_month.length; i++) {
                        previously_seleced_month[i].classList.remove('selected')
                    }

                    // Add the selected class to the clicked month
                    month_to_select.classList.add('selected')
                }

                // Refresh the map
                initMap()
            }

            // Handle the clicking of the previous month button
            function previousMonth() {
                // Get the currently selected month
                var selected_month = document.getElementsByClassName('selected')[0]

                // Remove the selected class from the currently selected month
                selected_month.classList.remove('selected')

                // Get the currently selected month number (e.g. Jan = 1, Feb = 2, etc.)
                selected_month = selected_month.id.split('-')[1]


                // Initialise the previous month number as 12 (Dec)
                var prev_month_no = 12

                // If the previous month is less than 1, loop back around to 12 (Dec)
                if ((Number(selected_month) - 1) < 1) {
                    prev_month_no = 12
                } else {
                    // Get the current month number - 1
                    prev_month_no = (Number(selected_month) - 1)
                }

                // Get the element for the newly selected month
                var prev_month = document.getElementById('month-' + prev_month_no)

                // Add the selected class to the newly selected month
                prev_month.classList.add('selected')

                // Refresh the map
                initMap()
            }

            // Handles the clicking of the next month button
            function nextMonth() {
                // Get the currently selected month
                var selected_month = document.getElementsByClassName('selected')[0]

                // Remove the selected class from the currently selected month
                selected_month.classList.remove('selected')

                // Get the currently selected month number (e.g. Jan = 1, Feb = 2, etc.)
                selected_month = selected_month.id.split('-')[1]

                // Initialise the next month number as 2 (Feb)
                var next_month_no = 2

                // If the next month will be more than 12, loop back to 1 (Jan)
                if ((Number(selected_month) + 1) > 12) {
                    next_month_no = 1
                } else {
                    // Get the current month number + 1
                    next_month_no = (Number(selected_month) + 1)
                }

                // Get the element for the next month
                var next_month = document.getElementById('month-' + next_month_no)

                // Add the selected class to the next month
                next_month.classList.add('selected')

                // Refresh the map
                initMap()
            }

            // Asynchronous function to get the JSON formatted XML data from getData.php
            // using an AJAX request
            async function getData() {
                $.ajax({
                    url: "getData.php",
                    type: "GET",
                    dataType: "json",
                    async: true,
                    processData: false,
                    contentType: false,
                    cache: false,
                    // On request success
                    success: (json) => {
                        // Set the returned json to the jsonData variable
                        jsonData = json
                        
                        // Refresh the map
                        initMap()

                        // Map the map visible
                        var map = document.getElementById('map')
                        map.classList.remove('invisible')
                    },
                    // On request error
                    error: () => {
                        // Change the load text to be error message
                        var msg = document.getElementById('msg')
                        msg.innerText = "Something went wrong..."
                    }
                })
            }
        </script>
    </body>
</html>