function setColor(obj){
    add = $(obj).hasClass("fa-plus");
    if(add) {
        var field = $('.color-field:last');
        clone = field.clone();
        clone.appendTo("#new-colors");
        input = $('.color-input:last');
        input1 = clone.find('.color-hidden');
        var id = input.attr('id');
        num = parseInt(input1.val());
        num += 1;
        id = 'color-name' + num;
        input.attr('id', id);
        input.val('');
        input.attr('name','Color['+num+'][name]');
        input1.attr('id','color-id'+num);
        input1.attr('name','Color['+num+'][id]');
        input1.val(num);
        $('#saveform').yiiActiveForm('add', {
            id: id,
            name: 'name',
            container: '.field-color-name0',
            input: '#'+id,
            error: '.invalid-feedback',
            validate: function (attribute, value, messages, deferred, $form) {
                yii.validation.required(value, messages, {message: "Укажите название цвета"});
            }
        });
        $('#saveform').yiiActiveForm('add', {
            id: 'color-id'+num,
            name: 'id',
            container: '.field-color-0-id',
            input: '#color-id'+num,
            error: '.invalid-feedback',
            validate: function (attribute, value, messages, deferred, $form) {
                yii.validation.required(value, messages, {message: "Укажите ID"});
            }
        });
        $('.fa').removeClass('fa-plus').addClass('fa-minus');
        $('.fa:last').addClass('fa-plus');
    }else{
        $(obj).parents('.color-field').each(function(){
            $('#saveform').yiiActiveForm('remove', this.id);
            $(this).remove();
        });
    }
    return false;
}
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