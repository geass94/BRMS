<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<script type="text/javascript">
$('#name').typeahead({
    name: 'name',
    highlight: false,
    limit: 10,
    property: 'name',
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
    	$( "#sell-price" ).val( Math.round( item.sell_price * 100) / 100 )
        $( "#cost-price" ).val( Math.round(item.cost_price * 100) / 100 )
        $( "#average-time" ).val( item.average_time )
        $( "#item-id" ).val( item.id )
        load_ingredients( item.id,item.ingredients )
        return item.name;
  }
});

$('#wh-item-name').typeahead({
    name: 'name',
    highlight: false,
    limit: 10,
    property: 'name',
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
        $( "#wh-item-id" ).val( item.id )
        return item.name;
  }
});

function load_ingredients(id,ings){
    $.post("index.php",{ load_ingredients : ings, id : id },function(data){
        $("#ingredients").html( data )
        if(id > 0)
            $("#ingredients-form-0").removeClass("hidden");
    })
}


$(".edit-menu").click(function(event){
	var form = $("#edit-form")
	$.post("index.php",form.serialize(),function(data){
		alert( parseAlerts(data)[0] )
	})
	event.preventDefault()
})

$(".cancel").click(function(event){
    var form = $("#edit-form");
    form.each(function(){ 
        this.reset();
    });
    event.preventDefault();
})
</script>