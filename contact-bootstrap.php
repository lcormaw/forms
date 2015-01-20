<?php 
		
	// Initialize variables
	$result = $errorName = $errorEmail = $errorMsg = $name = $email = $msg = NULL;
	
	// Check for header injections
	function has_header_injection($str) {
		return preg_match("/[\r\n]/", $str);
	}

	// Verify form
	if(isset($_POST["submit"])) {
		if(!$_POST["name"]) {
			$errorName = "<div class='alert alert-danger'>Please enter your name</div>";
			$result = "<div class='alert alert-danger'><strong>Your message was not sent</strong></div>";
		} else {
			$name = trim($_POST["name"]);
		}

		if(!$_POST["email"]) {
			$errorEmail = "<div class='alert alert-danger'>Please enter your email</div>";
			$result = "<div class='alert alert-danger'><strong>Your message was not sent</strong></div>";
		} elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			$errorEmail = "<div class='alert alert-danger'>Please enter a valid email address</div>";
    		$result = "<div class='alert alert-danger'><strong>Your message was not sent</strong></div>";
		} else {
			$email = trim($_POST["email"]);
		}

		if(!$_POST["msg"]) {
			$errorMsg = "<div class='alert alert-danger'>Please enter a message</div>";
			$result = "<div class='alert alert-danger'><strong>Your message was not sent</strong></div>";
		} else {
			$msg = $_POST["msg"];
		}

		// Check for header injections
		if(has_header_injection($name) || has_header_injection($email)) {
			die();
		}

		if(!isset($result)) {
			$sendTo = "your@email.com";
			$subject = "You have received a message from $name";

			// Construct the message
			$message = "Name: $name\r\n";
			$message .= "Email: $email\r\n";
			$message .= "Message:\r\n$msg";
			$message = wordwrap($message, 72);

			// Set the mail headers
			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
			$headers .= "From: $name <$email> \r\n";
			$headers .= "X-Priority: 1\r\n";
			$headers .= "X-MSMail-Priority: High\r\n\r\n";

			if(mail($sendTo, $subject, $message, $headers)) {
				$result = "<div class='alert alert-success'>Your message was sent successfully.</div>";
				$name = $email = $msg = NULL;
			} else {
				$result = "<div class='alert alert-danger'><strong>Your message could not be sent. Please try again.</strong></div>";
			}
		}
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Contact Us</title>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

		<style>
			.form-wrapper {
				border: 1px solid #ccc;
				background-color: #f8f8f8;
				-webkit-border-radius: 5px;
				-moz-border-radius: 5px;
				border-radius: 5px;
				padding: 1em;
				margin-top: 15px;
			}

			textarea {
				resize: none;
			}
		</style>
	</head>

	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 form-wrapper">
					<h1>Contact Us</h1>

					<?php echo $result; ?>

					<p class="lead">Please get in touch - we'll get back to you shortly.</p>
				
					<form method="post">
						<div class="form-group">
							<label for="form-name">Your Name:</label>
							<input type="text" id="form-name" class="form-control" value="<?php echo $name; ?>" name="name" placeholder="Your Name">
						</div><!-- form-group -->

						<?php echo $errorName; ?>

						<div class="form-group">
							<label for="form-email">Your Email:</label>
							<input type="email" id="form-email" class="form-control" value="<?php echo $email; ?>" name="email" placeholder="your@email.com">
						</div><!-- form-group -->

						<?php echo $errorEmail; ?>

						<div class="form-group">
							<label for="form-message">Your Message:</label>
							<textarea id="form-message" class="form-control" name="msg" rows="3" placeholder="Your Message"><?php echo $msg; ?></textarea>
						</div><!-- form-group -->

						<?php echo $errorMsg; ?>

						<button type="submit" name="submit" class="btn btn-primary btn-lg pull-right">Submit</button>
					</form>
				</div><!-- form-wrapper -->
			</div><!-- row -->
		</div><!-- container -->

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	</body>
</html>