<?php
header("content-type:text/html; charset=UTF-8");
require_once('../database/dbhelper.php');

// Initialize variables
$id = $title = $price = $number = $content = $id_category = $id_sanpham = '';
$thumbnails = ['thumbnail', 'thumbnail_1', 'thumbnail_2', 'thumbnail_3', 'thumbnail_4', 'thumbnail_5'];
$existing_files = array_fill_keys($thumbnails, '');

// Get existing product data
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = 'SELECT * FROM product WHERE id = ?';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($product = mysqli_fetch_assoc($result)) {
        $title = $product['title'];
        $price = $product['price'];
        $number = $product['number'];
        $content = $product['content'];
        $id_category = $product['id_category'];
        $id_sanpham = $product['id_sanpham'];
        
        foreach ($thumbnails as $thumb) {
            $existing_files[$thumb] = $product[$thumb];
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        if (empty($_POST['title']) || empty($_POST['price']) || empty($_POST['number'])) {
            throw new Exception("Vui lòng điền đầy đủ thông tin bắt buộc");
        }

        // Clean and validate input
        $title = strip_tags(trim($_POST['title']));
        $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
        $number = filter_var($_POST['number'], FILTER_VALIDATE_INT);
        $content = strip_tags($_POST['content']);
        $id_category = filter_var($_POST['id_category'], FILTER_VALIDATE_INT);
        $id_sanpham = filter_var($_POST['id_sanpham'], FILTER_VALIDATE_INT);

        if ($price === false || $number === false) {
            throw new Exception("Giá hoặc số lượng không hợp lệ");
        }

        // File upload handling
        $upload_dir = __DIR__ . "/../product/uploads/";  // Quay lại 1 thư mục để vào
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                throw new Exception("Không thể tạo thư mục uploads");
            }
        }

        // Ensure directory is writable
        if (!is_writable($upload_dir)) {
            chmod($upload_dir, 0777);
        }

        $file_paths = [];
        foreach ($thumbnails as $thumb) {
            if (isset($_FILES[$thumb]) && $_FILES[$thumb]['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES[$thumb];
                
                // Validate file type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
                
                if (!in_array($mime_type, ['image/jpeg', 'image/png', 'image/gif'])) {
                    throw new Exception("File $thumb phải là ảnh (JPG, PNG, hoặc GIF)");
                }
                
                // Validate file size (800KB)
                if ($file['size'] > 8000000000000) {
                    throw new Exception("File $thumb không được vượt quá 800MB");
                }
                
                // Generate safe filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                $target_path = $upload_dir . $new_filename;
                
                // Move uploaded file
                if (!move_uploaded_file($file['tmp_name'], $target_path)) {
                    throw new Exception("Không thể upload file $thumb");
                }
                
                $file_paths[$thumb] = 'uploads/' . $new_filename;
            } else {
                // Keep existing file if no new upload
                $file_paths[$thumb] = $existing_files[$thumb];
            }
        }

        // Database operation
        $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
        if (!$conn) {
            throw new Exception("Lỗi kết nối database: " . mysqli_connect_error());
        }

        if (empty($id)) {
            $sql = "INSERT INTO product (title, price, number, thumbnail, thumbnail_1, 
                    thumbnail_2, thumbnail_3, thumbnail_4, thumbnail_5, content, 
                    id_category, id_sanpham, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssssssss", 
                $title, $price, $number, 
                $file_paths['thumbnail'], $file_paths['thumbnail_1'],
                $file_paths['thumbnail_2'], $file_paths['thumbnail_3'],
                $file_paths['thumbnail_4'], $file_paths['thumbnail_5'],
                $content, $id_category, $id_sanpham);
        } else {
            $sql = "UPDATE product SET title=?, price=?, number=?, thumbnail=?, 
                    thumbnail_1=?, thumbnail_2=?, thumbnail_3=?, thumbnail_4=?, 
                    thumbnail_5=?, content=?, id_category=?, id_sanpham=?, 
                    updated_at=NOW() WHERE id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssssssssi",
                $title, $price, $number,
                $file_paths['thumbnail'], $file_paths['thumbnail_1'],
                $file_paths['thumbnail_2'], $file_paths['thumbnail_3'],
                $file_paths['thumbnail_4'], $file_paths['thumbnail_5'],
                $content, $id_category, $id_sanpham, $id);
        }
        if (empty($id)) {
            $sql = "INSERT INTO product (title, price, number, thumbnail, thumbnail_1, thumbnail_2, thumbnail_3, thumbnail_4, thumbnail_5, content, id_category, id_sanpham, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssssssss", $title, $price, $number, $file_paths['thumbnail'], $file_paths['thumbnail_1'], $file_paths['thumbnail_2'], $file_paths['thumbnail_3'], $file_paths['thumbnail_4'], $file_paths['thumbnail_5'], $content, $id_category, $id_sanpham);
        } else {
            $sql = "UPDATE product SET title=?, price=?, number=?, thumbnail=?, thumbnail_1=?, thumbnail_2=?, thumbnail_3=?, thumbnail_4=?, thumbnail_5=?, content=?, id_category=?, id_sanpham=?, updated_at=NOW() WHERE id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssssssssi", $title, $price, $number, $file_paths['thumbnail'], $file_paths['thumbnail_1'], $file_paths['thumbnail_2'], $file_paths['thumbnail_3'], $file_paths['thumbnail_4'], $file_paths['thumbnail_5'], $content, $id_category, $id_sanpham, $id);
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Lỗi database: " . mysqli_stmt_error($stmt));
        }

        // Success redirect
        header('Location: index.php');
        exit();

    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
    }

    // Handle delete
    if (isset($_POST['delete'])) {
        $id = (int)$_POST['delete'];
        $sql = "DELETE FROM product WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        header('Location: index.php');
        exit();
    }

}
?>

