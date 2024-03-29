<?php
/**
 * Created by PhpStorm.
 * User: luka
 * Date: 25-Dec-17
 * Time: 16:43
 */
// Connect to the database
include_once 'dbconn.php';

$query = "SELECT * FROM users WHERE role='salesman'";
$result = mysqli_query($conn, $query);
$count = 1;
if (isset($_POST['modal_add_form'])) {
    $firstname = cleanData(mysqli_real_escape_string($conn, $_POST['modal_add_firstname']));
    $lastname = cleanData(mysqli_real_escape_string($conn, $_POST['modal_add_lastname']));
    $email = cleanData(mysqli_real_escape_string($conn, $_POST['modal_add_email']));
    $password = cleanData(mysqli_real_escape_string($conn, $_POST['modal_add_password_1']));
    $status = cleanData(mysqli_real_escape_string($conn, $_POST['modal_add_status']));

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) exit;

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (firstname, lastname, email, password, role, activated)
                      VALUES ('$firstname', '$lastname', '$email', '$hashedPassword', 'salesman', $status)";
    mysqli_query($conn, $query);
} else if (isset($_POST['modal_form'])) {
    $firstname = cleanData(mysqli_real_escape_string($conn, $_POST['modal_firstname']));
    $lastname = cleanData(mysqli_real_escape_string($conn, $_POST['modal_lastname']));
    $email = cleanData(mysqli_real_escape_string($conn, $_POST['modal_email']));
    $password = cleanData(mysqli_real_escape_string($conn, $_POST['modal_password_1']));
    $status = cleanData(mysqli_real_escape_string($conn, $_POST['modal_status']));

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Update user in database
    $id = $row['id'];
    // If we are changing password
    $pwd = "";
    if (strlen($password) > 0) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $pwd = "password='$hashedPassword',";
    }
    $query = "UPDATE users
              SET firstname='$firstname', lastname='$lastname', email='$email', $pwd activated='$status'
              WHERE id = $id";
    mysqli_query($conn, $query);

} else {
    while($row = mysqli_fetch_array($result)){

        if($row['activated'] == 1)
            $status = "Aktiviran";
        else
            $status = "Deaktiviran";

        echo '<tr id="'.$count.'">
            <th scope="row"> '. $count .' </th>
            <td> '. $row['firstname'] .' </td>
            <td> '. $row['lastname'] .' </td>
            <td> '. $row['email'] .' </td>
            <td> '. $status .' </td>
            <td><button type="button" class="btn btn-info btn-sm" onclick="edit('.$count.')" data-toggle="modal" data-target="#myModal">Uredi</button></td>
        </tr>';
        $count++;
    }
}
?>