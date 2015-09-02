<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     *
     * @return bool whether authentication succeeds.
     */
    public function authenticate()
    {
        $users = array(
            // username => password
            'demo' => 'demo',
            'admin' => 'admin',
        );
        //$users = User::model()->findByAttributes(array('username'=>$this->username));
        if (!isset($users[$this->username])) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif ($users[$this->username] !== $this->password) {
        //} elseif (!CPasswordHelper::verifyPassword($this->password,$users->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id=$users->userid;
            $this->setState('namalengkap', $this->namalengkap);
            $this->errorCode = self::ERROR_NONE;
        }

        return !$this->errorCode;        
    }
    
    public function getId()
    {
        return $this->_id;
    }
}
