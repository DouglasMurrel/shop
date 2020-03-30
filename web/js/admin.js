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
})