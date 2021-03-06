<?php

/*
Controller name: Contact
Controller description: Basic contact form handling
*/

class JSON_API_Contact_Controller
{
    public function send_message()
    {
        global $json_api;
        nocache_headers();
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $email = $request->email;
        $name = $request->name;
        $message = $request->message;
        if (empty($name) || empty($email) || empty($message)) {
            $json_api->error("Please fill out all required form fields (name, email, message).");
        }
        if ($email) {
            sanitize_email($email);
        }
        if (!is_email($email)) {
            $json_api->error("Please provide a valid email address.");
        }
        $email_headers = 'Reply-To: ' . $name . ' <' . $email . '>' . "\r\n";
        $admin_email = get_option('admin_email');
        wp_mail($admin_email, 'Contact form submission', $message, $email_headers);
        return array(
            'name' => $name,
            'email' => $email,
            'message' => $message
        );
    }
}

?>
