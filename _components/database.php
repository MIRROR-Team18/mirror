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
     * @throws Exception If unable to start a connection.
	 */
	public static function getConnection(): PDO {
		if (!isset(self::$dbConnection)) {
			try {
                if (file_exists("../vendor/autoload.php")) {
                    require_once '../vendor/autoload.php'; // Loading the .env module.
                } else if (file_exists("./vendor/autoload.php")) {
                    require_once './vendor/autoload.php'; // Loading the .env module but if it's in the wrong place for some reason
                } else {
                    throw new Exception("Cannot locate dotenv file.");
                }

				$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
				$dotenv->load();

				$db_host = $_ENV['DB_HOST'];
				$db_name = $_ENV['DB_NAME'];
				self::$dbConnection = new PDO("mysql:host=$db_host;dbname=$db_name", $_ENV['DB_USER'], $_ENV['DB_PASS']);
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
    public bool $isAdmin;

	public function __construct(int $userID, string $email, string $firstName, string $lastName, int $isAdmin = 0) {
		$this->userID = $userID;
		$this->email = $email;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
        $this->isAdmin = $isAdmin === 1;
	}
}

class Size {
	public string $sizeID;
	public string $name;
	public float $price;

	public function __construct(string $sizeID, string $name, float $price) {
		$this->sizeID = $sizeID;
		$this->name = $name;
		$this->price = $price;
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

		return new User($id, $email, $firstName, $lastName, 0);
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
			return new User($result['userID'], $result['email'], $result['firstName'], $result['lastName'], $result['admin']);

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
			$stmt = $this->conn->prepare("SELECT * FROM product_sizes INNER JOIN sizes ON product_sizes.sizeID = sizes.sizeID WHERE productID = ?;");
			$stmt->execute([$productResult['productID']]);
			$sizeResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$sizes = array();

			foreach ($sizeResults as $sizeResult) {
				$sizes[] = new Size($sizeResult['sizeID'], $sizeResult['name'], $sizeResult['price']);
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

		$stmt = $this->conn->prepare("SELECT * FROM product_sizes INNER JOIN sizes ON product_sizes.sizeID = sizes.sizeID WHERE productID = ?;");
		$stmt->execute([$productResult['productID']]);
		$sizeResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$sizes = array();
		foreach ($sizeResults as $sizeResult) {
			$sizes[] = new Size($sizeResult['sizeID'], $sizeResult['name'], $sizeResult['price']);
		}

		return new Product($productResult['productID'], $productResult['name'], $productResult['type'], $sizes);
	}

	/**
	 * Creates a product with the provided product
	 * @param Product $product
	 * @return bool Returns true if product added successfully.
	 */
	public function createProduct(Product $product): bool {
		foreach ($product->sizes as $size) {
			$stmt = $this->conn->prepare("INSERT INTO product_sizes (productID, sizeID, price) VALUES (?, ?, ?)");
			$stmt->execute([$product->productID, $size->sizeID, $size->price]);
		}

		$stmt = $this->conn->prepare("INSERT INTO products (productID, name, type) VALUES (?, ?, ?)");
		$stmt->execute([$product->productID, $product->name, $product->type]);

		return true;
	}

    /**
     * Creates an enquiry made from the contact us form.
     * @return boolean If enquiry was added successfully
     */
    public function createContactEnquiry(string $name, string $email, string $message): bool {
        // Check that this isn't a duplicate entry (caused by network errors, user resubmitting by accident, etc.)
        $check = $this->conn->prepare("SELECT * FROM enquiries WHERE email = ?");
        $check->execute([$email]);
        $results = $check->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            if ($row["name"] == $name && $row["message"] == $message) // Don't check email again.
                return false; // Do not save it again.
        }

        // Save new enquiry.
        $stmt = $this->conn->prepare("INSERT INTO enquiries (type, nameProvided, email, message) VALUES (?,?,?,?)");
        $stmt->execute(["contact", $name, $email, $message]);

        return true; // As we were successful.
    }

	/**
	 * Creates an order with the provided userID and basket
	 * @param string $userID
	 * @param Product[] $basket
	 * @param array $quantityMap A map/associative array of productID to quantity
	 * @return string OrderID of the order created.

	 */
	public function createOrder(string $userID, array $basket, array $quantityMap): string {
		// Create order
		$totalPrice = 0;
		$productsInOrdersQueue = array(); // Used to store the insertion of products into the products_in_orders table.

		// Fixes __PHP_Incomplete_Class_Name. Did you know I dislike PHP? - Pawel
		$basket = array_map(function ($item) {
			return unserialize(serialize($item));
		}, $basket);

		foreach	($basket as $item) {
			/* @var $item Product */
			$totalPrice += $item->sizes[0]->price * $quantityMap[$item->productID];
			$productsInOrdersQueue[] = [$item->productID, $item->sizes[0]->sizeID, $quantityMap[$item->productID]];
		}

		$stmt = $this->conn->prepare("INSERT INTO orders (userID, status, paidAmount) VALUES (?, ?, ?)");
		$stmt->execute([$userID, "processing", $totalPrice]);
		$orderID = $this->conn->lastInsertId();

		// Create products in orders
		foreach ($productsInOrdersQueue as $productInOrder) {
			$stmt = $this->conn->prepare("INSERT INTO products_in_orders (orderID, productID, sizeID, quantity) VALUES (?, ?, ?, ?)");
			$stmt->execute([$orderID, $productInOrder[0], $productInOrder[1], $productInOrder[2]]);
		}

		return $orderID;
	}
}

class Tester {
	/**
	 * A replication of console.log() but within PHP... by using console.log().
	 * @param mixed $data - Anything to print
	 * @return void - Echos the output to the console
	 */
	public static function debug_to_console(mixed $data): void {
		$output = $data;
		if (is_array($output))
			$output = implode(',', $output);

		echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
	}

	public static function main(): void {
		$db = new Database();
		$prod = $db->getAllProducts();
		self::debug_to_console($prod[0]->productID);
	}
}

// Tester::main();