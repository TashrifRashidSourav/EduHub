<?php
session_start();
$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$database = "eduhub"; // your database name
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['student_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['student_id'];
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['sendMessage'])) {
        $sender_id = $user_id;
        $receiver_id = $_POST['receiver_id'];
        $message = $_POST['message'];

        $sql = "INSERT INTO chat (sender_id, receiver_id, message) VALUES ('$sender_id', '$receiver_id', '$message')";

        if ($conn->query($sql) === TRUE) {
            echo "Message sent successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        exit;
    } elseif (isset($_POST['getMessages'])) {
        $contact_id = $_POST['contact_id'];
        $sql = "SELECT chat.*, students.name AS sender_name 
                FROM chat 
                JOIN students ON chat.sender_id = students.student_id 
                WHERE (sender_id='$user_id' AND receiver_id='$contact_id') 
                OR (sender_id='$contact_id' AND receiver_id='$user_id') 
                ORDER BY timestamp ASC";
        $result = $conn->query($sql);

        $messages = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['sender_id'] == $user_id) {
                    $row['sender_name'] = 'You';
                }
                $messages[] = $row;
            }
        }

        echo json_encode($messages);
        exit;
    } elseif (isset($_POST['searchUsers'])) {
        $name = $_POST['name'];
        $sql = "SELECT student_id, name FROM students WHERE name LIKE '%$name%'";
        $result = $conn->query($sql);

        $users = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        echo json_encode($users);
        exit;
    } elseif (isset($_POST['getRecentContacts'])) {
        $sql = "SELECT DISTINCT CASE WHEN sender_id = '$user_id' THEN receiver_id ELSE sender_id END AS contact_id,
                students.name AS contact_name
                FROM chat
                JOIN students ON CASE WHEN sender_id = '$user_id' THEN receiver_id ELSE sender_id END = students.student_id
                WHERE sender_id = '$user_id' OR receiver_id = '$user_id'
                ORDER BY (SELECT MAX(timestamp) FROM chat WHERE (sender_id = '$user_id' AND receiver_id = contact_id) 
                OR (sender_id = contact_id AND receiver_id = '$user_id')) DESC";
        $result = $conn->query($sql);

        $contacts = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $contacts[] = $row;
            }
        }

        echo json_encode($contacts);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Chat Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e5ddd5;
            margin: 0;
            padding: 0; 
            justify-content: center;
            align-items: center; 
            height: 100vh; 
            background-image: url("nahi2.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .container {
            display: flex;
            width: 80%;
            height: 80%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 40px;
            margin-left: 150px;
        }
        .sidebar {
            width: 30%;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #ccc;
        }
        .search-bar {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .search-bar input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .contacts {
            flex-grow: 1;
            overflow-y: scroll;
        }
        .contact {
            padding: 15px;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
        }
        .contact:hover {
            background-color: #f1f1f1;
        }
        .chat-box {
            width: 70%;
            background-color: #fcfcfc85;
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            padding: 15px;
            background-color: #088b8b;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }
        .messages {
            flex-grow: 1;
            padding: 10px;
            overflow-y: scroll;
        }
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            max-width: 70%;
        }
        .message.sender {
            background-color: lightgreen;
            align-self: flex-end;
            padding-left: 50px;
        }
        .message.receiver {
            background-color: #6a7fbc;;
            align-self: flex-start;
            border-radius: 10px;
        }
        .message .sender-name {
            font-weight: bold;
        }
        .input-box {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ccc;
            background-color: #fff;
        }
        .input-box input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .input-box button {
            padding: 10px;
            background-color: #34b7f1;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include 'navbaradmin.php'; ?>
    <div class="container">
        <div class="sidebar">
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search users" oninput="searchUsers()">
            </div>
            <div class="contacts" id="contactsList"></div>
        </div>
        <div class="chat-box">
            <div class="chat-header" id="chatHeader">Select a contact</div>
            <div class="messages" id="messagesList"></div>
            <div class="input-box" id="inputBox">
                <input type="text" id="messageInput" placeholder="Type a message...">
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>
    
    <script>
        let currentContactId = null;

        function searchUsers() {
            const name = document.getElementById('searchInput').value;
            const formData = new FormData();
            formData.append('name', name);
            formData.append('searchUsers', true);

            fetch('chat.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(users => {
                const contactsList = document.getElementById('contactsList');
                contactsList.innerHTML = ''; // Clear previous results
                users.forEach(user => {
                    const contactDiv = document.createElement('div');
                    contactDiv.classList.add('contact');
                    contactDiv.innerHTML = `${user.name}`;
                    contactDiv.onclick = function() {
                        openChat(user.student_id);
                    };
                    contactsList.appendChild(contactDiv);
                });
            });
        }

        function openChat(receiverId) {
            currentContactId = receiverId;
            const chatHeader = document.getElementById('chatHeader');
            chatHeader.textContent = "Chatting with " + receiverId; // You may want to fetch the actual name
            loadMessages();
        }

        function loadMessages() {
            if (currentContactId === null) return;

            const formData = new FormData();
            formData.append('contact_id', currentContactId);
            formData.append('getMessages', true);

            fetch('chat.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(messages => {
                const messagesList = document.getElementById('messagesList');
                messagesList.innerHTML = ''; // Clear previous messages
                messages.forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message', message.sender_id == '<?php echo $user_id; ?>' ? 'sender' : 'receiver');
                    messageDiv.innerHTML = `<span class="sender-name">${message.sender_name}:</span> ${message.message}`;
                    messagesList.appendChild(messageDiv);
                });
                messagesList.scrollTop = messagesList.scrollHeight; // Scroll to the bottom
            });
        }

        function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            if (message === '' || currentContactId === null) return;

            const formData = new FormData();
            formData.append('receiver_id', currentContactId);
            formData.append('message', message);
            formData.append('sendMessage', true);

            fetch('chat.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                loadMessages(); // Load messages after sending
                messageInput.value = ''; // Clear input field
            });
        }

        // Load recent contacts on page load
        document.addEventListener('DOMContentLoaded', function() {
            const formData = new FormData();
            formData.append('getRecentContacts', true);
            fetch('chat.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(contacts => {
                const contactsList = document.getElementById('contactsList');
                contacts.forEach(contact => {
                    const contactDiv = document.createElement('div');
                    contactDiv.classList.add('contact');
                    contactDiv.innerHTML = `${contact.contact_name}`;
                    contactDiv.onclick = function() {
                        openChat(contact.contact_id);
                    };
                    contactsList.appendChild(contactDiv);
                });
            });
        });
    </script>
</body>
</html>
