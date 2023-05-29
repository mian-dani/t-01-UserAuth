<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <title>Document</title>
    <style>
        .user-table {
        /* width: 100%; */
        border-collapse: collapse;
        }

        .user-table th,
        .user-table td {
        padding: 8px;
        border: 1px solid #ddd;
        }

        .user-table th {
        background-color: #f2f2f2;
        }

        .btn {
        display: inline-block;
        padding: 8px 16px;
        background-color: #4caf50;
        color: #fff;
        text-decoration: none;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        }

        .btn-view {
        background-color: #2196f3;
        }

        .btn-edit {
        background-color: #ffc107;
        }

        .btn-delete {
        background-color: #f44336;
        }

        .btn-create {
        display: block;
        margin-top: 16px;
        background-color: #4caf50;
        }

        .btn:hover {
        opacity: 0.8;
        }

        
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 5px;
        }

        button {
            padding: 10px 20px;
            background: #4caf50;
            color: #fff;
            border: none;
            cursor: pointer;
        }

    </style>
</head>
<body style=" display:flex; justify-content:center; align-items:center; flex-direction:column">
  <div style="display:flex; justify-content:center; align-items:center; flex-direction:column;">
        <h1>Crud Operations</h1>

        <table id="user-table" class="table">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

       

        <button  class="btn btn-create" id="createUserBtn">Create New User</button>

        <div id="createUserPopup" style="display: none;">
            <h2>User Form</h2>
            <form method="POST" action="{{ route('useradded') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>


    <script>



        var table;

        function deleteClicked(userId) {
            console.log("i am clicked");
            var csrfToken = "{{ csrf_token() }}";
            console.log(csrfToken);
            $.ajax({
                 url: '/deletecrud/' + userId,
                
                
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    console.log("Response is positive");
                    $('#user-table').DataTable().row($(this).closest('tr')).remove().draw();
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(error);
                }
            });
            
        }
        
                $(function () {
                     table= $('#user-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('crudfunctions') }}",
                        columns: [
                            {data: 'name', name: 'name'},
                            {data: 'description', name: 'description'},
                            {data: 'action', name: 'action', orderable: false, searchable: false}
                        ]
                    });
                })

                /// pop ups new user datat entry form
    $(function () {
        // Show the popup when Create User button is clicked
        $('#createUserBtn').on('click', function () {
            $('#createUserPopup').show();
        });

        // Handle form submission via AJAX
        $('#createUserPopup form').on('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission
            

            // Perform AJAX request to create a new user
            $.ajax({
                url: "{{ route('users.create') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    // clear and redraw table after getting new data
                    table.clear();
                         table.clear().destroy();
                         $('#user-table').empty();
                            
                            // Reinitialize the DataTable with the updated data
                            table = $('#user-table').DataTable({
                                data: response.data,
                                columns: [
                                    {data: 'name', name: 'name'},
                                    {data: 'description', name: 'description'},
                                    {data: 'action', name: 'action', orderable: false, searchable: false}
                                ]
                            });
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    // Show an error message or perform appropriate actions
                }
            });
            $('#name').val('');
            $('#description').val('');
            // Close the popup after the form is submitted
            $('#createUserPopup').hide();
        });
    });

    
//     $(document).ready(function() {
//     $('.btn-delete').on('click', function() {
        
//         var deleteUrl = $(this).closest('form').attr('action');

//         $.ajax({
//             url: deleteUrl,
//             // type: 'DELETE',
//             data: {
//                 _token: '{{ csrf_token() }}',
//             },
//             success: function(response) {
//                 // Handle success response
//                 // Remove the deleted row from the table
//                 $('#user-table').DataTable().row($(this).closest('tr')).remove().draw();
//             },
//             error: function(xhr, status, error) {
//                 // Handle error response
//                 console.log(error);
//             }
//         });
//     });
// });




</script>


</body>
</html>