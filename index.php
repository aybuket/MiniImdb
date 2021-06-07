<!--
    COMP306-

-->
<html>
<head>
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
            padding-bottom: 20px;
            border-bottom: 6px darkolivegreen solid;
        }

        label {
            display: block;
            width: 100px;
            margin: 0 auto;
        }

        .email{
            width: 150px;
        }

        input {
            display: block;
            margin: 0 auto;
            width: 200px;
        }

        form {
            padding-top: 60px;
        }

        .column {
            position: fixed;
            z-index: 1;
            top: 0;
            overflow-x: hidden;
            float: left;
            padding: 10px;
        }

        .left {
            left: 0;
            width: 80%;
        }

        .right {
            right: 0;
            width: 20%;
            background: bisque;
            height: 100%;
            text-align: center;
            border-left: 6px solid darkolivegreen;
        }

        .top_rated_directors{
            position: fixed;
            top: 100px;
            left: 20px;
            bottom: 10px;
            width: 300px;
            padding-left: 10px;
            overflow-y: scroll;

            border: 3px darkolivegreen dashed;
        }
        .top_avg_rated_actors{
            position: fixed;
            top: 100px;
            left: 330px;
            bottom: 10px;
            width: 300px;
            padding-left: 10px;
            overflow-y: scroll;

            border: 3px darkolivegreen dashed;
        }
        .top_rated_genres{

            position: fixed;
            top: 100px;
            left: 650px;
            bottom: 10px;
            width: 300px;
            padding-left: 10px;
            overflow-y: scroll;

            border: 3px darkolivegreen dashed;
        }
    </style>
</head>
<body style="font-family:Lucida Sans, Calibri;">
<h1> Mini IMDB</h1>
<div class="column left">
    <div class="top_rated_directors">
        <h2>Top Rated Directors</h2>
        <?php
        require_once 'include/dbConnect.php';
        require_once 'include/functions.php';
        session_start();

        $result = get_85_rated_directors($conn);

        echo "<table>";
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>| {$row['fname']} </td>";
            echo "<td>| {$row['lname']} </td>";
            echo "</tr>";
        }
        echo "</table>";
        mysqli_free_result($result);
        ?>
    </div>
    <div class="top_avg_rated_actors">
        <h2>Top Rated Actors</h2>
        <?php
        require_once 'include/dbConnect.php';
        require_once 'include/functions.php';

        $result = get_avg_top_rated_acts($conn);

        echo "<table>";
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>| {$row['fname']} </td>";
            echo "<td>| {$row['lname']} </td>";
            printf( "<td>| %.3s </td>",$row['avg']);
            echo "</tr>";
        }
        echo "</table>";
        mysqli_free_result($result);
        ?>
    </div>
    <div class="top_rated_genres">
        <h2>Top Rated Genres</h2>
        <?php
        require_once 'include/dbConnect.php';
        require_once 'include/functions.php';

        $result = get_top_rated_genres($conn);

        echo "<table>";
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>| {$row['genre']} </td>";
            printf( "<td>| %.3s </td>",$row['avg']);
            echo "</tr>";
        }
        echo "</table>";
        mysqli_free_result($result);
        ?>
    </div>

</div>
<div class="column right">
    <?php
    if (!isset($_SESSION['username'])) {
        ?>
        <form class="login" action='login.php' method='post'>
            <label>Username:</label>
            <input maxlength="12" width="100" type='text' name='username_post' required="required"/>
            <br/>
            <label>Password:</label>
            <input maxlength="16" width="100" type='password' name='pass_post' required="required"/>
            <br/>
            <input name="login" value='Login' type='submit'>
        </form>
        <a class="login" href="signup.php">Sign up</a>
        <?php
    } else {
        ?>
        <svg height="100" width="100">
            <circle cx="50" cy="50" r="40" stroke="darkolivegreen" fill="bisque"/>
        </svg>
        </br>
        <label><b>Username</b></label>
        <label><?php echo $_SESSION['username']; ?></label>
        </br>
        <label><b>Email</b></label>
        <label class="email"><?php echo $_SESSION['email']; ?></label>
        <hr>
        <a href="userpage.php">My Page</a>
        </br>
        <a href="logout.php">Log out</a>
        <?php
    }
    ?>
    <hr>
    <a class="top250" href="top250.php">Top 250</a>
    </br>
    <a class="duration" href="genres.php">Genres</a>
</div>


</body>
</html>