<?php namespace Norm\Services\Generator\Readers;

interface NodeReader {

	public function setXmlPath($xmlPath);

	public function getNodeTree();

}