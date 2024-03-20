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
				$autoloadPath = __DIR__ . "/../vendor/autoload.php";
				if (file_exists($autoloadPath)) {
					require_once $autoloadPath;
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
	public int $stock;

	public function __construct(string $sizeID, string $name, int $isKids, float $price, int $stock) {
		$this->sizeID = $sizeID;
		$this->name = $name;
		$this->isKids = $isKids === 1;
		$this->price = $price;
		$this->stock = $stock;
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
	public string $description;
	public bool $isSustainable;
	public array $sizes;

	public function __construct(string $productID, string $name, string $type, string $gender, string|null $description, int $isSustainable, array $sizes = null) {
		$this->productID = $productID;
		$this->name = $name;
		$this->type = $type;
		$this->gender = $gender;
		$this->description = $description ?? '';
		$this->isSustainable = $isSustainable === 1;
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
				"CREATE TABLE IF NOT EXISTS addresses (
    			id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    			name VARCHAR(128) NOT NULL,
    			line1 VARCHAR(128) NOT NULL,
    			line2 VARCHAR(128) NULL DEFAULT NULL,
    			line3 VARCHAR(128) NULL DEFAULT NULL,
    			city VARCHAR(64) NOT NULL,
    			postcode VARCHAR(10) NOT NULL,
    			country VARCHAR(64) NOT NULL
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
				description TEXT NULL DEFAULT NULL,
				isSustainable INT(1) NOT NULL DEFAULT 0,
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
				stock INT(4) NOT NULL DEFAULT 0,
				PRIMARY KEY (productID, sizeID),
				FOREIGN KEY (productID) REFERENCES products(id),
				FOREIGN KEY (sizeID) REFERENCES size_def(id)
			);",
				"CREATE TABLE IF NOT EXISTS orders (
				id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				userID VARCHAR(8) NOT NULL,
				addressID INT(8) NOT NULL,
				direction ENUM('in', 'out') NOT NULL,
				status ENUM('processing', 'dispatched', 'arrived') NOT NULL,
				paidAmount DECIMAL(9,2) NOT NULL,
				timeCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				timeModified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				FOREIGN KEY (userID) REFERENCES users(id),
				FOREIGN KEY (addressID) REFERENCES addresses(id)
			) AUTO_INCREMENT=10000000;",
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
				"CREATE TABLE IF NOT EXISTS alerts (
    			id INT(8) NOT NULL PRIMARY KEY AUTO_INCREMENT,
				userID VARCHAR(8) NOT NULL,
				productID VARCHAR(32) NOT NULL,
				FOREIGN KEY (userID) REFERENCES users(id),
				FOREIGN KEY (productID) REFERENCES products(id)
			);",
				"CREATE TABLE IF NOT EXISTS alert_methods (
				alertID INT(8) NOT NULL,
				threshold INT(4) NOT NULL,
				byEmail INT(1) NOT NULL DEFAULT 0,
				bySMS INT(1) NOT NULL DEFAULT 0,
				bySite INT(1) NOT NULL DEFAULT 0,
				FOREIGN KEY (alertID) REFERENCES alerts(id),
				PRIMARY KEY (alertID, threshold)
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
				"CREATE TRIGGER update_stock_after_order_update
				    AFTER UPDATE ON products_in_orders
				    FOR EACH ROW
				BEGIN
				    DECLARE in_quantity INT DEFAULT 0;
				    DECLARE out_quantity INT DEFAULT 0;
				
				    SELECT IFNULL(SUM(quantity), 0) INTO in_quantity
				    FROM products_in_orders INNER JOIN orders ON products_in_orders.orderID = orders.id
				    WHERE direction = 'in'
				      AND status = 'arrived'
				      AND productID = NEW.productID
				      AND sizeID = NEW.sizeID;
				
				    SELECT IFNULL(SUM(quantity), 0) INTO out_quantity
				    FROM products_in_orders INNER JOIN orders ON products_in_orders.orderID = orders.id
				    WHERE direction = 'out'
				      AND productID = NEW.productID
				      AND sizeID = NEW.sizeID;
				
				    UPDATE product_sizes
				    SET stock = in_quantity - out_quantity
				    WHERE productID = NEW.productID
				      AND sizeID = NEW.sizeID;
				END;
				
				CREATE TRIGGER update_stock_after_order_insert
				    AFTER INSERT ON products_in_orders
				    FOR EACH ROW
				BEGIN
				    DECLARE in_quantity INT DEFAULT 0;
				    DECLARE out_quantity INT DEFAULT 0;
				
				    SELECT IFNULL(SUM(quantity), 0) INTO in_quantity
				    FROM products_in_orders INNER JOIN orders ON products_in_orders.orderID = orders.id
				    WHERE direction = 'in'
				      AND status = 'arrived'
				      AND productID = NEW.productID
				      AND sizeID = NEW.sizeID;
				
				    SELECT IFNULL(SUM(quantity), 0) INTO out_quantity
				    FROM products_in_orders INNER JOIN orders ON products_in_orders.orderID = orders.id
				    WHERE direction = 'out'
				      AND productID = NEW.productID
				      AND sizeID = NEW.sizeID;
				
				    UPDATE product_sizes
				    SET stock = in_quantity - out_quantity
				    WHERE productID = NEW.productID
				      AND sizeID = NEW.sizeID;
				END;
				
				CREATE TRIGGER update_stock_after_order_delete
				    AFTER DELETE ON products_in_orders
				    FOR EACH ROW
				BEGIN
				    DECLARE in_quantity INT DEFAULT 0;
				    DECLARE out_quantity INT DEFAULT 0;
				
				    SELECT IFNULL(SUM(quantity), 0) INTO in_quantity
				    FROM products_in_orders INNER JOIN orders ON products_in_orders.orderID = orders.id
				    WHERE direction = 'in'
				      AND status = 'arrived'
				      AND productID = OLD.productID
				      AND sizeID = OLD.sizeID;
				
				    SELECT IFNULL(SUM(quantity), 0) INTO out_quantity
				    FROM products_in_orders INNER JOIN orders ON products_in_orders.orderID = orders.id
				    WHERE direction = 'out'
				      AND productID = OLD.productID
				      AND sizeID = OLD.sizeID;
				
				    UPDATE product_sizes
				    SET stock = in_quantity - out_quantity
				    WHERE productID = OLD.productID
				      AND sizeID = OLD.sizeID;
				END;
			"
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

	public static function findPrimaryProductImageUrl(string $productID): string {
		$pathForPhoto = "/../_images/products/" . $productID . "/";
		return file_exists(__DIR__ . $pathForPhoto) && count(scandir(__DIR__ . $pathForPhoto)) > 2 ? $pathForPhoto . scandir(__DIR__ . $pathForPhoto)[2] : "https://picsum.photos/512"; // [0] is ".", [1] is ".."
	}
	public static function findAllProductImageUrls(string $productID): array {
		$pathForPhoto = "/../_images/products/" . $productID . "/";
		if (!is_dir(__DIR__ . $pathForPhoto)) return array();

		$images = scandir(__DIR__ . $pathForPhoto);
		$images = array_filter($images, function ($image) {
			return $image !== "." && $image !== "..";
		});

		return array_map(function ($image) use ($pathForPhoto) {
			return $pathForPhoto . $image;
		}, $images);
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
            $sizes[$sizeResult['sizeID']] = new Size($sizeResult['sizeID'], $sizeResult['name'], $sizeResult['isKids'], $sizeResult['price'], $sizeResult['stock']);
        }

        return $sizes;
    }

	/**
	 * Returns an array of all the products available in the database, with no filtering.
	 * @return Product[] An array of all the products.
	 */
	public function getAllProducts(): array {
		$stmt = $this->conn->prepare("SELECT products.id, products.name, products.description, products.isSustainable, type_def.name AS type, gender_def.name AS gender FROM products INNER JOIN type_def ON products.type = type_def.id INNER JOIN gender_def ON products.gender = gender_def.id;");
		$stmt->execute();
		$productResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$products = array();
		foreach ($productResults as $productResult) {
            $sizes = $this->getSizesOfProduct($productResult['id']);
			$products[] = new Product($productResult['id'], $productResult['name'], $productResult['type'], $productResult['gender'], $productResult['description'], $productResult['isSustainable'], $sizes);
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
		$stmt = $this->conn->prepare("SELECT products.id, name, description, type, gender, isSustainable, COUNT(products_in_orders.productID) as popularity FROM products_in_orders RIGHT OUTER JOIN products ON products_in_orders.productID = products.id GROUP BY id ORDER BY popularity DESC LIMIT ?;");
		$stmt->execute([$limit === -1 ? 1000 : $limit]); // Pretty sure 1000 is max MySQL supports anyway
		// Below is a duplicated code fragment. Consider moving parts to a private function interpolateSizes()
		$productResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$products = array();
		foreach ($productResults as $productResult) {
			$sizes = $this->getSizesOfProduct($productResult['id']);
			$products[] = new Product($productResult['id'], $productResult['name'], $productResult['type'], $productResult['gender'], $productResult['description'], $productResult['isSustainable'], $sizes);
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
			$products[] = new Product($productResult['id'], $productResult['name'], $productResult['type'], $productResult['gender'], $productResult['description'], $productResult['isSustainable'], $sizes);
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
		return new Product($productResult['id'], $productResult['name'], $productResult['type'], $productResult['gender'], $productResult['description'], $productResult['isSustainable'], $sizes);
	}

	/**
	 * Returns the history of stock for a given product.
	 * @param string $productID
	 * @param string $period Expects "month" / "year" / "all"
	 * @return array An array of stock history.
	 * @throws Exception If there is a problem in getting stock history.
	 */
	public function getProductStockHistory(string $productID, string $period): array {
		if (!in_array($period, ["month", "year", "all"])) throw new Exception("Invalid period for stock history: " . $period);
		$dateLimit = match ($period) {
			"year" => "DATE_SUB(NOW(), INTERVAL 1 YEAR)",
			"all" => "DATE_SUB(NOW(), INTERVAL 100 YEAR)",
			default => "DATE_SUB(NOW(), INTERVAL 1 MONTH)" // includes "month"
		};

		$stmt = $this->conn->prepare("SELECT orders.timeCreated, orders.direction, products_in_orders.quantity FROM products_in_orders INNER JOIN orders ON products_in_orders.orderID = orders.id WHERE productID = ? AND orders.timeCreated > " . $dateLimit . " ORDER BY orders.timeCreated DESC;");
		$stmt->execute([$productID]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Returns the history of stock for the entire site.
	 * @return array An array of stock history
	 * @note This will only be 30 days due to the load this could cause on the database.
	 */
	public function getSiteStockHistory(): array {
		$stmt = $this->conn->prepare("SELECT orders.timeCreated, orders.direction, products_in_orders.productID, products_in_orders.sizeID, products_in_orders.quantity FROM products_in_orders INNER JOIN orders ON products_in_orders.orderID = orders.id WHERE orders.timeCreated > DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY orders.timeCreated DESC;");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Get the lowest stock from across the site
	 * @param int $limit How many to return
	 * @return array
	 */
	public function getLowestStock(int $limit): array {
		// Find the products with the lowest stock
		if (is_nan($limit)) return [];
		$stmt = $this->conn->prepare("SELECT * FROM products INNER JOIN product_sizes on products.id = product_sizes.productID ORDER BY stock LIMIT " . $limit);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Creates a product with the provided product
	 * @param Product $product
	 * @return bool Returns true if product added successfully.
	 */
	public function createProduct(Product $product): bool {// Get the ID of the type and gender from the product class (as they are strings)
		$stmt = $this->conn->prepare("SELECT id FROM type_def WHERE name = ?");
		$stmt->execute([$product->type]);
		$typeID = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

		$stmt = $this->conn->prepare("SELECT id FROM gender_def WHERE name = ?");
		$stmt->execute([$product->gender]);
		$genderID = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

		// Change isSustainable to 1 or 0
		$isSustainable = $product->isSustainable ? 1 : 0;

		$stmt = $this->conn->prepare("INSERT INTO products (id, name, description, type, gender, isSustainable) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->execute([$product->productID, $product->name, $product->description, $typeID, $genderID, $isSustainable]);

		foreach ($product->sizes as $size) {
			$stmt = $this->conn->prepare("INSERT INTO product_sizes (productID, sizeID, price) VALUES (?, ?, ?)");
			$stmt->execute([$product->productID, $size->sizeID, $size->price]);
		}

		return true;
	}

	/**
	 * Updates a product with the provided product
	 * @param Product $product
	 * @return bool Returns true if product updated successfully.
	 * @throws Exception If there is a problem in updating a product.
	 */
	public function updateProduct(Product $product): bool {
		// First, delete all the sizes for the product. It's just easier this way
		$stmt = $this->conn->prepare("DELETE FROM product_sizes WHERE productID = ?");
		$stmt->execute([$product->productID]);

		// Then, re-add all the sizes for the product.
		foreach ($product->sizes as $size) {
			$stmt = $this->conn->prepare("INSERT INTO product_sizes (productID, sizeID, price) VALUES (?, ?, ?)");
			$stmt->execute([$product->productID, $size->sizeID, $size->price]);
		}

		// Get the ID of the type and gender from the product class (as they are strings)
		$stmt = $this->conn->prepare("SELECT id FROM type_def WHERE name = ?");
		$stmt->execute([$product->type]);
		$typeID = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

		$stmt = $this->conn->prepare("SELECT id FROM gender_def WHERE name = ?");
		$stmt->execute([$product->gender]);
		$genderID = $stmt->fetch(PDO::FETCH_ASSOC)['id'];

		// Change isSustainable to 1 or 0
		$isSustainable = $product->isSustainable ? 1 : 0;

		// And finally, update the product.
		$stmt = $this->conn->prepare("UPDATE products SET name = ?, type = ?, gender = ?, description = ?, isSustainable = ? WHERE id = ?");
		$stmt->execute([$product->name, $typeID, $genderID, $product->description, $isSustainable, $product->productID]);

		return true;
	}

	/**
	 * Deletes a product by the productID
	 * @param string $id
	 * @return bool Returns true if product was deleted successfully
	 */
	public function deleteProduct(string $id): bool {
		// ProductIDs appear everywhere. If we don't delete the other fields, we'll be blocked from doing so.
		// Check product exists
		$stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
		$stmt->execute([$id]);
		$product = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$product) return false;

		// Replace IDs in products_in_orders to NULL
		$stmt = $this->conn->prepare("UPDATE products_in_orders SET productID = NULL WHERE productID = ?");
		$stmt->execute([$id]);

		// Delete product_sizes
		$stmt = $this->conn->prepare("DELETE FROM product_sizes WHERE productID = ?");
		$stmt->execute([$id]);

		// Delete reviews that refer to this productID
		$stmt = $this->conn->prepare("DELETE FROM reviews WHERE productID = ?");
		$stmt->execute([$id]);

		// Then finally, delete the product.
		$stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
		$stmt->execute([$id]);

		return true;
	}

	/**
	 * Changes the ID of a product.
	 * @param string $oldID
	 * @param string $newID
	 * @return bool Returns true if productID updated successfully.
	 */
	public function changeProductID(string $oldID, string $newID): bool {
		$stmt = $this->conn->prepare("UPDATE products SET id = ? WHERE id = ?");
		$stmt->execute([$newID, $oldID]);
		return true;
	}

	/**
	 * Check if provided productID doesn't already exist, and is valid.
	 * @param string $productID ProductID to validate
	 * @return bool Returns true if valid
	 */
	public function validateProductID(string $productID): bool {
		// First we want to check if the productID only contains alphabetical characters and dashes.
		if (!preg_match("/^[a-zA-Z-]+$/", $productID)) return false;

		// Then we want to check if the productID already exists.
		$stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
		$stmt->execute([$productID]);
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return count($results) === 0;
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
            if ($row["nameProvided"] == $name && $row["message"] == $message) // Don't check email again.
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
	 * Creates an address with the provided parameters, or returns the existing ID if an exact address already exists.
	 * @param string $name Required, name on address (max 128 characters)
	 * @param string $line1 Required, first line of address (max 128 characters)
	 * @param string $line2 Optional, second line of address (max 128 characters)
	 * @param string $line3 Optional, third line of address (max 128 characters)
	 * @param string $city Required, city of address (max 64 characters)
	 * @param string $postcode Required, postcode of address (max 10 characters)
	 * @param string $country Required, country of address (max 64 characters)
	 * @return int The addressID of the address created or found.
	 * @see getAddressDetails() For getting the details of an address. This will only return an ID
	 */
	public function createOrGetAddress(string $name, string $line1, string $line2, string $line3, string $city, string $postcode, string $country): int {
		// Check for existing address
		$stmt = $this->conn->prepare("SELECT id FROM addresses WHERE name = ? AND line1 = ? AND line2 = ? AND line3 = ? AND city = ? AND postcode = ? AND country = ?");
		$stmt->execute([$name, $line1, $line2, $line3, $city, $postcode, $country]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($result) return $result['id'];

		// Doesn't exist, so we make a new one.
		$stmt = $this->conn->prepare("INSERT INTO addresses (name, line1, line2, line3, city, postcode, country) VALUES (?, ?, ?, ?, ?, ?, ?)");
		$stmt->execute([$name, $line1, $line2, $line3, $city, $postcode, $country]);
		return $this->conn->lastInsertId();
	}

	/**
	 * Gets the details of an address by the provided addressID
	 * @param int $addressID
	 * @return array An array of the address details
	 */
	public function getAddressDetails(int $addressID): array {
		$stmt = $this->conn->prepare("SELECT * FROM addresses WHERE id = ?");
		$stmt->execute([$addressID]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Creates an order with the provided userID and basket
	 * @param string $userID
	 * @param Product[] $basket
	 * @param array $quantityMap A map/associative array of productID to quantity
	 * @param int $addressID The addressID of the address that should already be in the database.
	 * @param string $direction The direction of the order. Default is "out".
	 * @param string $status The status of the order. Default is "processing".
	 * @return string OrderID of the order created.
	 * @see createOrGetAddress() For creating an address
	 */
	public function createOrder(string $userID, array $basket, array $quantityMap, int $addressID, string $direction = "out", string $status = "processing"): string {
		// Create order
		$totalPrice = 0;
		$productsInOrdersQueue = array(); // Used to store the insertion of products into the products_in_orders table.

		// Fixes __PHP_Incomplete_Class_Name. Did you know I dislike PHP? - Pawel
		$basket = array_map(function ($item) {
			return unserialize(serialize($item));
		}, $basket);

		foreach	($basket as $item) {
			/* @var $item Product */
			$totalPrice += $item->sizes[0]->price * $quantityMap[$item->productID][$item->sizes[0]->sizeID];
			$productsInOrdersQueue[] = [$item->productID, $item->sizes[0]->sizeID, $quantityMap[$item->productID][$item->sizes[0]->sizeID]];
		}

		$stmt = $this->conn->prepare("INSERT INTO orders (userID, addressID, direction, status, paidAmount) VALUES (?, ?, ?, ?, ?)");
		$stmt->execute([$userID, $addressID, $direction, $status, $totalPrice]);
		$orderID = $this->conn->lastInsertId();

		// Create products in orders
		foreach ($productsInOrdersQueue as $productInOrder) {
			$stmt = $this->conn->prepare("INSERT INTO products_in_orders (orderID, productID, sizeID, quantity) VALUES (?, ?, ?, ?)");
			$stmt->execute([$orderID, $productInOrder[0], $productInOrder[1], $productInOrder[2]]);
		}

		return $orderID;
	}

	/**
	 * Updates an order by replacing the products in the order, and updating the order itself.
	 * @param string $orderID
	 * @param array $basket
	 * @param array $quantityMap
	 * @param int $addressID
	 * @param string $direction
	 * @param string $status
	 * @return bool Returns true if order updated successfully.
	 * @see createOrGetAddress() For creating an address
	 */
	public function updateOrder(string $orderID, array $basket, array $quantityMap, int $addressID, string $direction, string $status): bool {
		$totalPrice = 0;
		$productsInOrdersQueue = array(); // Used to store the insertion of products into the products_in_orders table.

		// In case it happens again
		$basket = array_map(function ($item) {
			return unserialize(serialize($item));
		}, $basket);

		foreach	($basket as $item) {
			/* @var $item Product */
			$totalPrice += $item->sizes[0]->price * $quantityMap[$item->productID][$item->sizes[0]->sizeID];
			$productsInOrdersQueue[] = [$item->productID, $item->sizes[0]->sizeID, $quantityMap[$item->productID][$item->sizes[0]->sizeID]];
		}

		// Delete and reinsert products in order.
		$stmt = $this->conn->prepare("DELETE FROM products_in_orders WHERE orderID = ?");
		$stmt->execute([$orderID]);
		$stmt = $this->conn->prepare("INSERT INTO products_in_orders (orderID, productID, sizeID, quantity) VALUES (?, ?, ?, ?)");
		foreach ($productsInOrdersQueue as $productInOrder) {
			$stmt->execute([$orderID, $productInOrder[0], $productInOrder[1], $productInOrder[2]]);
		}

		// Update order itself
		$stmt = $this->conn->prepare("UPDATE orders SET addressID = ?, direction = ?, status = ?, paidAmount = ? WHERE id = ?");
		$stmt->execute([$addressID, $direction, $status, $totalPrice, $orderID]);

		return true;
	}

	/**
	 * Deletes an order by the provided orderID
	 * @param string $orderID
	 * @return bool Returns true if order deleted successfully
	 */
	public function deleteOrder(string $orderID): bool {
		// Delete products in order
		$stmt = $this->conn->prepare("DELETE FROM products_in_orders WHERE orderID = ?");
		$stmt->execute([$orderID]);

		// Then finally, delete the order.
		$stmt = $this->conn->prepare("DELETE FROM orders WHERE id = ?");
		$stmt->execute([$orderID]);

		return true;
	}

	/**
	 * Gets an order by the provided orderID
	 * @param string $orderID
	 * @return mixed
	 */
	public function getOrderByID(string $orderID): mixed {
		$stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
		$stmt->execute([$orderID]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Gets all orders.
	 * @return array Array of all orders
	 */
	public function getAllOrders(): array {
		$stmt = $this->conn->prepare("SELECT * FROM orders");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Returns the amount of products in the order
	 * @param string $orderID Which order to access
	 * @return int Quantity of products in order
	 */
	public function getQuantityProductsInOrder(string $orderID): int {
		$stmt = $this->conn->prepare("SELECT SUM(quantity) FROM products_in_orders WHERE orderID = ?");
		$stmt->execute([$orderID]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC)['SUM(quantity)'];
		if (!$result) return 0;
		else return $result;
	}

	/**
	 * Returns an array of all the products in the order.
	 * @param string $id The orderID to access
	 * @return array An array of all the products in the order.
	 */
	public function getProductsInOrder(string $id): array {
		$stmt = $this->conn->prepare("SELECT * FROM products_in_orders WHERE orderID = ?");
		$stmt->execute([$id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Gets a list of all the reviews in the "reviews" table of the database.
	 * @return array Array of all reviews
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
			$sizes[] = new Size($size['id'], $size['name'], $size['isKids'], 0, 0);
		}
		return $sizes;
	}

	/**
	 * Returns the alert information, with an extra field "thresholds" which is an array of all the thresholds + methods for the alert.
	 * @param string $alertID
	 * @return array
	 */
	public function getAlert(string $alertID): array {
		$stmt = $this->conn->prepare("SELECT * FROM alerts WHERE id = ?");
		$stmt->execute([$alertID]);
		$alert = $stmt->fetch(PDO::FETCH_ASSOC);
		return $this->mapThresholdsToAlert($alert);
	}

	/**
	 * Returns all alerts with the provided userID, with the extra field "thresholds"
	 * @param string $userID
	 * @return array An array of alerts
	 * @see getAlert() for fetching a specific alert.
	 */
	public function getAlertsByAuthor(string $userID): array {
		$stmt = $this->conn->prepare("SELECT * FROM alerts WHERE userID = ?");
		$stmt->execute([$userID]);
		$alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach ($alerts as &$alert) {
			$alert = $this->mapThresholdsToAlert($alert);
		}

		return $alerts;
	}

	public function getAlertByProduct(string $userID, string $productID): array | null {
		$stmt = $this->conn->prepare("SELECT * FROM alerts WHERE userID = ? AND productID = ?");
		$stmt->execute([$userID, $productID]);
		$alert = $stmt->fetch(PDO::FETCH_ASSOC);
		return $alert ? $this->mapThresholdsToAlert($alert) : null;
	}

	/**
	 * Adds the thresholds to an alert, and returns the alert with the thresholds.
	 * @param array $alert
	 * @return array $alert but with thresholds added.
	 */
	private function mapThresholdsToAlert(array $alert): array {
		// Using this approach instead of an INNER JOIN because it's cleaner,
		// and we don't need to worry about the alert not having any thresholds.
		$stmt = $this->conn->prepare("SELECT * FROM alert_methods WHERE alertID = ? ORDER BY threshold");
		$stmt->execute([$alert['id']]);
		$thresholds = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$alert['thresholds'] = $thresholds;
		return $alert;
	}

	/**
	 * Creates a new alert with the provided parameters.
	 * @param string $userID The userID to create the alert for
	 * @param string $productID The productID to create the alert for
	 * @param array $thresholds An array of thresholds, each with a threshold (number), byEmail, bySMS, and bySite (booleans)
	 * @return bool Returns true if alert added successfully
	 */
	public function createAlert(string $userID, string $productID, array $thresholds): bool {
		// First, check an alert doesn't already exist with this user and product IDs
		$stmt = $this->conn->prepare("SELECT * FROM alerts WHERE userID = ? AND productID = ?");
		$stmt->execute([$userID, $productID]);
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($results) return false;

		// Then, create the alert
		$stmt = $this->conn->prepare("INSERT INTO alerts (userID, productID) VALUES (?, ?)");
		$stmt->execute([$userID, $productID]);
		$alertID = $this->conn->lastInsertId();

		// With $alertID, create the thresholds
		$stmt = $this->conn->prepare("INSERT INTO alert_methods (alertID, threshold, byEmail, bySMS, bySite) VALUES (?, ?, ?, ?, ?)");
		foreach ($thresholds as $threshold) {
			$stmt->execute([$alertID, $threshold['value'], $threshold['email'] ? 1 : 0, $threshold['sms'] ? 1 : 0, $threshold['site'] ? 1 : 0]);
		}

		return true;
	}

	/**
	 * Updates an alert with the provided parameters.
	 * @param string $id
	 * @param string $userID
	 * @param string $productID
	 * @param array $thresholds
	 * @return bool
	 */
	public function updateAlert(string $id, string $userID, string $productID, array $thresholds): bool {
		// First, check an alert doesn't already exist with this user and product IDs
		$stmt = $this->conn->prepare("SELECT * FROM alerts WHERE userID = ? AND productID = ? AND id != ?");
		$stmt->execute([$userID, $productID, $id]);
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($results) return false;

		// Then, update the alert
		$stmt = $this->conn->prepare("UPDATE alerts SET userID = ?, productID = ? WHERE id = ?");
		$stmt->execute([$userID, $productID, $id]);

		// With $alertID, delete the old thresholds and create the new ones
		$stmt = $this->conn->prepare("DELETE FROM alert_methods WHERE alertID = ?");
		$stmt->execute([$id]);
		$stmt = $this->conn->prepare("INSERT INTO alert_methods (alertID, threshold, byEmail, bySMS, bySite) VALUES (?, ?, ?, ?, ?)");
		foreach ($thresholds as $threshold) {
			$stmt->execute([$id, $threshold['value'], $threshold['email'] ? 1 : 0, $threshold['sms'] ? 1 : 0, $threshold['site'] ? 1 : 0]);
		}

		return true;
	}

	/**
	 * Deletes the alert and alert methods from the database
	 * @param string $alertID
	 * @return bool If successful
	 */
	public function deleteAlert(string $alertID): bool {
		// First, delete methods
		$stmt = $this->conn->prepare("DELETE FROM alert_methods WHERE alertID = ?");
		$stmt->execute([$alertID]);
		// Then the alert itself
		$stmt = $this->conn->prepare("DELETE FROM alerts WHERE id = ?");
		$stmt->execute([$alertID]);

		return true;
	}

	public function sortByHighest(): array {
		$check = $this->conn->query("SELECT * FROM reviews order by rating DESC");
		return $check->fetchAll();
	} 
	public function sortByLowest(): array {
		$check = $this->conn->query("SELECT * FROM reviews order by rating");
		return $check->fetchAll();
	}
	public function sortByNewest(): array {
		$check = $this->conn->query("SELECT * FROM reviews order by date DESC");
		return $check->fetchAll();
	}
	public function sortByOldest(): array {
		$check = $this->conn->query("SELECT * FROM reviews order by date");
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
