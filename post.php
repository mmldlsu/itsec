<?php
session_start();
include 'connect.php'; // Ensure connection is included
include 'logfunctions.php';
include 'config.php';

function createPost($userEmail, $content, $numericInput1, $numericInput2)
{
    logMessage('INFO', 'User ' . $_SESSION['email'] . ' Attempting to create new post with details: content=' . $content . ', numeric_input1 =' . $numericInput1 . ', numeric_input2 = ' . $numericInput2 , $userId,  'Post Creation', 'Attempt', $client_ip, 'PostCreate.log');
    global $conn;
    $userId = $_SESSION['id']; 
    $client_ip = $_SERVER['REMOTE_ADDR'];

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve user_id from email
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $userId = $user['user_id'];
    $stmt->close();

    // Create post
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content, numeric_input1, numeric_input2) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $userId, $content, $numericInput1, $numericInput2);
    $stmt->execute();
    $stmt->close();
    logMessage('INFO', 'User ' . $_SESSION['email'] . 'New Post Created. '  . ' successfully created new post with details: content=' . $content . ', numeric_input1 =' . $numericInput1 . ', numeric_input2 =' . $numericInput2 , $userId,  'Post Creation', 'Success', $client_ip, 'PostCreate.log');
    logMessage('INFO', 'User ' . $_SESSION['email'] . 'Writing/Adding of Data on Database Tables Post (Appending)'  . ' successfully created new post with details: content=' . $content . ', numeric_input1 =' . $numericInput1 . ', numeric_input2 =' . $numericInput2 , $userId , 'Post Creation', 'Success', $client_ip, 'writeReqs.log');
}

function getPosts()
{
    global $conn;
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    logMessage('INFO', 'Attempt by user ' . $_SESSION['email'] . ' Reading of Data from Database Tables Posts' . $_SESSION['email'] , $_SESSION['id'], 'Post Creation', 'Attempted', $_SERVER['REMOTE_ADDR'], 'readReqs.log');
    $query = "SELECT posts.*, users.email, users.first_name FROM posts JOIN users ON posts.user_id = users.user_id ORDER BY created_at DESC";
    $result = $conn->query($query);
    logMessage('INFO', 'Attempt by user ' . $_SESSION['email'] . ' Reading of Data from Database Tables Posts' . $_SESSION['email'] , $_SESSION['id'], 'Post Creation', 'Success', $_SERVER['REMOTE_ADDR'], 'readReqs.log');
    

    if (!$result) {
        die("Error executing query: " . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

function editPost($postId, $content, $numericInput1, $numericInput2)
{
    global $conn;
    $userId = $_SESSION['id']; 
    $client_ip = $_SERVER['REMOTE_ADDR'];
    
    // Fetch the current content and numeric inputs of the post before updating
    $stmt = $conn->prepare("SELECT content, numeric_input1, numeric_input2 FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $stmt->bind_result($oldContent, $oldNumericInput1, $oldNumericInput2);
    $stmt->fetch();
    $stmt->close();

    // Log the attempt to edit a post
    logMessage(
        'INFO', 
        'User ' . $_SESSION['email'] . ' is attempting to edit post ID ' . $postId . '. Previous content: "' . $oldContent . '", numeric_input1=' . $oldNumericInput1 . ', numeric_input2=' . $oldNumericInput2 . '. New content: "' . $content . '", numeric_input1=' . $numericInput1 . ', numeric_input2=' . $numericInput2, 
        $userId,  
        'Post Edit', 
        'Attempt', 
        $client_ip, 
        'post_edit.log'
    );
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("UPDATE posts SET content = ?, numeric_input1 = ?, numeric_input2 = ? WHERE post_id = ?");
    if ($stmt) {
        $stmt->bind_param("siii", $content, $numericInput1, $numericInput2, $postId);
        $stmt->execute();

        // Log the successful post edit
        logMessage(
            'INFO', 
            'User ' . $_SESSION['email'] . ' successfully edited post ID ' . $postId . '. Previous content: "' . $oldContent . '", numeric_input1=' . $oldNumericInput1 . ', numeric_input2=' . $oldNumericInput2 . '. New content: "' . $content . '", numeric_input1=' . $numericInput1 . ', numeric_input2=' . $numericInput2, 
            $userId,  
            'Post Edit', 
            'Success', 
            $client_ip, 
            'post_edit.log'
        );
        
        $stmt->close();
    } else {
        // Log the failure to edit the post
        logMessage(
            'ERROR', 
            'User ' . $_SESSION['email'] . ' failed to edit post ID ' . $postId . '. Previous content: "' . $oldContent . '", numeric_input1=' . $oldNumericInput1 . ', numeric_input2=' . $oldNumericInput2 . '. New content: "' . $content . '", numeric_input1=' . $numericInput1 . ', numeric_input2=' . $numericInput2 . '. SQL error: ' . mysqli_error($conn), 
            $userId,  
            'Post Edit', 
            'Failure', 
            $client_ip, 
            'post_edit.log'
        );
    }
}



function deletePost($postId)
{
    global $conn;

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch the post details before deletion for logging purposes
    $stmt = $conn->prepare("SELECT content, numeric_input1, numeric_input2 FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $stmt->bind_result($content, $numericInput1, $numericInput2);
    $stmt->fetch();
    $stmt->close();

    // Log the deletion attempt with the old post details
    $userId = $_SESSION['id']; // Assuming this is stored in session
    $client_ip = $_SERVER['REMOTE_ADDR'];
    logMessage(
        'INFO',
        'User ' . $_SESSION['email'] . ' attempting to delete post with ID=' . $postId . 
        ',  content=' . $content . 
        ',  numeric_input1=' . $numericInput1 . 
        ',  numeric_input2=' . $numericInput2,
        $userId,
        'Post Deletion',
        'Attempt',
        $client_ip,
        'PostDelete.log'
    );

    // Try to delete the post
    $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $postId);
    $success = $stmt->execute();
    $stmt->close();

    if ($success) {
        // Log the successful deletion
        logMessage(
            'INFO',
            'User ' . $_SESSION['email'] . ' attempting to delete post with ID=' . $postId . 
            ',  content=' . $content . 
            ',  numeric_input1=' . $numericInput1 . 
            ',  numeric_input2=' . $numericInput2,
            $userId,
            'Post Deletion',
            'Success',
            $client_ip,
            'PostDelete.log'
        );
    } else {
        // Log the failure to delete
        logMessage(
            'ERROR',
            'User ' . $_SESSION['email'] . ' failed to delete post with ID=' . $postId,
            $userId,
            'Post Deletion',
            'Failure',
            $client_ip,
            'PostDelete.log'
        );
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_post'])) {
        $content = trim($_POST['content']);
        $numericInput1 = (int) $_POST['numeric_input1'];
        $numericInput2 = (int) $_POST['numeric_input2'];
        $userEmail = $_SESSION['email'];

        // Sanitize and validate input
        if (empty($content) || $numericInput1 < 0 || $numericInput2 < 0) {
            die("Invalid input.");
        }

        createPost($userEmail, $content, $numericInput1, $numericInput2);
    } elseif (isset($_POST['edit_post'])) {
        $postId = (int) $_POST['post_id'];
        $content = trim($_POST['content']);
        $numericInput1 = (int) $_POST['numeric_input1'];
        $numericInput2 = (int) $_POST['numeric_input2'];

        // Check if the post exists and if the user is the author of the post
        $stmt = $conn->prepare("SELECT users.email FROM posts JOIN users ON posts.user_id = users.user_id WHERE post_id = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();
        $stmt->close();

        if ($post && $post['email'] == $_SESSION['email']) {
            // Sanitize and validate input
            if (empty($content) || $numericInput1 < 0 || $numericInput2 < 0) {
                die("Invalid input.");
            }

            editPost($postId, $content, $numericInput1, $numericInput2);
        } else {
            die("Unauthorized.");
        }
    } elseif (isset($_POST['delete_post'])) {
        $postId = (int) $_POST['post_id'];

        // Check if the post exists and if the user is the author of the post
        $stmt = $conn->prepare("SELECT users.email FROM posts JOIN users ON posts.user_id = users.user_id WHERE post_id = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();
        $stmt->close();

        if ($post && $post['email'] == $_SESSION['email']) {
            deletePost($postId);
        } else {
            die("Unauthorized.");
        }
    }
    
    header("Location: home.php");
    exit();
}
?>
