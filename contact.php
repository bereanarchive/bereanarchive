<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/init.php';


// Process form
if (!empty($_POST)) {

	// A bot filled in the honeypot fields.
	if (!empty($_POST['email']) || !empty($_POST['address']))
		return;

	// Check duration
	$qwerty = openssl_decrypt(base64_decode($_POST['qwerty']), 'AES-256-CFB', '', OPENSSL_RAW_DATA, '0123456789abcdef');
	$duration = microtime(true) - floatval($qwerty);	
	if ($duration < 3 || $duration > 3600*24)
		return;
		
	// Protect email from web crawlers going through github projects:
	$part2 = '@berean';	
	$recipient = strrev('nhoj') . $part2 . 'archive.org';	
	$from = strrev('etis') . $part2 . "archive.org";
	
	// Send the email
	$headers = "MIME-Version: 1.0\r\n" .
		"Content-type: text/html; charset=utf-8\r\n" .
		"From: $from\r\n";
	$subject = 'Berean Archive Contact form';
	$body = 'From: ' . $_POST['something'] . "<br><br>" . $_POST['message'];
	mail($recipient, $subject, $body, $headers);
	
	// Redirect back to page.
	header('Location: /contact?success=' . substr(0, 4, mt_rand()));
	die;
}


// Setup page.
$title = 'Contact';
$image = '/articles/biology/functional-dna/header-square.jpg';
$headerStyle = "background: url('contact/header-wide.jpg') no-repeat 93% 0; background-size: cover";
$caption = 'Header image from <a href="https://commons.wikimedia.org/wiki/File:Woodswink03.jpg">WikiMedia Commons</a>, slightly modified to enhance colors and remove objects.';
$bodyClasses = 'noSidebars';


// $content
ob_start()?>
<h1>Contact</h1>

<?php if (isset($_GET['success'])):?>
	<div style="margin-bottom: 30px; font-size: 120%;
	background-color: rgba(0, 255, 0, .1);
	padding: 5px;
	border-top: 2px solid green;
	border-bottom: 2px solid #080;"><b>Message sent successfully!</b></div>
<?php endif?>

<p>Fill out the form to send us a message.</p>

<form method="post">

	<?php /* Honeypot fields to prevent spam */ ?>
	<div class="bots">	
		<?php /* 1. If a bot fills out this field instead of email1 and email2 we fail it.  Removed via js.*/?>
		<input name="email" type="email" style="position: absolute; left: -999px" required>
		
		<?php /* 2. If a bot fills out this field, we fail it.   Hidden via css. */?>
		<input name="address" tabindex="-1">
	</div>	
	<?php /* 3. Time the form was created.  If a bot fills it out faster than 4 seconds, we fail it. */?>
	<input name="qwerty" type="hidden" value="<?=base64_encode(openssl_encrypt(microtime(true), 'AES-256-CFB', '', OPENSSL_RAW_DATA, '0123456789abcdef'))?>">	
	<script>
		var email = document.querySelector('.bots input[name="email"]');
		email.parentNode.removeChild(email);
		document.querySelector('.bots input[name="address"]').setAttribute('aria-hidden', true);
	</script>
	<style>
		.bots input[name="address"] { opacity: 0; height: 0 }
	</style>
	
	
	
	
	<?php /* Actual form */ ?>
	<div>
		<label>
			Email Address:<br>
			<input name="something" type="email" style="max-width: 400px; width: 100%">
		</label>
	</div>	
	<br>
	
	<div>
		<label>
			Message:<br>
			<textarea name="message" rows="10" style="width: 100%"></textarea>
		</label>
	</div>
	<br>

	<button style="float: right; font-weight: bold">Send Message</button>

</form>
<?php $content = ob_get_clean();


require_once 'common/includes/theme.php';
