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
				// ! I probably should find another solution to this.
                if (file_exists("../vendor/autoload.php")) {
                    require_once '../vendor/autoload.php'; // Loading the .env module.
                } else if (file_exists("./vendor/autoload.php")) {
                    require_once './vendor/autoload.php'; // Loading the .env module but if it's in the wrong place for some reason
                } else if (file_exists("../../vendor/autoload.php")) {
					require_once '../../vendor/autoload.php'; // Loading the .env module but now admin stuff makes this a lot worse
				} else {
                    throw new Exception("Cannot locate autoload file for dotenv! Did you run 'composer install'?.");
                }

				$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
				$dotenv->load();

				$db_host = $_ENV['DB_HOST'];
				$db_name = $_ENV['DB_NAME'];
				self::$dbConnection = new PDO("mysql:host=$db_host;dbname=$db_name", $_ENV['DB_USER'], $_ENV['DB_PASS']);
				self::$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // This allows us to use LIMIT in prepared statements.
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

/**
 * Class to store Size data
 */
class Size {
	public string $sizeID;
	public string $name;
	public bool $isKids;
	public float $price;

	public function __construct(string $sizeID, string $name, int $isKids, float $price) {
		$this->sizeID = $sizeID;
		$this->name = $name;
		$this->isKids = $isKids === 1;
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
	public string $gender;
	public array $sizes;

	public function __construct(string $productID, string $name, string $type, string $gender, array $sizes = null) {
		$this->productID = $productID;
		$this->name = $name;
		$this->type = $type;
		$this->gender = $gender;
		$this->sizes = $sizes ?? array();
	}
}

class Database {
	private PDO $conn;

	public function __construct() {
        try {
            $this->conn = Connection::getConnection();
			$this->init();
        } catch (Exception $e) {
            exit("Could not create database connection! " . $e->getMessage());
        }
	}

	/**
	 * Ensures the database has been set up correctly. Call after constructor. Aim is to deprecate init_table.sql.
	 * @return void
	 * @throws Exception If there is a problem in setting up the database.
	 */
	private function init(): void {
		try {
			$queries = array( // I'm splitting each query into a separate string to make it easier to read.
				"CREATE TABLE IF NOT EXISTS users (
    			id VARCHAR(8) NOT NULL PRIMARY KEY,
				email VARCHAR(320) NOT NULL,
				firstName VARCHAR(100) NOT NULL,
				lastName VARCHAR(100) NOT NULL,
				password VARCHAR(256) NOT NULL,
				admin INT(1) NOT NULL DEFAULT 0
			);",
				"CREATE TABLE IF NOT EXISTS gender_def (
    			id INT(2) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				name VARCHAR(32) NOT NULL
			);",
				"CREATE TABLE IF NOT EXISTS type_def (
    			id INT(2) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				name VARCHAR(32) NOT NULL
			);",
				"CREATE TABLE IF NOT EXISTS products (
				id VARCHAR(32) NOT NULL PRIMARY KEY,
				name VARCHAR(64) NOT NULL,
				type INT(2) NOT NULL,
				gender INT(2) NOT NULL,
				timeCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				timeModified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				FOREIGN KEY (type) REFERENCES type_def(id),
				FOREIGN KEY (gender) REFERENCES gender_def(id)
			);",
				"CREATE TABLE IF NOT EXISTS size_def (
    			id INT(2) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				name VARCHAR(32) NOT NULL,
				isKids INT(1) NOT NULL DEFAULT 0
			);",
				"CREATE TABLE IF NOT EXISTS product_sizes (
				productID VARCHAR(32) NOT NULL,
				sizeID INT(2) NOT NULL,
				price DECIMAL(6,2) NOT NULL,
				PRIMARY KEY (productID, sizeID),
				FOREIGN KEY (productID) REFERENCES products(id),
				FOREIGN KEY (sizeID) REFERENCES size_def(id)
			);",
				"CREATE TABLE IF NOT EXISTS orders (
				id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				userID VARCHAR(8) NOT NULL,
				status ENUM('processing', 'dispatched') NOT NULL,
				paidAmount DECIMAL(9,2) NOT NULL,
				timeCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				timeModified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				FOREIGN KEY (userID) REFERENCES users(id)
			);",
				"CREATE TABLE IF NOT EXISTS products_in_orders (
				orderID INT(8) NOT NULL,
				productID VARCHAR(32) NOT NULL,
				sizeID INT(2) NOT NULL,
				quantity INT(2) NOT NULL,
				FOREIGN KEY (orderID) REFERENCES orders(id),
				FOREIGN KEY (productID, sizeID) REFERENCES product_sizes(productID, sizeID)
			);",
				"CREATE TABLE IF NOT EXISTS user_images (
    			id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    			filename VARCHAR(64) NOT NULL,
    			approved INT(1) NOT NULL DEFAULT 0
			);",
				"CREATE TABLE IF NOT EXISTS reviews (
				id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				name VARCHAR(100) NOT NULL,
				rating INT(1) NOT NULL,
				comment TEXT NOT NULL,
				date DATE NOT NULL,
				type ENUM('product', 'site') NOT NULL,
				productID VARCHAR(32) NULL,
				imageID INT(8) NULL,
				CONSTRAINT fk_type_product FOREIGN KEY (productID) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE,
				CONSTRAINT fk_review_image FOREIGN KEY (imageID) REFERENCES user_images(id) ON DELETE SET NULL ON UPDATE CASCADE
			);",
				"CREATE TABLE IF NOT EXISTS enquiries (
				id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				type ENUM('contact', 'refund') NOT NULL,
				nameProvided VARCHAR(100) NOT NULL,
				emailProvided VARCHAR(320) NOT NULL,
				userID VARCHAR(8) NULL,
				message TEXT NOT NULL,
				FOREIGN KEY (userID) REFERENCES users(id)
			);",
				"INSERT INTO gender_def (name) VALUES ('male'), ('female'), ('unisex')",
				"INSERT INTO type_def (name) VALUES ('tops'), ('bottoms'), ('socks'), ('shoes'), ('accessories')",
				"INSERT INTO products (id, name, type, gender) VALUES
                ('bag-bag', 'Bag Bag', 5, 3),
    			('black-socks', 'Plain Black Socks', 3, 3),
    			('conversation-high-shoes', 'Conversation High-Top Shoes', 4, 3),
    			('hardtail-shoes-men', 'Hardtail Mens Shoes', 4, 1),
    			('headfirst-jeans', 'Headfirst Jeans', 2, 3),
    			('highrise-tee-unisex', 'Highrise Unisex Top', 1, 3),
    			('mirror-cap', 'MIRЯOR Cap', 5, 3),
    			('pole-recycle-trousers', 'Recycleable Pole Trousers', 2, 3),
    			('shephard-tee-men', 'Shephard Mens Top', 1, 1),
    			('white-socks', 'Plain White Socks', 3, 3);                                 
			",
				"INSERT INTO size_def (name, isKids) VALUES
                ('XS', 0), ('S', 0), ('M', 0), ('L', 0), ('XL', 0), ('XXL', 0),
				('3-5 Years', 1), ('5-7 Years', 1), ('7-9 Years', 1), ('9-11 Years', 1), ('11-13 Years', 1)
			",
			);

			$stmt = $this->conn->prepare("SHOW TABLES");
			$stmt->execute();
			$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
			if (count($tables) > 0) return; // If there are tables, we assume the database is set up.

			foreach ($queries as $query) $this->conn->exec($query);
		} catch (Exception $e) {
			throw new Exception("Failed to set up database: " . $e->getMessage());
		}
	}

	public static function findProductImageUrl(string $productID): string {
		$pathForPhoto = "/../_images/products/" . $productID . "/";
		return file_exists(__DIR__ . $pathForPhoto) ? $pathForPhoto . scandir(__DIR__ . $pathForPhoto)[2] : "https://picsum.photos/512"; // [0] is ".", [1] is ".."
	}

	private function generateUserID(): string {
		// Keep generating a random number until we find one that doesn't exist.
		do {
			// Generate random 16-digit number and make it a string
			$randomNumber = strval(random_int(10000000, 99999999));
			// Check a user does not exist with that ID.
			$stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
			$stmt->execute([$randomNumber]);
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		} while ($results);

		return $randomNumber;
	}

	public function getUser(int $id): User | null {
		$stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
		$stmt->execute([$id]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result ? new User($result['userID'], $result['email'], $result['firstName'], $result['lastName'], $result['admin']) : null;
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
		$stmt = $this->conn->prepare("INSERT INTO users (id, email, firstName, lastName, password) VALUES (?, ?, ?, ?, ?)");
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
		} else return new User($result['id'], $result['email'], $result['firstName'], $result['lastName'], $result['admin']);
	}

    /**
     * Returns an array of sizes from a given productID.<br>Private as this should not be necessary outside of other database functions.
     * @param string $productID
     * @return Size[]
     * @see getProduct()
     */
    private function getSizesOfProduct(string $productID): array {
        $stmt = $this->conn->prepare("SELECT * FROM product_sizes INNER JOIN size_def ON product_sizes.sizeID = size_def.id WHERE productID = ?;");
        $stmt->execute([$productID]);
        $sizeResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sizes = array();
        foreach ($sizeResults as $sizeResult) {
            $sizes[] = new Size($sizeResult['sizeID'], $sizeResult['name'], $sizeResult['isKids'], $sizeResult['price']);
        }

        return $sizes;
    }

	/**
	 * Returns an array of all the products available in the database, with no filtering.
	 * @return Product[] An array of all the products.
	 */
	public function getAllProducts(): array {
		$stmt = $this->conn->prepare("SELECT products.id, products.name, type_def.name AS type, gender_def.name AS gender FROM products INNER JOIN type_def ON products.type = type_def.id INNER JOIN gender_def ON products.gender = gender_def.id;");
		$stmt->execute();
		$productResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$products = array();
		foreach ($productResults as $productResult) {
            $sizes = $this->getSizesOfProduct($productResult['id']);
			$products[] = new Product($productResult['id'], $productResult['name'], $productResult['type'], $productResult['gender'], $sizes);
		}

		return $products;
	}

	/**
	 * Returns an array of products, sorted by the latest 100 orders. Consider changing this to time based when we add that information to the db.
	 * @param int $limit The maximum number of products to return. -1 for no limit.
	 * @param bool $invert If true, the order is inverted (the least popular first)
	 * @return array An array of products, sorted by popularity.
	 */
	public function getProductsByPopularity(int $limit = -1, bool $invert = false): array {
		$stmt = $this->conn->prepare("SELECT products.id, name, type, gender, COUNT(products_in_orders.productID) as popularity FROM products_in_orders RIGHT OUTER JOIN products ON products_in_orders.productID = products.id GROUP BY id ORDER BY popularity DESC LIMIT ?;");
		$stmt->execute([$limit === -1 ? 1000 : $limit]); // Pretty sure 1000 is max MySQL supports anyway
		// Below is a duplicated code fragment. Consider moving parts to a private function interpolateSizes()
		$productResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$products = array();
		foreach ($productResults as $productResult) {
			$sizes = $this->getSizesOfProduct($productResult['id']);
			$products[] = new Product($productResult['id'], $productResult['name'], $productResult['type'], $productResult['gender'], $sizes);
		}

		return $products;
	}

	/**
	 * Returns an array of products, sorted by the newest products first. Consider changing this to time based when we add that information to the db.
	 * @param int $limit The maximum number of products to return. -1 for no limit.
	 * @param bool $invert If true, the order is inverted (oldest first)
	 * @return array An array of products, sorted by recency.
	 */
	public function getProductsByRecency(int $limit = -1, bool $invert = false): array {
		// Yes, this is completely ineffective as of right now, but it's a placeholder for when we add time to the database.
		$stmt = $this->conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT ?");
		$stmt->execute([$limit === -1 ? 1000 : $limit]);
		$productResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$products = array();
		foreach ($productResults as $productResult) {
			$sizes = $this->getSizesOfProduct($productResult['id']);
			$products[] = new Product($productResult['id'], $productResult['name'], $productResult['type'], $productResult['gender'], $sizes);
		}

		return $products;
	}

	/**
	 * Returns a product from a given productID.
	 * @param string $productID
	 * @return Product | null
	 */
	public function getProduct(string $productID): Product | null{
		$stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
		$stmt->execute([$productID]);
		$productResult = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!$productResult) return null;

		$sizes = $this->getSizesOfProduct($productResult['id']);
		return new Product($productResult['id'], $productResult['name'], $productResult['type'], $productResult['gender'], $sizes);
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

		$stmt = $this->conn->prepare("INSERT INTO products (id, name, type, gender) VALUES (?, ?, ?, ?)");
		$stmt->execute([$product->productID, $product->name, $product->type, $product->gender]);

		return true;
	}

    /**
     * Creates an enquiry made from the contact us form.
     * @return boolean If enquiry was added successfully
     */
    public function createContactEnquiry(string $name, string $email, string $message): bool {
        // Check that this isn't a duplicate entry (caused by network errors, user resubmitting by accident, etc.)
        $check = $this->conn->prepare("SELECT * FROM enquiries WHERE emailProvided = ?");
        $check->execute([$email]);
        $results = $check->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $row) {
            if ($row["name"] == $name && $row["message"] == $message) // Don't check email again.
                return false; // Do not save it again.
        }

        // Save new enquiry.
        $stmt = $this->conn->prepare("INSERT INTO enquiries (type, nameProvided, emailProvided, message) VALUES (?,?,?,?)");
        $stmt->execute(["contact", $name, $email, $message]);

        return true; // As we were successful.
    }

	/**
	 * Creates a refund request made from the refund form.
	 * @return boolean If refund request was added successfully
     * @throws Exception In case of serious error (a check for a user should've already occurred)
	 */
	public function createRefundRequest(string $userID, string $reason): bool {
		// Check that this isn't a duplicate entry (caused by network errors, user resubmitting by accident, etc.)
		$check = $this->conn->prepare("SELECT * FROM enquiries WHERE id = ?");
		$check->execute([$userID]);
		$results = $check->fetchAll(PDO::FETCH_ASSOC);

		foreach ($results as $row) {
			if ($row["message"] == $reason) // Don't check orderID again.
				return false; // Do not save it again.
		}

		$user = $this->getUser($userID);
		if (!$user) throw new Exception("User does not exist! (This shouldn't have happened!)");

		// Save new enquiry.
		$stmt = $this->conn->prepare("INSERT INTO enquiries (type, nameProvided, emailProvided, userID, message) VALUES (?,?,?,?,?)");
		$stmt->execute(["refund", $user->firstName . " " . $user->lastName, $user->email, $user->userID, $reason]);

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

	/**
	 * Gets an order by the provided orderID
	 * @param string $orderID
	 * @return mixed
	 * @note This function should update to use a class instead!
	 */
	public function getOrderByID(string $orderID): mixed {
		$stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
		$stmt->execute([$orderID]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Gets a list of all the reviews in the "reviews" table of the database.
	 * @return array Array of all reviews
	 * @note This function should update to use a class instead!
	 */
	public function getAllReviews(): array {
		$stmt = $this->conn->prepare("SELECT * FROM reviews");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Adds a review with the provided parameters.
	 * @param string $name
	 * @param int $rating
	 * @param string $comment
	 * @return bool True if succeeded successfully
	 * @throws Exception If an error occurred (and a check should've happened already)
	 */
	public function addReview(string $name, int $rating, string $comment): bool {
		if ($rating > 5 || $rating < 1) throw new Exception("Invalid rating provided!");

		$check = $this->conn->prepare("SELECT * FROM reviews WHERE name = ?");
		$check->execute([$name]);
		$reviews = $check->fetchAll(PDO::FETCH_ASSOC);
		foreach ($reviews as $review) {
			if ($review['rating'] == $rating && $review['comment'] == $comment) return false; // Duplicate entry, do not enter
		}

		$stmt = $this->conn->prepare("INSERT INTO reviews (name, rating, comment, date, type) VALUES (?, ?, ?, ?, ?)");
		$stmt->execute([$name, $rating, $comment, date("Y-m-d"), "site"]);

		return true;
	}
  
	/**
	 * Returns an array of all the types in the type_def table.
	 * @return array All the types of products available in the database.
	 */
	public function getTypes(): array {
		$stmt = $this->conn->prepare("SELECT * FROM type_def");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Returns an array of all the gender options in the gender_def table.
	 * @return array All the genders available in the database.
	 */
	public function getGenders(): array {
		$stmt = $this->conn->prepare("SELECT * FROM gender_def");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
 	 * Returns an array of all the sizes available in the size_def table.
 	 * @return Size[] All the sizes available in the database.
	 * @see getSizesOfProduct() if you're looking at product_sizes.
 	 */
	public function getSizes(): array {
		$stmt = $this->conn->prepare("SELECT * FROM size_def");
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$sizes = array();
		foreach ($result as $size) {
			$sizes[] = new Size($size['id'], $size['name'], $size['isKids'], 0);
		}
		return $sizes;
	}
  
	public function sortbyHighest(){
		$check = $this->conn->query("SELECT * FROM reviews order by rating DESC");
		return $check->fetchAll();
	} 
	public function sortbyLowest(){
		$check = $this->conn->query("SELECT * FROM reviews order by rating ASC");
		return $check->fetchAll();
	}
	public function sortbyNewest(){
		$check = $this->conn->query("SELECT * FROM reviews order by date DESC");
		return $check->fetchAll();
	}
	public function sortbyOldest(){
		$check = $this->conn->query("SELECT * FROM reviews order by date ASC");
		return $check->fetchAll();
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
		self::debug_to_console(__DIR__);
	}
}

// Tester::main();