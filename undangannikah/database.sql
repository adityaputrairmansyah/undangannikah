CREATE DATABASE IF NOT EXISTS wedding_invitation;
USE wedding_invitation;

CREATE TABLE guests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    attendance_status ENUM('Hadir', 'Tidak Hadir', 'Belum Konfirmasi') DEFAULT 'Belum Konfirmasi',
    number_of_guests INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    guest_id INT,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (guest_id) REFERENCES guests(id)
);

CREATE TABLE wedding_info (
    id INT PRIMARY KEY AUTO_INCREMENT,
    groom_name VARCHAR(100) NOT NULL,
    groom_parents VARCHAR(200) NOT NULL,
    bride_name VARCHAR(100) NOT NULL,
    bride_parents VARCHAR(200) NOT NULL,
    wedding_date DATE NOT NULL,
    akad_time TIME NOT NULL,
    reception_time TIME NOT NULL,
    venue_name VARCHAR(200) NOT NULL,
    venue_address TEXT NOT NULL,
    maps_link TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    image_path VARCHAR(255) NOT NULL,
    caption VARCHAR(200),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default wedding info
INSERT INTO wedding_info (
    groom_name, groom_parents, 
    bride_name, bride_parents, 
    wedding_date, akad_time, reception_time,
    venue_name, venue_address, maps_link
) VALUES (
    'Ahmad', 'Bpk. Lorem & Ibu Ipsum',
    'Fatimah', 'Bpk. Dolor & Ibu Sit',
    '2024-01-01', '09:00:00', '11:00:00',
    'Gedung Pernikahan Bahagia',
    'Jl. Contoh No. 123, Kecamatan Example, Kota Bahagia, 12345',
    'https://goo.gl/maps/your-location-link'
); 