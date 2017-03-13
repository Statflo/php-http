<?php
namespace Statflo\HTTP\DTO;

class Response implements \JsonSerializable
{
    private $code;
    private $data;

    public function __construct($code, $data)
    {
        $this->code = $code;
        $this->data = $data;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function jsonSerialize()
    {
        return ["code" => $this->code, "data" => $this->data];
    }

    public function __toString()
    {
        return json_encode($this);
    }
}
