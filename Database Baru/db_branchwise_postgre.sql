-- Memulai transaksi. Semua perintah akan dijalankan atau tidak sama sekali.
BEGIN;

-- Membuat tipe data ENUM kustom yang akan digunakan di beberapa tabel
CREATE TYPE criteria_type AS ENUM ('benefit', 'cost');
CREATE TYPE location_status AS ENUM ('aktif', 'nonaktif', 'renovasi');
CREATE TYPE project_priority AS ENUM ('tinggi', 'sedang', 'rendah');
CREATE TYPE project_status AS ENUM ('belum dimulai', 'dalam pengerjaan', 'selesai');
CREATE TYPE user_role AS ENUM ('admin', 'user');
CREATE TYPE user_status AS ENUM ('active', 'inactive');


--
-- Struktur dan Data untuk tabel "users"
--
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  full_name VARCHAR(255) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role user_role NOT NULL,
  status user_status NOT NULL,
  created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (id, full_name, username, password, role, status) VALUES
(1, 'Admin BranchWise', 'admin', 'admin123', 'admin', 'active'),
(2, 'Darban Maha Mursyidi', 'darban_mm', 'darban123', 'user', 'active'),
(3, 'DIAN NOVENDRIA MUTIARA SYAHARANI', 'dian', 'dian1234', 'admin', 'active');


--
-- Struktur dan Data untuk tabel "projects"
--
CREATE TABLE projects (
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL,
  project_name VARCHAR(255) NOT NULL,
  description TEXT,
  priority project_priority NOT NULL,
  status project_status NOT NULL,
  deadline DATE,
  assignee VARCHAR(255),
  created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_projects_users
    FOREIGN KEY (user_id) 
    REFERENCES users(id) 
    ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO projects (id, user_id, project_name, description, priority, status, deadline, assignee) VALUES
(1, 2, 'Proyek coba coba', 'deskripsi coba coba', 'rendah', 'selesai', '2025-06-30', 'aku lah'),
(2, 2, 'Cabang baru Blondo', 'cabang baru di blondo untuk memperluan sebagai cabang kedua', 'sedang', 'belum dimulai', '2025-07-30', 'Dian');


--
-- Struktur dan Data untuk tabel "criteria"
--
CREATE TABLE criteria (
  id SERIAL PRIMARY KEY,
  project_id INT NOT NULL,
  criteria_code VARCHAR(10) NOT NULL,
  criteria_name VARCHAR(255) NOT NULL,
  weight_percentage INT NOT NULL,
  type criteria_type NOT NULL,
  value_unit VARCHAR(50),
  created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_criteria_projects
    FOREIGN KEY (project_id) 
    REFERENCES projects(id) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE (project_id, criteria_code)
);

INSERT INTO criteria (id, project_id, criteria_code, criteria_name, weight_percentage, type, value_unit) VALUES
(1, 1, 'C1', 'TAK TAU', 25, 'benefit', '0-10'),
(2, 1, 'C2', 'ENTAH', 30, 'cost', '0-10'),
(3, 1, 'C3', 'DON NOW', 45, 'benefit', '0-10'),
(4, 2, 'C1', 'TAK TAU', 20, 'benefit', '0-10'),
(5, 2, 'C2', 'ENTAH', 10, 'cost', 'juta jiwa'),
(6, 2, 'C3', 'DON NOW', 70, 'benefit', '0-10');


--
-- Struktur dan Data untuk tabel "locations"
--
CREATE TABLE locations (
  id SERIAL PRIMARY KEY,
  project_id INT NOT NULL,
  branch_name VARCHAR(255) NOT NULL,
  address VARCHAR(255) NOT NULL,
  city VARCHAR(100) NOT NULL,
  phone VARCHAR(20),
  email VARCHAR(255),
  size_sqm DECIMAL(10,2),
  status location_status NOT NULL,
  gmaps_link TEXT,
  notes TEXT,
  created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_locations_projects
    FOREIGN KEY (project_id) 
    REFERENCES projects(id) 
    ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO locations (id, project_id, branch_name, address, city, phone, email, size_sqm, status, gmaps_link, notes) VALUES
(4, 1, 'Rejowinangun', 'Magelang Tengah, Magelang City, Central Java', 'Kota Magelang', '6281228693783', 'darbanmaha@gmail.com', 100.00, 'aktif', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1977.9106164607044!2d110.22141947817425!3d-7.484982562435522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a8f454e7ff8f7%3A0xf60d08b60e1159ab!2sJl.%20Mataram%2C%20Kec.%20Magelang%20Tengah%2C%20Kota%20Magelang%2C%20Jawa%20Tengah!5e0!3m2!1sen!2sid!4v1749015925608!5m2!1sen!2sid', 'tidak ada'),
(5, 1, 'Panca Arga', 'Banyurojo, Mertoyudan, Magelang Regency, Central Java', 'Kota Magelang', '12345678', '22106050083@student.uin-suka.ac.id', 130.00, 'aktif', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.613088203598!2d110.21200717357533!3d-7.507893474064607!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a8f30021f99ab%3A0xcfce394b1b523247!2sJl.%20Panca%20Arga%2C%20Banyurojo%2C%20Kec.%20Mertoyudan%2C%20Kabupaten%20Magelang%2C%20Jawa%20Tengah!5e0!3m2!1sen!2sid!4v1749018109756!5m2!1sen!2sid', 'lagi lagi tidak ada'),
(6, 1, 'RST Soedjono', 'Potrobangsan, Magelang Utara, Magelang City, Central Java 56116', 'Kota Magelang', '222222222', 'yusrina@mail.com', 110.00, 'aktif', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.9878349518!2d110.2190592735747!3d-7.466593973601004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a85f0510ed5fd%3A0xed7a0a87c49d4c8c!2sJl.%20RST%20Soedjono%2C%20Potrobangsan%2C%20Kec.%20Magelang%20Utara%2C%20Kota%20Magelang%2C%20Jawa%20Tengah%2056116!5e0!3m2!1sen!2sid!4v1749018264149!5m2!1sen!2sid', 'tentu saja tidak ada'),
(7, 2, 'Jalan Dr Sutomo', 'Blora, Blora Regency, Central Java', 'Blora', '1111111111', 'fakhri@mail.com', 120.00, 'aktif', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.3590758687474!2d111.41541567356617!3d-6.966898068212412!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7740fce55f93f9%3A0xc1504cb3fa4cec7!2sJl.%20Dr.%20Sutomo%2C%20Kec.%20Blora%2C%20Kabupaten%20Blora%2C%20Jawa%20Tengah!5e0!3m2!1sen!2sid!4v1749027837163!5m2!1sen!2sid', 'Jalan dekat dengan SMP di Blora'),
(8, 2, 'aaaaaa', 'aaaaaa', 'aaaaaa', '11111', 'darban@mail.com', 110.00, 'aktif', '', 'ddddddd'),
(9, 2, 'ddddd', 'cccc', 'hhhhh', '33333333', 'yusrina@mail.com', 100.00, 'aktif', '', 'shrrhrthrthrt');


--
-- Struktur dan Data untuk tabel "matrix_data"
--
CREATE TABLE matrix_data (
  id SERIAL PRIMARY KEY,
  project_id INT NOT NULL,
  location_id INT NOT NULL,
  criteria_id INT NOT NULL,
  value DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_matrix_data_projects
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_matrix_data_locations
    FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_matrix_data_criteria
    FOREIGN KEY (criteria_id) REFERENCES criteria(id) ON DELETE CASCADE ON UPDATE CASCADE,
  UNIQUE (project_id, location_id, criteria_id)
);

INSERT INTO matrix_data (id, project_id, location_id, criteria_id, value) VALUES
(1, 1, 5, 1, 2.00), (2, 1, 5, 2, 4.00), (3, 1, 5, 3, 9.00),
(4, 1, 4, 1, 6.00), (5, 1, 4, 2, 6.00), (6, 1, 4, 3, 7.00),
(7, 1, 6, 1, 7.00), (8, 1, 6, 2, 9.00), (9, 1, 6, 3, 3.00),
(19, 2, 8, 4, 7.00), (20, 2, 8, 5, 10.00), (21, 2, 8, 6, 5.00),
(22, 2, 9, 4, 4.00), (23, 2, 9, 5, 8.00), (24, 2, 9, 6, 8.00),
(25, 2, 7, 4, 8.00), (26, 2, 7, 5, 6.00), (27, 2, 7, 6, 6.00);


--
-- Membuat trigger untuk fungsionalitas ON UPDATE CURRENT_TIMESTAMP
--
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
   NEW.updated_at = NOW(); 
   RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

-- Menerapkan trigger ke setiap tabel
CREATE TRIGGER set_timestamp_users BEFORE UPDATE ON users FOR EACH ROW EXECUTE PROCEDURE update_updated_at_column();
CREATE TRIGGER set_timestamp_projects BEFORE UPDATE ON projects FOR EACH ROW EXECUTE PROCEDURE update_updated_at_column();
CREATE TRIGGER set_timestamp_criteria BEFORE UPDATE ON criteria FOR EACH ROW EXECUTE PROCEDURE update_updated_at_column();
CREATE TRIGGER set_timestamp_locations BEFORE UPDATE ON locations FOR EACH ROW EXECUTE PROCEDURE update_updated_at_column();
CREATE TRIGGER set_timestamp_matrix_data BEFORE UPDATE ON matrix_data FOR EACH ROW EXECUTE PROCEDURE update_updated_at_column();


-- Menyesuaikan sequence agar ID berikutnya sesuai dengan data yang di-dump
-- Ini dilakukan setelah semua data di-insert
SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));
SELECT setval('projects_id_seq', (SELECT MAX(id) FROM projects));
SELECT setval('criteria_id_seq', (SELECT MAX(id) FROM criteria));
SELECT setval('locations_id_seq', (SELECT MAX(id) FROM locations));
SELECT setval('matrix_data_id_seq', (SELECT MAX(id) FROM matrix_data));


-- Menyelesaikan transaksi
COMMIT;