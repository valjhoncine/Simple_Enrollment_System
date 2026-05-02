DROP DATABASE IF EXISTS enrollment_db;
CREATE DATABASE IF NOT EXISTS enrollment_db;
USE enrollment_db;

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    passwordhash VARCHAR(255) NOT NULL,
    role int default 3, -- administrator 0, clerk 1, faculty 2, student 3
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status bit default 1
);
INSERT INTO users (first_name, last_name, email, passwordhash, role) 
values ('Enrollment', 'Administrator', 'administrator@enrollment-mail.com', '$2y$10$pnecftsJk/Nr4r/ayxiw1.7XHrU6NqGOCjqMaUB.sPc8z449yjzqC', 0);

DROP TABLE IF EXISTS courses;
CREATE TABLE IF NOT EXISTS courses (
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) NOT NULL,
    name VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
INSERT INTO courses (code,name)values('ALL-C', 'All Programs/Courses');

DROP TABLE IF EXISTS profiles;
CREATE TABLE IF NOT EXISTS profiles(
    id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL UNIQUE,
    course_id BIGINT NOT NULL,
    student_number VARCHAR(30) UNIQUE,
    address VARCHAR(255),
    date_of_birth DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_profiles_users_id FOREIGN KEY (user_id) REFERENCES users (id),
    CONSTRAINT fk_profiles_course_id FOREIGN KEY (course_id) REFERENCES courses (id)
);
INSERT INTO profiles (user_id,course_id)values(1,1);

DROP TABLE IF EXISTS subjects;
CREATE TABLE IF NOT EXISTS subjects (
    id BIGINT not null PRIMARY key AUTO_INCREMENT,
    code VARCHAR(50) not null,
    name varchar(255) not null,
    course_id BIGINT not null,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_subjects_courses_id FOREIGN KEY (course_id) REFERENCES courses(id)
);

DROP TABLE IF EXISTS subject_schedules;
CREATE TABLE IF NOT EXISTS subject_schedules (
    id BIGINT not null PRIMARY KEY AUTO_INCREMENT,
    subject_id BIGINT not null,
    day VARCHAR(10) not null,
    start TIME not null,
    end TIME not null,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_subject_schedules_subjects_id FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

DROP TABLE IF EXISTS enrollment;
CREATE TABLE IF NOT EXISTS enrollment (
    id BIGINT not null PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT not null,
    status tinyint not null default 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    approved_declined_by BIGINT,
    approved_declined_at DATETIME,
    decline_reason VARCHAR(255),
    CONSTRAINT fk_enrollment_users_id FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_enrollment_users_approve_id FOREIGN KEY (approved_declined_by) REFERENCES users(id)
);

DROP TABLE IF EXISTS class_program;
CREATE TABLE IF NOT EXISTS class_program (
    id BIGINT not null PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT not null,
    enrollment_id BIGINT not null,
    subject_id BIGINT not null,
    schedule_id BIGINT not null,
    status tinyint not null default 0,
    created_at DATETIME default CURRENT_TIMESTAMP,
    approved_declined_by BIGINT,
    approved_declined_at DATETIME,
    decline_reason VARCHAR(255),
    CONSTRAINT fk_class_program_users_id FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_class_program_enrollment_id FOREIGN KEY (enrollment_id) REFERENCES enrollment(id),
    CONSTRAINT fk_class_program_subject_id FOREIGN KEY (subject_id) REFERENCES subjects(id),
    CONSTRAINT fk_class_program_subject_schedule_id FOREIGN KEY (schedule_id) REFERENCES subject_schedules(id),
    CONSTRAINT fk_class_program_users_approve_id FOREIGN KEY (approved_declined_by) REFERENCES users(id),
    CONSTRAINT unique_user_subject_schedule UNIQUE (user_id, subject_id, schedule_id)
);
