<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<script type="text/javascript">
$('#name').typeahead({
    name: 'name',
    highlight: false,
    limit: 10,
    property: 'item-name',
    source: function (query, process) {
            $.ajax({
                url: 'index.php',
                type: 'POST',
                dataType: 'JSON',
                data: 'warehouse_search=' + query,
                success: function(data) {
                    process(data);
                }
            });
        },
    updater: function( item ) {
    	$( "#unit-price" ).val( Math.round(item.AVG * 100) / 100 )
        $( "#total-price" ).val( Math.round((item.AVG * item.QTY) * 100) / 100 )
        $( "#quantity" ).val( Math.round(item.QTY * 100) / 100 )
        $( "#item-id" ).val( item.id )
        $( "#item-wid" ).val( item.wid )
        dimension( item.dimension )
        itemstats( item.id,item.wid )
        return item.name;
  }
});

$('#dist-name').typeahead({
    name: 'dist-name',
    highlight: false,
    limit: 10,
    property: 'item-name',
    source: function (query, process) {
            $.ajax({
                url: 'index.php',
                type: 'POST',
                dataType: 'JSON',
                data: 'distributor_search=' + query,
                success: function(data) {
                    process(data);
                }
            });
        },
    updater: function( item ) {
    	$( "#dist-id" ).val( item.id )
        $( "#waybill" ).val( item.waybill )
        return item.name;
  }
});

$("#total-price").change(function(){
	var tp = $(this).val(), qty = $("#quantity").val()
	$("#unit-price").val( tp/qty )
})

function dimension(dim){
	if(dim == "კგ"){
		$("#kg").attr('selected','selected')
		$("#na").removeAttr('selected')
		$("#unit").removeAttr('selected')
		$("#lt").removeAttr('selected')
	}else if(dim == "ცალი"){
		$("#unit").attr('selected','selected')
		$("#na").removeAttr('selected')
		$("#kg").removeAttr('selected')
		$("#lt").removeAttr('selected')
	}else if(dim == "ლიტრი"){
		$("#lt").attr('selected','selected')
		$("#na").removeAttr('selected')
		$("#unit").removeAttr('selected')
		$("#kg").removeAttr('selected')
	}else{
		$("#lt").removeAttr('selected')
		$("#na").removeAttr('selected')
		$("#unit").removeAttr('selected')
		$("#kg").removeAttr('selected')
	}
}

function itemstats(id,wid){
	$.post("index.php",{ load_whitem_stats : id, wid : wid },function(data){
		$("#stats-response").html( data )
	})
}

$(".refill").click(function(event){
	var form = $("#refill-form")
	$.post("index.php",form.serialize(),function(data){
		alert( parseAlerts(data)[0] )
	})
	event.preventDefault()
})

$(".cancel").click(function(event){
    var form = $("#refill-form");
    form.each(function(){ 
        this.reset();
    });
    event.preventDefault();
})

</script>