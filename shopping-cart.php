<?php
require_once('database/config.php');
require_once('database/dbhelper.php');
require_once('utils/utility.php');

$cart = [];
if (isset($_COOKIE['cart'])) {
    $json = $_COOKIE['cart'];
    $cart = json_decode($json, true);
}
$idList = [];
foreach ($cart as $item) {
    $idList[] = $item['id'];
}
if (count($idList) > 0) {
    $idList = implode(',', $idList);
    $sql = "select * from product where id in ($idList)";
    $cartList = executeResult($sql);
} else {
    $cartList = [];
}
?>

<?php include("Layout/header.php"); ?>

<section class="contact-img-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="con-text">
                    <h2 class="page-title">Shop</h2>
                    <p><a href="#">Home</a> | shop</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="shopping-cart-area s-cart-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="s-cart-all">
                    <div class="cart-form table-responsive">
                        <?php
                        if(!isset($_COOKIE['tendangnhap'])){
                            echo '<p style="font-weight: bold; text-align: center; font-size: 16px;">Vui lòng đăng nhập trước khi thêm vào giỏ hàng.</p>
                            <hr class="opacity-20">
                            <div class="row">
                                <div class="second-all-class">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="sub-total">
                                            <table>
                                                <tbody>
                                                    <tr class="order-total">
                                                        <th>Tổng Đơn Hàng:</th>
                                                        <td>
                                                            <strong>
                                                                <span class="amount">0</span>
                                                                <span> VNĐ</span>
                                                            </strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="wc-proceed-to-checkout" style="text-align: center;">
                                            <p class="return-to-shop">
                                                <a class="button wc-backward" href="login.php">Đăng nhập để tiếp tục</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        } elseif (empty($cartList)) {
                            echo '<p style="font-weight: bold; text-align: center; font-size: 16px;">Không có sản phẩm nào ở giỏ hàng.</p>
                            <hr class="opacity-20">
                            <div class="col-sm-12 col-xs-12 mx-auto">
                                <div class="sub-total">
                                    <table>
                                        <tbody>
                                            <tr class="order-total">
                                                <th>Total:</th>
                                                <td>
                                                    <strong>
                                                        <span class="amount">0 VNĐ</span>
                                                    </strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="wc-proceed-to-checkout">
                                    <p class="return-to-shop">
                                        <a class="button wc-backward" href="index.php">Continue Shopping</a>
                                    </p>
                                </div>
                            </div>';
                        } else {
                        ?>
                        <table id="shopping-cart-table" class="data-table cart-table">
                            <tr>
                                <th class="low8">STT</th>
                                <th class="low1">Ảnh Sản Phẩm</th>
                                <th class="low1">Tên Sản Phẩm</th>
                                <th class="low7">Số Lượng</th>
                                <th class="low7">Size</th>
                                <th class="low7">Giá</th>
                                <th class="low7">Tổng Tiền</th>
                                <th></th>
                            </tr>
                            <?php
                            $count = 0;
                            $grandTotal = 0;
                            foreach ($cartList as $item) {
                                $num = 0;
                                $size = '';
                                foreach ($cart as $value) {
                                    if ($value['id'] == $item['id']) {
                                        $num = $value['num'];
                                        $size = $value['size'];
                                        break;
                                    }
                                }
                                $itemTotal = $num * $item['price'];
                                $grandTotal += $itemTotal;
                                $itemId = 'item_' . $item['id'];
                                echo '
                                <tr style="text-align: center;">
                                    <td width="50px">' . (++$count) . '</td>
                                    <td class="sop-cart an-shop-cart">
                                        <a><img src="admin/product/' . $item['thumbnail'] . '" alt=""></a>
                                    </td>
                                    <td class="sop-cart an-shop-cart">
                                        <a>' . $item['title'] . '</a>
                                    </td>
                                    <td class="sop-cart an-sh">
                                        <div class="quantity ray">
                                            <input class="input-text qty text" id="' . $itemId . '_num" type="number" size="4" title="Qty" value="' . $num . '" min="1" onchange="updatePrice(\'' . $itemId . '\', ' . $item['price'] . ')" >
                                        </div>
                                    </td>
                                    <td class="sop-cart an-sh">
                                        <select class="form-select" id="' . $itemId . '_size" onchange="updateCart(\'' . $itemId . '\')">
                                            <option value="S" '.($size=='S'?'selected':'').'>S</option>
                                            <option value="M" '.($size=='M'?'selected':'').'>M</option>
                                            <option value="L" '.($size=='L'?'selected':'').'>L</option>
                                            <option value="XL" '.($size=='XL'?'selected':'').'>XL</option>
                                        </select>
                                    </td>
                                    <td class="b-500 red">
                                        <span class="gia none">' . $item['price'] . '</span>
                                        <span> VNĐ</span>
                                    </td>
                                    <td class="b-500 red">
                                        <span id="' . $itemId . '_price">' . number_format($itemTotal, 0, ',', '.') . '</span>
                                        <span> VNĐ</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" onclick="deleteCart(' . $item['id'] . ', \'' . $size . '\')">Xoá</button>
                                    </td>
                                </tr>';
                            }
                            ?>
                        </table>
                        
                        <div class="last-check1">
                            <div class="yith-wcwl-share yit">
                                <p class="checkout-coupon an-cop">
                                    <input type="submit" value="Update Cart" onclick="updateAllCart()">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="second-all-class">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="sub-total">
                        <table>
                            <tbody>
                                <tr class="order-total">
                                    <th>Tổng Đơn Hàng:</th>
                                    <td>
                                        <strong>
                                            <span class="amount"><?= number_format($grandTotal, 0, ',', '.') ?></span>
                                            <span> VNĐ</span>
                                        </strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="wc-proceed-to-checkout">
                        <p class="return-to-shop">
                            <a class="button wc-backward" href="index.php">Continue Shopping</a>
                        </p>
                        <a class="wc-forward" href="checkout_product.php">Confirm Order</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<script type="text/javascript">
function updatePrice(itemId, itemPrice) {
    var priceId = itemId + '_price';
    var num = document.getElementById(itemId + '_num').value;
    if (isNaN(num) || num <= 0) {
        num = 1;
        document.getElementById(itemId + '_num').value = 1;
    }
    var tong = itemPrice * num;
    document.getElementById(priceId).innerHTML = tong.toLocaleString();
    updateGrandTotal();
}

function updateCart(itemId) {
    var num = document.getElementById(itemId + '_num').value;
    var size = document.getElementById(itemId + '_size').value;
    
    $.post('api/cookie.php', {
        'action': 'update',
        'id': itemId.split('_')[1],
        'num': num,
        'size': size
    }, function(data) {
        location.reload();
    });
}

function updateAllCart() {
    var items = document.querySelectorAll('[id$="_num"]');
    items.forEach(function(item) {
        var itemId = item.id.replace('_num', '');
        var num = document.getElementById(itemId + '_num').value;
        var size = document.getElementById(itemId + '_size').value;
        
        $.post('api/cookie.php', {
            'action': 'update',
            'id': itemId.split('_')[1],
            'num': num,
            'size': size
        }, function(data) {
            location.reload();
        });
    });
}

function updateGrandTotal() {
    var grandTotal = 0;
    var itemPrices = document.querySelectorAll('.gia.none');
    var itemQuantities = document.querySelectorAll('.input-text.qty.text');
    for (var i = 0; i < itemPrices.length; i++) {
        var price = itemPrices[i].innerText.match(/\d/g).join("");
        var quantity = itemQuantities[i].value;
        grandTotal += price * quantity;
    }
    document.querySelector('.order-total .amount').innerText = grandTotal.toLocaleString();
}

function deleteCart(id, size) {
    $.post('api/cookie.php', {
        'action': 'delete',
        'id': id,
        'size': size
    }, function(data) {
        location.reload();
    });
}

// Function to add product to cart from product detail page
function addToCart(id, size, num = 1) {
    $.post('api/cookie.php', {
        'action': 'add',
        'id': id,
        'size': size,
        'num': num
    }, function(data) {
        location.href = 'cart.php';
    });
}
</script>

<hr class="opacity-20">
<?php require_once('Layout/footer.php'); ?>