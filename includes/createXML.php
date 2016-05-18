<?php

//Process file submission from file.php
		$newfilename = $_FILES["informerFile"]["name"];
		$xmlFile = str_replace('.csv', '.xml', $newfilename);
		$base = "informerFile";

		$target_path = "WHERE TO SAVE FILE";
        $target_path .= basename($newfilename);

       if(move_uploaded_file($_FILES[$base]['tmp_name'], $target_path)) {
         //   echo "woohoo";
        }
        else{
        //    echo "it didn't work!";
        }

//Get Expiration date from datepicker
	$expiry_date = $_POST["date"];

//Get CSV file from $targetPath
	$csv = array();
	$lines = file("folder/$newfilename", FILE_IGNORE_NEW_LINES);
	$c = 0;



//If faculty/staff save id as array
	session_start();
	if($_SESSION["patronType"] === "Faculty and Staff"){
		$ids = array();
		foreach ($lines as $key => $value){
				if($c > 0){
				$csv[$key] = str_getcsv($value);
				$id = $csv[$key][10];
				$ids[] = $id;
				}
				$c++;
			$_SESSION["facultyIds"] = $ids;
		}
	}


//If student, compare id to faculty array and remove from CSV
	if($_SESSION["patronType"] === "Students"){
		foreach ($lines as $key => $value){
				if($c > 0){
				$csv[$key] = str_getcsv($value);
				$studentId = $csv[$key][10];

				if(!empty($_SESSION["facultyIds"])){
					if(in_array($studentId, $_SESSION["facultyIds"], true)) {
					unset($lines[$c]);
				}}
			} $c++;
		}
	}


/************************* USER GROUP **********************************/
 /*  From Alma Config Form
  Code For Group    Name of Group   Patron Type
0   Undergrad Student
1   Grad Student
2   CAS/Grad Faculty    Faculty/Staff
3   Staff   Faculty/Staff
4   CAS/Grad Alum Student
  *
  *
  * 5 - Law Staff
  *
6   Guest     Student
9   CAS/Grad Emeritus   Faculty/Staff
13  ILL  Faculty/Staff
20  Dept Faculty/Staff
25  Summer Programs Student
35  Law Student  Student
37  Law Alum Student
38  Attorney Student
39  Law Faculty Faculty/Staff
41  Law General Public Student
42  Attorney Services   Attorney Services
43  Law ILL Faculty/Staff
44  reserve carrell Faculty/Staff
45  Law Adj. Fac.   Faculty/Staff
46  Law Research Asst.  Faculty/Staff
207 Summit Institution Patron   Faculty/Staff
216 Summit Visiting Patron Student
  */



//Faculty and Staff usergroups
function getUserGroup($status, $school){
	if($school === "Law"){
		switch($status) {
			//assign LAW faculty status
			case "FAC":
				$ugCode = 39;
				break;
			case "ADJ":
				$ugCode = 45;
				break;

			//assign LAW staff status
			case "STF":
			case "TMP":
			case "OTH":
				$ugCode = 5;
				break;
		} return $ugCode;
	} else {
	//school == CAS/GRAD/Common
		switch($status){
			//assign CAS/GRAD faculty status
			case "FAC":
			case "ADJ":
				$ugCode = 2;
				break;

			//assign CAS/GRAD staff status
			case "STF":
			case "TMP":
			case "OTH":
				$ugCode = 3;
				break;
		} return $ugCode;
	}
} //end of getUserGroup


//Student Usergroups
function getStudentUG($status){
	switch($status){
		//Law Students
		case "LS":
		case "LM":
		case "LV":
			$ugCode = 35;
			break;

		//Undergrad Students
		case "UG":
			$ugCode = 0;
			break;

		//GRAD students
		case "GR":
		case "CE":
			$ugCode = 1;
			break;
	} return $ugCode;
} //end of getStudentUG


$employeeFile = "WHERE TO SAVE FILE";


//Prep to create XML file

$doc = new DOMDocument('1.0', "UTF-8");
$users = $doc->createElement('users');
	$doc->appendChild($users);

/****************************** CREATE VARIABLES FROM CSV ******************************/

$c = 0;

	foreach ($lines as $key => $value){
		if($c > 0){
			$csv[$key] = str_getcsv($value);

			if($_SESSION["patronType"] === "Faculty and Staff"){
				$first_name = $csv[$key][1];
				$middle_name = $csv[$key][2];
				$last_name = $csv[$key][3];
			//	$full_name = $csv[$key][1];
			//	Need to explode username from email address
				$getUserName = explode('@', $csv[$key][12]);
				$username = $getUserName[0];
				$primary_id = $csv[$key][0];
			//	$campus_box = $csv[$key][3];
				$email_address = $csv[$key][12];
				$phone_number = $csv[$key][13];
		//		$address_lines = explode(',', $csv[$key][4]);
				$address_line1 = xmlEscape($csv[$key][4]);
				$address_line2 = $csv[$key][7] . ", " . $csv[$key][8] . " " . $csv[$key][9];
				$p_city = $csv[$key][7];
				$state = $csv[$key][8];
				$postal = $csv[$key][9];
				$school = $csv[$key][14];
				$fac_status = $csv[$key][15];
				$user_group = getUserGroup($fac_status, $school);
				$start_date = $csv[$key][17];

			}

			if($_SESSION["patronType"] === "Students"){
				//student
			//	$student_Lname = explode(',', $csv[$key][0]);
			//	$student_FMname = explode(' ', $csv[$key][0]);
				$first_name = $csv[$key][2];
				$middle_name = $csv[$key][3];
				$last_name = $csv[$key][1];
			//	$full_name = $csv[$key][0];
				$getUsername = explode('@', $csv[$key][13]);
				$username = $getUsername[0];
				$primary_id = $csv[$key][0];
				$campus_box = $csv[$key][4];
				$email_address = $csv[$key][13];
				$phone_number = $csv[$key][6];
			//	$address_lines = explode(',', $csv[$key][4]);
				$address_line1 = xmlEscape($csv[$key][7]);
				$address_line2 = $csv[$key][10] . ", " . $csv[$key][11] . " " . $csv[$key][12];
				$p_city = $csv[$key][10];
				$state = $csv[$key][11];
				$postal = $csv[$key][12];
			//	$school = $csv[$key][];
				$status = $csv[$key][15];
				$user_group = getStudentUG($status);
			//	$start_date = $csv[$key][];

			}

/***************************** CREATE XML SCHEMA ************************************/

	$user = $doc->createElement('user');
		$users->appendChild($user);

		//Patron Info

		$record_type = $doc->createElement('record_type', 'PUBLIC');
			$user->appendChild($record_type);

		$p_id = $doc->createElement('primary_id', $primary_id);
			$user->appendChild($p_id);

		$f_name = $doc->createElement('first_name', $first_name);
			$user->appendChild($f_name);

		$m_name = $doc->createElement('middle_name', $middle_name);
			$user->appendChild($m_name);

		$l_name = $doc->createElement('last_name', $last_name);
			$user->appendChild($l_name);

		$u_group = $doc->createElement('user_group', $user_group);
			$user->appendChild($u_group);

		$lang = $doc->createElement('preferred_language', 'en');
			$user->appendChild($lang);

		$expiration_date = $doc->createElement('expiry_date', $expiry_date);
			$user->appendChild($expiration_date);

		$purge = $doc->createElement('purge_date', $expiry_date);
			$user->appendChild($purge);

		$account = $doc->createElement('account_type', 'EXTERNAL');
			$user->appendChild($account);

		$status = $doc->createElement('status', 'ACTIVE');
			$user->appendChild($status);


	/****************** CONTACT INFO *************************/
	$contact = $doc->createElement('contact_info');
		$user->appendChild($contact);

		//address
		$addresses = $doc->createElement('addresses');
			$contact->appendChild($addresses);

		$address = $doc->createElement('address');
			//Create Attributes for address
			$ad_pref = $doc->createAttribute('preferred');
			$ad_pref->value = 'true';
			$segment = $doc->createAttribute('segment_type');
			$segment->value = 'External';

			$address->appendChild($ad_pref);
			$address->appendChild($segment);
			$addresses->appendChild($address);


			$line1 = $doc->createElement('line1', $address_line1);
				$address->appendChild($line1);

			$line2 = $doc->createElement('line2', $address_line2);
				$address->appendChild($line2);

			$city = $doc->createElement('city', $p_city);
				$address->appendChild($city);

			$state = $doc->createElement('state_province', $state);
				$address->appendChild($state);

			$postal = $doc->createElement('postal_code', $postal);
				$address->appendChild($postal);

			$country = $doc->createElement('country');
				$address->appendChild($country);

			$address_note = $doc->createElement('address_note');
				$address->appendChild($address_note);

			$start = $doc->createElement('start_date', $start_date);
				$address->appendChild($start);

			$address_types = $doc->createElement('address_types');
				$address->appendChild($address_types);

				$address_type = $doc->createElement('address_type', 'home');
				//Create attributes for address type
				$ad_desc = $doc->createAttribute('desc');
				$ad_desc->value = 'Home';

					$address_type->appendChild($ad_desc);
					$address_types->appendChild($address_type);

		//Email
			$emails = $doc->createElement('emails');
				$contact->appendChild($emails);

			$email = $doc->createElement('email');
				//Create attributes for email
				$e_pref = $doc->createAttribute('preferred');
				$e_pref->value = 'true';

				$email->appendChild($e_pref);
				$emails->appendChild($email);

				$e_address = $doc->createElement('email_address', $email_address);
					$email->appendChild($e_address);

				$email_types = $doc->createElement('email_types');
					$email->appendChild($email_types);

					$email_type = $doc->createElement('email_type', 'school');
					//Create attributes for email type
					$e_desc = $doc->createAttribute('desc');
					$e_desc->value = 'School';

						$email_type->appendChild($e_desc);
						$email_types->appendChild($email_type);


	/****************** USER IDENTIFIERS *************************/

	$identifiers = $doc->createElement('user_identifiers');
		$user->appendChild($identifiers);

		$user_identifier = $doc->createElement('user_identifier');
				//Create attributes for user id
				$user_seg = $doc->createAttribute('segment_type');
				$user_seg->value = 'External';

				$user_identifier->appendChild($user_seg);
			$identifiers->appendChild($user_identifier);

			$id_type = $doc->createElement('id_type', 'EXTERNAL');
				//Create attributes for id type
				$id_desc = $doc->createAttribute('desc');
				$id_desc->value = 'External';

				$id_type->appendChild($id_desc);
				$user_identifier->appendChild($id_type);

			$value = $doc->createElement('value', $username);
				$user_identifier->appendChild($value);

			$status = $doc->createElement('status', 'ACTIVE');
				$user_identifier->appendChild($status);

	/****************** LIBRARIES *************************/
	$rs_libraries = $doc->createElement('rs_libraries');
		$user->appendChild($rs_libraries);

		$rs_library = $doc->createElement('rs_library');
			$rs_libraries->appendChild($rs_library);

			$code = $doc->createElement('code', 'Watzek');
				//Create attributes for code
				$code_desc = $doc->createAttribute('desc');
				$code_desc->value = 'Watzek Library';

					$code->appendChild($code_desc);
				$rs_library->appendChild($code);


	} //End of foreach if statement
		$c++;

} //End of foreach to create XML from CSV

/**************** SAVE XML FILE ***********************/
	$doc->formatOutput = true;
if($_SESSION["patronType"] === "Faculty and Staff"){
	$doc->save("finishedXML/Employee.xml");
	$xmlFile = ("Employee.xml");
	}

	else {
	$doc->save("finishedXML/students.xml");
	$xmlFile = ("students.xml");
	}
//}// end of create xml else statement

//Get rid of bad chars in XML
function xmlEscape($string) {
	return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
}




/************************ CREATE ZIP FILE *********************************/

$zip = new ZipArchive;
$zipXmlFile = str_replace('.xml', '.zip', $xmlFile);

$zip->open("zips/$zipXmlFile", ZipArchive::CREATE);

$fileToZip = ("finishedXML/$xmlFile");

$zip->addFile($fileToZip);

$zip->close();


/**************************** MOVE FILE ***********************************/

$newFile = ("$zipXmlFile");
$path = "PATH TO ALMA FOLDER";

rename("FILEPATH/zips/$newFile", "$path/$newFile");

?>