<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'eduhub'); // Replace with your actual database credentials

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search for users by name
if (isset($_POST['searchUsers']) && !empty($_POST['name'])) {
    $name = $_POST['name'];

    // Prepare and execute the query to search users from the 'students' table
    $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ?");
    $searchTerm = "%".$name."%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = [
                'name' => $row['name'],
                'email' => $row['email']
            ];
        }
    }

    echo json_encode($users);
    exit();
}

// Handle fetching recent contacts
if (isset($_POST['getRecentContacts'])) {
    $stmt = $conn->prepare("SELECT DISTINCT name, email FROM students LIMIT 10");
    $stmt->execute();
    $result = $stmt->get_result();

    $contacts = [];
    while ($row = $result->fetch_assoc()) {
        $contacts[] = [
            'contact_name' => $row['name'],
            'contact_email' => $row['email']
        ];
    }

    echo json_encode($contacts);
    exit();
}

// Handle sending messages
if (isset($_POST['sendMessage']) && !empty($_POST['message']) && !empty($_POST['receiver_email'])) {
    $senderEmail = 'your_logged_in_user_email'; // Replace this with the logged-in user's email
    $receiverEmail = $_POST['receiver_email'];
    $message = $_POST['message'];

    // Insert the message into the chat_messages table
    $stmt = $conn->prepare("INSERT INTO chat_messages (sender_email, receiver_email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $senderEmail, $receiverEmail, $message);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'error' => $stmt->error]);
    }
    exit();
}

// Handle loading messages for a selected contact
if (isset($_POST['getMessages']) && !empty($_POST['contact_email'])) {
    $senderEmail = 'your_logged_in_user_email'; // Replace with the logged-in user's email
    $contactEmail = $_POST['contact_email'];

    // Fetch messages between the logged-in user and the selected contact
    $stmt = $conn->prepare("SELECT * FROM chat_messages WHERE (sender_email = ? AND receiver_email = ?) OR (sender_email = ? AND receiver_email = ?) ORDER BY timestamp ASC");
    $stmt->bind_param("ssss", $senderEmail, $contactEmail, $contactEmail, $senderEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode($messages);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <style>
        /* Styles for the chat application */
        .container {
            display: flex;
            justify-content: space-between;
            height: 90vh;
            width: 90%;
            margin: 0 auto;
        }

        .contacts-box {
            width: 30%;
            border-right: 1px solid #ccc;
        }

        .contact-search {
            padding: 10px;
        }

        .contacts {
            overflow-y: auto;
            height: 80vh;
        }

        .contact {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
        }

        .chat-box {
            width: 70%;
        }

        .chat-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
        }

        .messages {
            height: 80vh;
            overflow-y: auto;
            padding: 10px;
        }

        .message {
            margin-bottom: 10px;
        }

        .input-box {
            display: flex;
            padding: 10px;
        }

        .input-box input {
            width: 100%;
            padding: 10px;
            margin-right: 10px;
        }

        .input-box button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .sender {
            text-align: right;
            color: green;
        }

        .receiver {
            text-align: left;
            color: blue;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="contacts-box">
            <div class="contact-search">
                <input type="text" id="searchInput" placeholder="Search contacts..." oninput="searchUsers()">
            </div>
            <div class="contacts" id="contactList">
                <!-- Contacts will be loaded here -->
            </div>
        </div>
        <div class="chat-box">
            <div class="chat-header" id="chatHeader">Select a contact to chat</div>
            <div class="messages" id="chatMessages">
                <!-- Messages will be displayed here -->
            </div>
            <div class="input-box">
                <input type="text" id="messageInput" placeholder="Type a message...">
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <script>
        let selectedContact = null;

        function searchUsers() {
            const searchInput = document.getElementById('searchInput').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const users = JSON.parse(xhr.responseText);
                    const contactList = document.getElementById('contactList');
                    contactList.innerHTML = '';
                    users.forEach(user => {
                        const contactDiv = document.createElement('div');
                        contactDiv.classList.add('contact');
                        contactDiv.textContent = user.name;
                        contactDiv.setAttribute('data-email', user.email);
                        contactDiv.onclick = () => selectContact(user.email, user.name);
                        contactList.appendChild(contactDiv);
                    });
                }
            };
            xhr.send('searchUsers=true&name=' + searchInput);
        }

        function selectContact(contactEmail, contactName) {
            selectedContact = contactEmail;
            document.getElementById('chatHeader').textContent = contactName;
            loadMessages(contactEmail);
        }

        function loadMessages(contactEmail) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const messages = JSON.parse(xhr.responseText);
                    const chatMessages = document.getElementById('chatMessages');
                    chatMessages.innerHTML = '';
                    messages.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('message');
                        if (message.sender_email === 'your_logged_in_user_email') { // Replace with the logged-in user's email
                            messageDiv.classList.add('sender');
                        } else {
                            messageDiv.classList.add('receiver');
                        }
                        messageDiv.innerHTML = `<span class="sender-name">${message.sender_email}</span><br>${message.message}`;
                        chatMessages.appendChild(messageDiv);
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            };
            xhr.send('getMessages=true&contact_email=' + contactEmail);
        }

        function sendMessage() {
            if (selectedContact === null) {
                alert('Please select a contact to chat with');
                return;
            }

            const messageInput = document.getElementById('messageInput').value;
            if (messageInput.trim() === '') {
                alert('Message cannot be empty');
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('messageInput').value = '';
                    loadMessages(selectedContact); // Reload messages after sending
                }
            };
            xhr.send('sendMessage=true&receiver_email=' + selectedContact + '&message=' + encodeURIComponent(messageInput));
        }

        function loadRecentContacts() {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const contacts = JSON.parse(xhr.responseText);
                    const contactList = document.getElementById('contactList');
                    contacts.forEach(contact => {
                        const contactDiv = document.createElement('div');
                        contactDiv.classList.add('contact');
                        contactDiv.textContent = contact.contact_name;
                        contactDiv.setAttribute('data-email', contact.contact_email);
                        contactDiv.onclick = () => selectContact(contact.contact_email, contact.contact_name);
                        contactList.appendChild(contactDiv);
                    });
                }
            };
            xhr.send('getRecentContacts=true');
        }

        window.onload = function() {
            loadRecentContacts();
        };
    </script>
</body>
</html>
