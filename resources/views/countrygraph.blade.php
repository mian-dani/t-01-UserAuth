<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.canvasjs.com/ga/canvasjs.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <div id="chartContainer" style="height: 300px; width: 100%;"></div>

    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            var data = @json($data);

            var options = {
                data: [{
                    type: "column",
                    dataPoints: data,
                    click: function(e) {
                        var country = e.dataPoint.label;
                        window.location.href = '/chartuserdetail?country=' + country;
                    }
                }]
            };

            var chart = new CanvasJS.Chart("chartContainer", options);
            chart.render();
        });
        </script>

    
    <!-- <script>
    window.addEventListener('DOMContentLoaded', (event) => {
        // Get the data passed from the controller
        var data = @json($data);

        // Options for the chart
        var options = {
            data: [{
                type: "column",
                dataPoints: data
            }]
        };

        // Render the chart
        var chart = new CanvasJS.Chart("chartContainer", options);
        chart.render();
    });
    </script> -->

    
</body>
</html>



