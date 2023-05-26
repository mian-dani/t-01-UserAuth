<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

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
        <form action="{{ route('registeruser') }}" method="post">
            @csrf
            @method('post')
            <!-- <label for="">Name</label> -->
            <input class="inputs" type="text" placeholder="Name" name="name" required>
            <!-- <label for="">Email</label> -->
            <input class="inputs" type="email" placeholder="Email" name="email" required>
            <!-- <label for="">Password</label> -->
            <input class="inputs" type="text" placeholder="password" name="password" required>
            <!-- <label for="">Phone</label> -->
            <input class="inputs" type="text" placeholder="phone" name="phone" required>
            <!-- <label for="">Country</label> -->
            <input class="inputs" type="text" placeholder="Country" name="country" required>
            <input type="submit" class="button" value="submit">
        </form>
    
</body>
</html>