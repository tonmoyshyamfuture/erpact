<?php

/**
 * Twitter OAuth library.
 * Sample controller.
 * Requirements: enabled Session library, enabled URL helper
 * Please note that this sample controller is just an example of how you can use the library.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class TwitterLogin extends CI_Controller {

    /**
     * TwitterOauth class instance.
     */
    private $connection;

    /**
     * Controller constructor
     */
    function __construct() {
        parent::__construct();
        // Loading TwitterOauth library. Delete this line if you choose autoload method.
        $this->load->library('twitteroauth');
        // Loading twitter configuration.
        $this->config->load('twitter');
        $this->load->model('front/users', 'customer');
        if ($this->session->userdata('access_token') && $this->session->userdata('access_token_secret')) {
            // If user already logged in
            $this->connection = $this->twitteroauth->create($this->config->item('twitter_consumer_token'), $this->config->item('twitter_consumer_secret'), $this->session->userdata('access_token'), $this->session->userdata('access_token_secret'));
        } elseif ($this->session->userdata('request_token') && $this->session->userdata('request_token_secret')) {
            // If user in process of authentication
            $this->connection = $this->twitteroauth->create($this->config->item('twitter_consumer_token'), $this->config->item('twitter_consumer_secret'), $this->session->userdata('request_token'), $this->session->userdata('request_token_secret'));
        } else {
            // Unknown user
            $this->connection = $this->twitteroauth->create($this->config->item('twitter_consumer_token'), $this->config->item('twitter_consumer_secret'));
        }
    }

    /**
     * Here comes authentication process begin.
     * @access	public
     * @return	void
     */
    public function auth() {


        if ($this->session->userdata('access_token') && $this->session->userdata('access_token_secret')) {
            // User is already authenticated. Add your user notification code here.
            //redirect(base_url());  
        } else {
            // Making a request for request_token
            $request_token = $this->connection->getRequestToken(base_url('/twitterLogin/callback'));

            $this->session->set_userdata('request_token', $request_token['oauth_token']);
            $this->session->set_userdata('request_token_secret', $request_token['oauth_token_secret']);

            if ($this->connection->http_code == 200) {

                $url = $this->connection->getAuthorizeURL($request_token);
                redirect($url);
            } else {
                // An error occured. Make sure to put your error notification code here.
                $this->session->set_userdata('twitterinserterror', 'Oops! Something went wrong.Please try again later');
                redirect(base_url());
            }
        }
    }

    /**
     * Callback function, landing page for twitter.
     * @access	public
     * @return	void
     */
    public function callback() {
        if ($this->input->get('oauth_token') && $this->session->userdata('request_token') !== $this->input->get('oauth_token')) {
            $this->reset_session();
            redirect(base_url('/twitterLogin/auth'));
        } else {
            $access_token = $this->connection->getAccessToken($this->input->get('oauth_verifier'));

            if ($this->connection->http_code == 200) {
                $this->session->set_userdata('access_token', $access_token['oauth_token']);
                $this->session->set_userdata('access_token_secret', $access_token['oauth_token_secret']);
                $this->session->set_userdata('twitter_user_id', $access_token['user_id']);
                $this->session->set_userdata('twitter_screen_name', $access_token['screen_name']);

                $this->session->unset_userdata('request_token');
                $this->session->unset_userdata('request_token_secret');

                $userinfo = $this->connection->get('account/verify_credentials');

                $sess_array = array();
                $sid = $userinfo->id;
                echo $sid;

                $testresult = $this->customer->getTwitterCustomer($sid);


                if (count($testresult) > 0) {

                    $row = $testresult[0];
                    $name = $row->fname . " " . $row->lname;
                    $sess_array = array(
                        'uid' => $row->id,
                        'username' => $name,
                        'is_user_logged_in' => true
                    );
                    $this->session->set_userdata('user_logged_in', $sess_array);
                    $url = base_url('/twitterLogin/twitterLogout');
                    $this->session->set_userdata('twitterlogout', $url);

                    redirect(base_url());
                } else {

                    $socialid = $sid;
                    $sess_array = array(
                        'uid' => $userinfo->id,
                        'username' => $userinfo->name,
                        'email' => $userinfo->email,
                        'social' => 'social'
                    );
                    $this->session->set_userdata('user_tw_signup', $sess_array);
                    redirect(site_url('user-register'));

                    /* $resultno= $this->customer->insertSocialTwitterData($userinfo->name,"",$socialid);
                      if($resultno>0)
                      {


                      $name=$userinfo->name;
                      $sess_array = array(
                      'uid' => $resultno,
                      'username' => $name,
                      'is_user_logged_in' => true
                      );
                      $this->session->set_userdata('user_logged_in', $sess_array);
                      $url=base_url('/twitterLogin/twitterLogout');
                      $this->session->set_userdata('twitterlogout', $url);


                      }
                      else{
                      $this->session->set_userdata('twitterinserterror','Oops! Something went wrong.Please try again later');

                      } */
                }
            } else {
                $this->session->set_userdata('twitterinserterror', 'Oops! Something went wrong.Please try again later');
                redirect(base_url());
            }
        }
    }

    public function post($in_reply_to) {
        $message = $this->input->post('message');
        if (!$message || mb_strlen($message) > 140 || mb_strlen($message) < 1) {
            // Restrictions error. Notification here.
            redirect(base_url());
        } else {
            if ($this->session->userdata('access_token') && $this->session->userdata('access_token_secret')) {
                $content = $this->connection->get('account/verify_credentials');
                if (isset($content->errors)) {
                    // Most probably, authentication problems. Begin authentication process again.
                    $this->reset_session();
                    redirect(base_url('/twitterLogin/auth'));
                } else {
                    $data = array(
                        'status' => $message,
                        'in_reply_to_status_id' => $in_reply_to
                    );
                    $result = $this->connection->post('statuses/update', $data);

                    if (!isset($result->errors)) {
                        // Everything is OK
                        redirect(base_url());
                    } else {
                        // Error, message hasn't been published
                        redirect(base_url());
                    }
                }
            } else {
                // User is not authenticated.
                redirect(base_url('/twitterLogin/auth'));
            }
        }
    }

    /**
     * Reset session data
     * @access	private
     * @return	void
     */
    private function reset_session() {
        $this->session->unset_userdata('access_token');
        $this->session->unset_userdata('access_token_secret');
        $this->session->unset_userdata('request_token');
        $this->session->unset_userdata('request_token_secret');
        $this->session->unset_userdata('twitter_user_id');
        $this->session->unset_userdata('twitter_screen_name');
    }

    public function twitterLogout() {
        $this->session->unset_userdata('access_token');
        $this->session->unset_userdata('access_token_secret');
        $this->session->unset_userdata('request_token');
        $this->session->unset_userdata('request_token_secret');
        $this->session->unset_userdata('twitter_user_id');
        $this->session->unset_userdata('twitter_screen_name');
        $this->session->unset_userdata('user_logged_in');

        redirect(base_url());
    }

}

/* End of file twitter.php */
/* Location: ./application/controllers/twitterLogin.php */
