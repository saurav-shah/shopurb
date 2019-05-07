<?php
session_start();

if(!isset($_SESSION['trader_id'])) {
    header('location: ../login.php');
    die();
}
$trader_id = $_SESSION['trader_id'];

$get_info = 'select * from users where user_id = '.$trader_id;
$select = oci_parse($con, $get_info);
oci_execute($select);
$row = oci_fetch_assoc($select);    
$image = $row['PROFILE_PICTURE'];
if($image == null) {
    $image = 'default.jpg';
}
$fname = $row['FIRSTNAME'];
$lname = $row['LASTNAME'];
$email = $row['EMAIL'];
$username = $row['USERNAME'];
$dob = strtotime($row['DOB']);
$dob = date('Y/m/d',$dob);
$addr = $row['ADDRESS'];
$old_pic = $row['PROFILE_PICTURE'];
$pass = $row['PASSWORD'];
$e = $row['EMAIL'];
$p_line = strtoupper($row['PRODUCT_LINE']);
$contact = $row['PHONE'];
$role = $row['ROLE'];




function get_shop_count() {
    global $con;   
    global $trader_id;
    $sql = 'select count(*) from shop where fk_trader_id = '.$trader_id;
    $prep = oci_parse($con, $sql);
    oci_execute($prep);
    oci_fetch($prep);
    $count = oci_result($prep,'COUNT(*)');
    return number_format($count);
    
}

function get_prod_count() {
    global $con;
    global $trader_id;
    $count = 0;
    $sql = 'select * from shop where fk_trader_id = '.$trader_id;
    $prep = oci_parse($con, $sql);
    oci_execute($prep);
    
    while($row = oci_fetch_assoc($prep)) {
        $shop_id = $row['SHOP_ID'];
        $qry = 'select count(*) from product where fk_shop_id = '.$shop_id;
        $get = oci_parse($con, $qry);
        oci_execute($get);
        oci_fetch($get);
        $count += oci_result($get, 'COUNT(*)');
    }
    return number_format($count);
}

function get_paid_order_count() {
    global $con;
    global $trader_id;
    $sql = "select count(distinct i.invoice_no) as paid_orders 
            from invoice i, shop s, orders o 
            where i.shop_id = s.shop_id 
            and i.invoice_no = o.invoice_no 
            and o.payment_status = 'Paid' 
            and s.fk_trader_id = $trader_id";
    $get = oci_parse($con, $sql);
    oci_execute($get);
    oci_fetch($get);
    $count = oci_result($get, 'PAID_ORDERS');
    return number_format($count);
}

function get_revenues() {
    global $con;
    global $trader_id;
    $sql = "select sum(i.quantity * p.prod_price) as revenues 
            from product p, invoice i, shop s, orders o 
            where i.prod_id = p.prod_id 
            and s.shop_id = i.shop_id 
            and o.invoice_no = i.invoice_no 
            and o.payment_status = 'Paid' 
            and s.fk_trader_id = $trader_id";
    $get = oci_parse($con, $sql);
    oci_execute($get);
    oci_fetch($get);
    $count = oci_result($get, 'REVENUES');
    return number_format($count);
}

function get_customer_count() {
    global $con;
    global $trader_id;
    $sql = "select count(distinct customer_id) as customer_count
            from orders o, shop s, invoice i
            where s.shop_id = i.shop_id
            and i.invoice_no = o.invoice_no
            and s.fk_trader_id = $trader_id";
    $get = oci_parse($con, $sql);
    oci_execute($get);
    oci_fetch($get);
    $count = oci_result($get, 'CUSTOMER_COUNT');
    return number_format($count);
}

function get_table_data() {
    global $con;
    global $trader_id;
    $num = 1;
    $sql = 'select * from shop where fk_trader_id = '.$trader_id;
    $get = oci_parse($con, $sql);
    oci_execute($get);
    $html = '';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>#</th>';
    $html .= '<th>Shop Name</th>';
    $html .= '<th colspan="2">Actions</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    oci_fetch_all($get, $out);
    if(oci_num_rows($get) != 0){
        oci_execute($get);
        while($row = oci_fetch_assoc($get)) {        
        $html .= '<tr>';                                           
        $html .= '<td>'.$num.'</td>';
        $html .= '<td>'.$row['SHOP_NAME'].'</td>';
        $html .= '<td>';
        $html .= '<a data-id="'.$row['SHOP_ID'].'" id="update" href="#" class="icon"><i class="glyphicon glyphicon-edit"></i></a>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<a data-id="'.$row['SHOP_ID'].'" id="delete" href="#" class="icon text-danger"><i class="glyphicon glyphicon-trash"></i></a>';
        $html .= '</td>';
        $html .= '</tr>';        
        $num++;
        
    }
    echo json_encode(['status'=>'success', 'html'=>$html]);
    }
    else{
        $html .= '<tr colspan=""> ';                                           
        $html .= '<td>No Shop Found</td>';
        $html .= '</tr>';
        echo json_encode(['status'=>'success', 'html'=>$html]);
    }
    
}



function add_shop($post){
    global $con;
    global $trader_id;
    $shop_name = $post['sname'];
    $unique = true;
    
    
    $sql ='select shop_name from shop';
    $get = oci_parse($con, $sql);
    oci_execute($get);
    
    while($row = oci_fetch_assoc($get)){
        
        $s = $row['SHOP_NAME'];
        if($shop_name == $s ){
            $unique = false;			
            break;
        }
        
    }
   
    if($unique){

        if(get_shop_count() < 1){
        // if(true){

            $sql = "insert into shop (shop_id, shop_name, fk_trader_id)values(shop_id.nextval,'$shop_name',$trader_id)";

            $insert= oci_parse($con, $sql);
            oci_execute($insert);
            if($insert){
                echo json_encode(['status'=>'success','message'=>'Shop Added!']);
            }
            else{
               echo json_encode(['status'=>'fail','message'=>'Could not insert into database!']);
            }

        }
        else {
            echo json_encode(['status'=>'fail','message'=>'Currently you can\'t create more than 1 shop.']);
        } 
    } 
    else{
        echo json_encode(['status'=>'fail','message'=>'Shop name is taken!']);
    }
    
    
}


function add_prod($post){
    global $con;
    global $trader_id;
    $pname = $post['prod_name'];
    $cat_id = $post['prod_cat'];
    $stock = $post['stock'];
    $price = $post['price'];
    $allergy = $post['allergy'];
    $desc = $post['prod_desc'];
    $keywords = $post['keywords'];
    $max_order = $stock - 1;
    $shop_id = $post['shop_id'];
    
    //print_r($post);
    //print_r($_FILES);
 
    $image = $_FILES['image']['name'];    
    $image_temp = $_FILES['image']['tmp_name'];
    
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    
    if($check == 0) {
        
        // invalid image
        echo json_encode(['status'=>'fail','message'=>'Please upload a valid image!']);
        

    }  
    else if(strpbrk($pname, '1234567890')){
         // invalid product name
        echo json_encode(['status'=>'fail','message'=>'No numbers allowed in product title']);
    }
    else {
        
        // uploading image
        move_uploaded_file($image_temp,"product_images/$image");
        
        
        $sql = "insert into product  (prod_id, prod_title, fk_cat_id, stock, prod_price,allergy_info, prod_desc,keywords,prod_img, max_order ,fk_shop_id) values  (prod_id.nextval,'$pname',$cat_id,$stock,$price,'$allergy','$desc','$keywords','$image',$max_order, $shop_id)";
        
        
        $insert = oci_parse($con, $sql);
        oci_execute($insert);
        
        if($insert){
            
            echo json_encode(['status'=>'success','message'=>'Product Added!']);
            
        } else {
            
           echo json_encode(['status'=>'fail','message'=>'Product couldn\'t be added!']);
            
        }
        
        
    }
}


function get_shop($post){
    global $con;
    $id = $post['id'];
    $sql ="select * from shop where shop_id = $id";
    $prep = oci_parse($con, $sql);
    oci_execute($prep);
    if($prep){
        $row = oci_fetch_assoc($prep);
        $sname = $row['SHOP_NAME'];
        $id = $row['SHOP_ID'];
        echo json_encode(['status'=>'success', 'sname'=>$sname ,'id'=>$id ]);
    }
    else{
         echo json_encode(['status'=>'fail','message'=>'Failed to get data']);
    }
    
    
}
function get_prod($post){
    global $con;
    $id = $post['id'];
    $sql ="select * from product where prod_id = $id";
    $prep = oci_parse($con, $sql);
    oci_execute($prep);
    if($prep){
        $row = oci_fetch_assoc($prep);
        $pname = $row['PROD_TITLE'];
        $id = $row['PROD_ID']; 
        $cat_id = $row['FK_CAT_ID'];
        $shop_id = $row['FK_SHOP_ID'];
        $stock = $row['STOCK']; 
        $price = $row['PROD_PRICE'];
        $a_info = $row['ALLERGY_INFO'];
        $desc = $row['PROD_DESC'];
        $tags = $row['KEYWORDS'];
        $img = $row['PROD_IMG'];
        $min_order = $row['MIN_ORDER'];
        $max_order = $row['MAX_ORDER'];
        $dis = $row['DISCOUNT'];
        
        echo json_encode(['status'=>'success', 'pname'=>$pname ,'id'=>$id ,'cat_id'=>$cat_id ,'shop_id'=>$shop_id ,'stock'=>$stock ,'price'=>$price ,'a_info'=>$a_info ,'desc'=>$desc,'tags'=>$tags,'img'=>$img,'min_order'=>$min_order ,'max_order'=>$max_order,'dis'=>$dis  ]);
    }
    else{
         echo json_encode(['status'=>'fail','message'=>'Failed to get data']);
    }
    
    
}


function update_shop($post){
    
    global $con;
    $id = $post['id'];
    $sname = $post['sname'];
    $sql= "update shop set shop_name = '$sname' where shop_id = $id";
    
    $update= oci_parse($con, $sql);
    oci_execute($update);
    if($update){
        echo json_encode(['status'=>'success','message'=>'Shop Updated!']);
    }
    else{
        echo json_encode(['status'=>'fail','message'=>'Could not Update!']);
    }
}
function update_prod($post){
    
    global $con;
    $id = $post['id'];
    $pname = $post['prod_name'];
    $cat_id = $post['prod_cat'];
    $stock = $post['stock'];
    $price = $post['price'];
    $allergy = $post['allergy'];
    $desc = $post['prod_desc'];
    $keywords = $post['keywords'];
    $max_order = $post['max_order'];
    $min_order = $post['min_order'];
    $discount = $post['discount'];
    $shop_id = $post['shop_id'];
    
    $sql = 'select prod_img from product where prod_id = '.$id;
    $get=oci_parse($con, $sql);
    oci_execute($get);
    $row = oci_fetch_assoc($get);
    $old_pic = $row['PROD_IMG'];
    
    $updated = false;
    
    
    if(strpbrk($pname, '1234567890')) {
        echo json_encode(['status'=>'fail','message'=>'No numbers allowed in Product Title']);
    } else{
        
        $sql="update product set prod_title = '$pname' where prod_id = $id ";
        $updated = true;
    }
    
    
    if($updated){
    // if image was uploaded
    if(is_uploaded_file($_FILES['image']['tmp_name'])) {
            
        // check for error
            $image = $_FILES['image']['name'];    
            $image_temp = $_FILES['image']['tmp_name'];    
            $check_image = getimagesize($_FILES["image"]["tmp_name"]);
            
            if($check_image == 0) {
                // error found then throw error
               
                    echo json_encode(['status'=>'fail','message'=>'Invalid Image']); 
                    $updated = false;
                
            }
            
             else{
                 
                unlink('product_images/'.$old_pic);
                move_uploaded_file($image_temp,'product_images/'.$image);

                //update image
                $sql = "update product set prod_img = '$image' where prod_id = $id";
                oci_execute(oci_parse($con, $sql));
                $updated = true;
             }
            
        }
    
    
     }
    
     if($updated) {
          $id = $post['id'];
    $pname = $post['prod_name'];
    $cat_id = $post['prod_cat'];
    $stock = $post['stock'];
    $price = $post['price'];
    $allergy = $post['allergy'];
    $desc = $post['prod_desc'];
    $keywords = $post['keywords'];
    $max_order = $post['max_order'];
    $min_order = $post['min_order'];
    $discount = $post['discount'];
    $shop_id = $post['shop_id'];
         
    $sql = "update product set 
            fk_cat_id = $cat_id, 
            fk_shop_id = $shop_id, 
            stock = $stock, 
            prod_price = $price, 
            min_order = $min_order, 
            max_order = $max_order, 
            discount = $discount, 
            allergy_info = '$allergy', 
            prod_desc = '$desc', 
            keywords = '$keywords' 
            where prod_id = $id";
         $update = oci_parse($con, $sql);
         oci_execute($update);
         if($update){
             echo json_encode(['status'=>'success', 'message'=>'Product Updated!']);
         } else {
             echo json_encode(['status'=>'fail', 'message'=>'Product couldn\'t be Updated!']);
         }
     }
    
  
}


function delete_shop($post){
    global $con;
    $id = $post['id'];
    $sql= "delete from shop where shop_id = $id";
    
    $delete= oci_parse($con, $sql);
    oci_execute($delete);
    if($delete){
        echo json_encode(['status'=>'success','message'=>'Shop Deleted!']);
    }
    else{
        echo json_encode(['status'=>'fail','message'=>'Could not Delete!']);
    }
}

function delete_prod($post){
    global $con;
    $id = $post['id'];
    
    $del_image = "select prod_img from product where prod_id = $id";
    $del =oci_parse($con, $del_image);
    oci_execute($del);
    $data = oci_fetch_assoc($del);
    $pic_name = $data['PROD_IMG'];
    $path = "product_images/$pic_name";
    unlink($path);
    
    
    
    $sql= "delete from product where prod_id = $id";
    
    $delete= oci_parse($con, $sql);
    oci_execute($delete);
    if($delete){
        echo json_encode(['status'=>'success','message'=>'Product Deleted!']);
    }
    else{
        echo json_encode(['status'=>'fail','message'=>'Could not Delete Product!']);
    }
}


function get_prod_data() {
    global $con;
    global $trader_id;
    $num = 1;
    $sql = "
    SELECT
        prod_img ,  prod_title ,  prod_price,  prod_desc , allergy_info , Stock ,       
        Keywords ,  Min_Order ,  Max_Order,  discount  ,  shop_name  ,prod_id  
    FROM
        product   p, shop      s
    WHERE
        p.fk_shop_id = s.shop_id
    AND s.fk_trader_id = $trader_id";
    
    $get = oci_parse($con, $sql);
    oci_execute($get);
    $html = '';    
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>#</th>';
    $html .= '<th>Item</th> ';  
    $html .= '<th>Price</th> '; 
    $html .= '<th>Description</th>';
    $html .= '<th>Allergy Info</th>';
    $html .= '<th>Stock</th>';
    $html .= '<th>Tags</th>';
    $html .= '<th>Min Order</th>';
    $html .= '<th>Max Order</th>';
    $html .= '<th>Discount</th>';
    $html .= '<th>Shop</th>';
    $html .= '<th colspan="2">Action</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    
   
    
    oci_fetch_all($get, $out);
    if(oci_num_rows($get) != 0){
        oci_execute($get);
        while($row = oci_fetch_assoc($get)) {  
        $p_id = $row['PROD_ID'];   
        $desc = $row['PROD_DESC'];
        $p_img = $row['PROD_IMG'];
        $p_title = $row['PROD_TITLE'];
        $p_price = $row['PROD_PRICE'];
        $allergy = $row['ALLERGY_INFO'];
        $stock = $row['STOCK'];
        $tags = $row['KEYWORDS'];
        $min_order = $row['MIN_ORDER'];
        $max_order= $row['MAX_ORDER'];
        $discount = $row['DISCOUNT'];
        $s_name = $row['SHOP_NAME'];
           
        $html .= '<tr><td>'.$num.'</td>';
        $html .= '<td>'.$p_title.'<br>';
        $html .= '<img class="img-thumbnail img-responsive" src="product_images/'.$p_img.'" alt="img" width="40">';
        $html .= '</td>';
        $html .= '<td>$'.$p_price.'</td>';
        $html .= '<td>'.$desc.'</td>'; 
        $html .= '<td> '.$allergy .'</td>';
        $html .= '<td> '.$stock.' </td>';
        $html .= '<td> '.$tags.' </td>';
        $html .= '<td> '.$min_order.' </td>';
        $html .= '<td> '.$max_order.' </td>';
        $html .= '<td> '.$discount.'%</td>';
        $html .= '<td> '.$s_name.' </td>';
        $html .= '<td>';
        $html .= '<a href="#" data-id="'.$p_id.'" id="update" class="icon"><i class="glyphicon glyphicon-edit" ></i></a>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<a href="#" data-id="'.$p_id.'" id="delete" class="icon text-danger"><i class="glyphicon glyphicon-trash"></i></a>';       
        $html .= '</td>';
        $html .= '</tr>';        
        $num++;
        
    }
    echo json_encode(['status'=>'success', 'html'=>$html]);
    }
    else{
        $html .= '<tr colspan=""> ';                                           
        $html .= '<td>No Product Found</td>';
        $html .= '</tr>';
        echo json_encode(['status'=>'success', 'html'=>$html]);
    }
    
}

?>


