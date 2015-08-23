<?php

class DHttpRequest extends CHttpRequest
{

    private $_csrfToken;
    public $CsrfValidationRoutes = array(
        
    );

    /**
     * Normalizes the request data.
     * This method strips off slashes in request data if get_magic_quotes_gpc() returns true.
     * It also performs CSRF validation if {@link enableCsrfValidation} is true.
     */
    protected function normalizeRequest()
    {
        parent::normalizeRequest();

        if ($this->getIsPostRequest() && $this->enableCsrfValidation && $this->checkCurrentRoute()) {
            Yii::app()->detachEventHandler('onbeginRequest', array($this, 'validateCsrfToken'));
        }
    }

    /**
     * Checks if current route should be validated by validateCsrfToken().
     *
     * @return bool true if current route should be validated
     */
    private function checkCurrentRoute()
    {
        foreach ($this->CsrfValidationRoutes as $checkPath) {
            if (($pos = strpos($checkPath, '*')) !== false) {
                $checkPath = substr($checkPath, 0, $pos - 1);

                if (strpos($this->pathInfo, $checkPath) === 0) {
                    return false;
                }
            } elseif ($this->pathInfo === $checkPath) {
                return false;
            }
        }

        return true;
    }

    public function getCsrfToken()
    {
        if ($this->_csrfToken === null) {
            $session = Yii::app()->session;
            $csrfToken = $session->itemAt($this->csrfTokenName);

            if ($csrfToken === null) {
                $csrfToken = sha1(uniqid(mt_rand(), true));
                $session->add($this->csrfTokenName, $csrfToken);
            }

            $this->_csrfToken = $csrfToken;
        }

        return $this->_csrfToken;
    }

    public function validateCsrfToken($event)
    {
        if ($this->getIsPostRequest()) {
            // only validate POST requests
            $session = Yii::app()->session;

            if ($session->contains($this->csrfTokenName) && isset($_POST[$this->csrfTokenName])) {
                $tokenFromSession = $session->itemAt($this->csrfTokenName);
                $tokenFromPost = $_POST[$this->csrfTokenName];
                $valid = $tokenFromSession === $tokenFromPost;
            } else {
                $valid = false;
            }

            if (!$valid) {
                throw new CHttpException(400, Yii::t('yii', 'Akses tidak diijinkan.'));
            }
        }
    }

}
