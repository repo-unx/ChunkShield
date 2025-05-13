<?php
/* Obfuscated with ChunkShield */
namespace ChunkShield\Test;

/**
 * Base abstract class
 */
abstract class BaseTestClass {
    protected $id;
    protected $createdAt;
    
    public function __construct($id = null) {
        $this->id = $id ?: uniqid('test_');
        $this->createdAt = new \DateTime();
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getCreatedAt() {
        return $this->createdAt->format('Y-m-d H:i:s');
    }
    
    abstract public function process();
}

/**
 * Concrete implementation
 */
class ConcreteTestClass extends BaseTestClass {
    private $name;
    private $properties = [];
    
    public function __construct($name, $id = null) {
        parent::__construct($id);
        $this->name = $name;
    }
    
    public function setProperty($key, $value) {
        $this->properties[$key] = $value;
        return $this;
    }
    
    public function process() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'properties' => $this->properties,
            'created' => $this->getCreatedAt()
        ];
    }
}

// Usage example
$test = new ConcreteTestClass('Advanced Test');
$test->setProperty('category', 'testing')
    ->setProperty('priority', 'high');

echo "Test Result: " . json_encode($test->process());
$1YkH1q=359;
