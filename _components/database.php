<?php

/**
 * Singleton class is used to connect to the database.
 */
class Connection {
	private static PDO $dbConnection;

	/**
	 * Constructor is private, please use getConnection();
	 * @throws Exception because you shouldn't be trying to do this.
	 */
	private final function __construct() {
		throw new Exception("Cannot instantiate a connection! Use getConnection() instead.");
	}

	/**
	 * This function is used to get, or initialize the connection.
	 * @return PDO The connection.
	 */
	public static function getConnection(): PDO {
		if (!isset(self::$dbConnection)) {
			try {
				// Currently this only supports local connections, maybe later I'll add support for virtualmin.
				self::$dbConnection = new PDO("mysql:host=localhost;dbname=mirror_mvp", "root", "");
				self::$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				exit("Database Connection failed: " . $e->getMessage());
			}
		}
		return self::$dbConnection;
	}
}

/**
 * Treat this like an object to store user data.
 */
class User {
	public int $userID;
	public string $email;
	public string $firstName;
	public string $lastName;

	public function __construct(int $userID, string $email, string $firstName, string $lastName) {
		$this->userID = $userID;
		$this->email = $email;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
	}
}

class Size {
	public string $sizeID;
	public string $name;

	public function __construct(string $sizeID, string $name) {
		$this->sizeID = $sizeID;
		$this->name = $name;
	}
}

/**
 * Treat this like an object to store product data.
 */
class Product {
	public string $productID;
	public string $name;
	public string $type;
	public array $sizes;

	public function __construct(string $productID, string $name, string $type, array $sizes = null) {
		$this->productID = $productID;
		$this->name = $name;
		$this->type = $type;
		$this->sizes = $sizes ?? array();
	}
}

class Database {
	private PDO $conn;

	public function __construct() {
		$this->conn = Connection::getConnection();
	}

	private function generateUserID(): string {
		// Keep generating a random number until we find one that doesn't exist.
		do {
			// Generate random 16 digit number and make it a string
			$randomNumber = strval(random_int(10000000, 99999999));
			// Check user does not exists with that ID.
			$stmt = $this->conn->prepare("SELECT * FROM users WHERE userID = ?");
			$stmt->execute([$randomNumber]);
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} while ($results);

		return $randomNumber;
	}

	public function getUser(int $id) {
		$stmt = $this->conn->prepare("SELECT * FROM users WHERE userID = ?");
		$stmt->execute([$id]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result ?? null;
	}

	/**
	 * Creates a new user, and returns it, or throws an exception if there's an issue.
	 * @throws Exception If there is a problem in creating a user.
	 */
	public function registerUser(string $email, string $firstName, string $lastName, string $password): User {
		// First check there's no users with that email.
		$stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
		$stmt->execute([$email]);
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($results) {
			throw new Exception("User with that email already exists!");
		}

		// Hash password, generate ID, then insert into database.
		$hashPassword = password_hash($password, PASSWORD_DEFAULT);
		$id = $this->generateUserID();
		$stmt = $this->conn->prepare("INSERT INTO users (userID, email, firstName, lastName, password) VALUES (?, ?, ?, ?, ?)");
		$stmt->execute([$id, $email, $firstName, $lastName, $hashPassword]);

		return new User($id, $email, $firstName, $lastName);
	}

	/**
	 * Logins in the user, whilst checking username and password, before returning a User object.
	 * @param string $email
	 * @param string $password
	 * @return User
	 * @throws Exception If there is a problem in logging in.
	 */
	public function loginUser(string $email, string $password): User {
		$stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
		$stmt->execute([$email]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!$result || !password_verify($password, $result['password'])) {
			throw new Exception("Incorrect email or password!"); // We use the same error to prevent brute force attacks
		} else
			return new User($result['userID'], $result['email'], $result['firstName'], $result['lastName']);

	}

	/**
	 * Returns an array of all the products available in the database, with no filtering.
	 * @return Product[] An array of all the products.
	 */
	public function getAllProducts(): array {
		$stmt = $this->conn->prepare("SELECT * FROM products");
		$stmt->execute();
		$productResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$products = array();
		foreach ($productResults as $productResult) {
			$stmt = $this->conn->prepare("SELECT * FROM product_sizes WHERE productID = ?");
			$stmt->execute([$productResult['productID']]);
			$sizeResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$sizes = array();

			foreach ($sizeResults as $sizeResult) {
				$sizes[] = new Size($sizeResult['sizeID'], $sizeResult['name']);
			}

			$products[] = new Product($productResult['productID'], $productResult['name'], $productResult['type'], $sizes);
		}

		return $products;
	}

	/**
	 * Returns a product from a given productID.
	 * @param string $productID
	 * @return Product | null
	 */
	public function getProduct(string $productID): Product | null {
		$stmt = $this->conn->prepare("SELECT * FROM products WHERE productID = ?");
		$stmt->execute([$productID]);
		$productResult = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!$productResult) return null;

		$stmt = $this->conn->prepare("SELECT * FROM product_sizes WHERE productID = ?");
		$stmt->execute([$productResult['productID']]);
		$sizeResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$sizes = array();
		foreach ($sizeResults as $sizeResult) {
			$sizes[] = new Size($sizeResult['sizeID'], $sizeResult['name']);
		}

		return new Product($productResult['productID'], $productResult['name'], $productResult['type'], $sizes);
	}


}

class Tester {
	public static function debug_to_console($data) {
		$output = $data;
		if (is_array($output))
			$output = implode(',', $output);

		echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
	}

	public static function test() {
		$db = new Database();
		$user = $db->registerUser("224@aston.ac.uk", "Test", "User", "password");
		self::debug_to_console($user->userID);
	}
}

// Tester::test();