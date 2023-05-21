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
</head>
<body>
    <div class="container">
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="date_filter">Date Filter:</label>
                <input type="date" id="date_filter" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="country_filter">Country Filter:</label>
                <input type="text" id="countryfilter" class="form-control">
            </div>
            <div class="col-md-4">
                <button id="filterbtn" class="btn btn-primary">Apply Filters</button>
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
        <tbody>
            <!-- Loop through the users and generate the table rows -->
            @foreach ($users as $user)
                <tr>
                    
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->country }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
        
    
    
    
    

    


    

   

<script>
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
   

        
    </script>

</body>
</html>















   

   

