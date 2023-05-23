<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .container {
    width: 400px;
    margin: 0 auto;
    padding: 20px;
    background: #f5f5f5;
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
<body>
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

</body>
</html>