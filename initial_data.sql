-- Add some initial users
INSERT INTO users (username, password, is_admin) VALUES
('admin', '$2y$10$YourHashedPasswordHere', 1),
('priya_sharma', '$2y$10$YourHashedPasswordHere', 0),
('arjun_patel', '$2y$10$YourHashedPasswordHere', 0),
('neha_gupta', '$2y$10$YourHashedPasswordHere', 0),
('raj_kumar', '$2y$10$YourHashedPasswordHere', 0);

-- Add some initial confessions
INSERT INTO confessions (content, tag, upvotes, downvotes) VALUES
('My parents want me to study engineering, but my passion is classical dance. I haven''t told them yet.', 'other', 15, 2),
('I help my friend cheat during online exams. Ab bahut guilt ho raha hai...', 'study', 8, 5),
('Mess ke samne jo cute si ladki hai, I write shayari thinking about her but can''t share with anyone.', 'love', 25, 3),
('Hostel me maggi banate time fire alarm baj gaya, maine sabko convince kar diya ki false alarm tha!', 'campus', 30, 4),
('My roommate thinks I''m studying for CAT, but I''m secretly preparing for UPSC.', 'study', 12, 2),
('College ke annual function me dance performance ke time stage pe slip ho gaya, but played it cool!', 'other', 40, 5),
('Library me jo corner seat hai, wahan roz ek hi bandi baithti hai. Kash himmat hoti baat karne ki!', 'love', 20, 3);