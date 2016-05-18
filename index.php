<?php
include('includes/top.php');
?>

        <h3>Step One</h3>
        <p>
        	Please follow the instructions below to download and save the patron file from <a target="_blank" href="LINK TO PATRON INFORMATION">YOUR DB</a>.
        	<ul>
        	<li> Employees Data Export</li>
        	<li> Students (Select terms for CAS & Grad or Law)</li>
        	<li> Export results as Excel Comma-Separated Values</li>
        	<li> Change Multivalue Handler to "List by comma"</li>
        	<li> Change Document Encoding to "UTF-8"</li>
			<li> Export!</li>
    		</ul>
    	 </p>

    	 <h3>Step Two</h3>
        <p>
        	Select the type of patron you are updating. </br>
        	*If you are updating both patron types, please select Faculty/Staff first.
        </p>
    <!--    <p>
          <a class="btn btn-lg btn-primary" href="" role="button"></a>
        </p> -->
      </div>


      <!-- What type of patron upload -->
<form method="post" action="file.php" enctype="multipart/form-data">
<div class="btn-group btn-group-justified" role="group" aria-label="...">
  <div class="btn-group" role="group">
    <button type="submit" class="btn btn-default" id="faculty" name="submit" value="Faculty and Staff">Faculty/Staff</button>
  </div>
 <div class="btn-group" role="group">
    <button type="submit" class="btn btn-default" id="students" name="submit" value="Students">Students</button>
  </div>

<!--  <div class="btn-group" role="group">
    <button type="button" class="btn btn-default" id="both">Both</button>
  </div>
</div>
-->
</form>



    </div> <!-- /container -->


 <?php
 include('includes/bottom.php');
 ?>