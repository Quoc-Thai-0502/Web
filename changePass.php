<?php
session_start();
require_once('config.php');
require_once('database/dbhelper.php');

// Check login status
if (!isset($_COOKIE['tendangnhap']) || !isset($_COOKIE['matkhau'])) {
    echo '<script language="javascript">
        alert("Vui lòng đăng nhập để thực hiện chức năng này!");
        window.location = "login.php";
    </script>';
    exit();
}

$error = '';
$success = '';

if (isset($_POST['submit'])) {
    $password = $_POST['password'];
    $newpassword = $_POST['newpassword'];
    $renewpassword = $_POST['renewpassword'];
    $tendangnhap = $_COOKIE['tendangnhap'];
    
    // Validate input
    if (empty($password) || empty($newpassword) || empty($renewpassword)) {
        $error = "Vui lòng điền đầy đủ thông tin";
    }
    else if ($newpassword !== $renewpassword) {
        $error = "Mật khẩu mới không khớp!";
    }
    else if (strlen($newpassword) < 6) {
        $error = "Mật khẩu mới phải có ít nhất 6 ký tự!";
    }
    else {
        // Get current user data
        $stmt = $mysqli->prepare("SELECT id_user, matkhau FROM user WHERE tendangnhap = ?");
        $stmt->bind_param("s", $tendangnhap);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $error = "Không tìm thấy tài khoản!";
        } else {
            $user = $result->fetch_assoc();
            
            // Verify current password
            if ($password !== $user['matkhau']) {  // Assuming passwords are stored in plain text. Should be hashed in production!
                $error = "Mật khẩu hiện tại không chính xác!";
            } else {
                // Update password
                $update_stmt = $mysqli->prepare("UPDATE user SET matkhau = ? WHERE id_user = ?");
                $update_stmt->bind_param("si", $newpassword, $user['id_user']);
                
                if ($update_stmt->execute()) {
                    // Update cookie
                    setcookie("matkhau", $newpassword, time() + 30*24*60*60, '/');
                    
                    echo '<script language="javascript">
                        alert("Đổi mật khẩu thành công!");
                        window.location = "index.php";
                    </script>';
                    exit();
                } else {
                    $error = "Có lỗi xảy ra khi cập nhật mật khẩu: " . $mysqli->error;
                }
                $update_stmt->close();
            }
        }
        $stmt->close();
    }
}
?>

<?php require_once('database/config.php');
require_once('database/dbhelper.php');?>
<?php 
 include("Layout/header.php");
?>
<!-- Page Title Section -->
<section class="contact-img-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="con-text">
                    <h2 class="page-title">Đổi Mật Khẩu</h2>
                    <p><a href="#">Trang chủ</a> | Đổi mật khẩu</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Change Password Form Section -->
<div class="login-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="tb-login-form">
                    <h5 class="tb-title">Đổi Mật Khẩu</h5>
                    <p>Đổi mật khẩu tài khoản để bảo mật thông tin của bạn</p>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form action="" method="POST">
                        <div class="checkout-coupon top log a-an">
                            <label class="l-contact">
                                Mật khẩu hiện tại
                                <em>*</em>
                            </label>
                            <input type="password" name="password" required>
                        </div>
                        
                        <div class="checkout-coupon top-down log a-an">
                            <label class="l-contact">
                                Mật khẩu mới
                                <em>*</em>
                            </label>
                            <input type="password" name="newpassword" required minlength="6">
                        </div>
                        
                        <div class="checkout-coupon top-down log a-an">
                            <label class="l-contact">
                                Nhập lại mật khẩu mới
                                <em>*</em>
                            </label>
                            <input type="password" name="renewpassword" required minlength="6">
                        </div>
                        
                        <div class="login-submit5">
                            <input type="submit" name="submit" class="button-primary" value="Đổi mật khẩu">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>/* Body container styles */
.login-area {
    min-height: 100vh;
    background-color: #f5f5f5;
    padding: 50px 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.tb-login-form {
    background: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

/* Form input container */
.checkout-coupon {
    margin-bottom: 25px;
}

.top.log.a-an {
    position: relative;
}

/* Input fields */
.checkout-coupon input {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    font-size: 15px;
    transition: all 0.3s ease;
}

.checkout-coupon input:focus {
    border-color: #4a90e2;
    box-shadow: 0 0 5px rgba(74, 144, 226, 0.3);
}

/* Labels */
.l-contact {
    color: #333333;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
}

.l-contact em {
    color: #ff4444;
    margin-left: 4px;
}

/* Submit button container */
.login-submit5 {
    margin-top: 30px;
}

/* Submit button */
.login-submit5 .button-primary {
    width: 100%;
    background: #333333;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s ease;
}

.login-submit5 .button-primary:hover {
    background: #3f3f3f;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .login-area {
        padding: 30px 0;
    }
    
    .tb-login-form {
        padding: 20px;
    }
    
    .checkout-coupon input {
        padding: 10px 12px;
        font-size: 14px;
    }
}
</style>
<?php require_once('Layout/footer.php'); ?>