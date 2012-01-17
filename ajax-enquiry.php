<?
//v0.3
//check valid email
function is_valid_email($email) { 
	if( (preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/', $email)) || 
		(preg_match('/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/',$email)) ) { 
		$host = explode('@', $email);
		if(checkdnsrr($host[1].'.', 'MX') ) return true;
		if(checkdnsrr($host[1].'.', 'A') ) return true;
		if(checkdnsrr($host[1].'.', 'CNAME') ) return true;
	}
	return false;
}

//check valid email
if (!is_valid_email($_GET["email"])) {
	$error["email"] = "Please enter a valid email.";
}
//check reqd fields
$reqd = explode(",", $_GET['reqd']);
foreach ($reqd as $field) {
	if ($_GET[$field] == "") {
		$error[$field] = "Please complete your ".str_replace("_", " ", $field).".";
	}
}
//send enquiry
if (!$error) {
	$to = $_GET["email_to"];
	$from = $_GET["email"];
	$subject = "Online enquiry";
	foreach ($_GET as $key => $value) {
		if ($key=='email_to' || $key=='reqd') { continue; }
		$msg .= ucfirst(str_replace("_", " ", $key)).": $value \n\n";
	}
	if (mail($to, $subject, $msg, "From: $from\r\n")) {
		$report[] = "Enquiry delivered.";	
	} else {
		$error[] = "Enquiry failed.";	
	}
}

//parse results
if ($error) {
	foreach ($error as $field_id => $txt) {
			$field_string .= $field_id.",";
	}
	echo '<ul class="error" title="'.substr($field_string, 0, -1).'">'; 
	foreach ($error as $field_id => $txt) {
			echo "<li>$txt</li>";
	}
	echo "</ul>"; 
}
if ($report) {
	echo '<ul class="report" title="">'; 
	foreach ($report as $txt) {
			echo "<li>$txt</li>";
	}
	echo "</ul>"; 
}
?>