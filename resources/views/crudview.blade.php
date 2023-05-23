<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        form {
  margin: 20px;
}

label {
  display: block;
  margin-bottom: 10px;
  font-weight: bold;
}

input[type="text"],
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-bottom: 20px;
}

button[type="submit"] {
  padding: 10px 20px;
  background-color: #4CAF50;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button[type="submit"]:hover {
  background-color: #45a049;
}

    </style>
</head>
<body>
    <!-- View Form -->
    <form>
        

        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}">
        </div>

        <div>
            <label for="description">Description:</label>
            <textarea name="description" id="description">{{ $user->description }}</textarea>
        </div>

       
    </form>

</body>
</html>