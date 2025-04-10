<?php
class Cart {
    private $conn;
    private $table = 'cart_items';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addToCart($userId, $productId, $variantId = null, $quantity = 1) {
        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $existingItem = $this->getCartItem($userId, $productId, $variantId);
        
        if ($existingItem) {
            // Nếu đã có, cập nhật số lượng
            $newQuantity = $existingItem['quantity'] + $quantity;
            return $this->updateCartItemQuantity($existingItem['cart_item_id'], $newQuantity);
        } else {
            // Nếu chưa có, thêm mới
            $query = "INSERT INTO " . $this->table . " 
                    (user_id, product_id, variant_id, quantity) 
                    VALUES (:user_id, :product_id, :variant_id, :quantity)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':variant_id', $variantId);
            $stmt->bindParam(':quantity', $quantity);

            return $stmt->execute();
        }
    }

    public function getCartItem($userId, $productId, $variantId = null) {
        $query = "SELECT * FROM " . $this->table . " 
                WHERE user_id = :user_id 
                AND product_id = :product_id 
                AND variant_id " . ($variantId ? "= :variant_id" : "IS NULL");

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        if ($variantId) {
            $stmt->bindParam(':variant_id', $variantId);
        }

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCartItemQuantity($cartItemId, $quantity) {
        $query = "UPDATE " . $this->table . " 
                SET quantity = :quantity 
                WHERE cart_item_id = :cart_item_id";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':cart_item_id', $cartItemId);

        return $stmt->execute();
    }

    public function removeFromCart($cartItemId, $userId) {
        $query = "DELETE FROM " . $this->table . " 
                WHERE cart_item_id = :cart_item_id 
                AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':cart_item_id', $cartItemId);
        $stmt->bindParam(':user_id', $userId);

        return $stmt->execute();
    }

    public function getCartItems($userId) {
        $query = "SELECT ci.*, p.name, p.image_url, p.base_price, 
                        pv.variant_value, pv.additional_price
                FROM " . $this->table . " ci
                JOIN products p ON ci.product_id = p.product_id
                LEFT JOIN productvariants pv ON ci.variant_id = pv.variant_id
                WHERE ci.user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function clearCart($userId) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    public function getCartTotal($userId) {
        $query = "SELECT SUM((p.base_price + COALESCE(pv.additional_price, 0)) * ci.quantity) as total
                FROM " . $this->table . " ci
                JOIN products p ON ci.product_id = p.product_id
                LEFT JOIN productvariants pv ON ci.variant_id = pv.variant_id
                WHERE ci.user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
} 