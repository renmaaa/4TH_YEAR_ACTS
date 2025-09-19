<?php  
/**
 * Class for handling Subcategory-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Subcategory extends Database {

    /**
     * Creates a new Subcategory.
     * @param int $category_id The ID of the parent category.
     * @param string $subcategory_name The name of the subcategory.
     * @return bool True on success, false on failure.
     */
    public function createSubcategory($category_id, $subcategory_name) {
        $sql = "INSERT INTO subcategories (category_id, subcategory_name) VALUES (?, ?)";
        return $this->executeNonQuery($sql, [$category_id, $subcategory_name]);
    }

    /**
     * Retrieves subcategories from the database.
     * @param int|null $id The subcategory ID to retrieve, or null for all subcategories.
     * @param int|null $category_id The parent category ID to filter by.
     * @return array
     */
    public function getSubcategories($id = null, $category_id = null) {
        $sql = "SELECT * FROM subcategories";
        $params = [];

        if ($id) {
            $sql .= " WHERE subcategory_id = ?";
            $params[] = $id;
            return $this->executeQuerySingle($sql, $params);
        } elseif ($category_id) {
            $sql .= " WHERE category_id = ?";
            $params[] = $category_id;
        }
        $sql .= " ORDER BY subcategory_name ASC";
        return $this->executeQuery($sql, $params);
    }

    /**
     * Updates a subcategory.
     * @param string $subcategory_name The new subcategory name.
     * @param int $subcategory_id The ID of the subcategory to update.
     * @return int The number of affected rows.
     */
    public function updateSubcategory($subcategory_name, $subcategory_id) {
        $sql = "UPDATE subcategories SET subcategory_name = ? WHERE subcategory_id = ?";
        return $this->executeNonQuery($sql, [$subcategory_name, $subcategory_id]);
    }

    /**
     * Deletes a subcategory.
     * @param int $id The subcategory ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteSubcategory($id) {
        $sql = "DELETE FROM subcategories WHERE subcategory_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>