<?php

include("xmlrpc-2.2/lib/xmlrpc.inc");

class Restore extends CFormModel
{
    public $email;
    public $login;
    public function rules()
    {
        return array(
            array('email', 'required'),
            array('login', 'required'),
            array('email', 'email'),
            array('login', 'length', 'max'=>128),
            array('email', 'length', 'max'=>128),
        );
    }
}
?>