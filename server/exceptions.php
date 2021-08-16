<?php
    class DBUnmatchedParams extends Exception {
        public function getErrorMessage() {
            //error message
            $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': '.$this->getMessage();
            return $errorMsg;
        }
    }
    class InvalidURLFormatting extends Exception {
        public function getErrorMessage() {
            //error message
            $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': '.$this->getMessage();
            return $errorMsg;
        }
    }
    class InvalidRedirectPointer extends Exception {
        public function getErrorMessage() {
            //error message
            $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': '.$this->getMessage();
            return $errorMsg;
        }
    }
    class ViewNotFound extends Exception {
        public function getErrorMessage() {
            //error message
            $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': '.$this->getMessage();
            return $errorMsg;
        }
    }
    class InvalidRouteData extends Exception {
        public function getErrorMessage() {
            //error message
            $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': '.$this->getMessage();
            return $errorMsg;
        }
    }
    class InvalidControllerMethod extends Exception {
        public function getErrorMessage() {
            //error message
            $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': '.$this->getMessage();
            return $errorMsg;
        }
    }
    class InvalidControllerClass extends Exception {
        public function getErrorMessage() {
            //error message
            $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': '.$this->getMessage();
            return $errorMsg;
        }
    }
?>