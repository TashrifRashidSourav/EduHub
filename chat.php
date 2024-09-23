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
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['student_id'];

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-image: url("nahi2.jpg");
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
            background-color: #fff;
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
        .menu-bar {
            background-color:#194d85a1;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
        }
        .menu-bar a {
            display: inline-block;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
        }
        .menu-bar a:hover {
            background-color: #0a94dc;
            border-radius: 5px;
        }

        .active-page {
            background-color: greenyellow;
            border-radius: 5px;
            color:black;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search users by name" oninput="searchUsers()">
            </div>
            <div class="contacts" id="contacts"></div>
        </div>
        <div class="chat-box">
            <div class="chat-header" id="chatHeader">Select a user to start chat</div>
            <div class="messages" id="messages"></div>
            <div class="input-box">
                <input type="text" id="messageInput" placeholder="Type a message...">
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>
    <script>
        const senderId = <?php echo json_encode($user_id); ?>;
        let receiverId = null;
        let receiverName = '';

        function getMessages() {
            if (!receiverId) return;

            const formData = new FormData();
            formData.append('contact_id', receiverId);
            formData.append('getMessages', true);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const messagesDiv = document.getElementById('messages');
                messagesDiv.innerHTML = '';
                data.forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message');
                    messageDiv.classList.add(message.sender_id === senderId ? 'sender' : 'receiver');
                    messageDiv.innerText = message.message;
                    messagesDiv.appendChild(messageDiv);
                });
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            });
        }

        function sendMessage() {
            if (!receiverId) {
                alert("Please select a user to chat with.");
                return;
            }

            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value;
            if (message.trim() === '') return;

            const formData = new FormData();
            formData.append('sender_id', senderId);
            formData.append('receiver_id', receiverId);
            formData.append('message', message);
            formData.append('sendMessage', true);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                messageInput.value = '';
                getMessages();
                updateRecentContacts();
            });
        }

        function searchUsers() {
            const searchInput = document.getElementById('searchInput');
            const name = searchInput.value;

            const formData = new FormData();
            formData.append('name', name);
            formData.append('searchUsers', true);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const contactsDiv = document.getElementById('contacts');
                contactsDiv.innerHTML = '';
                data.forEach(user => {
                    const contactDiv = document.createElement('div');
                    contactDiv.classList.add('contact');
                    contactDiv.innerText = user.name;
                    contactDiv.onclick = () => {
                        receiverId = user.student_id;
                        receiverName = user.name;
                        document.getElementById('chatHeader').innerText = `Chat with ${receiverName}`;
                        getMessages();
                    };
                    contactsDiv.appendChild(contactDiv);
                });
            });
        }

        function getRecentContacts() {
            const formData = new FormData();
            formData.append('getRecentContacts', true);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const contactsDiv = document.getElementById('contacts');
                contactsDiv.innerHTML = '';
                data.forEach(contact => {
                    const contactDiv = document.createElement('div');
                    contactDiv.classList.add('contact');
                    contactDiv.innerText = contact.contact_name;
                    contactDiv.onclick = () => {
                        receiverId = contact.contact_id;
                        receiverName = contact.contact_name;
                        document.getElementById('chatHeader').innerText = `Chat with ${receiverName}`;
                        getMessages();
                    };
                    contactsDiv.appendChild(contactDiv);
                });
            });
        }

        function fetchMessagesPeriodically() {
            setInterval(() => {
                getMessages();
                getRecentContacts();
            }, 3000);
        }

        getRecentContacts();
        fetchMessagesPeriodically();
    </script>
</body>
</html>
