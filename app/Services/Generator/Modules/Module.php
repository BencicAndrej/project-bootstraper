<?php namespace Norm\Services\Generator\Modules;

use Norm\Services\Generator\Node;

interface Module {

	public function generate(Node $node);

}