<?php  
require_once '../classloader.php';

if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$contact_number = htmlspecialchars(trim($_POST['contact_number']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);
    $is_fiverr_admin = isset($_POST['is_fiverr_admin']) ? 1 : 0; 

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password, $contact_number, 0, $is_fiverr_admin)) { // Pass is_client=0 and is_fiverr_admin
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
            if ($userObj->isFiverrAdmin()) {
                header("Location: ../client/fiverr_admin/index.php"); 
            } else {
                header("Location: ../index.php"); 
            }
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

if (isset($_POST['updateUserBtn'])) {
	$contact_number = htmlspecialchars($_POST['contact_number']);
	$bio_description = htmlspecialchars($_POST['bio_description']);
	if ($userObj->updateUser($contact_number, $bio_description, $_SESSION['user_id'])) {
		$_SESSION['status'] = "200";
		$_SESSION['message'] = "Profile updated successfully!";
		header("Location: ../profile.php");
	}
}

if (isset($_POST['insertNewProposalBtn'])) {
	$user_id = $_SESSION['user_id'];
	$description = htmlspecialchars($_POST['description']);
	$min_price = htmlspecialchars($_POST['min_price']);
	$max_price = htmlspecialchars($_POST['max_price']);
    $category_id = (int)$_POST['category_id']; // New
    $subcategory_id = (int)$_POST['subcategory_id']; // New

	$fileName = $_FILES['image']['name'];

	$tempFileName = $_FILES['image']['tmp_name'];

	$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

	$uniqueID = sha1(md5(rand(1,9999999)));

	$imageName = $uniqueID.".".$fileExtension;

	$folder = "../../images/".$imageName;

	if (move_uploaded_file($tempFileName, $folder)) {
		if ($proposalObj->createProposal($user_id, $description, $imageName, $min_price, $max_price, $category_id, $subcategory_id)) { // Pass category and subcategory
			$_SESSION['status'] = "200";
			$_SESSION['message'] = "Proposal saved successfully!";
			header("Location: ../index.php");
		} else {
            $_SESSION['status'] = "400";
            $_SESSION['message'] = "Failed to save proposal.";
            header("Location: ../index.php");
        }
	} else {
        $_SESSION['status'] = "400";
        $_SESSION['message'] = "Failed to upload image.";
        header("Location: ../index.php");
    }
}

if (isset($_POST['updateProposalBtn'])) {
	$min_price = $_POST['min_price'];
	$max_price = $_POST['max_price'];
	$proposal_id = $_POST['proposal_id'];
	$description = htmlspecialchars($_POST['description']);
    $category_id = (int)$_POST['category_id']; 
    $subcategory_id = (int)$_POST['subcategory_id']; 

    
    $imageName = "";
    if (!empty($_FILES['image']['name'])) {
        $fileName = $_FILES['image']['name'];
        $tempFileName = $_FILES['image']['tmp_name'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $uniqueID = sha1(md5(rand(1,9999999)));
        $imageName = $uniqueID.".".$fileExtension;
        $folder = "../../images/".$imageName;

        
        $oldProposal = $proposalObj->getProposals($proposal_id);
        if ($oldProposal && !empty($oldProposal['image'])) {
            unlink("../../images/".$oldProposal['image']);
        }
        move_uploaded_file($tempFileName, $folder);
    }

	if ($proposalObj->updateProposal($description, $min_price, $max_price, $proposal_id, $category_id, $subcategory_id, $imageName)) { // Pass category, subcategory, and new image
		$_SESSION['status'] = "200";
		$_SESSION['message'] = "Proposal updated successfully!";
		header("Location: ../your_proposals.php");
	} else {
        $_SESSION['status'] = "400";
        $_SESSION['message'] = "Failed to update proposal.";
        header("Location: ../your_proposals.php");
    }
}

if (isset($_POST['deleteProposalBtn'])) {
	$proposal_id = $_POST['proposal_id'];
	$image = $_POST['image'];

	if ($proposalObj->deleteProposal($proposal_id)) {
		if (!empty($image) && file_exists("../../images/".$image)) {
            unlink("../../images/".$image);
        }
		
		$_SESSION['status'] = "200";
		$_SESSION['message'] = "Proposal deleted successfully!";
		header("Location: ../your_proposals.php");
	} else {
        $_SESSION['status'] = "400";
        $_SESSION['message'] = "Failed to delete proposal.";
        header("Location: ../your_proposals.php");
    }
}