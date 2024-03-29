<?php
// Connect to the database
include_once 'dbconn.php';
$firstname = $lastname = "";
$email = "";
$street = $street_number = $city = "";
$postal_code = "";
$phone = "";
$password_1 = $password_2 = "";
$errors = array();

if (isset($_POST['register'])) {
    $firstname = cleanData(mysqli_real_escape_string($conn, $_POST['firstname']));
    $lastname = cleanData(mysqli_real_escape_string($conn, $_POST['lastname']));
    $email = cleanData(mysqli_real_escape_string($conn, $_POST['email']));
    $street = cleanData(mysqli_real_escape_string($conn, $_POST['street']));
    $street_number = cleanData(mysqli_real_escape_string($conn, $_POST['street_number']));
    $city = cleanData(mysqli_real_escape_string($conn, $_POST['city']));
    $postal_code = cleanData(mysqli_real_escape_string($conn, $_POST['postal_code']));
    $phone = cleanData(mysqli_real_escape_string($conn, $_POST['phone']));
    $password_1 = cleanData(mysqli_real_escape_string($conn, $_POST['password_1']));
    $password_2 = cleanData(mysqli_real_escape_string($conn, $_POST['password_2']));

    // Ensure that form fields are filled properply

    if (empty($firstname) || empty($lastname) || empty($email) || empty($street) || empty($city)
        || empty($postal_code) || empty($phone) || empty($street_number) || empty($password_1)
        || empty($password_2) || !isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
        // Check empty fields
        array_push($errors, "Izpolni vsa vnosna polja");
    }
    else {
        if (!preg_match("/^[a-žA-Ž]*$/", $firstname) || !preg_match("/^[a-žA-Ž]*$/", $lastname)) {
            // Check if input characters are valid
            $firstname = $lastname = "";
            array_push($errors, "Uporaba prepovedanih znakov");
        }
        if (!is_numeric($postal_code)) {
            $postal_code = "";
            array_push($errors, "Poštna številka mora vsebovati samo numerične znake");
        }
        if (!is_numeric($phone)) {
            $phone = "";
            array_push($errors, "Telefonska številka mora vsebovati samo numerične znake");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Check if email is valid
            $email = "";
            array_push($errors, "Email ni pravilen");
        }
        if (strlen($password_1) < 6) {
            // Password must be at least 6 chars long
            $password_1 = $password_2 = "";
            array_push($errors, "Geslo mora vsebovati vsaj 6 znakov");
        }
        if ($password_1 != $password_2) {
            // Passwords do not match
            $password_1 = $password_2 = "";
            array_push($errors, "Gesli se ne ujemata");
        }
        //your site secret key
        $secret = '6Le9kEAUAAAAAKfq_l_wPuja8SfpJFvQuzSIT2My';
        //get verify response data
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);

        if (!$responseData->success) {
            array_push($errors, "Robotska verifikacija ni bila uspešna");
        }
    }
    // No errors
    if (count($errors) == 0) {
        // Check if user already exists in database
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        $rows = mysqli_num_rows($result);

        if ($rows > 0) {
            array_push($errors, "Uporabnik s tem email naslovom že obstaja");
        } else {
            // Insert new user into database
            // Encrypt password with md5
            $hashedPassword = password_hash($password_1, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (firstname, lastname, email, password, role, activated)
                      VALUES ('$firstname', '$lastname', '$email', '$hashedPassword', 'customer', '0')";
            mysqli_query($conn, $query);

            // Get his ID to insert his data into contact_data table
            $query = "SELECT id FROM users WHERE email='$email'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $id = $row['id'];

            $query = "INSERT INTO contact_data (user_id, street, street_number, city, postal_code, phone)
                      VALUES ('$id', '$street', '$street_number', '$city', '$postal_code', '$phone')";
            mysqli_query($conn, $query);

            $firstname = $lastname = $email = $street = $street_number = $city = $postal_code = $phone = $password_1 = $password_2 = "";
            $registerSuccess =
                '<div class="alert alert-success mar-top-2rem">
                    Registracija uspešna!<br>Preusmeri me na <a href="login.php" class="alert-link">prijavno stran</a>.
                 </div>';
        }
    }
}
?>