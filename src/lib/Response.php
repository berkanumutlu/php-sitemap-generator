<?php

namespace App\Library;

class Response
{
    /**
     * @var bool
     */
    protected $status = false;
    /**
     * @var int
     */
    protected $status_code;
    /**
     * @var string|null
     */
    protected $status_text;
    /**
     * @var string|array|null
     */
    protected $message;
    /**
     * @var string|array|null
     */
    protected $data;
    /**
     * @var string|null
     */
    protected $date;

    /**
     * @return bool
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @param  bool  $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @param  int  $status_code
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
    }

    /**
     * @return string|null
     */
    public function getStatusText()
    {
        return $this->status_text;
    }

    /**
     * @param  string|null  $status_text
     */
    public function setStatusText($status_text)
    {
        $this->status_text = $status_text;
    }

    /**
     * @return array|string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param  array|string|null  $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return array|string|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param  array|string|null  $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param  string|null  $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'date'        => $this->getDate(),
            'status'      => $this->isStatus(),
            'code'        => $this->getStatusCode(),
            'status_text' => $this->getStatusText(),
            'message'     => $this->getMessage(),
            'data'        => $this->getData()
        ];
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}