<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-storage.js"></script>
    
    
    


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
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Country</th>
                    <th scope="col">Action</th>
                    <!-- <th scope="col">Profile</th> -->
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

       

        <button  class="btn btn-create"  id="createUserBtn">Create New User</button>

        

        <div class="modal" tabindex="-1" id="createUserPopup">
            <div class="modal-dialog">
                <div class="modal-content" style="align-items: center;">
                    <h2>User Form</h2>
                <form id="createUserForm"  enctype="multipart/form-data">
                    @csrf
                    @method('post')
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="createPopupName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input id="createPopupEmail" name="email" required></input>
                    </div>
                    <div class="form-group">
                        <label for="phone">Password:</label>
                        <input id="createPopupPassword" name="password" required></input>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input id="createPopupPhone" name="phone" required></input>
                    </div>
                    <div class="form-group">
                        <!-- <label for="country">Country:</label>
                        <input id="createPopupCountry" name="country" required></input> -->
                        <select name="country" id="createPopupCountry">
                        <option disable selected>Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                     <div class="form-group">
                        <label for="description">Profile:</label>
                        <input type="file" name="profile_image" id="createPopupImage">
                        <input type="hidden" name="image_url" id="imageURLInput">
                    </div>
                    <input type="submit"   class="button" value="submit">
                    <!-- onclick="uploadAndSubmit()" -->
                </form>
                </div>
            </div>
        </div>

        <!-- <div class="modal" tabindex="-1" id="createUserPopup">
            <div class="modal-dialog">
                <div class="modal-content" style="align-items: center;">
                    <h2>User Form</h2>
                    <form action="{{ route('registeruser') }}" method="post" enctype="multipart/form-data" id="registerForm">
            @csrf
            @method('post')
            <input class="inputs" type="text" placeholder="Name" name="name" required>
            <input class="inputs" type="email" placeholder="Email" name="email" required>
            <input class="inputs" type="text" placeholder="password" name="password" required>
            <input class="inputs" type="text" placeholder="phone" name="phone" required>
            <select name="country" id="selectCountry">
            <option disable selected>Country</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>
            <input type="file" name="profile_image" id="imageToUpload">
            <input type="hidden" name="image_url" id="imageURLInput">
            <input type="button" onclick="uploadAndSubmit()" class="button" value="submit">
        </form>
                </div>
            </div>
        </div> -->



        <div class="modal" tabindex="-1" id="editUserPopup">
            <div class="modal-dialog">
                <div class="modal-content" style="align-items: center;">
                    <h2>User Form</h2>
                <form >
                    @csrf
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="editPopupName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input id="editPopupEmail" name="email" required></input>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input id="editPopupPhone" name="phone" required></input>
                    </div>
                    <div class="form-group">
                        <label for="country">Country:</label>
                        <input id="editPopupCountry" name="country" required></input>
                    </div>
                    <!-- <div class="form-group">
                        <label for="description">Profile:</label>
                        <img src="#" alt="Profile Image">
                    </div> -->
                    <button type="button" id="saveChangesPopup">Save</button>
                </form>
                </div>
            </div>
        </div>


        <div class="modal" tabindex="-1" id="viewUserPopup">
            <div class="modal-dialog">
                <div class="modal-content" style="align-items: center;">
                    <h2>User Form</h2>
                <form >
                    @csrf
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="viewPopupName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input id="viewPopupEmail" name="email" required></input>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input id="viewPopupPhone" name="phone" required></input>
                    </div>
                    <div class="form-group">
                        <label for="country">Country:</label>
                        <input id="viewPopupCountry" name="country" required></input>
                    </div>
                    <!-- <div class="form-group">
                        <label for="description">Profile:</label>
                        <img src="#" alt="Profile Image">
                    </div> -->
                    <button type="button" id="viewChangesPopup">Done</button>
                </form>
                </div>
            </div>
        </div>


       
        <!-- <div id="viewUserPopup" style="display: none;">
            <h2>User Form</h2>
            <form >
                
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="viewPopupName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="viewPopupDescription" name="description" required></textarea>
                </div>
                 <button  onclick="viewPopup()">Hide</button> 
            </form>
        </div> -->
    </div>


    <script>



        var table;
        var userId =0;
        function deleteClicked(uId) {
            
            var csrfToken = "{{ csrf_token() }}";
            
            $.ajax({
                 url: '/deletecrud/' + uId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                        table.destroy();
                        // table.clear().destroy();
                            // Reinitialize the DataTable with the updated data
                            table = $('#user-table').DataTable({
                                data: response.data,
                                columns: [
                                    {data: 'name', name: 'name'},
                                    {data: 'email', name: 'email'},
                                    {data: 'phone', name: 'phone'},
                                    {data: 'country_id', name: 'country_id'},
                                    // {data: 'image', name: 'image'},
                                    {data: 'action', name: 'action', orderable: false, searchable: false}
                                ]
                            });
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(error);
                }
            });
            
        }
        
                $(document).ready(function () {
                     table= $('#user-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('crudfunctions') }}",
                        columns: [
                            {data: 'name', name: 'name'},
                            {data: 'email', name: 'email'},
                            {data: 'phone', name: 'phone'},
                            {data: 'country_id', name: 'country_id'},
                            // {data: 'image', name: 'image'},
                            {data: 'action', name: 'action', orderable: false, searchable: false}
                        ]
                    });
                });


                $('#createUserBtn').on('click', function () {
                    $('#createUserPopup').show();
                });
    

    // function createClicked() {
    //     // Get the updated values from the input fields in the popup
    //     var newName = $('#createPopupName').val();
    //     var newEmail = $('#createPopupEmail').val();
    //     var newPassword = "123567";
    //     var newImage = "hahsshhsh3h3hh3";
    //     var newPhone = $('#createPopupPhone').val();
    //     var newCountry = $('#createPopupCountry').val();

    //     var csrfToken = $('meta[name="csrf-token"]').attr('content');
    //     // Make an AJAX request to save the changes
    //     $.ajax({
    //         url: '/createuser',
    //         type: 'POST',
    //         headers: {
    //             'X-CSRF-TOKEN': csrfToken
    //         },
    //         data: {
    //             //id: userId, // Assuming you have the user ID stored in a variable
    //             name: newName,
    //             email: newEmail,
    //             phone: newPhone,
    //             country: newCountry,
    //             password: newPassword,
    //             image: newImage
    //         },
    //         success: function(response) {
                
    //                  table.clear();
    //                      table.clear().destroy();
    //                      $('#user-table').empty();
                            
    //                         // Reinitialize the DataTable with the updated data
    //                         table = $('#user-table').DataTable({
    //                             data: response.data,
    //                             columns: [
    //                                 {data: 'name', name: 'name'},
    //                                 {data: 'email', name: 'email'},
    //                                 {data: 'phone', name: 'phone'},
    //                                 {data: 'country_id', name: 'country_id'},
    //                                 // {data: 'image', name: 'image'},
    //                                 {data: 'action', name: 'action', orderable: false, searchable: false}
    //                             ]
    //                         });

    //             // $('#editUserPopup').hide();
    //         },
    //         error: function(xhr, status, error) {
    //             // Handle the error response
    //             console.log('Error saving changes:', error);
    //         }
    //     });
    // }

    function editClicked(uid){
        userId = uid;
        console.log(uid);
            // Perform AJAX request to fetch user data
            $.ajax({
                url: '/fetchuserdata/' + userId, // Replace with your route to fetch user data
                type: 'GET',
                success: function(response) {
                    
                    // Extract the name and description from the response
                    var name = response.name;
                    var email = response.email;
                    var phone = response.phone;
                    var country = response.country;
                    
                    // Populate the popup with the retrieved data
                    $('#editPopupName').val(name);
                    $('#editPopupEmail').val(email);
                    $('#editPopupPhone').val(phone);
                    $('#editPopupCountry').val(country);

                    // Open the popup
                    $('#editUserPopup').show();
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(error);
                }
            });
        }

        $("#viewChangesPopup").on('click', ()=>{
            $('#viewUserPopup').hide();
        });

        function viewClicked(uid){
        userId = uid;
        console.log(uid);
            // Perform AJAX request to fetch user data
            $.ajax({
                url: '/fetchuserdata/' + userId, // Replace with your route to fetch user data
                type: 'GET',
                success: function(response) {
                    
                    // Extract the name and description from the response
                    var name = response.name;
                    var email = response.email;
                    var phone = response.phone;
                    var country = response.country;
                    
                    // Populate the popup with the retrieved data
                    $('#viewPopupName').val(name);
                    $('#viewPopupEmail').val(email);
                    $('#viewPopupPhone').val(phone);
                    $('#viewPopupCountry').val(country);

                    // Open the popup
                    $('#viewUserPopup').show();
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log(error);
                }
            });
        }

    //     function viewClicked(userId){
    //     // Perform AJAX request to fetch user data
    //     $.ajax({
    //         url: '/fetchuserdata/' + userId, // Replace with your route to fetch user data
    //         type: 'GET',
    //         success: function(response) {
                
    //             // Extract the name and description from the response
    //             var name = response.name;
    //             var description = response.description;
                
    //             // Populate the popup with the retrieved data
    //             $('#viewPopupName').val(name);
    //             $('#viewPopupDescription').val(description);
                
    //             // Open the popup
    //             $('#viewUserPopup').show();
    //         },
    //         error: function(xhr, status, error) {
    //             // Handle error response
    //             console.log(error);
    //         }
    //     });
    // }

    // function viewPopup(){
    //     // Close the popup
    //     $('#viewUserPopup').css('display', 'none');

    // }


    $('#saveChangesPopup').on('click', function() {
        // Get the updated values from the input fields in the popup
        var newName = $('#editPopupName').val();
        var newEmail = $('#editPopupEmail').val();
        var newPhone = $('#editPopupPhone').val();
        var newCountry = $('#editPopupCountry').val();

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        // Make an AJAX request to save the changes
        $.ajax({
            url: '/updateuser/' + userId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                //id: userId, // Assuming you have the user ID stored in a variable
                name: newName,
                email: newEmail,
                phone: newPhone,
                country: newCountry
            },
            success: function(response) {
                        table.destroy();
                        //  table.clear().destroy();
                        //  $('#user-table').empty();
                            // Reinitialize the DataTable with the updated data
                            table = $('#user-table').DataTable({
                                data: response.data,
                                columns: [
                                    {data: 'name', name: 'name'},
                                    {data: 'email', name: 'email'},
                                    {data: 'phone', name: 'phone'},
                                    {data: 'country_id', name: 'country_id'},
                                    // {data: 'image', name: 'image'},
                                    {data: 'action', name: 'action', orderable: false, searchable: false}
                                ]
                            });
                    $('#editUserPopup').hide();
                    // $('#createPopupName').val('');
                    // $('#createPopupEmail').val('');
                    // $('#createPopupPhone').val('');
                    // $('#createPopupCountry').val('');
            },
            error: function(xhr, status, error) {
                // Handle the error response
                console.log('Error saving changes:', error);
            }
        });
    });


    // pop ups new user datat entry form
    $(function () {
        // Show the popup 
        $('#createUserBtn').on('click', function () {
            $('#createUserPopup').show();
        });

        // Handle form submission via AJAX
        // $('#createUserPopup form').on('submit', function (event) {
        //    event.preventDefault(); // Prevent the default form submission
        //   var newName = $('#createPopupName').val();
        //     var newEmail = $('#createPopupEmail').val();
        //     var newPassword = "123567";
        //     var newImage = "hahsshhsh3h3hh3";
        //     var newPhone = $('#createPopupPhone').val();
        //     var newCountry = $('#createPopupCountry').val();
        //     var csrfToken = "{{ csrf_token() }}";
        //     // Perform AJAX request to create a new user
        //     $.ajax({
        //         url: "{{ route('users.create') }}",
        //         type: "POST",
        //         data: {
        //             _token: csrfToken,
        //             name: newName,
        //             email: newEmail,
        //             phone: newPhone,
        //             country: newCountry,
        //             password: newPassword,
        //             image: newImage
        //         },
        //         // data: $(this).serialize(),
        //         success: function (response) {
        //             // clear and redraw table after getting new data
        //                 table.destroy();
        //                 //  table.clear().destroy();
        //                 //  $('#user-table').empty();
                            
        //                     // Reinitialize the DataTable with the updated data
        //                     table = $('#user-table').DataTable({
        //                         data: response.data,
        //                         columns: [
        //                             {data: 'name', name: 'name'},
        //                             {data: 'email', name: 'email'},
        //                             {data: 'phone', name: 'phone'},
        //                             {data: 'country_id', name: 'country_id'},
        //                             // {data: 'image', name: 'image'},
        //                             {data: 'action', name: 'action', orderable: false, searchable: false}
        //                         ]
        //                     });
        //         },
        //         error: function (xhr, status, error) {
        //             // Handle error response
        //         }
        //     });
        //      $('#createPopupName').val('');
        //      $('#createPopupEmail').val('');
        //      $('#createPopupPhone').val('');
        //      $('#createPopupCountry').val('');
        //     // Close the popup after the form is submitted
        //     $('#createUserPopup').hide();
        // });
    });


</script>

<script>
            var imageUrl="";

            $(document).ready(function() {
            $('#createUserForm').submit(function(event) {
                event.preventDefault(); // Prevent the default form submission
                
                var formData = new FormData(this); // Create a new FormData object with the form data
                console.log(formData);
                $.ajax({
                type: 'POST',
                url: '/users',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Handle successful response
                    console.log(response);
                    table.destroy();
                        //  table.clear().destroy();
                        //  $('#user-table').empty();
                            // Reinitialize the DataTable with the updated data
                            table = $('#user-table').DataTable({
                                data: response.data,
                                columns: [
                                    {data: 'name', name: 'name'},
                                    {data: 'email', name: 'email'},
                                    {data: 'phone', name: 'phone'},
                                    {data: 'country_id', name: 'country_id'},
                                    // {data: 'image', name: 'image'},
                                    {data: 'action', name: 'action', orderable: false, searchable: false}
                                ]
                            });
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(error);
                }
                });
                $('#createPopupName').val('');
                $('#createPopupEmail').val('');
                $('#createPopupPhone').val('');
                $('#createPopupCountry').val('');
                $('#createPopupPassword').val('');
                $('#imageURLInput').val('');
                $("#createUserPopup").hide();
            });
            });

       
    //    function uploadAndSubmit() {
    //     console.log("upload and submit");
    //         uploadToFirebase()
    //             .then((imageUrl) => {
    //                 // document.getElementById('createUserForm').submit();
    //                 // var formData = new FormData(document.getElementById('createUserForm'));
    //                 // console.log(formData);
    //                 $.ajax({
    //                     type: "POST",
    //                     url: "/users",
    //                     data: formData,
    //                     processData: false,
    //                     contentType: false,
    //                     success: function(response) {
    //                         // Handle successful response
    //                         console.log(response);
    //                     },
    //                     error: function(xhr, status, error) {
    //                         // Handle error
    //                         console.error(error);
    //                     }
    //                 });
    //             })
    //             .catch((error) => {
    //                 console.error(error);
    //                 // Handle any error that occurred during image upload
    //             });
    //             $('#createPopupName').val('');
    //             $('#createPopupEmail').val('');
    //             $('#createPopupPhone').val('');
    //             $('#createPopupCountry').val('');
    //             $('#createPopupPassword').val('');
    //             $("#createUserPopup").hide();
    //     }

        $(document).ready(function() {
            $('#createPopupImage').change(function() {
                var imageUploadPath = "";
                var imageUpload = "";
                       console.log("now in upload to firebase function");
                       var image = document.getElementById("createPopupImage").files[0];
                       var img = image.name;


                       // Initialize Firebase app
                       const firebaseConfig = {
                       apiKey: "AIzaSyCsJALsE6AEPvhLKo1HUYSPyzyo-0yQ6oU",
                       authDomain: "t-1-userauth.firebaseapp.com",
                       // databaseURL: "https://t-1-userh-default-rtdb.firebaseio.com",
                       projectId: "t-1-userauth",
                       storageBucket: "t-1-userauth.appspot.com",
                       // messagingSenderId: "5823983",
                       appId: "1:58239891333:web:f13a680d6254e91296f6ec",
                    
                       };

                       firebase.initializeApp(firebaseConfig);
                        imageUploadPath = firebase.storage().ref('images/' + img);
                        imageUpload = imageUploadPath.put(image);
                    
                       imageUpload.then((path)=>{
                           return path.ref.getDownloadURL();
                           console.log("got url after uploading ");
                       }).then((url)=>{
                           imageUrl = url;
                           document.getElementById('imageURLInput').value = imageUrl;
                           console.log(url);
                            
                       }).catch((error)=>{
                        console.log(error);
                       });
            });
            });

    //    function uploadToFirebase() {
    //     return new Promise((resolve, reject) => {
    //     console.log("now in upload to firebase function");
    //        var image = document.getElementById("createPopupImage").files[0];
    //        var img = image.name;
           

    //        // Initialize Firebase app
    //        const firebaseConfig = {
    //        apiKey: "AIzaSyCsJALsE6AEPvhLKo1HUYSPyzyo-0yQ6oU",
    //        authDomain: "t-1-userauth.firebaseapp.com",
    //        // databaseURL: "https://t-1-userh-default-rtdb.firebaseio.com",
    //        projectId: "t-1-userauth",
    //        storageBucket: "t-1-userauth.appspot.com",
    //        // messagingSenderId: "5823983",
    //        appId: "1:58239891333:web:f13a680d6254e91296f6ec",
           
    //        };
    //        firebase.initializeApp(firebaseConfig);

    //        var imageUploadPath = firebase.storage().ref('images/' + img);
           
    //        var imageUpload = imageUploadPath.put(image);
           
    //        imageUpload.then((path)=>{
    //            return path.ref.getDownloadURL();
    //            console.log("got url after uploading ");
    //        }).then((url)=>{
    //            imageUrl = url;
    //            document.getElementById('imageURLInput').value = imageUrl;
    //            console.log(url);
    //             resolve(imageUrl);
    //        }).catch((error)=>{
    //         reject(error);
    //        });
    //    })
       

    // }


       
   </script>

</body>
</html>



 <!-- <div id="editUserPopup"  style="display: none;">
            <h2>User Form</h2>
            <form >
                @csrf
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="editPopupName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input id="editPopupEmail" name="email" required></input>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input id="editPopupPhone" name="phone" required></input>
                </div>
                <div class="form-group">
                    <label for="country">Country:</label>
                    <input id="editPopupCountry" name="country" required></input>
                </div>
                 <div class="form-group">
                    <label for="description">Profile:</label>
                    <img src="#" alt="Profile Image">
                </div> 
                <button type="button" id="saveChangesPopup">Save</button>
            </form>
        </div> -->

        