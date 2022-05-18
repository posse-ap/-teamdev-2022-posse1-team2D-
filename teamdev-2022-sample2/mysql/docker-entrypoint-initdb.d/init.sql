DROP SCHEMA IF EXISTS shukatsu;

CREATE SCHEMA shukatsu;

USE shukatsu;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO
    users
SET
    email = 'test@posse-ap.com',
    password = sha1('password');

DROP TABLE IF EXISTS events;

CREATE TABLE events (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO events SET title = 'イベント1';

INSERT INTO events SET title = 'イベント2';

INSERT INTO events SET title = 'イベント3';

DROP TABLE IF EXISTS agents;

CREATE TABLE agents (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    agent_name VARCHAR(255) NOT NULL,
    agent_url VARCHAR(8190) NOT NULL,
    representative VARCHAR(255) NOT NULL,
    contractor VARCHAR(255) NOT NULL,
    department VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone_number VARCHAR(255) UNIQUE NOT NULL,
    address VARCHAR(255) UNIQUE NOT NULL,
    post_period DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at INT DEFAULT 0
);

INSERT INTO
    agents (
        agent_name,
        agent_url,
        representative,
        contractor,
        department,
        email,
        phone_number,
        address,
        post_period,
        deleted_at
    )
VALUES
    (
        "ラクナビ",
        "rakunavi.com",
        "田中太郎",
        "佐藤次郎",
        "営業",
        "rakunavi@gmail.com",
        "0120-000-000",
        "サンプル",
        "2023-05-05"
    );

DROP TABLE IF EXISTS tags;

CREATE TABLE tags (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO tags (name) VALUES ("IT"), ("Finance"), ("Insurance"), ("文系");
DROP TABLE IF EXISTS agents_tags;

CREATE TABLE agents_tags (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    agent_id INT NOT NULL,
    tag_id INT NOT NULL
);

