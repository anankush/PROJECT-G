<?php
/**
 * Gift Card Functions
 * Centralized logic for retrieving and managing gift cards.
 */

/**
 * Retrieves all active gift cards from the database.
 * 
 * @param PDO|null $pdo The PDO database connection instance
 * @return array Array of associative arrays containing gift card data
 */
function getActiveGiftCards($pdo) {
    if (!$pdo) {
        return [];
    }

    try {
        // Prepare the SQL query to fetch only active gift cards
        $stmt = $pdo->prepare("SELECT * FROM gift_cards WHERE status = 'active' ORDER BY brand_name ASC");
        $stmt->execute();
        
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        // If the table doesn't exist yet or another DB error occurs, return an empty array
        // to ensure the landing page doesn't break.
        return [];
    }
}

/**
 * Retrieves a specific active gift card by its ID.
 * 
 * @param PDO|null $pdo The PDO database connection instance
 * @param int $id The ID of the gift card
 * @return array|null Associative array of the gift card data, or null if not found
 */
function getGiftCardById($pdo, $id) {
    if (!$pdo) {
        return null;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM gift_cards WHERE id = :id AND status = 'active' LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    } catch (PDOException $e) {
        return null;
    }
}
?>