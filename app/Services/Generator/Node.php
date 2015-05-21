<?php namespace Norm\Services\Generator;

class Node {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var Node|null
	 */
	protected $parent;

	/**
	 * @var array
	 */
	protected $attributes;

	/**
	 * @var array
	 */
	protected $children;

	/**
	 * @var int
	 */
	protected $childPointer;

	/**
	 * @param string $name
	 * @param Node $parent
	 */
	public function __construct($name, Node $parent = null) {
		$this->name = $name;
		$this->children = [];
		$this->childPointer = 0;
		$this->attributes = [];

		if ($parent)
			$parent->addChild($this);
	}

	/**
	 * Getter for the attribute: $name.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Getter for the attribute: $parent.
	 *
	 * @return Node|null
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * Setter for the attribute: $parent.
	 *
	 * @param Node $parent
	 * @return Node
	 */
	public function setParent(Node $parent) {
		$this->parent = $parent;

		return $this;
	}

	/**
	 * Setter for the attribute: $attributes.
	 *
	 * @param array $attributes key-value pairs of attributes
	 * @return Node
	 */
	public function setAttributes(array $attributes) {
		$this->attributes = $attributes;

		return $this;
	}

	/**
	 * Add a key-value pair to the list of attributes.
	 *
	 * @param string $key
	 * @param string $value
	 * @return Node
	 */
	public function setAttribute($key, $value) {
		$this->attributes[$key] = $value;

		return $this;
	}

	/**
	 * Getter for the attribute: $attributes.
	 *
	 * @return array
	 */
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * Retrieve the attribute with a specific key, if it exists. Otherwise return null.
	 *
	 * @param string $key
	 * @return string|null
	 */
	public function getAttribute($key) {
		return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
	}

	/**
	 * Add a node to the list of children.
	 *
	 * @param Node $child
	 * @return Node
	 */
	public function addChild(Node $child) {
		$this->children[] = $child;
		$child->setParent($this);

		return $this;
	}

	/**
	 * Retrieve the number of children for the node.
	 *
	 * @return int
	 */
	public function childrenCount() {
		return count($this->children);
	}

	/**
	 * Return the first child and set the pointer to the next one.
	 *
	 * @return Node|null
	 */
	public function getFirstChild() {
		if ($this->childrenCount() == 0) {
			return null;
		}

		$this->childPointer = 1;

		return $this->children[0];
	}

	/**
	 * Return the next child and increment the pointer.
	 *
	 * @return Node|null
	 */
	public function getNextChild() {
		if ($this->childrenCount() == $this->childPointer) {
			return null;
		}

		return $this->children[$this->childPointer++];
	}

	/**
	 * Return all children with a passed name.
	 *
	 * @param string|null $name
	 * @return array
	 */
	public function getChildren($name = null) {
		if ($name == null) return $this->children;

		$result = [];
		foreach($this->children as &$child) {
			if($child->name == $name) {
				$result[] = $child;
			}
		}

		return $result;
	}

	/**
	 * Find a parent node with provided name and return it, or the provided attribute.
	 *
	 * @param string $name
	 * @param string|null $attribute
	 * @return Node|string|null
	 */
	public function findParent($name, $attribute = null) {
		if ($this->parent && $this->parent->getName() == $name) {
			return $attribute ? $this->parent->getAttribute($attribute) : $this->parent;
		}

		return $this->parent ? $this->parent->findParent($name, $attribute) : null;

	}

}