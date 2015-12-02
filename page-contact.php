<?php
 
	//response generation function
	$response = "";

	//function to generate response
	function my_contact_form_generate_response($type, $message){
		global $response;
		if($type == "success") $response = "<div class='form-response success'>{$message}</div>";
		else $response = "<div class='form-response error'>{$message}</div>";
	}

	//response messages
	$not_human       = "Human verification incorrect.";
	$missing_content = "Please supply all information.";
	$email_invalid   = "Email Address Invalid.";
	$message_unsent  = "Message was not sent. Try Again.";
	$message_sent    = "Thanks! Your message has been sent.";
	 
	//user posted variables
	$name = $_POST['message_name'];
	$email = $_POST['message_email'];
	$message = $_POST['message_text'];
	$human = $_POST['message_human'];
	 
	//php mailer variables
	$to = 'trevor@journalists.org'; // Change me
	$subject = "Contact submission from ".get_bloginfo('name');
	$headers = 'From: '. $email . "\r\n" .
	  'Reply-To: ' . $email . "\r\n";

	if(!$human == 0) {
		if($human != 2){ 
			my_contact_form_generate_response("error", $not_human); //not human!
		} else {
			//validate email
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				my_contact_form_generate_response("error", $email_invalid);
			} else {
			//email is valid
				//validate presence of name and message
				if (empty($name) || empty($message)){
					my_contact_form_generate_response("error", $missing_content);
				} else {
					//send email
					$sent = wp_mail($to, $subject, strip_tags($message), $headers);
					if ($sent) my_contact_form_generate_response("success", $message_sent); //message sent!
					else my_contact_form_generate_response("error", $message_unsent); //message wasn't sent
				}
			}
		}
	} else if ($_POST['submitted']){
		my_contact_form_generate_response("error", $missing_content);
	}

get_header(); ?>

<div class="main-column" role="content">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<h1><?php the_title(); ?></h1>

	<?php the_content(); ?>
				
	<div id="respond">
	  <?php echo $response; ?>
	  <form action="<?php the_permalink(); ?>" method="post">
	    <p><label for="name">Name: <span>*</span> <br><input type="text" name="message_name" value="<?php echo esc_attr($_POST['message_name']); ?>"></label></p>
	    <p><label for="message_email">Email: <span>*</span> <br><input type="text" name="message_email" value="<?php echo esc_attr($_POST['message_email']); ?>"></label></p>
	    <p><label for="message_text">Message: <span>*</span> <br><textarea rows="5" type="text" name="message_text"><?php echo esc_textarea($_POST['message_text']); ?></textarea></label></p>
	    <p><label for="message_human">Human Verification: <span>*</span> <br><input type="text" style="width: 60px;" name="message_human"> + 3 = 5</label></p>
	    <input type="hidden" name="submitted" value="1">
	    <p><input type="submit" class="btn btn-primary btn-lg btn-block" value="Send Message"></p>
	  </form>
	</div>

	<?php endwhile; else: ?>

	<?php endif; ?>
</div>

<?php get_footer(); ?>