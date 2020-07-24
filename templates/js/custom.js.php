<?php if (!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); } ?>
<script type="text/javascript">
$(".login").click(function(event){
    $(".loader").removeClass("hidden")
    var form = $("#login"), response = $("#response");
    $.post("index.php",form.serialize(),function(data){
        console.log(data)
        $(".loader").addClass("hidden")
        response.removeAttr("class");
        response.addClass( parseAlerts(data)[1] );
        response.html( parseAlerts(data)[0] );
        // console.log(parseAlerts(data)[2])
        // if( parseAlerts(data)[2] == true )
        //     window.location.reload();
    })
    event.preventDefault();
})

/**************************/
// date and time picker for tables module
var inputdate = "", divid ="";
jQuery('.datetime').datetimepicker({
    inline:false,
    lang:'en',
    step: 5,
    onClose:function(dp,$input){
        inputdate = $input.val()
        divid = $input.attr('id')
        if(divid == "undefined")
        	divid = 0;
        else
        	divid = divid.split("-")[1]
        document.getElementById("reserved-date-"+divid).value = inputdate;
    }
});
/**************************/
// pattern form submiting forms: attribute->id="module + form + db_id"
$(".save-button").click(function(event){
	var id = $(this).attr('id'), module = $(this).attr('module');
	id = id.split("-")[1];
	var form = $("#"+module+"-form-"+id), response = $("#response-"+id);
	//console.log(form.serialize())
	$.post("index.php",form.serialize(),function(data){
        response.removeAttr('class')
        response.addClass( parseAlerts(data)[1] )
        //console.log( parseAlerts(data)[1] )
		response.html( parseAlerts(data)[0] )
        //console.log( parseAlerts(data)[0] )
        form.each(function(){ 
            this.reset();
        });
        if(parseAlerts(data)[2] === true)
            return window.location.reload();
	})
	event.preventDefault();
})

function save_item(module,id){
    var form = $("#"+module+"-form-"+id), response = $("#response-"+id);
    //console.log(form.serialize())
    $.post("index.php",form.serialize(),function(data){
        response.removeAttr('class')
        response.addClass( parseAlerts(data)[1] )
        //console.log( parseAlerts(data)[1] )
        response.html( parseAlerts(data)[0] )
        //console.log( parseAlerts(data)[0] )
        form.each(function(){ 
            //this.reset();
        });
        if(parseAlerts(data)[2] === true)
            return window.location.reload();
    })
}
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
function parseAlerts(data){
    // if I want to reload page after action last variable should be set to true
    if(data == "success"){
        return ["<?=$lang['success']?>","text-success",true];
    }else if(data == "failure"){
        return arr = ["შეცდომა!","text-danger",false];
    }else if(data == "duplication_error"){
        return ["<?=$lang['duplication_error']?>","text-success",true];
    }else if(data == "deleted"){
        return ["<?=$lang['deleted']?>","",false];
    }else if(data == "login_success"){
        return ["<?=$lang['login_success']?>","bg-success",true];
    }else if(data == "login_error"){
        return ["<?=$lang['login_error']?>","bg-danger",false];
    }else{
        return ["შეცდომა!","",true];
    }
}

$('.modal-box').on('hidden.bs.modal', function (e) {
  $(".modal-content").empty();
  window.location.reload();
})

$(".open-order").click(function(event){
    $(".modal-content").html(' <center><img class="loader" src="templates/images/loader.gif" /></center> ')
    var id = $(this).attr('id');
    $.post("index.php",{ open_order : id },function(data){
        $(".loader").addClass("hidden")
        $("#modal-data").html(data)
    })
    event.preventDefault();
})

$(".see-order").click(function(event){
    $(".modal-content").html(' <center><img class="loader" src="templates/images/loader.gif" /></center> ')
    var id = $(this).attr('id');
    $.post("index.php",{ see_order : id },function(data){
        $(".loader").addClass("hidden")
        $("#modal-data").html(data)
    })
    event.preventDefault();
})

$(".see-product").click(function(event){
    $(".modal-content").html(' <center><img class="loader" src="templates/images/loader.gif" /></center> ')
    var id = $(this).attr('id');
    $.post("index.php",{ see_product : id },function(data){
        $(".loader").addClass("hidden")
        $("#modal-data").html(data)
    })
    event.preventDefault();
})

$(".edit-menu").click(function(event){
    $(".modal-content").html(' <center><img class="loader" src="templates/images/loader.gif" /></center> ')
    var id = $(this).attr('id');
    $.post("index.php",{ edit_menu : id },function(data){
        $(".loader").addClass("hidden")
        $("#modal-data").html(data)
    })
    event.preventDefault();
})

function generateCode(divid){
    var curVal = $("#reserver-code-"+divid).val()
    var min = 1000;
    var max = 9999;
    var num = Math.floor(Math.random() * (max - min + 1)) + min;
    var cnfrm = confirm( "ნამდვილად გსურთ დაჯავშვნა და კოდის გენერირება? კოდის გენერირების შემდეგ აირჩიეთ დრო და დააჭირეთ შენახვას!" )
    if(cnfrm == true)
        $("#reserver-code-"+divid).val( num )
    else
        $("#reserver-code-"+divid).val(curVal)
}

function print_reciept(id,sid){
    alert(id+"  "+sid)
}

$( document ).ready(function() {
    $(".navbar-nav li").each(function(){
        //alert( $(this).attr("menu") )
        if( $(this).attr("menu") == mGET || $(this).attr("menu") == cGET)
            $(this).addClass('active')
        else
            $(this).removeClass('active')
    })
});

function close_session(id,sid){
    $.post("index.php",{ close_session : <?=date("Ymd")?>,  tid: id, sid : sid },function(data){
        alert(parseAlerts(data)[0]);
        window.close();
    })
}

$(function() {
    $( "#from-date" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd"
    });
    $(" #to-date ").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd"
    })
  });
$("#to-date").change(function(){
    $("#td").val( $(this).val() )
})
$("#from-date").change(function(){
    $("#fd").val( $(this).val() )
})

function reset_filter(){
    $.post("index.php",{ reset_filter : '<?=$url?>' }, function(data){
        alert(parseAlerts(data)[0])
        if(parseAlerts(data)[2] === true)
            window.location.reload();
    })
}

function delete_item(module,id){
    var tr = $("#"+module+"-"+id), to_delete = "delete_"+module;
    $.post("index.php",{ delete_action : to_delete, id : id },function(data){
        alert( parseAlerts(data)[0] )
        tr.fadeOut("slow")
    })
}

$(".export-to-excel").click(function(){
    $("#export-form").submit()
})
var counter = 1;
$('.chck-box').click(function(){
var checkedBoxes = document.querySelectorAll('input[type=checkbox]:checked');
    if(checkedBoxes.length > 1){
        $("#merge").removeClass("btn-default")
        $("#merge").addClass("btn-success merge")
        counter++;
    }else{
        $("#merge").removeClass("btn-success merge")
        $("#merge").addClass("btn-default")
        counter = 1;
    }
})

$(".merge").click(function(event){
var form = $("#table-merge-form")
    if(counter > 1){
        $.post("index.php",form.serialize(),function(data){
                if(parseAlerts(data)[2] === true)
                    window.location.reload();
            })
    }
    else{
        alert("მონიშნეთ მინიმუმ 2 თავისუფალი მაგიდა")
    }
    event.preventDefault();
})
function split_tables(id){
    var ids = [0,id]
    $.post("index.php", {merge_tables : <?=date("Ymd")?>, table_id : id}, function(data){
        alert(parseAlerts(data)[0])
        if(parseAlerts(data)[2] === true)
            window.location.reload();
    })
}

function generate_api(id,secret,date){
    $.post("index.php",{ generate_api:date,secret:secret,id:id },function(data){
        $("#api_key").val( data )
    })
}

$('.grid').isotope({
  itemSelector: '.grid-item',
  percentPosition: true,
  masonry: {
    // use outer width of grid-sizer for columnWidth
    columnWidth: '.col-xs-12'
  }
})
</script>