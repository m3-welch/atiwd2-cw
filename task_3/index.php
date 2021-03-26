<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ATIWD2 - Task 3</title>
        <link rel="stylesheet" href="stylesheet.css"/>
        <link href="https://unpkg.com/mono-icons@1.2.4/iconfont/icons.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <?php include_once('sites_list.php');?>
        <script type="text/javascript">
            var sites = []
            var retrieved_data = false
            var jsonData = ''
            var site_list = <?php echo json_encode($sites);?>

            function coCertainTimeOfDay() {
                second_menu = document.getElementById('second_menu')
                button = document.getElementById('certainTimeOfDayButton')
                
                third_menu = document.getElementById('third_menu')
                alternateButton = document.getElementById('allLevels6StationsButton')

                if (second_menu.classList.contains('invisible')) {
                    second_menu.classList.remove('invisible')
                    button.style.backgroundColor = '#ADB5BD'

                    third_menu.classList.add('invisible')
                    alternateButton.style.backgroundColor = '#495057'
                } else {
                    second_menu.classList.add('invisible')
                    button.style.backgroundColor = '#495057'
                }
            }

            function allLevels6Stations() {
                third_menu = document.getElementById('third_menu')
                button = document.getElementById('allLevels6StationsButton')

                second_menu = document.getElementById('second_menu')
                alternateButton = document.getElementById('certainTimeOfDayButton')

                if (third_menu.classList.contains('invisible')) {
                    third_menu.classList.remove('invisible')
                    button.style.backgroundColor = '#ADB5BD'

                    second_menu.classList.add('invisible')
                    alternateButton.style.backgroundColor = '#495057'
                } else {
                    third_menu.classList.add('invisible')
                    button.style.backgroundColor = '#495057'
                }
            }

            function showNOChart() {
                var site_list = <?php echo json_encode($sites);?>

                var site = document.getElementById('site').value;
                var year = document.getElementById('year').value;
                var time = document.getElementById('time').value;

                if (site == '' || year == '' || time == '') {
                    alert('You must enter data for each of the inputs')
                    return
                }

                // Load the Visualization API and the piechart package.
                google.charts.load('current', {'packages':['corechart']});
                
                // Set a callback to run when the Google Visualization API is loaded.
                google.charts.setOnLoadCallback(drawChart);
                    
                function drawChart() {
                    var formData = new FormData()
                    formData.append("site", site)
                    formData.append("year", year)
                    formData.append("time", time)

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
                        });
                    }
                }
            }

            function addSite() {
                var site_to_add = document.getElementById('site-add').value
                
                if (sites.includes(site_to_add)) {
                    alert('That site is already in the list')
                    return
                }
                if (sites.length == 6) {
                    alert('You can only have 6 sites selected')
                    return
                }

                sites.push(site_to_add)

                var site_list_html = ''
                sites.forEach(site => {
                    var site_name = site_list[site]
                    if (site_name.length > 11) {
                        site_name = site_name.replace(site_name.substr(10), '...')
                    }
                    site_list_html += '<div class="site-list-item"><span onclick="show6StationsChart(' + site + ')">' + site_name + '</span><i class="mi-close close" onclick="removeFromList(' + site + ')"></i></div>'
                })

                document.getElementById('site-list').innerHTML = site_list_html
                retrieved_data = false
            }

            function removeFromList(site) {
                sites = sites.filter((value, index, arr) => {
                    return value != site
                })

                var site_list_html = ''

                sites.forEach(site => {
                    var site_name = site_list[site]
                    if (site_name.length > 11) {
                        site_name = site_name.replace(site_name.substr(10), '...')
                    }
                    site_list_html += '<div class="site-list-item"><span onclick="show6StationsChart(' + site + ')">' + site_name + '</span><i class="mi-close close" onclick="removeFromList(' + site + ')"></i></div>'
                })

                document.getElementById('site-list').innerHTML = site_list_html
                retrieved_data = false
            }

            function show6StationsChart(site) {
                var date = document.getElementById('date').value

                if (sites.length != 6) {
                    alert('You must select 6 sites to view data from')
                    return
                }

                if (date == '') {
                    alert('You must select a date to view data on')
                    return
                }

                // Load the Visualization API and the piechart package.
                google.charts.load('current', {'packages':['corechart']});
                
                // Set a callback to run when the Google Visualization API is loaded.
                google.charts.setOnLoadCallback(drawChart);
                    
                function drawChart() {
                    var formData = new FormData()
                    formData.append("sites", sites.join(','))
                    formData.append("date", date)

                    if (!retrieved_data) {
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

                    if (JSON.parse(jsonData).error == 'No data available for the provided parameters') {
                        alert(JSON.parse(jsonData).error)
                    } else {
                        // Create our data table out of JSON data loaded from server.
                        var data = new google.visualization.DataTable(JSON.parse(jsonData)[site]);
                
                        // Instantiate and draw our chart, passing in some options.
                        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                        var site_names = []
                        sites.forEach((site) => {
                            site_names.push(site_list[site])
                        })

                        chart.draw(data, {
                            width: 1920,
                            height: 1080 - 350,
                            backgroundColor: '#E9ECEF',
                            title: 'All levels from ' + site_list[site] + ' per hour on the day ' + date,
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

            <div class="top-menu-bar-item">
                <div class="top-menu-bar-button" id="certainTimeOfDayButton" onclick="coCertainTimeOfDay()">
                    <i class="mi-clock"></i>
                    View Nitrogen Monoxide Levels for a Certain Time of Day
                </div>
            </div>

            <div class="top-menu-bar-item">
                <div class="top-menu-bar-button" id="allLevels6StationsButton" onclick="allLevels6Stations()">
                    <i class="mi-calendar"></i>
                    View All Levels for a Certain Day From 6 Stations
                </div>
            </div>

            <div class="top-menu-bar-item me">
                <span class="top-menu-bar-text"><i class="mi-user"></i>Morgan Welch - 17006647</span>
            </div>
        </div>

        <!-- SECOND MENU NAV BAR (FOR INPUTTING DATA) -->
        <div class="second-menu-bar invisible" id="second_menu">

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

            <div class="second-menu-bar-item">
                <label for="year">
                    Select Year to Display
                    <i class="mi-calendar"></i>
                </label>
                <input id="year" type="number" name="year" value="2016"/>
            </div>

            <div class="second-menu-bar-item">
                <label for="year">
                    Select Time to Compare
                    <i class="mi-clock"></i>
                </label>
                <input id="time" type="time" name="time" value="08:00"/>
            </div>

            <div class="second-menu-bar-item">
                <div class="button" onclick="showNOChart()">
                    <i class="mi-refresh"></i>
                    Update
                </div>
            </div>
        </div>

        <!-- THIRD MENU NAV BAR (FOR INPUTTING DATA) -->
        <div class="third-menu-bar invisible" id="third_menu">

            <div class="third-menu-bar-item">
                <label for="date">
                    Select Date to View Data From
                    <i class="mi-calendar"></i>
                </label>
                <input type="date" name="date" id="date" onchange="retrieved_data = false"/>
            </div>

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

            <div class="third-menu-bar-item">
                <div class="button" onclick="addSite()">
                    <i class="mi-add"></i>
                    Add Site to List
                </div>
            </div>

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

        <div class="main-content" style="z-index: 1;">
            <div id="chart_div" style="width: 900px; height: 500px; z-index: 1;"></div>
        </div>
    </body>
</html>