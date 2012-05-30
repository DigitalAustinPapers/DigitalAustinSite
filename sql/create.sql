DROP TABLE PersonReference;
DROP TABLE PlaceReference;
DROP TABLE StemCount;
DROP TABLE Document;
DROP TABLE NormalizedPerson;
DROP TABLE NormalizedPlace;

CREATE  TABLE IF NOT EXISTS NormalizedPerson (
  id BIGINT NOT NULL AUTO_INCREMENT,
  name VARCHAR(200) NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB
COMMENT = 'Notable people related to the stored documents' ;

CREATE  TABLE IF NOT EXISTS NormalizedPlace (
  id BIGINT NOT NULL AUTO_INCREMENT,
  name VARCHAR(200) NULL ,
  lat FLOAT(10, 6),
  lng FLOAT(10, 6),
  PRIMARY KEY (id) )
ENGINE = InnoDB
COMMENT = 'Notable places related to the stored documents' ;

CREATE TABLE IF NOT EXISTS Idf (
  stem VARCHAR(100) NULL,
  idf double,
  PRIMARY KEY (stem)
)
ENGINE = InnoDB
COMMENT = 'The inverse document frequency of each stem' ;

CREATE  TABLE IF NOT EXISTS Document (
  id VARCHAR(100) NOT NULL ,
  xml MEDIUMTEXT NOT NULL,
  title VARCHAR(300) NOT NULL,
  summary TEXT,
  creation DATE,
  sentFromPlace BIGINT,
  sentToPlace BIGINT,
  vectorLength double,
  PRIMARY KEY (id),
  FOREIGN KEY (sentFromPlace) REFERENCES NormalizedPlace(id) ON DELETE SET NULL,
  FOREIGN KEY (sentToPlace) REFERENCES NormalizedPlace(id) ON DELETE SET NULL
)
ENGINE = InnoDB
COMMENT = 'The stored TEI compliant XML files of historical documents' ;

CREATE  TABLE IF NOT EXISTS PlaceReference (
  id BIGINT NOT NULL AUTO_INCREMENT,
  docId VARCHAR(100) NULL,
  normalId BIGINT NULL,
  text VARCHAR(200) NULL,
  PRIMARY KEY (id),
  INDEX (docId),
  FOREIGN KEY (docId) REFERENCES Document(id) ON DELETE CASCADE,
  FOREIGN KEY (normalId) REFERENCES NormalizedPlace(id) ON DELETE SET NULL)
ENGINE = InnoDB
COMMENT = 'A reference from a stored document to a place' ;

CREATE  TABLE IF NOT EXISTS PersonReference (
  id BIGINT NOT NULL AUTO_INCREMENT,
  docId VARCHAR(100) NULL,
  normalId BIGINT NULL,
  text VARCHAR(200) NULL,
  PRIMARY KEY (id), 
  INDEX (docId),
  FOREIGN KEY (docId) REFERENCES Document(id) ON DELETE CASCADE,
  FOREIGN KEY (normalId) REFERENCES NormalizedPerson(id) ON DELETE SET NULL)
ENGINE = InnoDB
COMMENT = 'A reference from a stored document to a person' ;


CREATE  TABLE IF NOT EXISTS StemCount (
  docId VARCHAR(100) NULL,
  stem VARCHAR(100) NULL,
  count int not NULL,
  tfIdf double,
  PRIMARY KEY (docId, stem),
  INDEX (stem),
  FOREIGN KEY (docId) REFERENCES Document(id) ON DELETE CASCADE)
ENGINE = InnoDB
COMMENT = 'A reference from a stored document to a place' ;

CREATE  TABLE IF NOT EXISTS WordCount (
  docId VARCHAR(100) NULL,
  word VARCHAR(100) NULL,
  count int not NULL,
  PRIMARY KEY (docId, word),
  INDEX (word),
  FOREIGN KEY (docId) REFERENCES Document(id) ON DELETE CASCADE)
ENGINE = InnoDB
COMMENT = 'A reference from a stored document to a place' ;

