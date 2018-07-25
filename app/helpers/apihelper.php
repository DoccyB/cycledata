<?php
class apiHelper
{
//	private getFields = array("theft", "collision", "page", "search");

        public function validateNumeric ($field, &$error = false)
        {
                $result = false;
                if (isSet ($_GET[$field])) {
                        if(!ctype_digit ($_GET[$field])) {
                                $error = "{$field} must be a number";
                                return false;
                        }
                        $result = $_GET[$field];
                }
                return $result;
        }


        public function validateChars ($field, &$error = false)
        {
                $result = false;
                if (isSet ($_GET[$field])) {
                        if (!preg_match ('/^[a-z A-Z]{1,40}$/', $_GET[$field])) {
                                $error = "{$field} must consist of letters only";
                                return false;
                        }
                        $result = $_GET[$field];
                }
                return $result;
        }



}
?>
