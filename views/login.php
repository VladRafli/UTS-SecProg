<?php

// Example only!
if ( isset($_POST['user']) && isset($_POST['pass']) ) {
    if ($_POST['user'] === 'admin' && $_POST['pass'] === 'admin') {
        create_session($_POST['user']);
        header('Location: http://localhost/Ecommercehandal/Home');
    } else {
        header('Location: http://localhost/Ecommercehandal/Login');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Page</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <h1>E-Commerce Handal</h1>
    <form action="http://localhost/Ecommercehandal/Login" method="post">
        <input type="text" name="user" id="user" placeholder="Username">
        <input type="password" name="pass" id="pass">
        <button type="submit">Login</button>
    </form>
</body>

</html>