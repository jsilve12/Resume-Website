CREATE TABLE users(
  user_id INTEGER NOT NULL AUTO_INCREMENT,
  name Varchar(255),
  email Varchar(255),
  password Varchar(255),
    PRIMARY KEY(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Profile(
	profile_id INTEGER NOT NULL AUTO_INCREMENT,
  user_id INTEGER NOT NULL,
	first_name Varchar(255),
  last_name Varchar(255),
	email VARCHAR(255),
	headline VARCHAR(255),
	summary VARCHAR(1024),
    PRIMARY KEY(profile_id),
  CONSTRAINT profile_ibfk_1
    FOREIGN KEY(user_id)
    REFERENCES users(user_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Education(
  prim INTEGER NOT NULL AUTO_INCREMENT,
  profile_id INTEGER,
  name VARCHAR(255),
  Years VARCHAR(255),
  Degree VARCHAR(255),
  Description VARCHAR(255),
  GPA Double,
    PRIMARY KEY(prim),
  CONSTRAINT education_ibfk_1
    FOREIGN KEY(profile_id)
    REFERENCES Profile(profile_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Posit(
  prim INTEGER NOT NULL AUTO_INCREMENT,
  profile_id INTEGER,
  years VARCHAR(255),
  header VARCHAR(255),
  description VARCHAR(512),
  PRIMARY KEY(prim),
  CONSTRAINT position_ibfk_1
    FOREIGN KEY (profile_id)
    REFERENCES Profile(profile_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Skills(
  prim INTEGER NOT NULL AUTO_INCREMENT,
  profile_id INTEGER,
  Skill VARCHAR(255),
  Description VARCHAR(255),
  PRIMARY KEY(prim),
  CONSTRAINT skill_ibfk_1
    FOREIGN KEY (profile_id)
    REFERENCES Profile(profile_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Interest(
  prim INTEGER NOT NULL AUTO_INCREMENT,
  profile_id INTEGER,
  description VARCHAR(255),
  PRIMARY KEY(prim),
  CONSTRAINT interest_ibfk_1
    FOREIGN KEY(profile_id)
    REFERENCES Profile(profile_id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
