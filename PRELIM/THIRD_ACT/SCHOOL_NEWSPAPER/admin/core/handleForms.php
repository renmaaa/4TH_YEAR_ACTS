<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Article.php';

$db = new Database();
$pdo = $db->connect();

$userObj = new User($pdo);
$articleObj = new Article($pdo);

if (isset($_POST['loginUserBtn'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email !== '' && $password !== '') {
        if ($userObj->loginUser ($email, $password)) {
            if ($userObj->isAdmin()) {
                $_SESSION['message'] = "Login successful!";
                $_SESSION['status'] = '200';
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['message'] = "Access denied. You are not an admin.";
                $_SESSION['status'] = '403';
                header("Location: ../admin/login.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Invalid email or password.";
            $_SESSION['status'] = '400';
            header("Location: ../admin/login.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Please fill in all fields.";
        $_SESSION['status'] = '400';
        header("Location: ../admin/login.php");
        exit();
    }
}

if (isset($_POST['registerAdminBtn'])) {
    $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if ($username !== '' && $email !== '' && $password !== '' && $confirm_password !== '') {
        if ($password === $confirm_password) {
            if (!$userObj->usernameExists($username)) {
                if ($userObj->registerUser ($username, $email, $password, true)) {
                    $_SESSION['message'] = "Admin registered successfully! You can now log in.";
                    $_SESSION['status'] = '200';
                    header("Location: ../admin/login.php");
                    exit();
                } else {
                    $_SESSION['message'] = "Failed to register admin.";
                    $_SESSION['status'] = '400';
                    header("Location: ../admin/register.php");
                    exit();
                }
            } else {
                $_SESSION['message'] = "Username already exists.";
                $_SESSION['status'] = '400';
                header("Location: ../admin/register.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "Passwords do not match.";
            $_SESSION['status'] = '400';
            header("Location: ../admin/register.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "All fields are required.";
        $_SESSION['status'] = '400';
        header("Location: ../admin/register.php");
        exit();
    }
}

if (isset($_POST['deleteArticleBtn'])) {
    $article_id = $_POST['article_id'] ?? null;
    if ($article_id) {
        $article_to_delete = $articleObj->getArticles($article_id);

        if ($articleObj->deleteArticle($article_id)) {
            if ($article_to_delete) {
                $author_id = $article_to_delete['author_id'];
                $article_title = $article_to_delete['title'];
                $message = "Your article titled '{$article_title}' has been deleted by an administrator.";
                $userObj->createNotification($author_id, $message);
            }
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
    exit();
}

if (isset($_POST['acceptEditRequestBtn'])) {
    $request_id = $_POST['request_id'] ?? null;
    $article_id = $_POST['article_id'] ?? null;
    $proposed_title = $_POST['proposed_title'] ?? '';
    $proposed_content = $_POST['proposed_content'] ?? '';

    if ($request_id && $article_id && $proposed_title !== '' && $proposed_content !== '') {
        if ($articleObj->updateArticle($article_id, $proposed_title, $proposed_content)) {
            $articleObj->respondToEditRequest($request_id, 'accepted');
            $request_details = $articleObj->getEditRequestById($request_id);

            if ($request_details) {
                $requester_id = $request_details['requester_id'];
                $message = "Your edit request for article '{$proposed_title}' has been accepted!";
                $userObj->createNotification($requester_id, $message);
            }

            $_SESSION['message'] = "Edit request accepted and article updated!";
            $_SESSION['status'] = '200';
        } else {
            $_SESSION['message'] = "Failed to update article.";
            $_SESSION['status'] = '400';
        }
    } else {
        $_SESSION['message'] = "Invalid data for accepting edit request.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../admin/articles_from_students.php");
    exit();
}

if (isset($_POST['rejectEditRequestBtn'])) {
    $request_id = $_POST['request_id'] ?? null;

    if ($request_id) {
        if ($articleObj->respondToEditRequest($request_id, 'rejected')) {
            $request_details = $articleObj->getEditRequestById($request_id);
            if ($request_details) {
                $article = $articleObj->getArticles($request_details['article_id']);
                $article_title = $article['title'] ?? '';
                $requester_id = $request_details['requester_id'];
                $message = "Your edit request for article '{$article_title}' has been rejected.";
                $userObj->createNotification($requester_id, $message);
            }

            $_SESSION['message'] = "Edit request rejected.";
            $_SESSION['status'] = '200';
        } else {
            $_SESSION['message'] = "Failed to reject edit request.";
            $_SESSION['status'] = '400';
        }
    } else {
        $_SESSION['message'] = "Invalid request ID.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../admin/articles_from_students.php");
    exit();
}

if (isset($_POST['insertAdminArticleBtn'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['message'] = "Unauthorized access.";
        $_SESSION['status'] = '403';
        header("Location: ../admin/login.php");
        exit();
    }

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['description'] ?? '');
    $author_id = $_SESSION['user_id'];

    if ($title !== '' && $content !== '') {
        if ($articleObj->createArticle($title, $content, $author_id)) {
            $_SESSION['message'] = "Article submitted successfully by Admin!";
            $_SESSION['status'] = '200';
        } else {
            $_SESSION['message'] = "Failed to submit article by Admin.";
            $_SESSION['status'] = '400';
        }
    } else {
        $_SESSION['message'] = "Please fill in all fields for the article.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../admin/index.php");
    exit();
}

if (isset($_POST['updateArticleVisibility'])) {
    $article_id = $_POST['article_id'] ?? null;
    $status = $_POST['status'] ?? null; // 0 for pending, 1 for active

    if ($article_id !== null && ($status === '0' || $status === '1')) {
        $sql = "UPDATE articles SET is_active = ? WHERE article_id = ?";
        if ($db->executeNonQuery($sql, [$status, $article_id])) {
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
    exit();
}

if (isset($_POST['editArticleBtn'])) {
    $article_id = $_POST['article_id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['description'] ?? '');

    if ($article_id && $title !== '' && $content !== '') {
        if ($articleObj->updateArticle($article_id, $title, $content)) {
            $_SESSION['message'] = "Article updated successfully!";
            $_SESSION['status'] = '200';
        } else {
            $_SESSION['message'] = "Failed to update article.";
            $_SESSION['status'] = '400';
        }
    } else {
        $_SESSION['message'] = "Please fill in all fields.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../admin/articles_from_students.php");
    exit();
}

if (isset($_POST['insertArticleBtn'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['message'] = "Unauthorized access.";
        $_SESSION['status'] = '403';
        header("Location: ../login.php"); 
        exit();
    }

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $author_id = $_SESSION['user_id'];
    $image_path = null;

    if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/"; 
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $filename = basename($_FILES["article_image"]["name"]);
        $imageFileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            $new_filename = uniqid('img_') . '.' . $imageFileType;
            $target_file = $target_dir . $new_filename;
            if (move_uploaded_file($_FILES["article_image"]["tmp_name"], $target_file)) {
                $image_path = 'uploads/' . $new_filename; 
            }
        }
    }

    if ($title !== '' && $description !== '') {
        $is_active = 0;
        if ($articleObj->createArticle($title, $description, $author_id, $image_path, $is_active)) {
            $_SESSION['message'] = "Article submitted successfully! It is now pending review.";
            $_SESSION['status'] = '200';
        } else {
            $_SESSION['message'] = "Failed to submit article.";
            $_SESSION['status'] = '400';
        }
    } else {
        $_SESSION['message'] = "Please fill in all fields for the article.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../admin/articles_from_students.php");
    exit();
}

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
}

    if (isset($_POST['shareArticleBtn'])) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['message'] = "Unauthorized access.";
            $_SESSION['status'] = '403';
            header("Location: ../admin/login.php");
            exit();
        }

        $article_id = $_POST['article_id'] ?? null;
        $shared_with_user_id = $_POST['shared_with_user_id'] ?? null;
        $shared_by_user_id = $_SESSION['user_id'];

        if ($article_id && $shared_with_user_id) {
            if ($articleObj->shareArticle($article_id, $shared_with_user_id, $shared_by_user_id)) {
                $article_title = $articleObj->getArticles($article_id)['title'] ?? 'an article';
                $sharer_username = $_SESSION['username'];
                $message = "{$sharer_username} has shared the article '{$article_title}' with you.";
                $userObj->createNotification($shared_with_user_id, $message);

                $_SESSION['message'] = "Article shared successfully!";
                $_SESSION['status'] = '200';
            } else {
                $_SESSION['message'] = "Failed to share article.";
                $_SESSION['status'] = '400';
            }
        } else {
            $_SESSION['message'] = "Invalid data for sharing article.";
            $_SESSION['status'] = '400';
        }
        header("Location: ../admin/articles_submitted.php");
        exit();
    }
