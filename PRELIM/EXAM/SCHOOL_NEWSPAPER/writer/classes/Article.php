<?php
require_once 'database.php';

class Article extends Database {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createArticle($title, $content, $author_id, $image_path = null, $is_active = 1, $category_id = null) {
        $sql = "INSERT INTO articles (title, content, author_id, is_active, image_path, category_id) VALUES (?, ?, ?, ?, ?, ?)";
        return $this->executeNonQuery($sql, [$title, $content, $author_id, $is_active, $image_path, $category_id]);
    }

    public function updateArticle($id, $title, $content, $image_path = null, $category_id = null) {
        $sql = "UPDATE articles SET title = ?, content = ?, image_path = ?, category_id = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$title, $content, $image_path, $category_id, $id]);
    }

    public function getArticles($id = null) {
        if ($id) {
            $sql = "SELECT a.*, c.category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.category_id WHERE a.article_id = ?";
            return $this->executeQuery($sql, [$id])[0] ?? null;
        }
        $sql = "SELECT a.*, spu.username, spu.is_admin, c.category_name FROM articles a 
                JOIN school_publication_users spu ON a.author_id = spu.user_id 
                LEFT JOIN categories c ON a.category_id = c.category_id
                ORDER BY a.created_at DESC";
        return $this->executeQuery($sql);
    }

    public function deleteArticle($id) {
        $sql = "DELETE FROM articles WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }

    public function createEditRequest($article_id, $requester_id, $proposed_title, $proposed_content) {
        $sql = "INSERT INTO article_edit_requests (article_id, requester_id, proposed_title, proposed_content) VALUES (?, ?, ?, ?)";
        return $this->executeNonQuery($sql, [$article_id, $requester_id, $proposed_title, $proposed_content]);
    }

    public function getEditRequestsForArticle($article_id) {
        $sql = "SELECT aer.*, spu.username as requester_username FROM article_edit_requests aer
                JOIN school_publication_users spu ON aer.requester_id = spu.user_id
                WHERE aer.article_id = ? ORDER BY requested_at DESC";
        return $this->executeQuery($sql, [$article_id]);
    }

    public function getPendingEditRequests() {
        $sql = "SELECT aer.*, a.title as article_title, spu.username as requester_username, a.author_id
                FROM article_edit_requests aer
                JOIN articles a ON aer.article_id = a.article_id
                JOIN school_publication_users spu ON aer.requester_id = spu.user_id
                WHERE aer.request_status = 'pending' ORDER BY requested_at DESC";
        return $this->executeQuery($sql);
    }

    public function respondToEditRequest($request_id, $status) {
        $sql = "UPDATE article_edit_requests SET request_status = ?, responded_at = CURRENT_TIMESTAMP WHERE request_id = ?";
        return $this->executeNonQuery($sql, [$status, $request_id]);
    }

    public function getEditRequestById($request_id) {
        $sql = "SELECT * FROM article_edit_requests WHERE request_id = ?";
        $result = $this->executeQuery($sql, [$request_id]);
        return $result[0] ?? null;
    }

    public function shareArticle($article_id, $shared_with_user_id, $shared_by_user_id) {
        $sql = "INSERT INTO article_shares (article_id, shared_with_user_id, shared_by_user_id) VALUES (?, ?, ?)";
        return $this->executeNonQuery($sql, [$article_id, $shared_with_user_id, $shared_by_user_id]);
    }

    public function getSharedArticlesForUser($user_id) {
        $sql = "SELECT a.*, spu.username as author_username, ars.share_date, c.category_name
                FROM article_shares ars
                JOIN articles a ON ars.article_id = a.article_id
                JOIN school_publication_users spu ON a.author_id = spu.user_id
                LEFT JOIN categories c ON a.category_id = c.category_id
                WHERE ars.shared_with_user_id = ? ORDER BY ars.share_date DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    public function getArticlesByUserID($user_id) {
        $sql = "SELECT a.*, spu.username, c.category_name FROM articles a 
                JOIN school_publication_users spu ON a.author_id = spu.user_id 
                LEFT JOIN categories c ON a.category_id = c.category_id
                WHERE a.author_id = ? ORDER BY a.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    // Category Management
    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY category_name ASC";
        return $this->executeQuery($sql);
    }
}