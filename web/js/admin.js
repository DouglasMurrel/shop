function recountOrder(){
    result = 0;
    $('.order_row').each(function(k,obj){
        price = $(obj).find('.price').html();
        price = parseFloat(price);
        quantity = $(obj).find('input.form-control[type=text]').val();
        quantity = parseInt(quantity);
        result += price*quantity;
    });
    $('#full_amount').html(result);
}
$(function(){
    $('#delform button').on('click',function(){
        if (confirm('Вы уверены?')){
            $('#delform').submit();
        }
    });
    $('#truncate_form button').on('click',function(){
        if (confirm('Вы уверены?')){
            $('#truncate_form').submit();
        }
    });
    var csrfParam = $("meta[name=csrf-param]").attr("content");
    var csrfValue = $("meta[name=csrf-token]").attr("content");
    $('.admin_checkbox').on('click',function(){
        var data = {};
        data['id'] = this.value;
        data['value'] = this.checked?1:0;
        data[csrfParam] = csrfValue;
        $.ajax(
            'user/admin',
            {
                'method':'post',
                'data':data,
            }
        );
    });
    tinymce.init({
        selector: '.tinymce',
        plugins: [
            'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
            'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
            'table emoticons template paste help'
        ],
        toolbar: 'undo redo | styleselect | link image | bold italic | alignleft aligncenter alignright alignjustify |' +
            ' bullist numlist outdent indent | print preview media fullpage | ' +
            'forecolor backcolor emoticons',
        menubar: 'edit view insert format tools table',
        content_css: 'css/content.css',
        language: 'ru'
    });
})