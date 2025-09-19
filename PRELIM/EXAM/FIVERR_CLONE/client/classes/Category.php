<?php  
/**
 * Class for handling Category-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Category extends Database {

    /**
     * Creates a new Category.
     * @param string $category_name The name of the category.
     * @return bool True on success, false on failure.
     */
    public function createCategory($category_name) {
        $sql = "INSERT INTO categories (category_name) VALUES (?)";
        return $this->executeNonQuery($sql, [$category_name]);
    }

    /**
     * Retrieves categories from the database.
     * @param int|null $id The category ID to retrieve, or null for all categories.
     * @return array
     */
    public function getCategories($id = null) {
        if ($id) {
            $sql = "SELECT * FROM categories WHERE category_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM categories ORDER BY category_name ASC";
        return $this->executeQuery($sql);
    }

    /**
     * Updates a category.
     * @param string $category_name The new category name.
     * @param int $category_id The ID of the category to update.
     * @return int The number of affected rows.
     */
    public function updateCategory($category_name, $category_id) {
        $sql = "UPDATE categories SET category_name = ? WHERE category_id = ?";
        return $this->executeNonQuery($sql, [$category_name, $category_id]);
    }

    /**
     * Deletes a category.
     * @param int $id The category ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteCategory($id) {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>