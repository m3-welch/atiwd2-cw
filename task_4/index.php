<!DOCTYPE html>
<html>
    <head>
        <title>ATIWD2 - Task 4</title>
        <link href="stylesheet.css" rel="stylesheet"/>
        <link href="https://unpkg.com/mono-icons@1.2.4/iconfont/icons.css" rel="stylesheet"/>
        <link rel="preconnect" href="https://fonts.gstatic.com"/>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        <script type="text/javascript">
            window.addEventListener("load", () => getData())
            
            var jsonData = ''

            function svgMarker(hex, scale) {
                return {
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: hex,
                    fillOpacity: 0.85,
                    strokeWeight: 0,
                    rotation: 0,
                    scale: 10 * scale,
                    anchor: new google.maps.Point(0, 0),
                }
            }

            function initMap() {
                const center = new google.maps.LatLng(51.4545, -2.5879)
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 13,
                    center: center,
                })

                var scale = 1
                for (var stationid in jsonData.stations) {
                    new google.maps.Marker({
                        position: { lat: Number(jsonData.stations[stationid].lat), lng: Number(jsonData.stations[stationid].long) },
                        icon: svgMarker('#A67BE0', scale),
                        map: map,
                        title: jsonData.stations[stationid].name,
                    })
                    scale = scale + 0.3
                }
            }

            function selectMonth(month) {
                var month_to_select = document.getElementById('month-' + month)

                if (month_to_select.classList.contains('selected')) {
                    return
                } else {
                    var previously_seleced_month = document.getElementsByClassName('selected')

                    for (var i = 0; i < previously_seleced_month.length; i++) {
                        previously_seleced_month[i].classList.remove('selected')
                    }

                    month_to_select.classList.add('selected')
                }
            }

            function previousMonth() {
                var selected_month = document.getElementsByClassName('selected')[0]

                selected_month.classList.remove('selected')

                selected_month = selected_month.id.split('-')[1]

                var prev_month_no = 12

                if ((Number(selected_month) - 1) < 1) {
                    prev_month_no = 12
                } else {
                    prev_month_no = (Number(selected_month) - 1)
                }
                var prev_month = document.getElementById('month-' + prev_month_no)

                prev_month.classList.add('selected')
            }

            function nextMonth() {
                var selected_month = document.getElementsByClassName('selected')[0]

                selected_month.classList.remove('selected')

                selected_month = selected_month.id.split('-')[1]

                var next_month_no = 2

                if ((Number(selected_month) + 1) > 12) {
                    next_month_no = 1
                } else {
                    next_month_no = (Number(selected_month) + 1)
                }
                var next_month = document.getElementById('month-' + next_month_no)

                next_month.classList.add('selected')
            }

            function getData() {
                jsonData = $.ajax({
                    url: "getData.php",
                    type: "GET",
                    dataType: "json",
                    async: false,
                    processData: false,
                    contentType: false,
                    cache: false,
                }).responseText

                jsonData = JSON.parse(jsonData)

                var map = document.getElementById('map')
                map.classList.remove('invisible')
            }
        </script>
    </head>
    <body>
        <div class="top-menu-bar">
            <div class="top-menu-bar-title">
                <span class="title">ATIWD2 - Task 4</span>
            </div>

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
        <div class="loading">
            Loading data and maps...
        </div>
        <div style="height:100%; width:100%;">
            <div id="map" class="invisible"></div>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB24ptRAWnA0xYqcqV7KEf2LjRDxeOmNvI&callback=initMap&libraries=&v=weekly" async></script>
    </body>
</html>