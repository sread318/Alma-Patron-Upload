<?php

	$patronType = $_POST['submit'];

	session_start();

	$_SESSION["patronType"] = $patronType;

include('includes/top.php');
?>


	<!-- File Uploader -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>

    <!-- Date/Time Calendar -->

	<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen"
     href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">


	<h3>Step Three</h3>
		<p>
			Select the file you would like to upload.
		</p>
		<p>
			Select the expiration date for the user group.
		</p>
  </div>


<h4><?php echo $_SESSION["patronType"]; ?></h4>
      <!-- File Submission -->
	<form action="process.php" method="post"enctype="multipart/form-data">
	<div class="fileinput fileinput-new" data-provides="fileinput">
	  <span class="btn btn-default btn-file"><span class="fileinput-new">Select file</span>
	  <span class="fileinput-exists">Change</span>
		<input type="file" name="informerFile"></span>
	  <span class="fileinput-filename"></span>
	  <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
	</div>


	 <!-- Patron Experiation Date -->
	 <div id="datetimepicker" class="input-append date">
		  <input id="date" name="date" type="text" required ></input>
		  <span class="add-on">
			<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
		  </span>
		</div>
		<script type="text/javascript"
		 src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js">
		</script>
		<script type="text/javascript"
		 src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js">
		</script>
		<script type="text/javascript"
		 src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js">
		</script>
		<script type="text/javascript"
		 src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js">
		</script>

		<script type="text/javascript">
		  $('#datetimepicker').datetimepicker({
			format: 'yyyy-MM-dd',
			language: 'en'
		  });
		</script>
		   <p>
			  <input id="fileSubmit" type="submit" value="Next Step" class="btn btn-lg btn-primary" role="button"></input>
			</p>

	</form>



    </div> <!-- /container -->


<script type="text/javascript">
	$("#fileSubmit").click(function(){
		var dateInput = $("#date").val();
		console.log(dateInput);
	});
</script>
<script>
	$('.datepicker').datepicker({
    format: 'mm/dd/yyyy',
    startDate: '-3d'
	})
</script>

<?php
	include('includes/bottom.php');
?>