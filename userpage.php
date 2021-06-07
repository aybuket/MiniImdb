<?php
session_start();
if(!isset($_SESSION['username'])){
    header("location: index.php");
}
else
{?>
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

            .column, .inner {
                position: fixed;
                z-index: 1;
                top: 0;
                overflow-x: hidden;
                float: left;
                padding: 10px;
            }

            .inner{
                padding-top: 100px;
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

            .rating{
                padding-left: 50px;
                left: 0;
                width: 50%;
            }

            .watched{
                padding-right: 20%;
                right: 0;
                width: 50%;
            }

            .hour {
                padding-left: 50px;
                top: 175px;
            }

            .language {
                padding-right: 20%;
                right: 0;
                width: 50%;
                top: 175px;
            }

        </style>
    </head>
    <body style="font-family:Lucida Sans, Calibri;">
    <h1> Mini IMDB</h1>
    <div class="column left">
        <div class="inner rating">
            <h2><a href="myratings.php">My Ratings</a></h2>
            <?php
            require_once 'include/dbConnect.php';
            require_once 'include/functions.php';
            $result = get_top_n_ratings($conn, $_SESSION['username'], 5);
            foreach($result as $row){
                echo $row['mname'], " ", $row['vote'], "<br>";
            }
            mysqli_free_result($result);
            ?>
        </div>
        <div class="inner watched">
            <h2><a href="watched.php">Watched</a></h2>
            <?php
            require_once 'include/dbConnect.php';
            require_once 'include/functions.php';
            $result = get_top_n_watched($conn, $_SESSION['username'], 5);
            foreach($result as $row){
                echo $row['mname'], "<br>";
            }
            mysqli_free_result($result);
            ?>
        </div>
        <div class="inner movies hour">
            <h3>How many minutes spent?</h3>
            <?php
            require_once 'include/dbConnect.php';
            require_once 'include/functions.php';
            $result = get_total_watched_hour($conn, $_SESSION['username']);
            foreach($result as $row){
                echo $row['dur'], " mins spent while watching movies.<br>";
            }
            mysqli_free_result($result);
            echo "<h3>Average Rating</h3>";
            $result = get_avg_watched_rating($conn, $_SESSION['username']);
            foreach($result as $row){
                printf("%.3s", $row['avg']);
                echo " is the average rating of watched movies.<br>";
            }
            mysqli_free_result($result);
            ?>
        </div>
        <div class="inner movies language">
            <h3>Which language, how many times, minutes</h3>
            <?php
            require_once 'include/dbConnect.php';
            require_once 'include/functions.php';
            $result = get_watched_languages($conn, $_SESSION['username']);
            echo "<table>";
            echo "<tr>";
            echo "<td><b>| Language </b></td>";
            echo "<td><b>| Count </b></td>";
            echo "<td><b>| Total duration </b></td>";
            echo "</tr>";
            echo "</br>";
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>| {$row['lang']} </td>";
                echo "<td>| {$row['count']} </td>";
                echo "<td>| {$row['dur']} </td>";
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
            <a href="index.php">Main Page</a>
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
<?php
}
?>