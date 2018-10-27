<?php
/**
* Example View block
*
* @codepool   Local
* @category   Fido
* @package    Fido_Example
* @module     Example
*/
class Fido_Example_Block_View extends Mage_Core_Block_Template
{
private $message;
private $att;

protected function createMessage($msg) {
$this->message = $msg;
}

public function receiveMessage() {
if($this->message != '') {
return $this->message;
} else {
$this->createMessage('Hello World');
return $this->message;
}
}

protected function _toHtml() {
$html = parent::_toHtml();

if($this->att = $this->getMyCustom() && $this->getMyCustom() != '') {
$html .= '<br />'.$this->att;
} else {
$html .= '<br />No Custom Attribute Found';
}

return $html;
}
}
