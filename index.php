<?php
/**
 * PHP + cURL Automatic birthday wisher
 */
 
//Import PHPMailer classes into the global namespace
	//These must be at the top of your script, not inside a function
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

function url_test( $url ) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
	curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT,10);
	$output = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	return $httpcode;
}

$file = fopen("birthdays.txt", "r");
$birthdays =  fread($file, filesize("birthdays.txt"));
fclose($file);
$birthdays = explode(PHP_EOL, rtrim(ltrim($birthdays, PHP_EOL), PHP_EOL));

if(empty($birthdays)) {
	return 0;
}

foreach($birthdays as $birthday) {
	$bd = explode(';', $birthday);
	$name = $bd[0];
	$dob = $bd[1];
	$email = $bd[2];
	
	// Get the current month and day
	$currentMonth = date('m');
	$currentDay = date('d');

	// Get the month and day from the date of birth
	$birthdayMonth = date('m', strtotime($dob));
	$birthdayDay = date('d', strtotime($dob));

	// Compare the month and day with the current date
	if ($currentMonth == $birthdayMonth && $currentDay == $birthdayDay) {
		$data = array(
			'name' => $name,
		);
		
		// Load and render the view file
		$msg = loadView('template.php', $data);

		//Load Composer's autoloader
		require './phpMailer/vendor/autoload.php';

		//Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try {
				//Server settings
				$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
				$mail->isSMTP();                                            //Send using SMTP
				$mail->Host       = 'ssl://smtp.gmail.com';                     //Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
				$mail->Username   = 'sender@gmail.com';                     //SMTP username
				$mail->Password   = 'YourAppPassword';                               //SMTP password //https://support.google.com/mail/answer/185833?hl=en#:~:text=Create%20%26%20use%20App%20Passwords
				$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

				//Recipients
				$mail->setFrom('sender@gmail.com', 'Sender Name');
				$mail->addAddress(	$email);     //Add a recipient

				//Content
				$mail->isHTML(true);                                  //Set email format to HTML
				$mail->Subject = 'Happy Birthday';
				$mail->Body    = $msg;

				$mail->send();
				echo 'Message has been sent';
		} catch (Exception $e) {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	} else {
		  return 0;
	}
}

function loadView($viewFile, $data = []) {
    extract($data);  // Extract the data array into individual variables

    // Start output buffering to capture the rendered view
    ob_start();

    // Include the view file
    include $viewFile;

    // Get the rendered view content from the output buffer
    $viewContent = ob_get_clean();

    // Display the rendered view content
    return $viewContent;
}



?>
