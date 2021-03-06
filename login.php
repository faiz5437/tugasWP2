<?php
session_start();
include("conn.php");
//atur koneksi ke database
// $get = $_GET['id'];
// $username = md5($get);
// var_dump($username);
// $sqlUser = "SELECT username FROM tbl_operator ORDER BY id_operator DESC LIMIT 1";
// $queryUser = $conn->query($sqlUser);
// $getUser = $queryUser->fetch_array();
// $getUsername = md5($getUser[0]);
//atur variabel
$err        = "";
$username   = "";
$ingataku   = "";

if (isset($_COOKIE['cookie_username'])) {
    $cookie_username = $_COOKIE['cookie_username'];
    $cookie_password = $_COOKIE['cookie_password'];

    $sql1 = "select * from tbl_operator where username = '$cookie_username'";
    $q1   = mysqli_query($conn, $sql1);
    $r1   = mysqli_fetch_array($q1);
    if ($r1['password'] == $cookie_password) {
        $_SESSION['session_username'] = $cookie_username;
        $_SESSION['session_password'] = $cookie_password;
    }
}

if (isset($_SESSION['session_username'])) {
    header("location:index.php");
    exit();
}

if (isset($_POST['login'])) {
    $ingataku = $_POST['ingataku'];
    $username   = $_POST['username'];
    $password   = $_POST['password'];

    if ($username == '' or $password == '') {
        $err .= "<li>Silakan masukkan username dan juga password.</li>";
    } else {
        $sql1 = "select * from tbl_operator where username = '$username'";
        $q1   = mysqli_query($conn, $sql1);
        $r1   = mysqli_fetch_array($q1);

        if ($r1['username'] == '') {
            $err .= "<li>Username <b>$username</b> tidak tersedia.</li>";
        } elseif ($r1['password'] != md5($password)) {
            $err .= "<li>Password yang dimasukkan tidak sesuai.</li>";
        }


        if (empty($err)) {
            $_SESSION['session_username'] = $username; //server
            $_SESSION['session_password'] = md5($password);

            if ($ingataku == 1) {
                $cookie_name = "cookie_username";
                $cookie_value = $username;
                $cookie_time = time() + (60 * 60 * 24 * 30);
                setcookie($cookie_name, $cookie_value, $cookie_time, "/");

                $cookie_name = "cookie_password";
                $cookie_value = md5($password);
                $cookie_time = time() + (60 * 60 * 24 * 30);
                setcookie($cookie_name, $cookie_value, $cookie_time, "/");

                $sqlId = "SELECT id_operator FROM tbl_operator WHERE username = '$username'";
                $sqlIdTemp = $conn->query($sqlId);
                $getId = $sqlIdTemp->fetch_array();
                header("location:index.php?id=$getId[0]");
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
</head>

<body>
    <div class="container my-4">
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Login</div>
                </div>
                <div style="padding-top:30px" class="panel-body">
                    <?php if ($err) { ?>
                        <div id="login-alert" class="alert alert-danger col-sm-12">
                            <ul><?php echo $err ?></ul>
                        </div>
                    <?php } ?>
                    <form id="loginform" class="form-horizontal" action="" method="post" role="form">
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="login-username" type="text" class="form-control" name="username" value="<?php echo $username ?>" placeholder="username">
                        </div>
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
                        </div>
                        <div class="input-group">
                            <div class="checkbox">
                                <label>
                                    <input id="login-remember" type="checkbox" name="ingataku" value="1" <?php if ($ingataku == '1') echo "checked" ?>> Ingat Aku
                                </label>
                            </div>
                        </div>
                        <div style="margin-top:10px; margin-bottom: 30px;" class="form-group">
                            <div class="col-sm-12 controls">
                                <input type="submit" name="login" class="btn btn-success" value="Login" />
                            </div>
                        </div>

                        <h4>Belum Punya Akun?</h4><a href="register.php">Daftar</a>

                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>