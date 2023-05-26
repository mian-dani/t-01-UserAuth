<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body style="margin-left: 10%; margin-right: 10%; margin-top:2%">
    <div class="container">
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="date_filter">Date Filter:</label>
                <input type="date" id="date_filter" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="country_filter">Country Filter:</label>
                <input type="text" id="countryfilter" class="form-control" name="countryfilter">
            </div>
            <div class="col-md-4">
                <button id="filterbtn" class="btn btn-primary mt-4">Apply Filters</button>
            </div>
        </div>
        
    </div>

    <table id="user-table" class="table">
            <thead>
                <tr>
                
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Country</th>
                </tr>
            </thead>
            
    </table>

    <script>
        $(function () {
           var table= $('#user-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('allusers') }}",
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'country', name: 'country'},
                ]
            });

            $('#filterbtn').on('click', function (e) {
                e.preventDefault(); // Prevent the form from submitting
                var country= $('#countryfilter').val();
                

                table.clear(); // Redraw the DataTable to apply the filters
                $.ajax({
                    url: '/allusers',
                    type: 'get',
                    data: {
                        country: country
                    },
                    success: function (response){
                        table.rows.add(response.data).draw();
                    },
                    error: function(xhr, status,error){
                        console.log(error);
                    }
                });
            });

        });
    </script>
        

    <!-- <script>
        $(function () {
            $('#user-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('allusers') }}",
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'country', name: 'country'},
                ]
            });
            $('#filterbtn').on('submit', function (e) {
                e.preventDefault(); // Prevent the form from submitting

                table.draw(); // Redraw the DataTable to apply the filters
            });
        });
    </script> -->
        

<!-- <script>
        $(document).ready(function () {
            
            var table = $('#user-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('allusers') }}",
                    data: function (data) {
                        data.country = $('#countryfilter').val(); // Get the value of the country filter input
                    }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'country', name: 'country'},
                ]
            });
            
            
            $('#filterbtn').on('submit', function (e) {
                e.preventDefault(); // Prevent the form from submitting

                table.draw(); // Redraw the DataTable to apply the filters
            });
            
        });
   

        
    </script> -->

</body>
</html>















   

   

