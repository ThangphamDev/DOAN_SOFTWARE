<?php
class OrderItem {
    private $conn;
    private $table_name = "orderitems";

    // Properties
    public $order_item_id;
    public $order_id;
    public $product_id;
    public $variant_id;
    public $quantity;
    public $unit_price;
    public $notes;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new order item
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (order_id, product_id, variant_id, quantity, unit_price, notes)
                VALUES
                (:order_id, :product_id, :variant_id, :quantity, :unit_price, :notes)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->order_id = htmlspecialchars(strip_tags($this->order_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->variant_id = isset($this->variant_id) ? htmlspecialchars(strip_tags($this->variant_id)) : null;
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->unit_price = htmlspecialchars(strip_tags($this->unit_price));
        $this->notes = isset($this->notes) ? htmlspecialchars(strip_tags($this->notes)) : null;

        // Ensure quantity is positive
        $this->quantity = max(1, intval($this->quantity));

        // Bind values
        $stmt->bindParam(":order_id", $this->order_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":variant_id", $this->variant_id);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":unit_price", $this->unit_price);
        $stmt->bindParam(":notes", $this->notes);

        // Execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read items by order ID
    public function readByOrder() {
        $query = "SELECT oi.*, p.name as product_name, p.image_url, pv.variant_value as size
                FROM " . $this->table_name . " oi
                LEFT JOIN products p ON oi.product_id = p.product_id
                LEFT JOIN productvariants pv ON oi.variant_id = pv.variant_id
                WHERE oi.order_id = ?
                ORDER BY oi.order_item_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->order_id);
        $stmt->execute();

        return $stmt;
    }
} 