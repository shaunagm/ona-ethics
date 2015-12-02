<?php

/*
  Plugin Name: Designmodo Registration Form
  Plugin URI: http://designmodo.com
  Description: Simple WordPress registration form plugin that just work
  Version: 1.0
  Author: Agbonghama Collins
  Author URI: http://tech4sky.com
 */


class Designmodo_registration_form
{

    private $username;
    private $email;
    private $password;
    private $website;
    private $first_name;
    private $last_name;
    private $org;
    private $job;

    function __construct()
    {
        add_shortcode('dm_registration_form', array($this, 'shortcode'));
        add_action('wp_loaded',  array($this, 'ONA_check_signup'));
    }

    public function ONA_check_signup() {

        if ($_POST['reg_submit']) {
            $this->username = $_POST['reg_email'];
            $this->email = $_POST['reg_email'];
            $this->password = $_POST['reg_password'];
            $this->website = $_POST['reg_website'];
            $this->first_name = $_POST['reg_fname'];
            $this->last_name = $_POST['reg_lname'];
            $this->org = $_POST['reg_org'];
            $this->job = $_POST['reg_job'];

            if ( is_user_logged_in() ){
                $this->update();
            } else {
                $this->validation();
                $this->registration();
            }
        }
    }

    public function registration_form()
    {
        if ( !isset($_POST['reg_submit']) && is_user_logged_in() ){
            $current_user = wp_get_current_user();
            $_POST['reg_email'] = $current_user->user_email;
            $_POST['reg_fname'] = $current_user->user_firstname;
            $_POST['reg_lname'] = $current_user->user_lastname;
            $_POST['reg_org'] = $current_user->user_description;
            $_POST['reg_job'] = get_user_meta($current_user->ID, 'user_job', true);
            $_POST['reg_website'] = get_user_meta($current_user->ID, 'user_website', true);
        }
        if ($this->errorcode) echo $this->errorcode;
        ?>

        <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
            <div class="login-form">

                <div class="form-group">
                    <input name="reg_email" type="email" class="form-control login-field"
                           value="<?php echo(isset($_POST['reg_email']) ? $_POST['reg_email'] : null); ?>"
                           placeholder="Email" id="reg-email" required/>
                    <label class="login-field-icon fui-mail" for="reg-email"></label>
                </div>

                <? if ( !is_user_logged_in() ){ ?>
                <div class="form-group">
                    <input name="reg_password" type="password" class="form-control login-field"
                           value="<?php echo(isset($_POST['reg_password']) ? $_POST['reg_password'] : null); ?>"
                           placeholder="Password" id="reg-pass" required/>
                    <label class="login-field-icon fui-lock" for="reg-pass"></label>
                </div>

                <div class="form-group">
                    <input name="pass_confirm" type="password" class="form-control login-field"
                           placeholder="Confirm Password" id="conf-pass" required/>
                    <label class="login-field-icon fui-lock" for="conf-pass"></label>
                </div>
                <? } ?>

                <div class="form-group">
                    <input name="reg_fname" type="text" class="form-control login-field"
                           value="<?php echo(isset($_POST['reg_fname']) ? $_POST['reg_fname'] : null); ?>"
                           placeholder="First Name" id="reg-fname"/>
                    <label class="login-field-icon fui-user" for="reg-fname"></label>
                </div>

                <div class="form-group">
                    <input name="reg_lname" type="text" class="form-control login-field"
                           value="<?php echo(isset($_POST['reg_lname']) ? $_POST['reg_lname'] : null); ?>"
                           placeholder="Last Name" id="reg-lname"/>
                    <label class="login-field-icon fui-user" for="reg-lname"></label>
                </div>

                <div class="form-group">
                    <input name="reg_org" type="text" class="form-control login-field"
                           value="<?php echo(isset($_POST['reg_org']) ? $_POST['reg_org'] : null); ?>"
                           placeholder="Organization" id="reg-org"/>
                    <label class="login-field-icon fui-new" for="reg-org"></label>
                </div>

                <div class="form-group">
                    <input name="reg_job" type="text" class="form-control login-field"
                           value="<?php echo(isset($_POST['reg_job']) ? $_POST['reg_job'] : null); ?>"
                           placeholder="Job Title" id="reg-job"/>
                    <label class="login-field-icon fui-new" for="reg-job"></label>
                </div>

                <div class="form-group">
                    <input name="reg_website" type="text" class="form-control login-field"
                           value="<?php echo(isset($_POST['reg_website']) ? $_POST['reg_website'] : null); ?>"
                           placeholder="Website" id="reg-website"/>
                    <label class="login-field-icon fui-chat" for="reg-website"></label>
                </div>

                <div id="register-message"></div>
                <input class="btn btn-primary btn-lg btn-block" type="submit" name="reg_submit" value="<?=(is_user_logged_in()?'Update':'Start Building')?>"/>
        </form>
        </div>

        <script>
            jQuery( document ).ready(function( $ ) {
                $( "#conf-pass" ).keyup(function() {
                    pass = $( "#reg-pass" ).val();
                    conf = $( "#conf-pass" ).val();
                    if ( pass == conf ) {
                        $( "#conf-pass" ).addClass('match').removeClass('different');
                        //$( ".btn-primary" ).removeAttr('disabled');
                    } else {
                        $( "#conf-pass" ).addClass('different').removeClass('match');
                        //$( ".btn-primary" ).attr('disabled','disabled');
                    }
                });
                $( ".btn-primary" ).on('click', function(e){
                    pass = $( "#reg-pass" ).val();
                    conf = $( "#conf-pass" ).val();
                    if ( pass != conf ) {
                        e.preventDefault();
                        $( "#register-message" ).slideDown().text( "Please correctly confirm your password." );
                    }
                });
            });
        </script>
    <?php
    }

    function validation()
    {

        if ( empty($this->email) ) {
            return new WP_Error('field', 'Email address is missing');
        }

        if (strlen($this->username) < 4) {
            return new WP_Error('username_length', 'Username too short. At least 4 characters is required');
        }

        if ( !is_user_logged_in() ) {
            if (empty($this->password) ) {
                return new WP_Error('field', 'Required form field is missing');
            }
            if (strlen($this->password) < 5) {
                return new WP_Error('password', 'Password length must be greater than 5');
            }
            if (email_exists($this->email)) {
                return new WP_Error('email', 'Email Already in use');
            }
        }

        if (!is_email($this->email)) {
            return new WP_Error('email_invalid', 'Email is not valid');
        }

        if (!empty($website)) {
            if (!filter_var($this->website, FILTER_VALIDATE_URL)) {
                return new WP_Error('website', 'Website is not a valid URL');
            }
        }

        $details = array('Username' => $this->email,
            'First Name' => $this->first_name,
            'Last Name' => $this->last_name,
            'Nickname' => $this->first_name.' '.$this->last_name,
            'org' => $this->org
        );

        if (!validate_username($details['Username'])) {
            return new WP_Error('name_invalid', 'Sorry, the email you entered is not valid as a username');
        }

    }

    function registration()
    {

        $userdata = array(
            'user_login' => esc_attr($this->email),
            'user_email' => esc_attr($this->email),
            'user_pass' => esc_attr($this->password),
            'user_url' => esc_attr($this->website),
            'first_name' => esc_attr($this->first_name),
            'last_name' => esc_attr($this->last_name),
            'description' => esc_attr($this->org)
        );

        if (is_wp_error($this->validation())) {
            $this->errorcode  = '<div style="margin-bottom: 6px" class="btn btn-block btn-lg btn-danger">';
            $this->errorcode .= '<strong>' . $this->validation()->get_error_message() . '</strong>';
            $this->errorcode .= '</div>';
        } else {
            $register_user = wp_insert_user($userdata);
            if (!is_wp_error($register_user)) {
                update_user_meta($register_user, 'user_job', $this->job);
                update_user_meta($register_user, 'user_website', $this->website);
                //echo '<div style="margin-bottom: 6px" class="btn btn-block btn-lg btn-danger">';
                //echo '<strong>Registration complete. Goto <a href="' . site_url() . '/signin/">login page</a></strong>';
                //echo '</div>';

                $creds = array();
                $creds['user_login'] = $userdata['user_login'];
                $creds['user_password'] = $userdata['user_pass'];
                $creds['remember'] = true;
                $user = wp_signon( $creds, false );
                if ( is_wp_error($user) ){
                    $this->errorcode  = '<div style="margin-bottom: 6px" class="btn btn-block btn-lg btn-danger">';
                    $this->errorcode .= '<strong>' . $user->get_error_message() . '</strong>';
                    $this->errorcode .= '</div>';
                } else {
                    wp_redirect( site_url().'/dashboard', $status );
                    exit;
                }

            } else {
                $this->errorcode  = '<div style="margin-bottom: 6px" class="btn btn-block btn-lg btn-danger">';
                $this->errorcode .= '<strong>' . $register_user->get_error_message() . '</strong>';
                $this->errorcode .= '</div>';
            }
        }
    }

    function update()
    {

        $userdata = array(
            'ID' => get_current_user_id(),
            'user_email' => esc_attr($this->email),
            'user_url' => esc_attr($this->website),
            'first_name' => esc_attr($this->first_name),
            'last_name' => esc_attr($this->last_name),
            'description' => esc_attr($this->org)
        );

        if (is_wp_error($this->validation())) {
            echo '<div style="margin-bottom: 6px" class="btn btn-block btn-lg btn-danger">';
            echo '<strong>' . $this->validation()->get_error_message() . '</strong>';
            echo '</div>';
        } else {
            $register_user = wp_update_user($userdata);
            if (!is_wp_error($register_user)) {
                update_user_meta($register_user, 'user_job', $this->job);
                update_user_meta($register_user, 'user_website', $this->website);
                echo '<div style="margin-bottom: 6px" class="btn btn-block btn-lg btn-danger">';
                echo '<strong>Your information has been updated!</strong>';
                echo '</div>';
            } else {
                echo '<div style="margin-bottom: 6px" class="btn btn-block btn-lg btn-danger">';
                echo '<strong>' . $register_user->get_error_message() . '</strong>';
                echo '</div>';
            }
        }
    }


    function shortcode()
    {

        ob_start();
        $this->registration_form();
        return ob_get_clean();
    }

}

new Designmodo_registration_form;
