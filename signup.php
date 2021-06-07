<html lang="en">
<head><title>Sign up Page</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: darkseagreen;
        }

        h1 {
            padding-left: 20px;
            padding-top: 20px;
        }

        label {
            display: block;
            width: 100px;
            margin: 0 auto;
        }

        input {
            display: block;
            margin: 0 auto;
            width: 200px;
        }

        .signup {
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
<div class="signup">
    <form action='signup.php' method='post'>
        <label>Username: </label><input type='text' name='username_insert' required="required"/>
        <br/>
        <label>Password: </label><input type='password' name='pass_insert' required="required"/>
        <br/>
        <label>Email: </label> <input type='text' name='email_insert' required="required"/>
        <br/>
        <input name="signup" value='Sign Up' type='submit'>
    </form>
    <hr>
    <a href="login.php">Login</a>
    <br/>
    <a href="index.php">Main Page</a>
</div>
<?php

require_once 'include/dbConnect.php';
require_once 'include/functions.php';

if (isset($_POST['signup'])) {

    $username = $_POST["username_insert"];
    $pass = $_POST["pass_insert"];
    $email = $_POST["email_insert"];

    if (check_username_exist($conn, $username) or check_email_exist($conn, $email)) {
        echo "<hr>";
        echo "This username or email is in use. Please use another or login.";
    } elseif (!check_username($username)) {
        echo "<hr>";
        echo "Invalid username.";
    } elseif (!check_email($email)) {
        echo "<hr>";
        echo "Invalid email.";
    } elseif (!check_pass($pass)) {
        echo "<hr>";
        echo "Invalid password. Password should be alphanumeric, at least 5 character, at most 16 character";
    } else {
        session_start();
        $result = insert_user($conn, $username, sha1($pass), $email);
        if ($result === TRUE) {

            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            header("Location: userpage.php");
        } else {
            echo "<hr>";
            echo "Error signing up: " . $conn->error;
        }
    }
}
?>

</body>
</html>
