<?php
      session_start(); //inisialisasi session dalam php

      //periksa apakah user sudah login. jika sudah, maka langsung redirect / pindah otomatis ke halaman index.php
      if(isset($SESSION["loggedin"])&& $_SESSION["loggedin"] === true) {
          header("location: index.php");
          exit;
      }

      //include koneksi data
      require_once "koneksi.php";

      //definisi variable dan beri nilai kosong dulu
      $username = $password = "";
      $username_err = $password_err = $login_err = "";

      //pemrosesan data ketika form login di-submit
      if($_SERVER["REQUEST_METHOD"] == "POST") {
        //validasi isian form 
        //1. periksa apakah username kosong 
        if (empty(trim($_POST["username"]))) {
            $username_err = "please enter a username" ;
    } else {
        $username = trim($_POST["username"]);
    }

    //2. periksa apakah password kosong
    if(empty(trim($_POST["password"]))){
        $password_err = "please enter a password";
    }else{ 
        $password_err = trim($_POST["password"]);
    }


    //validasi login
    if(empty($username_err) && empty($password_err)){
        //query select untuk menyeleksi satu data 
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

    if($stmt = $kon->prepare($sql)){
        $stmt->bind_param("s", $param_username);
        $param_username = $username;

        if($stmt->execute()){
            $stmt->store_result();

            if($stmt->num_rows == 1){
                $stmt->bind_result($sid, $username, $hashed_password);
                if($stmt->fetch()){
                    if(password_verify($password, $hashed_password)){
                        session_start();

                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;

                        header("location:index.php");
                   }else{
                        $login_err = "Invalid username or password";
                    }
                }
            }else{
                $login_err = "Username doesn't exist";
            }
        }else{
            echo "Oops! Something went wrong. Please try again later";
        }
        $stmt->close();
    }
    }
    $stmt->close();
      }
?>

<!DOCTYPE html>3
<html>

<head>
    <meta charset="UTF-8">
    <title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<style>
    body{
        font: 14px sans-serif;
    }
    .wreaper {
        width: 300px;
        padding: 20px; 
    }
</style>

</head>

<body>
    <div class="wreaper">
        <h2>Login</h2>
        <p>Please enter your username and password</p>

        <?php
            if(!empty($login_err)) {
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"method="post">
        <div class="form-group mt-3">
            <label for="">
                Username
            </label>
            <input type="text" name="username" class="form-control <?php echo(!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group mt-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo(!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="ivalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group mt-3 mb-3">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
            <p>Don't have an account? <a href="register.php">Sing Up Now!</a</p>
        </form>
    </div>
</body>

</html>