<?php

interface IAjaxAction
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return array
     */
    public function getAttributes();
}
