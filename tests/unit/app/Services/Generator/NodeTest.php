<?php

use Norm\Services\Generator\Node;

class NodeTest extends TestCase {

	public function testNodeConstruction() {
		$node = new Node('root');
		$this->assertEquals('root', $node->getName());
		$this->assertEquals(null, $node->getParent());
		$this->assertEquals([], $node->getChildren());
		$this->assertEquals([], $node->getAttributes());

		$childNode = new Node('child', $node);
		$this->assertEquals('child', $childNode->getName());
		$this->assertEquals($node, $childNode->getParent());

		$this->assertEquals(1, count($node->getChildren()));
	}

	public function testSettingNodeAttributes() {
		$attributes = [
			'first'  => 'yes',
			'second' => 'almost'
		];

		$node = new Node('root');
		$node->setAttributes($attributes);
		$this->assertEquals($attributes, $node->getAttributes());

		$node->setAttributes([]);
		$this->assertEquals([], $node->getAttributes());
	}

	public function testAccessingSingleAttribute() {
		$attributes = [
			'first'  => 'yes',
			'second' => 'almost'
		];

		$node = new Node('root');
		$node->setAttributes($attributes);

		$this->assertEquals(null, $node->getAttribute('money'));
		$this->assertEquals('almost', $node->getAttribute('second'));
	}

	public function testSettingSingleAttribute() {
		$attributes = [
			'first'  => 'yes',
			'second' => 'almost'
		];

		$node = new Node('root');
		$node->setAttributes($attributes);

		$node->setAttribute('first', 'no');
		$this->assertEquals('no', $node->getAttribute('first'));

		$node->setAttribute('third', 'wohoo');
		$this->assertEquals(3, count($node->getAttributes()));
	}

	public function testAddingChildrenToParent() {
		$node = new Node('root');
		$child = new Node('child');

		$node->addChild($child);

		$this->assertEquals(1, count($node->getChildren()));
		$this->assertEquals($node, $child->getParent());
	}

	public function testChildrenCount() {
		$node = new Node('root');
		$child = new Node('child');

		$node->addChild($child);

		$this->assertEquals(1, $node->childrenCount());
	}

	public function testFirstChildAccessor() {
		$node = new Node('root');
		$firstChild = new Node('child', $node);
		new Node('child', $node);

		$this->assertEquals($firstChild, $node->getFirstChild());
		$this->assertEquals(2, $node->childrenCount());
	}

	public function testNextChildAccessor() {
		$node = new Node('root');
		$firstChild = new Node('child', $node);
		$secondChild = new Node('child', $node);

		$this->assertEquals($firstChild, $node->getNextChild());
		$this->assertEquals($secondChild, $node->getNextChild());
		$this->assertEquals(null, $node->getNextChild());
	}

	public function testGetChildrenAccessor() {
		$node = new Node('root');
		$firstChild = new Node('child', $node);
		$secondChild = new Node('child', $node);
		$thirdChild = new Node('duck', $node);

		$this->assertEquals([$firstChild, $secondChild, $thirdChild], $node->getChildren());
		$this->assertEquals([$firstChild, $secondChild], $node->getChildren('child'));
		$this->assertEquals([], $node->getChildren('goose'));
	}

	public function findParentAccessor() {
		$root = new Node('root');
		$first = new Node('first', $root);
		$second = new Node('second', $first);
		$third = new Node('third', $second);

		$first->setAttribute('key', 'value');

		$this->assertEquals($root, $third->findParent('root'));
		$this->assertEquals('value', $third->findParent('first', 'key'));
		$this->assertEquals(null, $third->findParent('galacto'));
		$this->assertEquals(null, $third->findParent('root', 'key'));
	}

}