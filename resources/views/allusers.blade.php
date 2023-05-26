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
                <input type="date" id="datefilter" class="form-control" name="datefilter">
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
            <tbody>
            </tbody>
            
    </table>

    
    <script>
        var table;
        $(function () {
            table= $('#user-table').DataTable({
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

            $('#filterbtn').on('click', function () {
                
               // table.clear();
                $.ajax({
                    url: 'allusers',
                    type: 'GET',
                    data: {
                        country: $('#countryfilter').val(),
                        date: $('#datefilter').val()
                    },
                    success: function (response){
                        
                         table.clear();
                         table.clear().destroy();
                         $('#user-table').empty();
                            
                            // Reinitialize the DataTable with the updated data
                            table = $('#user-table').DataTable({
                                data: response.data,
                                columns: [
                                    {data: 'name', name: 'name'},
                                    {data: 'email', name: 'email'},
                                    {data: 'phone', name: 'phone'},
                                    {data: 'country', name: 'country'},
                                ]
                            });
                    },
                    error: function(xhr, status,error){
                        console.log(error);
                    }
                });
            });
        });
    </script>
        

    
</body>
</html>















   

   

