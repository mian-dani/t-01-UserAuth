<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<body>
    <form   enctype="multipart/form-data">
        <!-- @csrf -->
        <input type="file" name="image" id="imageToUpload">
        <button type="button" onclick="uploadToFirebase()">Upload</button>
    </form>

    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-storage.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    

    <script>
       

        function uploadToFirebase() {
            var image = document.getElementById("imageToUpload").files[0];
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

            var imageUploadPath = firebase.storage().ref('images/' + img);
            
            var imageUpload = imageUploadPath.put(image);
            
            imageUpload.then((path)=>{
                return path.ref.getDownloadURL();
            }).then((url)=>{
                saveUrlInDb(url);
                console.log(url);
            }).catch((error)=>{
                console.log("Image Upload Un-Successful", error);
            });
        }

        function saveUrlInDb(url) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                 url: '/save-url',
                method: 'GET',
                // headers: {
                //     'X-CSRF-TOKEN': csrfToken
                // },
                data: { url: url }, 
                success: function(response) {
                    console.log(url);   
                },
                error: function(error) {
                    console.log(error);   
                }
            });
        }
    </script>

</body>
</html>