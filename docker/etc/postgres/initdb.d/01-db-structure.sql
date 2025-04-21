CREATE TYPE USER_STATUS_ENUM AS ENUM ('Validation', 'Confirmed', 'Banned');

CREATE TABLE users
(
    uuid UUID NOT NULL,
    login VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    status USER_STATUS_ENUM NOT NULL,
    sign_up_datetime_utc TIMESTAMP NOT NULL,
    sign_in_datetime_utc TIMESTAMP NULL,
    CONSTRAINT uuid PRIMARY KEY (uuid),
    CONSTRAINT login UNIQUE (login)
);

COMMENT ON TABLE users IS 'Таблица пользователей';
COMMENT ON COLUMN users.uuid IS 'UUD пользователя';
COMMENT ON COLUMN users.login IS 'Логин пользователя';
COMMENT ON COLUMN users.password_hash IS 'Хеш пользователя';
COMMENT ON COLUMN users.email IS 'Адрес электронной почты';
COMMENT ON COLUMN users.status IS 'Статус (Validation, Confirmed, Banned)';
COMMENT ON COLUMN users.sign_up_datetime_utc IS 'Дата и время регистрации в UTC';
COMMENT ON COLUMN users.sign_in_datetime_utc IS 'Дата и время последнего входа в UTC';

DROP TABLE IF EXISTS refresh_tokens;
CREATE TABLE refresh_tokens
(
    token_uuid UUID NOT NULL,
    user_uuid UUID NOT NULL,
    created_datetime_utc TIMESTAMP NOT NULL,
    expiration_datetime_utc TIMESTAMP NOT NULL,
    CONSTRAINT token_uuid PRIMARY KEY (token_uuid)
);

CREATE INDEX idx_user_uuid ON refresh_tokens (user_uuid);

COMMENT ON TABLE refresh_tokens IS 'Таблица содержит refresh-токены пользователей';
COMMENT ON COLUMN refresh_tokens.token_uuid IS 'UUD токена';
COMMENT ON COLUMN refresh_tokens.user_uuid IS 'UUD пользователя';
COMMENT ON COLUMN refresh_tokens.created_datetime_utc IS 'Дата и время создания токена';
COMMENT ON COLUMN refresh_tokens.expiration_datetime_utc IS 'Дата и время истечения срока действия токена';