<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sha
 * Date: 22/9/13
 * Time: 11:50 PM
 * To change this template use File | Settings | File Templates.
 */

class Admin_Controller extends My_Controller {
    public function __construct()
    {
        parent::__construct();
        $accessible = ($this->session->userdata('isAdmin') || $this->session->userdata('isTutor'));
        if (!$accessible) {
            redirect(base_url() . "home");
            return;
        }

    }
}