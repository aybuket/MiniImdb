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

        input {
            display: block;
            margin: 0 auto;
            width: 200px;
        }

        form {
            padding-top: 60px;
        }

        .label_padding{
            padding-left: 20px;
        }
        .ordering {
            display: inline-flex;
            margin-left: auto;
            width: 100px;
            padding-top: 0px;
            padding-left: 20px;
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

        .list_rating {
            padding-left: 50px;
        }
        .right {
            right: 0;
            width: 20%;
            background: bisque;
            height: 100%;
            text-align: center;
            border-left: 6px solid darkolivegreen;
        }
    </style>
</head>
<body style="font-family:Lucida Sans, Calibri;">
<h1>My Wacthed List</h1>
<div class="column left">
    <labell class="label_padding">Order by</labell>
    <form class="ordering" action="watched.php" method="post">
        <input type="submit" name="order_name_asc" value="Name (A->Z)"/>
        <input type="submit" name="order_name_desc" value="Name (Z->A)"/>
        <input type="submit" name="order_year_desc" value="Year (Newest)"/>
        <input type="submit" name="order_year_asc" value="Year (Oldest)"/>
        <input type="submit" name="order_time_desc" value="Time (Last)"/>
        <input type="submit" name="order_time_asc" value="Time (First)"/>
    </form>
    <hr>
    <div class="list_rating">
        <?php
        require_once 'include/dbConnect.php';
        require_once 'include/functions.php';
        session_start();

        if (isset($_POST['watched']))
        {
            $movie_name = $_POST['movie_name'];
            watch_movie($conn, $_SESSION['username'], $movie_name);
        }

        $ordering = " ORDER BY m.mname ASC";
        if (isset($_POST['order_name_asc'])) {
            $ordering = " ORDER BY m.mname ASC";
        }
        if (isset($_POST['order_name_desc'])) {
            $ordering = " ORDER BY m.mname DESC";
        }
        if (isset($_POST['order_year_desc'])) {
            $ordering = " ORDER BY m.year DESC";
        }
        if (isset($_POST['order_year_asc'])) {
            $ordering = " ORDER BY m.year ASC";
        }
        if (isset($_POST['order_time_desc'])) {
            $ordering = " ORDER BY w.watch_date DESC";
        }
        if (isset($_POST['order_time_asc'])) {
            $ordering = " ORDER BY w.watch_date ASC";
        }

        $result = get_watched($conn, $_SESSION['username'], $ordering);
        echo "<table>";
        echo "<tr>";
        echo "<td><b>| When </b></td>";
        echo "<td><b>| Movie Year </b></td>";
        echo "<td><b>| Movie Name </b></td>";
        echo "</tr>";
        echo "</br>";
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>| {$row['watch_date']} </td>";
            echo "<td>| {$row['year']} </td>";
            echo "<td>| {$row['mname']} </td>";
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

