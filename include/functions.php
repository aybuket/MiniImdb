<?php

function check_username($username){
    return ctype_alpha($username) and (strlen($username) < 12) and (strlen($username) > 3);
}

function check_username_exist($conn, $username){
    $sql = $conn->prepare("SELECT username FROM users WHERE username=?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $dbusername = "";
    $sql->bind_result($dbusername);
    $sql->fetch();
    $sql->close();

    return $dbusername!="";
}

function check_email_exist($conn, $email){
    $sql = $conn->prepare("SELECT username FROM users WHERE email=?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $dbmail = "";
    $sql->bind_result($dbmail);
    $sql->fetch();
    $sql->close();

    return $dbmail!="";
}

function check_email($email){
    return (strlen($email) < 20) and (strlen($email) > 5);
}

function check_pass($pass){
    return ctype_alpha($pass) and (strlen($pass) < 16) and (strlen($pass) > 4);
}

function insert_user($conn,$username,$hashed_pass,$email){
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_pass', '$email')";
    return $conn->query($sql);
}

function get_top_n_ratings($conn, $username, $n)
{
    $sql = "SELECT m.mname, r.vote 
            FROM movies as m 
                JOIN user_rating as r ON m.mid = r.mid 
            WHERE username='$username' 
            ORDER BY RAND() 
            LIMIT $n";
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function get_my_ratings($conn, $username, $ordering)
{
    $sql = "SELECT m.mname, m.year, r.vote 
            FROM movies as m 
                JOIN user_rating as r ON m.mid = r.mid 
            WHERE username='$username'".$ordering;;

    if ($result = mysqli_query($conn, $sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function get_top_n_watched($conn, $username, $n)
{
    $sql = "SELECT m.mname, m.year
            FROM movies as m 
                JOIN watched as w ON w.mid = m.mid 
            WHERE username='$username' 
            ORDER BY RAND() 
            LIMIT $n";
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function get_genre_country($conn)
{
    /*View is created:
     *CREATE VIEW genre_country_count AS
     * (SELECT g.genre, c.country, count(*) as cunt
     *  FROM movies_genre as g
     *       JOIN movies_country as c ON g.mid = c.mid
     *  GROUP BY g.genre, c.country);
     */
    $sql = "SELECT genre, country, cunt 
            FROM genre_country_count 
            WHERE (genre,cunt) IN 
                (SELECT genre, MAX(cunt) 
                    FROM genre_country_count 
                GROUP BY genre)";
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function get_genre_language($conn)
{
    /*View is created:
     *CREATE VIEW genre_language_count AS
	 *(SELECT g.genre, l.language, count(*) AS count
	 *	FROM movies_genre as g
	 *		JOIN movies_language AS l ON g.mid = l.mid
	 *GROUP BY g.genre, l.language);
     */
    $sql = "SELECT genre, language, count 
            FROM genre_language_count 
            WHERE (genre,count) IN 
                (SELECT genre, MAX(count) 
                    FROM genre_language_count 
                GROUP BY genre)";
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function get_watched($conn, $username, $ordering)
{
    $sql = "SELECT m.mname, m.year, w.watch_date
            FROM movies as m 
                JOIN watched as w ON w.mid = m.mid 
            WHERE username='$username'".$ordering;
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function get_85_rated_directors($conn)
{
    $sql = "SELECT fname, mname, lname 
            FROM people_name 
            WHERE pid IN 
                (SELECT DISTINCT d.pid 
                 FROM directed AS d 
                     JOIN rating AS r ON r.mid = d.mid 
                 WHERE r.avg > 8.5 and r.total > 10000)";
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function get_avg_duration_genre($conn)
{
    $sql = "SELECT genre, AVG(duration) as avg
            FROM movies_genre AS g 
                JOIN movies AS m ON g.mid = m.mid 
            GROUP BY g.genre 
            ORDER BY AVG(duration) DESC;";
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function get_top_rated_genres($conn)
{
    $sql = "SELECT g.genre, AVG(r.avg) as avg
            FROM movies_genre AS g 
                JOIN rating AS r ON g.mid = r.mid 
            GROUP BY g.genre 
            ORDER BY AVG(r.avg) DESC;";
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function get_top_250($conn)
{
    $sql = "SELECT m.mname, r.avg
            FROM movies AS m
                JOIN rating AS r ON m.mid = r.mid 
            WHERE r.total > 10000
            ORDER BY r.avg DESC LIMIT 250;";
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function get_avg_top_rated_acts($conn)
{
    $sql = "SELECT p.fname, p.lname, r.avg 
            FROM people_name AS p 
                JOIN (SELECT a.pid, AVG(r.avg) AS avg 
                        FROM acted AS a  
                            JOIN rating AS r ON r.mid = a.mid 
                        WHERE r.total > 10000 
                        GROUP BY a.pid  
                        HAVING AVG(r.avg) > 8.5
                        ORDER BY AVG(r.avg) DESC) AS r 
                ON p.pid = r.pid;";
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function watch_movie($conn, $username, $movie_name){
    $sql = "SELECT mid FROM movies WHERE mname = '$movie_name'";
    if ($result = mysqli_query($conn,$sql))
    {

        foreach ($result as $row) {
            $sql = "INSERT INTO watched (username, mid) VALUES ('$username', '{$row['mid']}')";
            return $conn->query($sql);
        }

    }
    else
    {
        echo die($conn->error);
    }
}

function insert_rating($conn, $username, $movie_name, $my_rate){
    $sql = "SELECT mid FROM movies WHERE mname = '$movie_name'";
    if ($result = mysqli_query($conn,$sql))
    {
        foreach ($result as $row) {
            $sql = "INSERT INTO user_rating (username, mid, vote) VALUES ('$username', '{$row['mid']}', $my_rate)";
            return $conn->query($sql);
        }

    }
    else
    {
        echo die($conn->error);
    }
}

function get_total_watched_hour($conn, $username){
    $sql = "SELECT SUM(m.duration) as dur
            FROM watched AS w 
                JOIN movies AS m ON w.mid = m.mid 
            WHERE w.username = '$username'";
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function get_watched_languages($conn, $username){
    $sql = "SELECT l.language as lang, count(*) as count, SUM(m.duration) as dur
            FROM watched AS w 
                JOIN movies_language AS l ON w.mid = l.mid 
                JOIN movies AS m ON m.mid = w.mid
            WHERE w.username = '$username'
            GROUP BY l.language
            ORDER BY 2 DESC";
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}

function get_avg_watched_rating($conn, $username){
    $sql = "SELECT AVG(r.avg) as avg
            FROM watched AS w 
                JOIN rating AS r ON w.mid = r.mid 
            WHERE w.username = '$username'";
    if ($result = mysqli_query($conn,$sql))
    {
        return $result;
    }
    else
    {
        echo die($conn->error);
    }
}