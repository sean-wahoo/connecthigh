<?php
include 'dbh.inc.php';
function fetch_user_last_activity($user_id, $conn)
{

    $update_time_sql = "SELECT * FROM user_details
 WHERE user_id = ?
 ORDER BY last_activity DESC
 LIMIT 1
 ";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $update_time_sql))
    {
        var_dump($stmt);
        var_dump($update_time_sql);
    }
    else
    {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        foreach ($result as $row)
        {

            return $row['last_activity'];

        }

    }

}
?>
