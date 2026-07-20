
CREATE TABLE type (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    idType INTEGER NOT NULL,
    baremeMin REAL NOT NULL,
    baremeMax REAL NOT NULL,
    valeur_frais REAL NOT NULL,
    FOREIGN KEY (idType) REFERENCES type(id) ON DELETE CASCADE
);

CREATE TABLE user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero VARCHAR(50) NOT NULL UNIQUE,
    date_creation TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    idUser INTEGER NOT NULL,                
    idType INTEGER NOT NULL,                
    montant REAL NOT NULL,                  
    date_operation TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idUser) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (idType) REFERENCES type(id) ON DELETE CASCADE
);

CREATE TABLE prefix(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    valeur VARCHAR(50)
);





-- 1. Insertion des préfixes d'opérateur
INSERT INTO prefix (valeur) VALUES 
('032'),
('037');

-- 2. Insertion des types d'opérations
INSERT INTO type (libelle) VALUES 
('depot'),
('retrait'),
('transfert');


-- Application du barème de frais pour les dépôts (idType = 1)
INSERT INTO frais (idType, baremeMin, baremeMax, valeur_frais) VALUES 
(1, 100, 1000, 50),
(1, 1001, 5000, 50),
(1, 5001, 10000, 100),
(1, 10001, 25000, 200),
(1, 25001, 50000, 400),
(1, 50010, 100000, 800),
(1, 100001, 250000, 1500),
(1, 250001, 500000, 1500),
(1, 500001, 1000000, 2500),
(1, 1000001, 2000000, 3000);

-- 3. Insertion de la grille des frais (barème de l'image)
-- On applique ici ce barème pour les retraits (idType = 2) à titre d'exemple
INSERT INTO frais (idType, baremeMin, baremeMax, valeur_frais) VALUES 
(2, 100, 1000, 50),
(2, 1001, 5000, 50),
(2, 5001, 10000, 100),
(2, 10001, 25000, 200),
(2, 25001, 50000, 400),
(2, 50010, 100000, 800),
(2, 100001, 250000, 1500),
(2, 250001, 500000, 1500),
(2, 500001, 1000000, 2500),
(2, 1000001, 2000000, 3000);

-- Si besoin, tu peux aussi dupliquer ces barèmes pour le transfert (idType = 3) :
INSERT INTO frais (idType, baremeMin, baremeMax, valeur_frais) VALUES 
(3, 100, 1000, 50),
(3, 1001, 5000, 50),
(3, 5001, 10000, 100),
(3, 10001, 25000, 200),
(3, 25001, 50000, 400),
(3, 50010, 100000, 800),
(3, 100001, 250000, 1500),
(3, 250001, 500000, 1500),
(3, 500001, 1000000, 2500),
(3, 1000001, 2000000, 3000);


-- 4. Insertion d'utilisateurs de test
INSERT INTO user (numero) VALUES 
('0000000000');

