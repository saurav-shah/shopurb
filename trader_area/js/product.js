$(function(){
    load_prod_data();   

    
    

    $('.form').on('submit', function(e){
        e.preventDefault(); 
        $form = $(this); crud($form);
      
    });
    
    $('.add_update').on('submit', function(e){
        
        $form = $(this);
        resetMessage();
        e.preventDefault();
        $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData:false,

        success: function(response){
        $('.add_update')[0].reset();

        response = $.parseJSON(response);
        if(response.status == 'success'){
            showSuccessMessage($form,response.message);
            load_prod_data();
        }
        else if(response.status == 'fail'){
            showErrorMessage($form,response.message);
        }

        console.log(response);
        }

        });
    }); 
    
    
    
});  


$(function(){
   
    $('#product_name').keyup(function(){
        var regexp = /^[a-zA-Z ]+$/;
    if(regexp.test($('#product_name').val())){
         $('#product_name').closest('.form-group').removeClass('has-error');
        $('#product_name').closest('.form-group').addClass('has-success');
        
    }
    else{
         $('#product_name').closest('.form-group').addClass('has-error');
       
    }
  
        
    });
});

$(function(){
   
$('.table').on('click',function(e){
  e.preventDefault();
   var $anchor= $(e.target).parent('.icon');
    var id = $anchor.attr('data-id');
    if($anchor.hasClass('icon')){
       getRecord($anchor.attr('id'),id);
        
    }
});
});


function getRecord(actionName, id){
    
    var modal = '';
    var form = '';
    $.ajax({
        url: 'get_prod.php',
        method: 'post',
        data: {id: id},
        success: function(response){
            response = $.parseJSON(response);
            
            if(response.status == 'success'){
                
                if(actionName == 'update'){
                    
                    $modal = $('#update-modal');                     
                    $form = $modal.find('.add_update');
                    $form.find('.id').val(response.id);
                    $form.find('.pname').val(response.pname);
                    $form.find('.cat').val(response.cat_id);
                    $form.find('.shop').val(response.shop_id);
                    $form.find('.stock').val(response.stock);
                    $form.find('.price').val(response.price);
                    $form.find('.a_info').val(response.a_info);
                    $form.find('.desc').val(response.desc);
                    $form.find('.tags').val(response.tags);
                    $form.find('.img').val(response.img);
                    $form.find('.min_order').val(response.min_order);
                    $form.find('.max_order').val(response.max_order);
                    $form.find('.discount').val(response.dis);
                    $form.find('.min_order').attr('max',response.stock - 1);
                    $form.find('.max_order').attr('max',response.stock - 1);
                    $modal.modal('show');
                
                }
                else if(actionName == 'delete'){
                    $modal = $('#delete-modal');
                    $form = $modal.find('.form');
                    $form.find('.id').val(response.id);
                    $form.find('.pname').val(response.pname);
                    $modal.modal('show');
                    
                }
             //console.log($modal.find('.form').html());
               
            }
        }
        
    });
}

function crud($form){
  resetMessage();
    $.ajax({
        url: $form.attr('action'),
        method: $form.attr('method'),
        data: $form.serialize(),
        success: function(response){
           
    response = $.parseJSON(response);
    if(response.status == 'success'){
        showSuccessMessage($form,response.message);
        load_prod_data();
    }
    else if(response.status == 'fail'){
        showErrorMessage($form,response.message);
    }
            
            console.log(response);
        }
    });
    
}


function showErrorMessage($form,message) {
    var $alert = $form.find('.status');
    $alert.addClass('alert');
    $alert.addClass('alert-danger');
    $alert.html(message);
}
function showSuccessMessage($form,message) {
    var $alert = $form.find('.status');
    $alert.addClass('alert');
    $alert.addClass('alert-success');
    $alert.html(message);
    console.log(message);
}
function resetMessage() {
    $('.status').removeClass('alert');
    $('.status').removeClass('alert-danger');
    $('.status').removeClass('alert-success');
    $('.status').html('');
}







function load_prod_data(){
    $('#prod_table').html('<tr colspan="8" class="text-center"><td><img src="images/ajax-loader.gif"></td></tr>');
    $.ajax({
        url: 'read_prod_data.php',
        method: 'get',
        success: function(response){
            response = $.parseJSON(response);
            
            $('#prod_table').html(response.html);
           
            
        }
    });
}