<?php  
require_once __DIR__ . '/../../classloader.php';

if (!$userObj->isLoggedIn() || !$userObj->isFiverrAdmin()) {
    $_SESSION['message'] = "Unauthorized access.";
    $_SESSION['status'] = '403';
    header("Location: ../login.php");
    exit();
}

// Handle Category Forms
if (isset($_POST['addCategoryBtn'])) {
    $category_name = htmlspecialchars(trim($_POST['category_name']));

    if (!empty($category_name)) {
        if ($categoryObj->createCategory($category_name)) {
            $_SESSION['message'] = "Category added successfully!";
            $_SESSION['status'] = '200';
        } else {
            $_SESSION['message'] = "Failed to add category.";
            $_SESSION['status'] = '400';
        }
    } else {
        $_SESSION['message'] = "Category name cannot be empty.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../fiverr_admin/manage_categories.php");
    exit();
}

if (isset($_POST['updateCategoryBtn'])) {
    $category_id = (int)$_POST['category_id'];
    $category_name = htmlspecialchars(trim($_POST['category_name']));

    if ($category_id > 0 && !empty($category_name)) {
        if ($categoryObj->updateCategory($category_name, $category_id)) {
            $_SESSION['message'] = "Category updated successfully!";
            $_SESSION['status'] = '200';
        } else {
            $_SESSION['message'] = "Failed to update category.";
            $_SESSION['status'] = '400';
        }
    } else {
        $_SESSION['message'] = "Invalid category data.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../fiverr_admin/manage_categories.php");
    exit();
}

if (isset($_POST['deleteCategoryBtn'])) {
    $category_id = (int)$_POST['category_id'];

    if ($category_id > 0) {
        if ($categoryObj->deleteCategory($category_id)) {
            $_SESSION['message'] = "Category deleted successfully!";
            $_SESSION['status'] = '200';
        } else {
            $_SESSION['message'] = "Failed to delete category.";
            $_SESSION['status'] = '400';
        }
    } else {
        $_SESSION['message'] = "Invalid category ID.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../fiverr_admin/manage_categories.php");
    exit();
}

// Handle Subcategory Forms
if (isset($_POST['addSubcategoryBtn'])) {
    $category_id = (int)$_POST['category_id'];
    $subcategory_name = htmlspecialchars(trim($_POST['subcategory_name']));

    if ($category_id > 0 && !empty($subcategory_name)) {
        if ($subcategoryObj->createSubcategory($category_id, $subcategory_name)) {
            $_SESSION['message'] = "Subcategory added successfully!";
            $_SESSION['status'] = '200';
        } else {
            $_SESSION['message'] = "Failed to add subcategory.";
            $_SESSION['status'] = '400';
        }
    } else {
        $_SESSION['message'] = "Invalid subcategory data.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../fiverr_admin/manage_subcategories.php");
    exit();
}

if (isset($_POST['updateSubcategoryBtn'])) {
    $subcategory_id = (int)$_POST['subcategory_id'];
    $subcategory_name = htmlspecialchars(trim($_POST['subcategory_name']));

    if ($subcategory_id > 0 && !empty($subcategory_name)) {
        if ($subcategoryObj->updateSubcategory($subcategory_name, $subcategory_id)) {
            $_SESSION['message'] = "Subcategory updated successfully!";
            $_SESSION['status'] = '200';
        } else {
            $_SESSION['message'] = "Failed to update subcategory.";
            $_SESSION['status'] = '400';
        }
    } else {
        $_SESSION['message'] = "Invalid subcategory data.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../fiverr_admin/manage_subcategories.php");
    exit();
}

if (isset($_POST['deleteSubcategoryBtn'])) {
    $subcategory_id = (int)$_POST['subcategory_id'];

    if ($subcategory_id > 0) {
        if ($subcategoryObj->deleteSubcategory($subcategory_id)) {
            $_SESSION['message'] = "Subcategory deleted successfully!";
            $_SESSION['status'] = '200';
        } else {
            $_SESSION['message'] = "Failed to delete subcategory.";
            $_SESSION['status'] = '400';
        }
    } else {
        $_SESSION['message'] = "Invalid subcategory ID.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../fiverr_admin/manage_subcategories.php");
    exit();
}
?>