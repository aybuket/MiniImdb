<html lang="en">
<head><title>Sign up Page</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body{
            background: darkseagreen;
        }
        h1{
            padding-left: 20px;
            padding-top: 20px;
        }

        label {
            display: block;
            width: 100px;
            margin:0 auto;
        }
        input {
            display: block;
            margin:0 auto;
            width: 200px;
        }

        .login {
            padding-top: 10px;
            padding-bottom: 10px;
            background: bisque;
            width: 300px;
            text-align: center;
            border: 6px solid darkolivegreen;
            border-radius: 25px;
            margin: auto;
            margin-top: 100px;
        }

    </style>
</head>
<body>
<div class="login">
    <form action='login.php' method='post'>
        <label>Username:</label>
        <input maxlength="12" width="100" type='text' name='username_post' required="required"/>
        <br/>
        <label>Password:</label>
        <input maxlength="16" width="100" type='password' name='pass_post' required="required"/>
        <br/>
        <input name="login" value='Login' type='submit'>
    </form>
    <hr>
    <a href="signup.php">Sign up</a>
    <br/>
    <a href="index.php">Main Page</a>
</div>

</body>
</html>

<?php

require_once 'include/dbConnect.php';

if (isset($_POST['login'])) {

    session_start();
    $username = $_POST["username_post"];
    $pass = $_POST["pass_post"];
    $hashed_pass = sha1($pass);

    $sql = $conn->prepare("SELECT username, email FROM users WHERE username=? AND password=?");
    $sql->bind_param("ss", $username, $hashed_pass);
    $sql->execute();
    $sql->bind_result($dbusername, $email);
    $sql->fetch();
    $sql->close();

    if($dbusername!="")
    {
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        header("Location: userpage.php");
    }
    else
    {
        echo "<hr>";
        echo "You give wrong info or user does not exist.";
    }
}
?>