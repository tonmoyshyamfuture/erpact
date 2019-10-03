<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<div id="results"></div>
<div id="loader_image"><img src="<?php echo base_url('assets/loading.gif') ?>"></div>
<div id="loader_message"></div>
</body>
</html>

<?php

?>
<script src="http://access.myact-erp.local/assets/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script type="text/javascript">
	// $("#loader_image").hide();
	var busy = false;
	var limit = 25
    var offset = 0;
	function displayRecords(lim, off) {
		$.ajax({
			url: "<?php echo base_url('admin/loadMoreRecords'); ?>",
			type: "POST",
			data: "limit=" + lim + "&offset=" + off,
			cache: false,
			async: false,
			beforeSend: function() {
				$("#loader_message").html("").hide();
				$('#loader_image').show();
			},
			success: function(html) {
				console.log(html);
				$('#loader_image').hide();
				$("#results").append(html);
				if (html.trim() == "") {
	              $("#loader_message").html('<button data-atr="nodata" class="btn btn-default" type="button">No more records.</button>').show()
	            } else {
	              $("#loader_message").html('<button class="btn btn-default" type="button">Loading please wait...</button>').show();
	            }
				window.busy = false;
			}
		});
	}


	$(document).ready(function() {
	        // start to load the first set of data
	        displayRecords(limit, offset);
	 
	        $('#loader_message').click(function() {
	 
	          // if it has no more records no need to fire ajax request
	          var d = $('#loader_message').find("button").attr("data-atr");
	          if (d != "nodata") {
	            offset = limit + offset;
	            displayRecords(limit, offset);
	          }
	        });

	        $(window).scroll(function() {
	            // make sure u give the container id of the data to be loaded in.
	            if ($(window).scrollTop() + $(window).height() > $("#results").height() && !busy) {
	                busy = true;
	                offset = limit + offset;
	         
	                displayRecords(limit, offset);
	         
	            }
	        });

    });
</script>