<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Document</title>
</head>
<body>
    <div style="width: 80%"><canvas id="userRegistrationGraph"></canvas></div>
    

    <script>
        var userRegistrations = @json($userRegistrations);

        var dates = Object.keys(userRegistrations);
        var registrationCounts = Object.values(userRegistrations);

        var ctx = document.getElementById('userRegistrationGraph').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Daily User Registrations',
                    data: registrationCounts,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });
    </script>

</body>
</html>




    

   
