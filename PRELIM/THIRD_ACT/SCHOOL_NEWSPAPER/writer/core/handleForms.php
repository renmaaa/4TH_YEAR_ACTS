<?php
session_start();
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Article.php';

$db = new Database();

$userObj = new User($pdo);
$articleObj = new Article($pdo);


if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);


	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password)) {
					header("Location: ../login.php");
				}

				else {
					$_SESSION['message'] = "An error occured with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
				}
			}

			else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
			}
		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
		}
	}
	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {

		if ($userObj->loginUser($email, $password)) {
			header("Location: ../index.php");
		}
		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
	}

}

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
}

    if (isset($_POST['insertArticleBtn'])) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['message'] = "Unauthorized access.";
            $_SESSION['status'] = '403';
            header("Location: ../login.php");
            exit();
        }

        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $author_id = $_SESSION['user_id'];
        $image_path = null;

        if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "../../uploads/"; 
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
                } else {
                    $_SESSION['message'] = "Failed to upload image.";
                    $_SESSION['status'] = '400';
                    header("Location: ../index.php");
                    exit();
                }
            } else {
                $_SESSION['message'] = "Invalid image file type. Only JPG, JPEG, PNG, GIF are allowed.";
                $_SESSION['status'] = '400';
                header("Location: ../index.php");
                exit();
            }
        }

        if ($title !== '' && $description !== '') {
            $is_active = 1;
            if ($articleObj->createArticle($title, $description, $author_id, $image_path, $is_active)) {
                $_SESSION['message'] = "Article submitted successfully!";
                $_SESSION['status'] = '200';
            } else {
                $_SESSION['message'] = "Failed to submit article.";
                $_SESSION['status'] = '400';
            }
        } else {
            $_SESSION['message'] = "Please fill in all fields for the article.";
            $_SESSION['status'] = '400';
        }
        header("Location: ../index.php");
        exit();
    }

if (isset($_POST['editArticleBtn'])) {
    $article_id = $_POST['article_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    $old_article = $articleObj->getArticles($article_id);
    $image_path = $old_article['image_path'] ?? null;

    if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/";
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

    if ($articleObj->updateArticle($article_id, $title, $description, $image_path)) {
        $_SESSION['message'] = "Article updated successfully!";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Failed to update article.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../articles_submitted.php");
    exit();
}

    if (isset($_POST['submitEditRequestBtn'])) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['message'] = "Unauthorized access.";
            $_SESSION['status'] = '403';
            header("Location: ../login.php");
            exit();
        }

        $article_id = $_POST['article_id'] ?? null;
        $requester_id = $_SESSION['user_id'];
        $proposed_title = htmlspecialchars(trim($_POST['proposed_title'] ?? ''));
        $proposed_content = htmlspecialchars(trim($_POST['proposed_content'] ?? ''));

        if ($article_id && $proposed_title !== '' && $proposed_content !== '') {
            if ($articleObj->createEditRequest($article_id, $requester_id, $proposed_title, $proposed_content)) {
                $original_article = $articleObj->getArticles($article_id);
                if ($original_article) {
                    $author_id = $original_article['author_id'];
                    $article_title = $original_article['title'];
                    $requester_username = $_SESSION['username'];
                    $message = "{$requester_username} has requested an edit for your article titled '{$article_title}'.";
                    $userObj->createNotification($author_id, $message);
                }
                $_SESSION['message'] = "Edit request submitted successfully! Awaiting admin review.";
                $_SESSION['status'] = '200';
            } else {
                $_SESSION['message'] = "Failed to submit edit request.";
                $_SESSION['status'] = '400';
            }
        } else {
            $_SESSION['message'] = "Invalid data for edit request.";
            $_SESSION['status'] = '400';
        }
        header("Location: ../index.php");
        exit();
    }

if (isset($_POST['editSharedArticleBtn'])) {
    $article_id = $_POST['article_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    $old_article = $articleObj->getArticles($article_id);
    $image_path = $old_article['image_path'] ?? null;

    if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/";
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

    if ($articleObj->updateArticle($article_id, $title, $description, $image_path)) {
        $_SESSION['message'] = "Shared article updated successfully!";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Failed to update shared article.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../shared_articles.php");
    exit();
}
