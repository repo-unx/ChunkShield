<?php
/* Obfuscated with ChunkShield */
/**
 * Complex Test Class
 */
class TestClass {
    private $name;
    private $data = [];
    private static $counter = 0;
    
    /**
     * Constructor
     */
    public function __construct($name = 'Default') {
        $this->name = $name;
        self::$counter++;
    }
    
    /**
     * Add data to the object
     */
    public function addData($key, $value) {
        $this->data[$key] = $value;
        return $this;
    }
    
    /**
     * Get all data as JSON
     */
    public function toJson() {
        return json_encode([
            'name' => $this->name,
            'data' => $this->data,
            'instance' => self::$counter
        ]);
    }
    
    /**
     * Get instance count
     */
    public static function getCount() {
        return self::$counter;
    }
}

// Example usage
$obj1 = new TestClass('First Object');
$obj1->addData('color', 'blue')
    ->addData('size', 'large');

$obj2 = new TestClass('Second Object');
$obj2->addData('color', 'red')
    ->addData('shape', 'circle');

echo "Object 1: " . $obj1->toJson() . "\n";
echo "Object 2: " . $obj2->toJson() . "\n";
echo "Total instances: " . TestClass::getCount() . "\n";
$blsuU3=435;
