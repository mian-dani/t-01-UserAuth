<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .button{
            height: 50px;
            width: 50%;
            background: red;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>User logged in successfully</h1>
    <h3>{{ Auth::user()->name }}</h3>
    <!-- <h3>{{ Auth::user()->phone }}</h3>
    <h3>{{ Auth::user()->email }}</h3>
    <h3>{{ Auth::user()->country }}</h3> -->
       <?php
        
       ?>

    <form action="{{ route('userdetails') }}" method="get">
        <input type="submit" name="" id="" value="User Details" class="button">
    </form>

    <form action="{{ route('logout') }}" method="get">
        <input type="submit" name="" id="" value="Logout" class="button">
    </form>

</body>
</html>