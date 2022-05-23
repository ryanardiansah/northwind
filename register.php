
<?php
require_once "koneksi.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } elseif (!password_match('/^[a-zA-Z0-9_]+$/',trim($_POST["username"]))){
     $username_err = "Username can only contain letters, numbers and underscores.";
   } else {
       $sql = "SELECT id FROM users WHERE username = ?";

       if($stmt = $kon->prepare($sql)){
           $stmt->bind_pram("s", $param_username);

           $param_username = trim($_POST["username"]);

           if($stmt->execute()){

            $stmt->store_result();

            if($stmt->num_rows() == 1){
                $username_err = "This username is already taken.";
            } else {
                  $username = trim($_POST["username"]);
            }
           } else{
               echo "Oops! something went wrong. please try again later";
           }

           $stmt->close();
       }
   }

   if (empty(trim($_POST['password']))) {
      $password_err = "please enter a password."; 
   } elseif (strlen(trim($_POST["password"])) < 6) {
       $password_err = "password must have at least 6 charaters.";
   } else {
       $password = trim($_POST["password"]);
   }

   if(empty(trim($_POST['confrim_password']))){
       $confirm_password_err = "please confirm your password."; 
   } else {
       $confirm_password = trim($POST['confirm_password']);
       if (empty($password_err) && ($password != $confirm_password)){
           $confirm_password_err = "password did not match.";
       }
   }

   if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
       $sql = "INSERT INTO users (username, password) VALUES (?,?)";

       if($stmt = $kon->prepare($sql)) {
           $stmt->bind_param("ss", $param_username, $param_password);

           $param_username = $username;
           $param_password = password_hash($password, PASSWORD_DEFAULT);

           if ($stmt->execute()){
               header("location: login.php");
           } else {
               echo "Oops! something went wrong. please try again later.";
           }

           $stmt->close();
       }
   }

   $kon->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sing Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        body{
            font: 14px sans-serif;
        }
        .wrapper{
            width: 360px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sing Up</h2>
        <p>Please fill this to create an account.</p>
        <form action="<?php echo htmlspecialchars($SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group mt-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : '';?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group mt-3">
                <labe>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group mt-3">
                <labe>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group mt-4">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>
