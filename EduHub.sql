-- Table for storing student information
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    profile_rank DECIMAL(3,2),
    career_guidance TEXT,
    personalized_suggestions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE students 
ADD COLUMN isadmin ENUM('yes', 'no') DEFAULT 'no';


-- Table for storing skills
CREATE TABLE skills (
    skill_id INT AUTO_INCREMENT PRIMARY KEY,
    skill_name VARCHAR(50)
);

-- Table to store the skills of students
CREATE TABLE student_skills (
    student_skill_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    skill_id INT,
    skill_level INT CHECK (skill_level BETWEEN 1 AND 5),
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (skill_id) REFERENCES skills(skill_id)
);

-- Table for posts/ideas with admin approval
CREATE TABLE posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    content TEXT,
    post_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    category ENUM('educational', 'entertainment', 'professional') NOT NULL,
    picture_path VARCHAR(255),
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);


-- Table for books with admin approval
CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    title VARCHAR(255),
    author VARCHAR(100),
    price DECIMAL(10,2),
    conditions VARCHAR(50),
    pdf_link VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);

-- Table for book transactions
CREATE TABLE book_transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT,
    buyer_id INT,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('rent', 'sold', 'shared'),
    FOREIGN KEY (book_id) REFERENCES books(book_id),
    FOREIGN KEY (buyer_id) REFERENCES students(student_id)
);

-- Table for educational materials with admin approval
CREATE TABLE educational_materials (
    material_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    title VARCHAR(255),
    description TEXT,
    price DECIMAL(10,2),
    type ENUM('book', 'study_material', 'other'),
    pdf_link VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);

-- Table for skill development & freelancing
CREATE TABLE freelancing (
    freelancing_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    skill_id INT,
    skill_level INT CHECK (skill_level BETWEEN 1 AND 5),
    cv_link VARCHAR(255),
    freelance_opportunities TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (skill_id) REFERENCES skills(skill_id)
);

-- Table for ranking system
CREATE TABLE ranking (
    ranking_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    rank_type VARCHAR(50),
    rank_value INT CHECK (rank_value BETWEEN 1 AND 5),
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);

-- Table for blood donation requests
CREATE TABLE blood_donation (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'),
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    patient_problem TEXT,
    location VARCHAR(255),
    phone_number VARCHAR(15),
    status ENUM('pending', 'accepted', 'denied') DEFAULT 'pending',
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);

-- Table for online courses (upcoming)
CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    course_title VARCHAR(255),
    description TEXT,
    instructor VARCHAR(100),
    student_id INT,
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);

-- Table for tuition suggestions
CREATE TABLE tuition_suggestions (
    suggestion_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject VARCHAR(100),
    institution VARCHAR(100),
    tuition_details TEXT,
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);


-- Table for storing tuition offers
CREATE TABLE tuitions (
    tuition_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    class_level ENUM('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'),
    subject ENUM('Science', 'Math', 'English', 'Biology', 'Economics', 'Chemistry', 'Physics'),
    location ENUM('Mirpur', 'Dhanmondi', 'Khilkhet', 'Banani', 'Uttara', 'Mohammadpur', 'Bashundhara', 'Gulshan'),
    institution VARCHAR(255),
    phone_number VARCHAR(15),
    preferred_time VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);

-- Table for storing tutor information
CREATE TABLE tutors (
    tutor_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    class_range_start ENUM('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'),
    class_range_end ENUM('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'),
    subject ENUM('Science', 'Math', 'English', 'Biology', 'Economics', 'Chemistry', 'Physics'),
    location ENUM('Mirpur', 'Dhanmondi', 'Khilkhet', 'Banani', 'Uttara', 'Mohammadpur', 'Bashundhara', 'Gulshan'),
    phone_number VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);
-- Table for storing book purchase accounts
CREATE TABLE book_accounts (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT, -- The student buying the book
    seller_id INT, -- The student selling the book
    book_id INT, -- The book being bought
    transaction_id INT, -- The transaction reference
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    amount DECIMAL(10,2), -- The amount for the book purchase
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending', -- Payment status
    FOREIGN KEY (buyer_id) REFERENCES students(student_id),
    FOREIGN KEY (seller_id) REFERENCES students(student_id),
    FOREIGN KEY (book_id) REFERENCES books(book_id),
    FOREIGN KEY (transaction_id) REFERENCES book_transactions(transaction_id)
);
-- Create wallet table if not already created
-- Create wallet table if not already created
CREATE TABLE IF NOT EXISTS wallet (
    wallet_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    balance DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    material VARCHAR(255),  -- Column to store book name
    `from` INT,  -- Column to store buyer_id from book_accounts
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (`from`) REFERENCES students(student_id)  -- Assuming you want to reference students table
);

-- Copy data from book_accounts to wallet, including material and from
INSERT INTO wallet (student_id, balance, material, `from`)
SELECT 
    seller_id AS student_id, 
    amount AS balance,
    b.title AS material,  -- Assuming 'title' is the book name in books table
    ba.buyer_id AS `from`  -- Use buyer_id from book_accounts
FROM 
    book_accounts ba
JOIN 
    books b ON ba.book_id = b.book_id;  -- Join to get book title

DELIMITER $$

CREATE TRIGGER after_book_accounts_insert
AFTER INSERT ON book_accounts
FOR EACH ROW
BEGIN
    DECLARE book_title VARCHAR(255);
    
    -- Get the title of the book from the books table
    SELECT title INTO book_title
    FROM books
    WHERE book_id = NEW.book_id;
    
    -- Insert the data into the wallet table
    INSERT INTO wallet (student_id, balance, material, `from`, created_at)
    VALUES (
        NEW.seller_id,          -- student_id
        NEW.amount,             -- balance
        book_title,             -- material (book title)
        NEW.buyer_id,           -- from (buyer)
        NOW()                   -- created_at
    );
END$$

DELIMITER ;



    -- Table for storing items (books, electronics, study materials, computer components)
CREATE TABLE items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT, -- Student who added the item
    title VARCHAR(255), -- Item title
    author_or_brand VARCHAR(100), -- Author for books, or brand for electronics and other items
    price DECIMAL(10,2), -- Price of the item
    conditions VARCHAR(50), -- Condition of the item (e.g., New, Used)
    category ENUM('Electronics', 'Study Materials', 'Computer Components'), -- Dropdown for category
    pdf_link_or_image VARCHAR(255), -- File link for books (PDF) or image link for other items
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending', -- Admin approval status
    issold ENUM('yes', 'no') DEFAULT 'no', -- Whether the item is sold or not, default is 'no'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);


-- Table for item transactions (similar to book_transactions but more general)
CREATE TABLE item_transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT, -- Reference to the item
    buyer_id INT, -- Student buying the item
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('sold', 'shared'), -- Status of the transaction
    FOREIGN KEY (item_id) REFERENCES items(item_id),
    FOREIGN KEY (buyer_id) REFERENCES students(student_id)
);

-- Table for storing item accounts (similar to book_accounts but for general items)
CREATE TABLE item_accounts (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT, -- The student buying the item
    seller_id INT, -- The student selling the item
    item_id INT, -- The item being bought
    transaction_id INT, -- The transaction reference
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    amount DECIMAL(10,2), -- The amount for the item purchase
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending', -- Payment status
    FOREIGN KEY (buyer_id) REFERENCES students(student_id),
    FOREIGN KEY (seller_id) REFERENCES students(student_id),
    FOREIGN KEY (item_id) REFERENCES items(item_id),
    FOREIGN KEY (transaction_id) REFERENCES item_transactions(transaction_id)
);

-- Copy data from item_accounts to wallet, including material and from
INSERT INTO wallet (student_id, balance, material, `from`)
SELECT 
    seller_id AS student_id, 
    amount AS balance,
    i.title AS material,  -- Assuming 'title' is the item name in items table
    ia.buyer_id AS `from`  -- Use buyer_id from item_accounts
FROM 
    item_accounts ia
JOIN 
    items i ON ia.item_id = i.item_id;  -- Join to get item title




-- Create the wallet table
CREATE TABLE wallet (
    wallet_id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each wallet entry
    student_id INT,                            -- ID of the student associated with the wallet
    balance DECIMAL(10,2),                     -- Current balance in the wallet
    material VARCHAR(255),                     -- Description of the material (e.g., item or book name)
    `from` INT,                                -- ID of the buyer who made the transaction
    buyer_name VARCHAR(255),                   -- Name of the buyer
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Timestamp of when the entry was created
    FOREIGN KEY (student_id) REFERENCES students(student_id),  -- Foreign key referencing students table
    FOREIGN KEY (`from`) REFERENCES students(student_id)       -- Foreign key referencing students table for buyer ID
);

-- Insert into the wallet table
INSERT INTO wallet (student_id, balance, material, `from`, buyer_name)
SELECT 
    ia.seller_id AS student_id, 
    ia.amount AS balance,
    i.title AS material,  -- Assuming 'title' is the item name in items table
    ia.buyer_id AS `from`,  -- Use buyer_id from item_accounts
    s.name AS buyer_name  -- Get buyer's name from students table
FROM 
    item_accounts ia
JOIN 
    items i ON ia.item_id = i.item_id  -- Join to get item details
JOIN 
    students s ON ia.buyer_id = s.student_id  -- Join to get buyer's name
UNION ALL
SELECT 
    ba.seller_id AS student_id, 
    ba.amount AS balance,
    b.title AS material,  -- Assuming 'title' is the book name in books table
    ba.buyer_id AS `from`,  -- Use buyer_id from book_accounts
    s.name AS buyer_name  -- Get buyer's name from students table
FROM 
    book_accounts ba
JOIN 
    books b ON ba.book_id = b.book_id  -- Join to get book title
JOIN 
    students s ON ba.buyer_id = s.student_id;  -- Join to get buyer's name


-- Table for storing admin information
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,      -- Unique identifier for each admin
    username VARCHAR(100) UNIQUE NOT NULL,        -- Unique username for the admin
    email VARCHAR(100) UNIQUE NOT NULL,           -- Unique email for the admin
    password VARCHAR(255) NOT NULL,               -- Password for the admin
    role ENUM('superadmin', 'moderator') NOT NULL,-- Role of the admin
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,-- Timestamp of when the admin account was created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Timestamp of last update
);

CREATE TABLE instructors (
    instructor_id INT AUTO_INCREMENT PRIMARY KEY,  -- Unique identifier for each instructor
    student_id INT,                                -- Reference to the student who is also an instructor
    full_name VARCHAR(255) NOT NULL,               -- Full name of the instructor
    job_experience TEXT,                           -- Job experience details of the instructor
    available_courses ENUM('PowerPoint', 'Word', 'Excel', 'Web Frontend', 'Web Backend', 'Web Fullstack', 'Electronics Projects') NOT NULL,  -- Dropdown for available courses
    expected_money DECIMAL(10,2) NOT NULL,         -- Expected payment for the courses
    class_hour INT NOT NULL,                       -- Expected class hours for the course
    pdf_upload_path VARCHAR(255),                  -- Path to uploaded PDF files
    video_upload_path VARCHAR(255),                -- Path to uploaded video files
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Timestamp of when the instructor entry was created
    FOREIGN KEY (student_id) REFERENCES students(student_id) -- Foreign key reference to the students table
);


CREATE TABLE wallet2 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    balance DECIMAL(10, 2) NOT NULL,
    material VARCHAR(255) NOT NULL,
    `from` INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE wallet2 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    balance DECIMAL(10, 2) NOT NULL,
    material VARCHAR(255) NOT NULL,
    `from` INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO wallet2 (student_id, balance, material, `from`)
SELECT 
    buyer_id AS student_id,  -- Using buyer_id as student_id
    amount AS balance,
    b.title AS material,  -- Assuming 'title' is the book name in books table
    ba.seller_id AS `from`  -- Use seller_id from book_accounts
FROM 
    book_accounts ba
JOIN 
    books b ON ba.book_id = b.book_id;  -- Join to get book title
DELIMITER $$

CREATE TRIGGER after_book_accounts_insert_cost
AFTER INSERT ON book_accounts
FOR EACH ROW
BEGIN
    DECLARE book_title VARCHAR(255);
    
    -- Get the title of the book from the books table
    SELECT title INTO book_title
    FROM books
    WHERE book_id = NEW.book_id;
    
    -- Insert the data        -- Using buyer_id as student_id
        NEW.amount,            -- balance
        book_title,            -- material (book title)
        NEW.seller_id,         -- from (seller)
        NOW()                  -- created_at
    );
END$$

DELIMITER ;


-- Create a 'chats' table to store which users are chatting with each other
CREATE TABLE chats (
    chat_id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT,
    user2_id INT,
    FOREIGN KEY (user1_id) REFERENCES students(student_id),
    FOREIGN KEY (user2_id) REFERENCES students(student_id)
);

-- Create a 'messages' table to store the messages exchanged
CREATE TABLE messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    chat_id INT,
    sender_id INT,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chat_id) REFERENCES chats(chat_id),
    FOREIGN KEY (sender_id) REFERENCES students(student_id)
);

