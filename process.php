<!-- DOWNLOAD PAGE -->
<?php
include('includes/top.php');
include('includes/createXML.php');
session_start();
?>
<link href="css/process.css" rel="stylesheet">

        <h3><?php echo $_SESSION["patronType"]; ?> Upload Complete!</h3>
        <p>
		Upload a second patron file with the button below.
        </p>
        <p>
        Continue to <a href="LINK TO ALMA" target="_blank">
        Alma</a> and follow these instructions:
        	<ul>
        		<li>Select Integration Profiles (located in General Configuration Menu &lt; External Systems &lt; Integration Profiles).</li>
        		<li>Select "Patron Imports, V2"</li>
        		<li>Click on the Actions tab</li>
        		<li>Under Synchronize, click Run</li>
        		<li>Check progress under Monitor Jobs</li>
        		<li>Once the job is complete, open the Report (under Actions). Export to Excel and address any User Validation Failed errors:
        			<ul>
        				<li>User is not external: delete internal record (as long as there are not items currently checked out). </li>
        				<li>Primary ID already taken by another user: ID probably entered incorrectly in original record--fix it so record loads properly next time.</li>
        			</ul>
        		</li>
        		<li>Reload files if necessary</li>
        	</ul>
        </p>


      </div>


      <!-- What type of patron upload -->
<form method="post" action="file.php" enctype="multipart/form-data">
<div class="btn-group btn-group-justified upload-more" role="group" aria-label="...">

<?php

if($_SESSION["patronType"] === "Faculty and Staff"){
	echo '<div class="btn-group" role="group">
    <button type="submit" class="btn btn-default" id="students" name="submit" value="Students">Students</button>
  </div>';

} else {
	echo '<div class="btn-group" role="group">
    <button type="submit" class="btn btn-default" id="faculty" name="submit" value="Faculty and Staff">Faculty/Staff</button>
  </div>';
}
?>
</div>
</form>





    <script>$('.datepicker').datepicker({
    format: 'mm/dd/yyyy',
    startDate: '-3d'
})</script>

 <?php
 include('includes/bottom.php');
 ?>