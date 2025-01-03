
<?php
include('config.php');
?>
<!--------------------FOOTER--------------------------- -->
<footer class="section-p1"><!--mục footer -->
    <div class="col">
        <h4>HỆ THỐNG CỬA HÀNG</h4><!--Hệ thông cửa hàng -->
        <p>12 đường 42, phường Thảo Điền, Quận 2, TPHCM </p>
        <p>824 Huỳnh Tấn Phát, phường Tân Phú, quận 7, TPHCM </p>
    </div> 
    <div class="col">
        <h4>THÔNG TIN LIÊN HỆ</h4><!--Thông tin liên hệ -->
        <p>Tuyển dụng:<a href ="https://www.facebook.com/thai.dinh.3367174/"> link Website </a> </p>
        <p>Website:<a href ="http://localhost/Web/index.php"> Website </a></p>
        <p>Liên hệ CSKH: support@<a href ="liên kết "> link Website </a></p>
        <p>For business: contact@<a href ="liên kết "> link Website </a></p>
    </div>
    <div class="col">
        <h4>FOLLOW US ON SOCIAL MEDIA</h4><!--Follow us on social media-->
        <li><i class="fa fa-facebook"> <a href = "https://www.facebook.com/thai.dinh.3367174/"></a></i></li>
        <li><i class="fa fa-instagram"></i></li>
        <li><i class="fa fa-youtube"></i></li>            
    </div>    
</footer>
<style>
/*----------------FOOTER--------------------*/

footer{
    background:rgb(0, 0, 0);
    display:flex;
    margin-top:0px;
    justify-content: space-around;
    margin-bottom:0px;
    padding-bottom: 20px;   /*chỉnh size background đen */
    padding-left:150px;
    
}
footer.col{
    
    display:flex;
    flex-direction:column;
    align-items: flex-start;
    margin-top: 100p;
}
footer h4{   /*chỉnh size chữ H4*/
    color:rgb(255, 255, 255);
    font-size: 16px;
    padding-bottom:30px;
    margin-top:40px;
    font-weight: bold;
}
footer p{  /*chỉnh size chữ P*/
    color:rgb(255, 255, 255);
    font-size: 13px;
    text-decoration:none;
    margin-bottom:15px;

 
}
footer li{ /*chỉnh icon fb,instagram,youtube*/
    color:#fff;
    margin-top: 3px;
    font-weight: bold;
    
   
}

</style>

<style>