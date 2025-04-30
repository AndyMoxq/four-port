<?php
namespace Tracking\Ocean\Response;

abstract class BaseResponse {
  protected $body;

  protected $message;

  protected $data;

  public function setBody(array $body){
    $this -> body = $body;
  }

  public function getBody(): array{
    return $this -> body;
  }

  public function getCode(): int {
      return $this -> getBody()['code'] ?? 500;
  }

  public static function format(array $body): static{
    $response = new static;
    $response -> setBody($body);
    $response -> validate();
    return $response;
  }

  public function setMessage(string $message){
    $this -> message = $message;
  }

  public function getMessage(): string{
    return $this -> message ?: $this -> body['message'] ?? 'no message';
  }

  public function getData(): array{
    return $this -> getBody()['data'];
  }

  public function setData(array $data){
    $this -> data = $data;
  }

  abstract public function validate();
  
}