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
$('#delform button').on('click',function(){
    console.log('aaaa');
    if (confirm('вы уверены?')){
        console.log(this);
    }
});