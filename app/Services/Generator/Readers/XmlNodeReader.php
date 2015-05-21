<?php namespace Norm\Services\Generator\Readers;

use Norm\Services\Generator\Exceptions\FileNotFoundException;
use Norm\Services\Generator\Exceptions\PathNotFoundException;
use Norm\Services\Generator\Node;

class XmlNodeReader implements NodeReader {

	protected $xmlPath;

	public function __construct($xmlPath = null) {
		$this->xmlPath = $xmlPath;
	}

	public function setXmlPath($xmlPath) {
		$this->xmlPath = $xmlPath;

		return $this;
	}

	public function getNodeTree() {
		if (!isset($this->xmlPath))
			throw new PathNotFoundException("Xml path is not set.");
		if (!file_exists($this->xmlPath))
			throw new FileNotFoundException("File [ {$this->xmlPath} ] does not exist.");

		return $this->fromSimpleXML(simplexml_load_file($this->xmlPath));
	}

	protected function fromSimpleXML(\SimpleXMLElement $element) {
		$node = new Node($element->getName());

		foreach ($element->attributes() as $key => $value) {
			$node->setAttribute($key, (string)$value);
		}

		foreach ($element->children() as $child) {
			$node->addChild($this->fromSimpleXML($child));
		}

		return $node;
	}
}