<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-storage.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>User Form</title>
    <style>
        .body{
            background: #303030;
            color: white;
            display: flex;
            width:100vw;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .inputs{
            display: block;
            height: 50px;
            width: 60%;
            margin-bottom: 10px;
        }
        select{
            display: block;
            height: 50px;
            width: 60%;
            margin-bottom: 10px;
        }
        #imageToUpload{
            display: block;
            height: 50px;
            width: 60%;
            margin-bottom: 10px;
            margin-top: 10px;
        }
        form{
            width: 80%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .button{
            height: 50px;
            width: 50%;
            background: red;
            color: white;
            font-size: 20px;
        }
    </style>
</head>
@include('navbar')
<body class="body">
<!-- @include('navbar') -->
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
        


        <script>
            var imageUrl="";
       
       function uploadAndSubmit() {
        console.log("upload and submit");
            uploadToFirebase()
                .then((imageUrl) => {
                    console.log("response returnned to submit after uploading image");
                    document.getElementById('imageURLInput').value = imageUrl;
                    console.log(imageUrl);
                    document.getElementById('registerForm').submit();
                })
                .catch((error) => {
                    console.error(error);
                    // Handle any error that occurred during image upload
                });
        }

       function uploadToFirebase() {
        return new Promise((resolve, reject) => {
        console.log("now in upload to firebase function");
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
               console.log("got url after uploading ");
           }).then((url)=>{
               imageUrl = url;
               console.log(url);
                resolve(imageUrl);
           }).catch((error)=>{
            reject(error);
           });
       })
       

    }

       
   </script>

    
</body>
</html>

