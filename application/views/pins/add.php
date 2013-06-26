<? get_header();?>

<style type='text/css'>
.pin-images img.selected{
	border:2px green solid;
}
</style>

<section class="body">
	<fieldset>
		<legend>Add Pin</legend>
		<br>

		Enter URL:<br>
		<input type="text" name="url" id="pin_url">
		<input type="button" value="Add" onclick="parse_url()">
		<div id="parser_info"></div>

		<form action="" method="post" id="add_pin_form" style="display:none">
			<input type="text" name="title" id="pin_title">
			<div id="pin_images" class="pin-images"></div>
			<br>

			<input type="hidden" name="site_url" value="" id="pin_site_url">
			<input type="hidden" name="image_url" value="" id="pin_img_url">
			<input type="submit" value="Add">
		</form>
	</fieldset>
</section>

<script>
$("#add_pin_form").submit(function(){
	if($("#pin_title").val()==''){
		alert('Please, enter a title');
		$("#pin_title").focus();
		return false;
	}

	if($("#pin_title").val().length>200){
		alert('The title should be less than 200 symbols.');
		$("#pin_title").focus();
		return false;
	}

	if($("#pin_img_url").val()==''){
		alert("Please choose an image.")
		return false;
	}
});

var lastParsedURL="";
var parserXHR;
var parserXHRTimeout;
var lastParserData;

function parse_url()
{
	// Trim spaces
	var url = $("#pin_url").val().replace("/^\s+|\s+$/g", "");

	// If the idea is a URL
    if(/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i.test(url)){
        if(lastParsedURL==url) return;
        lastParsedURL=url;

        // Stop a previous AJAX request if any
        if(parserXHR) parserXHR.abort();

        $("#pin_images").html('');
        set_parser_info('Loading...');

        // Reset the add form
		document.getElementById("add_pin_form").reset();

        parserXHR=$.post('<?=base_url();?>/parser/get_url', {"url":url}, function(data){
            clearTimeout(parserXHRTimeout);
            lastParserData = data;

            if(data.status=="ok"){
                lastParsedURL='';

                // If we have images
                if(data.images.length>0){
                	// Set the images
                    for(i in data.images)
                    {
                    	img = data.images[i];
                    	$("#pin_images").append('<img src="'+img+'" width="150" height="150">');
					}

					$("#pin_images img").click(function(){
						$(this).parent().find('img').removeClass('selected');
						$(this).addClass('selected');

						$("#pin_img_url").val($(this).attr('src'));
					});
					$("#pin_images img:first").trigger('click');

					// Set the title and site URL
					$("#pin_title").val(data.title);
					$("#pin_site_url").val(url);

					set_parser_info('');
				}
				else set_parser_info('No images for this URL');
            }
        },'json');

		// Set a timeout in case the request takes too long
        parserXHRTimeout=setTimeout(function(){
            $("#parser_info").html('An unknown error occured.');
            if(parserXHR) parserXHR.abort();
        }, 25000);
    }
}

function set_parser_info(msg)
{
	if(typeof msg=='undefined' || msg==''){
		$('#parser_info').html('');
		$("#add_pin_form").show();
	}
	else{
	    $("#parser_info").html(msg).show();
	    $("#add_pin_form").hide();
	}
}
</script>

<? get_footer();?>