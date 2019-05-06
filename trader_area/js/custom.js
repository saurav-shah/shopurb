$(function(){
    load_shop_data();   
});


$(function(){
    $('.form').on('submit', function(e){
       e.preventDefault();
        
        $form = $(this);
        
        if(validateForm($form)){
            crud($form);
            
        } else {
             console.log('invalid form');
        }
    });
});


$(function(){
   
    $('#sname').keyup(function(){
        var regexp = /^[a-zA-Z]+$/;
    if(regexp.test($('#sname').val())){
         $('#sname').closest('.form-group').removeClass('has-error');
        $('#sname').closest('.form-group').addClass('has-success');
        
    }
    else{
         $('#sname').closest('.form-group').addClass('has-error');
       
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
        url: 'get_shop.php',
        method: 'post',
        data: {id: id},
        success: function(response){
            response = $.parseJSON(response);
            
            if(response.status == 'success'){
                
                if(actionName == 'update'){
                    
                    $modal = $('#update-modal');
                    
                
                }
                else if(actionName == 'delete'){
                     $modal = $('#delete-modal');
                    
                }
             //console.log($modal.find('.form').html());
                $form = $modal.find('.form');
                $form.find('.id').val(response.id);
                $form.find('.sname').val(response.sname);
               $modal.modal('show');
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
    }
    else if(response.status == 'fail'){
        showErrorMessage($form,response.message);
    }
            load_shop_data();
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
}
function resetMessage() {
    $('.status').removeClass('alert');
    $('.status').removeClass('alert-danger');
    $('.status').removeClass('alert-success');
    $('.status').html('');
}



function validateForm($form){
    var regexp = /^[a-zA-Z]+$/;

    
    var input = $form.find('#sname');
  
        if(regexp.test(input.val())){
           return true;
           }
    else {return false;}
           
    
}




function load_shop_data(){
    $('#shop_table').html('<tr colspan="4" class="text-center"><td><img src="images/ajax-loader.gif"></td></tr>');
    $.ajax({
        url: 'read_shop_data.php',
        method: 'get',
        success: function(response){
            response = $.parseJSON(response);
            
            $('#shop_table').html(response.html);
           
            
        }
    });
}