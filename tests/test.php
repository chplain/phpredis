<?php

// phpunit is such a pain to install, we're going with pure-PHP here.
class TestSuite {

	public static $errors = array();
	public static $warnings = array();

	protected function assertFalse($bool) {
		$this->assertTrue(!$bool);
	}

	protected function assertTrue($bool) {
		if($bool)
			return;

		$bt = debug_backtrace(false);
		self::$errors []= sprintf("Assertion failed: %s:%d (%s)\n",
			$bt[0]["file"], $bt[0]["line"], $bt[1]["function"]);
	}

	protected function assertEquals($a, $b) {
		if($a === $b)
			return;

		$bt = debug_backtrace(false);
		self::$errors []= sprintf("Assertion failed (%s !== %s): %s:%d (%s)\n",
			print_r($a, true), print_r($b, true),
			$bt[0]["file"], $bt[0]["line"], $bt[1]["function"]);
	}

	protected function markTestSkipped($msg='') {
		$bt = debug_backtrace(false);
		self::$warnings []= sprintf("Skipped test: %s:%d (%s) %s\n",
			$bt[0]["file"], $bt[0]["line"], $bt[1]["function"], $msg);

		throw new Exception($msg);
	}

	public static function run($className) {
        $runner = new TestRunner;
		$rc = new ReflectionClass($className);
		$methods = $rc->GetMethods(ReflectionMethod::IS_PUBLIC);

		foreach($methods as $m) {

			$name = $m->name;
			if(!$runner->isValidTest($name))
				continue;

			$count = count($className::$errors);
			$rt = new $className;
			try {
				$rt->setUp();
				$rt->$name();
				echo ($count === count($className::$errors)) ? "." : "F";
			} catch (Exception $e) {
				if ($e instanceof RedisException) {
					$className::$errors[] = "Uncaught exception '".$e->getMessage()."' ($name)\n";
					echo 'F';
				} else {
					echo 'S';
				}
			}
		}
		echo "\n";
		echo implode('', $className::$warnings);

		if(empty($className::$errors)) {
			echo "All tests passed.\n";
			return 0;
		}

		echo implode('', $className::$errors);
		return 1;
	}
}

class TestRunner
{
    private $filter;
    
    public function __construct()
    {
        $argv = $_SERVER['argv'];
        foreach ( $argv as $i => $opt ) {
            if ( $opt == '--filter' && isset($argv[$i+1]) ) {
                $this->setFilter($argv[$i+1]);
            }
        }
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function isValidTest($name)
    {
        if ( substr($name, 0, 4) !== 'test' ) {
            return false;
        }
        if ( isset($this->filter) ) {
            return preg_match($this->filter, $name);
        }
        return true;
    }
}
?>
