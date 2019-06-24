<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="https://api-maps.yandex.ru/2.1.74/?apikey=a9ce5401-6411-4ef5-9947-b731ba9ec329&lang=ru_RU" type="text/javascript"></script>
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

        <link type="text/css" rel="stylesheet" href="/plugins/rickshaw/rickshaw.min.css">
        <script src="/plugins/rickshaw/d3.min.js"></script>
        <script src="/plugins/rickshaw/d3.layout.min.js"></script>
        <script src="/plugins/rickshaw/rickshaw.min.js"></script>

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            #map {
                width: 100%;
                height: 500px;
                margin-bottom: 20px;
            }

            #chart_container {
                display: inline-block;
                font-family: Arial, Helvetica, sans-serif;
                width: 100%;
            }
            #chart {
                float: left;
            }
            #y_axis {
                float: left;
                width: 40px;
            }

        </style>
    </head>
    <body>

        <div id="map"></div>

        <div id="chart_container">
            <div id="y_axis"></div>
            <div id="chart"></div>
        </div>

    </body>
</html>

<script type="text/javascript">

    var cities = JSON.parse('{!! json_encode($cities->toArray()) !!}');

    var x_min = 0;
    var x_max = 0;
    var y_min = 0;
    var y_max = 0;

    var graph;
    var data = [];

    ymaps.ready(init);

    function init(){

        var itemsProcessed = 0;

        cities.forEach(function(el){
            ymaps.geocode(el.name).then(function (res) {
                var firstGeoObject = res.geoObjects.get(0);
                var cords = firstGeoObject.geometry.getCoordinates();
                el.x = cords[0];
                el.y = cords[1];
                if(cords[0] > x_max) {
                    x_max = cords[0]
                }
                if(cords[0] < x_min || x_min == 0) {
                    x_min = cords[0]
                }
                if(cords[1] > y_max) {
                    y_max = cords[1]
                }
                if(cords[1] < y_min || y_min == 0) {
                    y_min = cords[1]
                }
                itemsProcessed++;
                if(itemsProcessed === cities.length) {
                    callbackGeocode();
                }
            }, function (err) {
            });
        })
    }

    function callbackGeocode(){

        var x_center = (x_min + x_max) / 2;
        var y_center = (y_min + y_max) / 2;

        var myMap = new ymaps.Map("map", {
            center: [x_center, y_center],
            zoom: 4
        });

        cities.forEach(function(el) {
            //array_push
            var myGeoObject = new ymaps.Placemark([el.x, el.y], {
                hintContent: el.name + ": " + el.latest_offer_count['count'],
                cityID: el.id
            });
            myMap.geoObjects.add(myGeoObject);
            myGeoObject.events.add(['click'],  function (e) {
                var target = e.get('target');
                var cityID = target.properties.get('cityID');
                showOfferCountHistory(cityID);
            })
        });

    }

    function showOfferCountHistory(cityID)
    {
        $.ajax({
            url:'/offers',
            dataType:'JSON',
            method: 'GET',
            data:{
                'city_id': cityID
            },
            success:function(response){
                if(Array.isArray(response)){
                    renderChart(response)
                }
                else
                {
                    alert('Failed to load chart data :(');
                }
            }
        });
    }

    function renderChart(offers)
    {
        data = [];

        offers.forEach(function(offer){
            data.push({x: new Date(offer.created_at).getTime() / 1000, y: offer.count});
        });

        if(typeof graph !== "object") {

            var palette = new Rickshaw.Color.Palette();

            var width = $('#map').width() - 60;

            graph = new Rickshaw.Graph({
                element: document.querySelector("#chart"),
                width: width,
                height: 500,
                renderer: 'line',
                series: [
                    {
                        name: "Offers",
                        data: data,
                        color: palette.color()
                    }
                ]
            });

            var x_axis = new Rickshaw.Graph.Axis.Time({graph: graph});

            var y_axis = new Rickshaw.Graph.Axis.Y({
                graph: graph,
                orientation: 'left',
                tickFormat: Rickshaw.Fixtures.Number.formatKMBT,
                element: document.getElementById('y_axis'),
            });

            graph.render();

        } else {

            graph.series[0].data = data;
            graph.update();

        }
    }

</script>