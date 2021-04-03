<!-- Set the DOCTYPE and HTML Tag with lang attribute -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ATIWD2 - Task 3</title>
        
        <!-- Link to external documents (stylesheets, icons, fonts, charts API, JQuery) -->
        <link rel="stylesheet" href="stylesheet.css"/>
        <link href="https://unpkg.com/mono-icons@1.2.4/iconfont/icons.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        
        <!-- Include the site list for getting site names from the ID -->
        <?php include_once('sites_list.php');?>

        <!-- The main JavaScript code for the site's functionality -->
        <script type="text/javascript">
            // Initialise global variables
            var sites = []
            var retrieved_data = false
            var jsonData = ''
            var site_list = <?php echo json_encode($sites);?>

            //////////////////////////
            ////     Task 3.1     ////
            //////////////////////////

            // Function to handle the clicking of the button for Task 3.1
            function noCertainTimeOfDay() {
                // Retrieve the second menu element of the clicked button elements from the DOM
                second_menu = document.getElementById('second_menu')
                button = document.getElementById('certainTimeOfDayButton')
                
                // Retrieve the third menu and the button for Task 3.2 elements from the DOM
                third_menu = document.getElementById('third_menu')
                alternateButton = document.getElementById('allLevels6StationsButton')

                // Make the second menu visible and make the third menu invisible
                // Change the button colours to match the style of the second menu
                if (second_menu.classList.contains('invisible')) {
                    second_menu.classList.remove('invisible')
                    button.style.backgroundColor = '#ADB5BD'

                    third_menu.classList.add('invisible')
                    alternateButton.style.backgroundColor = '#495057'
                } else {
                    // Make the second menu invisible and change the button colour accordingly
                    second_menu.classList.add('invisible')
                    button.style.backgroundColor = '#495057'
                }
            }

            // Function to handle the clicking of the button for Task 3.2
            function allLevels6Stations() {
                // Retrieve the elements for the third menu and clicked button
                third_menu = document.getElementById('third_menu')
                button = document.getElementById('allLevels6StationsButton')

                // Retrieve the elements for the second menu and the button for Task 3.1
                second_menu = document.getElementById('second_menu')
                alternateButton = document.getElementById('certainTimeOfDayButton')

                // Show the third menu and make the second menu invisible
                // Change the button's colours accordingly
                if (third_menu.classList.contains('invisible')) {
                    third_menu.classList.remove('invisible')
                    button.style.backgroundColor = '#ADB5BD'

                    second_menu.classList.add('invisible')
                    alternateButton.style.backgroundColor = '#495057'
                } else {
                    // Hide the third menu and change the button colour accordingly
                    third_menu.classList.add('invisible')
                    button.style.backgroundColor = '#495057'
                }
            }

            // Show the NO chart for Task 3.1
            function showNOChart() {
                // Get the site list from PHP into JS
                // (Used to get the site name from the site ID)
                var site_list = <?php echo json_encode($sites);?>

                // Set the form data from the form elements
                var site = document.getElementById('site').value;
                var year = document.getElementById('year').value;
                var time = document.getElementById('time').value;

                // Make sure all form elements are filled out
                // Otherwise alert the user to enter in all the data
                if (site == '' || year == '' || time == '') {
                    alert('You must enter data for each of the inputs')
                    return
                }

                // Load the Visualization API from Google Charts
                google.charts.load('current', {'packages':['corechart']});
                
                // Set a callback to run when the Google Visualization API is loaded.
                google.charts.setOnLoadCallback(drawChart);
                    
                // Draw the google chart
                function drawChart() {
                    // Create a new FormData object and populate it with the
                    // data from the form elements
                    var formData = new FormData()
                    formData.append("site", site)
                    formData.append("year", year)
                    formData.append("time", time)

                    // Get the formatted JSON version of the XML files from
                    // certainTimeOfDayData.php using an AJAX request
                    var jsonData = $.ajax({
                        url: "certainTimeOfDayData.php",
                        type: "POST",
                        data: formData,
                        dataType: "json",
                        async: false,
                        processData: false,
                        contentType: false,
                        cache: false,
                    }).responseText;

                    // Make sure there is no error when parsing the JSON
                    if (JSON.parse(jsonData).error == 'No data available for the provided parameters') {
                        alert(JSON.parse(jsonData).error)
                    } else {
                        
                        // Create our data table out of JSON data loaded from server.
                        var data = new google.visualization.DataTable(jsonData);
                
                        // Instantiate and draw our chart, passing in some options.
                        var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
                        chart.draw(data, {
                            width: 1920,
                            height: 1080 - 350,
                            backgroundColor: '#E9ECEF',
                            title: 'Nitrogen Monoxide (NO) levels from ' + site_list[site] + ' at ' + time + ' averaged by month in the year ' + year,
                            legend: 'none',
                            vAxis: {
                                title: "μg"
                            },
                            hAxis: {
                                title: "Month"
                            }
                        });
                    }
                }
            }

            //////////////////////////
            ////     Task 3.2     ////
            //////////////////////////

            // Add site to site list
            function addSite() {
                // Get the site to add from the dropdown
                var site_to_add = document.getElementById('site-add').value
                
                // Make sure the site is not already in the list
                if (sites.includes(site_to_add)) {
                    alert('That site is already in the list')
                    return
                }
                // Make sure there are not more than 6 items in the list 
                if (sites.length == 6) {
                    alert('You can only have 6 sites selected')
                    return
                }

                // Push the new site to the array
                sites.push(site_to_add)

                // Create the HTML for the sites list element, truncating the
                // site name to 11 characaters to ensure optimal UX
                var site_list_html = ''
                sites.forEach(site => {
                    var site_name = site_list[site]
                    if (site_name.length > 11) {
                        site_name = site_name.replace(site_name.substr(10), '...')
                    }
                    site_list_html += '<div class="site-list-item"><span onclick="show6StationsChart(' + site + ')">' + site_name + '</span><i class="mi-close close" onclick="removeFromList(' + site + ')"></i></div>'
                })

                // Set the HTML of the site list to the newly created HTML
                document.getElementById('site-list').innerHTML = site_list_html
                
                // Set the retrieved_data flag to false to signal that the data
                // must be retrieved again
                retrieved_data = false
            }

            // Remove a site from the site list
            function removeFromList(site) {
                // Remove the site from the array
                sites = sites.filter((value, index, arr) => {
                    return value != site
                })

                // Create the HTML for the sites list element, truncating the
                // site name to 11 characaters to ensure optimal UX
                var site_list_html = ''

                sites.forEach(site => {
                    var site_name = site_list[site]
                    if (site_name.length > 11) {
                        site_name = site_name.replace(site_name.substr(10), '...')
                    }
                    site_list_html += '<div class="site-list-item"><span onclick="show6StationsChart(' + site + ')">' + site_name + '</span><i class="mi-close close" onclick="removeFromList(' + site + ')"></i></div>'
                })

                // Set the HTML of the site list to the newly created HTML
                document.getElementById('site-list').innerHTML = site_list_html
                
                // Set the retrieved_data flag to false to signal that the data
                // must be retrieved again
                retrieved_data = false
            }

            // Show the chart for Task 3.2
            function show6StationsChart(site) {
                // Get the date from the form element
                var date = document.getElementById('date').value

                // Make sure that there are exactly 6 sites in the site list
                if (sites.length != 6) {
                    alert('You must select 6 sites to view data from')
                    return
                }

                // Make sure the date is populated
                if (date == '') {
                    alert('You must select a date to view data on')
                    return
                }

                // Load the Visualization API for Google Charts.
                google.charts.load('current', {'packages':['corechart']});
                
                // Set a callback to run when the Google Visualization API is loaded.
                google.charts.setOnLoadCallback(drawChart);
                    
                // Draw the google chart
                function drawChart() {
                    // Create a new FormData object to contain the form elements
                    var formData = new FormData()
                    formData.append("sites", sites.join(','))
                    formData.append("date", date)

                    // If we need to refresh the data from the PHP source
                    if (!retrieved_data) {
                        // Get the formatted JSON from the XML files using
                        // an AJAX request to allLevels6Stations.php
                        jsonData = $.ajax({
                            url: "allLevels6Stations.php",
                            type: "POST",
                            data: formData,
                            dataType: "json",
                            async: false,
                            processData: false,
                            contentType: false,
                            cache: false,
                        }).responseText;
                        retrieved_data = true
                    }

                    // Check to see if there are any JSON errors
                    if (JSON.parse(jsonData).error == 'No data available for the provided parameters') {
                        alert(JSON.parse(jsonData).error)
                    } else {
                        // Create our data table out of JSON data loaded from server.
                        var data = new google.visualization.DataTable(JSON.parse(jsonData)[site]);
                
                        // Instantiate and draw our chart, passing in some options.
                        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                        
                        // Draw the chart
                        chart.draw(data, {
                            width: 1920,
                            height: 1080 - 350,
                            backgroundColor: '#E9ECEF',
                            title: 'All levels from ' + site_list[site] + ' per hour on the day ' + date,
                            vAxis: {
                                title: "μg"
                            },
                            hAxis: {
                                title: "Hour"
                            }
                        });
                    }
                }
            }
        </script>
    </head>
    <body>
        <!-- TOP MENU NAVIGATION BAR -->
        <div class="top-menu-bar">
            <div class="top-menu-bar-item">
                <span class="top-menu-bar-title">ATIWD2 - Task 3</span>
            </div>

            <!-- Button for Task 3.1 -->
            <div class="top-menu-bar-item">
                <div class="top-menu-bar-button" id="certainTimeOfDayButton" onclick="noCertainTimeOfDay()">
                    <i class="mi-clock"></i>
                    View Nitrogen Monoxide Levels for a Certain Time of Day
                </div>
            </div>

            <!-- Button for Task 3.2 -->
            <div class="top-menu-bar-item">
                <div class="top-menu-bar-button" id="allLevels6StationsButton" onclick="allLevels6Stations()">
                    <i class="mi-calendar"></i>
                    View All Levels for a Certain Day From 6 Stations
                </div>
            </div>

            <!-- Display my Student Name and Student ID -->
            <div class="top-menu-bar-item me">
                <span class="top-menu-bar-text"><i class="mi-user"></i>Morgan Welch - 17006647</span>
            </div>
        </div>

        <!-- SECOND MENU NAV BAR (FOR INPUTTING DATA) -->
        <div class="second-menu-bar invisible" id="second_menu">

            <!-- Dropdown to select site to view data from -->
            <div class="second-menu-bar-item">
                <label for="site">
                    Select Site to View Data From
                    <i class="mi-location"></i>
                </label>
                <select id="site" name="site">
                    <?php 
                    foreach ($sites as $siteid => $sitename) {
                        echo "<option value='" . $siteid . "'>" . $sitename . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Input for selecting the year to view data from -->
            <div class="second-menu-bar-item">
                <label for="year">
                    Select Year to Display
                    <i class="mi-calendar"></i>
                </label>
                <input id="year" type="number" name="year" value="2016"/>
            </div>

            <!-- Input to select the time to view data from -->
            <div class="second-menu-bar-item">
                <label for="year">
                    Select Time to Compare
                    <i class="mi-clock"></i>
                </label>
                <input id="time" type="time" name="time" value="08:00"/>
            </div>

            <!-- Button to update the chart with the selected data -->
            <div class="second-menu-bar-item">
                <div class="button" onclick="showNOChart()">
                    <i class="mi-refresh"></i>
                    Update
                </div>
            </div>
        </div>

        <!-- THIRD MENU NAV BAR (FOR INPUTTING DATA) -->
        <div class="third-menu-bar invisible" id="third_menu">

            <!-- Input to choose what day to view data from -->
            <div class="third-menu-bar-item">
                <label for="date">
                    Select Date to View Data From
                    <i class="mi-calendar"></i>
                </label>
                <input type="date" name="date" id="date" onchange="retrieved_data = false"/>
            </div>

            <!-- Dropdown to choose a site to add to the site list -->
            <div class="third-menu-bar-item">
                <label for="site-add">
                    Add a site to the list (6 needed)
                    <i class="mi-location"></i>
                </label>
                <select id="site-add" name="site-add">
                    <?php 
                    foreach ($sites as $siteid => $sitename) {
                        echo "<option value='" . $siteid . "'>" . $sitename . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Button to add the selected site to the site list -->
            <div class="third-menu-bar-item">
                <div class="button" onclick="addSite()">
                    <i class="mi-add"></i>
                    Add Site to List
                </div>
            </div>

            <!-- The site list element -->
            <div class="third-menu-bar-item">
                <label for="site-list">
                    Click a site to view chart
                    <i class="mi-circle-arrow-right"></i>
                </label>
                <div class="site-list" id="site-list">
                    No sites
                </div>
            </div>
        </div>

        <!-- The google chart -->
        <div class="main-content" style="z-index: 1;">
            <div id="chart_div" style="width: 900px; height: 500px; z-index: 1;"></div>
        </div>
    </body>
</html>