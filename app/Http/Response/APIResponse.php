<?php

namespace App\Http\Response;
use Illuminate\Http\JsonResponse;

class APIResponse extends JsonResponse {

    private $innerData;
    private $innerErrors;
    private $innerSuccess;
    private $innerMeta = array();
    private $innerMessage;

    public function __construct($success = false, $meta = null, $data = null, $errors = null, $message = null, $status = 200, $headers = [], $options = 0)
    {
        $this->innerSuccess = $success;
        $this->innerData = $data;
        $this->innerErrors = $errors;
        $this->innerMeta = $meta;
        $this->innerMessage = $message;
        parent::__construct('ERROR - Should not be shown', $status, $headers, $options);
    }

    public function getContent()
    {
        $fullContent = array();
        $fullContent['success'] = $this->innerSuccess;
        $fullContent['meta'] = $this->innerMeta;
        if ($this->innerSuccess) {
            $fullContent['data'] = is_a($this->innerData, "Illuminate\Database\Eloquent\Builder") ? $this->innerData->get() : $this->innerData;
        } else {
            $fullContent['errors'] = $this->innerErrors;
        }
        $fullContent['message'] = $this->innerMessage;
       return json_encode($fullContent);
    }

    public function finalize() {
        //Set the content (string) to a combination of the inner data variables
        $this->setContent($this->getContent());
    }

    public function setInnerSuccess($success) {
        $this->innerSuccess = $success;
    }

    public function getInnerSuccess() {
        return $this->innerSuccess;
    }

    public function setInnerMeta($meta) {
        $this->innerMeta = $meta;
    }

    public function addInnerMeta($namespace, $meta) {
        $this->innerMeta[$namespace] = $meta;
    }

    public function getInnerMeta() {
        return $this->innerMeta;
    }

    public function setInnerData($data) {
        $this->innerData = $data;
    }

    public function getInnerData() {
        return $this->innerData;
    }

    public function setInnerErrors($errors) {
        $this->innerErrors = $errors;
    }

    public function getInnerErrors() {
        return $this->innerErrors;
    }

    public function setInnerMessage($message) {
        $this->innerMessage = $message;
    }

    public function getInnerMessage() {
        return $this->innerMessage;
    }
}
