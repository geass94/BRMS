<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<script type="text/javascript">
$('#item-name').typeahead({
    name: 'item_name',
    highlight: false,
    limit: 10,
    property: 'item-name',
    source: function (query, process) {
            $.ajax({
                url: 'index.php',
                type: 'POST',
                dataType: 'JSON',
                data: 'menu_search=' + query,
                success: function(data) {
                    process(data);
                }
            });
        },
    updater: function( item ) {
    	$( "#menu-item-id" ).val( item.id )
        $( "#sell-price" ).val( item.sell_price )
        $( "#price-summary" ).val( item.sell_price )
        return item.name;
  }
});
$( "#item-qty" ).spinner({
	min: 1,
	spin: function(event, ui) {
		var nvalue = $("#sell-price").val()*(ui.value);
        $("#price-summary").val( nvalue );
        $("#quantity").val( ui.value )
    }
});

$("#quantity").change(function(){
	var nvalue = $("#sell-price").val()*($(this).val());
    $("#price-summary").val( nvalue );
})

$("#payment-method").change(function(){
	var curVal = $(this).val(), sum = $("#sum-price").val(), cash = $("#cash").val(), card = $("#card").val()
	if(curVal == "cash")
	{
		$("#"+curVal).removeAttr("disabled")
		$("#card").attr('disabled', 'disabled');
		$("#cash").val( sum )
		$("#card").val( 0 );
	}else if(curVal == "card"){
		$("#"+curVal).removeAttr("disabled")
		$("#cash").attr('disabled', 'disabled');
		$("#card").val( sum )
		$("#cash").val( 0 );
	}else if(curVal == "both"){
		$("#cash").removeAttr('disabled');
		$("#cash").val( sum/2 )
		$("#card").removeAttr('disabled');
		$("#card").val( sum/2 )
	}else{
		$("#cash").attr('disabled', 'disabled');
		$("#cash").val("");
		$("#card").attr('disabled', 'disabled');
		$("#card").val("");
		console.log("choose_payment_method")
	}
})

$(".add").click(function(event){
	var form = $("#orders-form"), response = $("#ordered-items");
	$.post("index.php",form.serialize(),function(data){
		response.append(data)
		form.each(function(){ 
	    	this.reset();
		});
	})
	event.preventDefault();
})

$(".enter-code").click(function(event){
	var form = $("#reserved-table"), id = $(this).attr('id');
	$.post("index.php", form.serialize(), function(data){
		if(data == "failure")
			$("#reservation-code").addClass("has-error")
		else
			$("#reservation-code").addClass("has-success")
		if(parseAlerts(data)[2] == true){
			window.location.reload();
		}
	})
	event.preventDefault()
})

$(".cancel").click(function(event){
	var form = $("#orders-form");
	form.each(function(){ 
    	this.reset();
	});
	event.preventDefault();
})
/*
$(".delete-button").click(function(event){
    var id = $(this).attr('id'), module = $(this).attr('module');
    id = id.split("-")[1];
    var tr = $("#"+module+"-"+id), to_delete = "delete_"+module;
    $.post("index.php",{ delete_action : to_delete, id : id },function(data){
        alert( parseAlerts(data)[0] )
        tr.fadeOut("slow")
    })
    event.preventDefault();
})
*/

$(".calculate-orders").click(function(event){
	var response = $("#response-data");
	var form = $("#finish-order");

	$.post("index.php",form.serialize(),function(data){
		response.html( data )
	})
	event.preventDefault();
})
</script>