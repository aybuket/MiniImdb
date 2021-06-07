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

        .email {
            width: 150px;
        }

        input .login {
            display: block;
            margin: 0 auto;
            width: 200px;
        }

        input .user {
            display: inline-flex;
            width: 150px;
            margin: auto;
        }

        form .class{
            padding-top: 60px;
        }

        form .user{
            padding:0px;
            margin: auto;
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
            padding-top: 100px;
        }

        .list_top250 {
            position: fixed;
            top: 100px;
            left: 10px;
            bottom: 10px;
            padding-left: 50px;
            width: 78%;
            overflow-y: scroll;

            border: 3px darkolivegreen dashed;
        }
        .right {
            right: 0;
            width: 20%;
            background: bisque;
            height: 100%;
            text-align: center;
            border-left: 6px solid darkolivegreen;
        }

        .wrap {
            display:block;
            width:800px;
            word-wrap: normal;
            padding-left: 20px;
        }
    </style>
</head>
<body style="font-family:Lucida Sans, Calibri;">
<h1>Top 250 List</h1>
<div class="column left">
    <div class="list_top250">
        <?php
        require_once 'include/dbConnect.php';
        require_once 'include/functions.php';
        session_start();

        $result = get_top_250($conn);
        echo "<table>";
        echo "<tr>";
        echo "<td><b>| Movie Name </b></td>";
        echo "<td><b>|---Rating---|</b></td>";
        if (isset($_SESSION['username'])){
            echo "<td><b>|-----Rate-----|</b></td>";
            echo "<td><b>|---Watched---|</b></td>";
        }
        echo "</tr>";
        echo "</br>";
        foreach ($result as $row) {
            echo "<tr>";
            echo "<div class='wrap'>";
            echo "<td>| {$row['mname']} </td>";
            echo "</div>";
            echo "<td>| {$row['avg']}</td>";
            if (isset($_SESSION['username'])){
                echo "<td>";
                echo "<form class='user' action='myratings.php' method='post'>";
                echo "<select name='my_rating'>  ";
                echo "<option value=''>*</option>";
                echo "<option value='1'>1</option>";
                echo "<option value='2'>2</option>";
                echo "<option value='3'>3</option>";
                echo "<option value='4'>4</option>";
                echo "<option value='5'>5</option>";
                echo "<option value='6'>6</option>";
                echo "<option value='7'>7</option>";
                echo "<option value='8'>8</option>";
                echo "<option value='9'>9</option>";
                echo "<option value='10'>10</option>";
                echo "</select>";
                echo "<input type='hidden' name='movie_name' value='{$row['mname']}'>";
                echo "<input class='user' name='rate' value='|---Rate---|' type='submit'>";
                echo "</form>";
                echo "</td>";
                echo "<td>";
                echo "<form class='user' action='watched.php' method='post'>";
                echo "<input type='hidden' name='movie_name' value='{$row['mname']}'>";
                echo "<input class='user' name='watched' value='|---Watched---|' type='submit'>";
                echo "</form>";
                echo "</td>";
            }
            echo "</tr>";
            echo "</div>";
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
            <input class="login" maxlength="12" width="100" type='text' name='username_post' required="required"/>
            <br/>
            <label>Password:</label>
            <input class="login" maxlength="16" width="100" type='password' name='pass_post' required="required"/>
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


