<?php
require_once('../utils/utility.php');
if(!empty($_POST)) {
    $action = getPost('action');
    $id = getPost('id');
    $num = getPost('num');
    $size = getPost('size'); // Thêm dòng này để lấy size
    $cart = [];
    if(isset($_COOKIE['cart'])) {
        $json = $_COOKIE['cart'];
        $cart = json_decode($json, true);
    }

    switch ($action) {
        case 'add':
            $isFind = false;
            for ($i = 0; $i < count($cart); $i++) {
                // Kiểm tra cả id và size
                if($cart[$i]['id'] == $id && $cart[$i]['size'] == $size) {
                    $cart[$i]['num'] += $num;
                    $isFind = true;
                    break;
                }
            }
            if(!$isFind) {
                $cart[] = [
                    'id' => $id,
                    'num' => $num,
                    'size' => $size // Thêm size vào mảng
                ];
            }
            setcookie('cart', json_encode($cart), time() + 30*24*60*60, '/');
            break;

        case 'update':
            for ($i = 0; $i < count($cart); $i++) {
                // Kiểm tra cả id và size khi cập nhật
                if ($cart[$i]['id'] == $id && $cart[$i]['size'] == $size) {
                    $cart[$i]['num'] = $num; // Cập nhật số lượng mới
                    break;
                }
            }
            setcookie('cart', json_encode($cart), time() + 30 * 24 * 60 * 60, '/');
            break;

        case 'delete':
            for ($i = 0; $i < count($cart); $i++) {
                // Kiểm tra cả id và size khi xóa
                if($cart[$i]['id'] == $id && $cart[$i]['size'] == $size) {
                    array_splice($cart, $i, 1);
                    break;
                }
            }
            setcookie('cart', json_encode($cart), time() + 30*24*60*60, '/');
            break;
    }
}
?>