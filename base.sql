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

CREATE TABLE operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE,
    est_interne INTEGER DEFAULT 0,              
    commission_pct REAL DEFAULT 0.0            
);

CREATE TABLE prefix (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    valeur VARCHAR(50) NOT NULL UNIQUE,
    idOperateur INTEGER NOT NULL,
    FOREIGN KEY (idOperateur) REFERENCES operateur(id) ON DELETE CASCADE
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
    montant REAL NOT NULL,                      -- Montant net envoyé/reçu/retiré
    numero_destinataire VARCHAR(50) DEFAULT NULL, -- Le numéro qui reçoit (si transfert)
    frais_notre_gain REAL DEFAULT 0.0,          -- Ce qui reste chez nous (notre gain)
    commission_operateur REAL DEFAULT 0.0,       -- Gain de l'autre opérateur (reversé)
    idOperationParent INTEGER DEFAULT NULL,       -- ID de regroupement pour les envois multiples
    date_operation TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idUser) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (idType) REFERENCES type(id) ON DELETE CASCADE,
    FOREIGN KEY (idOperationParent) REFERENCES operation(id) ON DELETE SET NULL
);

-- ==========================================
-- INSERTIONS INITIALES & DONNÉES DE TEST
-- ==========================================

-- Insertion des types d'opérations
INSERT INTO type (libelle) VALUES ('depot'), ('retrait'), ('transfert');

-- Configuration des opérateurs
INSERT INTO operateur (nom, est_interne, commission_pct) VALUES 
('Telmo', 1, 0.0),
('Airtel', 0, 5.0),   -- 5% de commission calculée par rapport au frais
('Orange', 0, 7.0);   -- 7% de commission calculée par rapport au frais

-- Configuration des préfixes
INSERT INTO prefix (valeur, idOperateur) VALUES 
('034', 1), -- Telmo (Interne)
('032', 2), -- Airtel (Externe)
('037', 3); -- Orange (Externe)

-- Grille des frais internes (Dépôts idType = 1, Retraits idType = 2, Transferts idType = 3)
INSERT INTO frais (idType, baremeMin, baremeMax, valeur_frais) VALUES 
(1, 100, 1000, 50), (1, 1001, 5000, 50), (1, 5001, 10000, 100), (1, 10001, 25000, 200), (1, 25001, 50000, 400),
(2, 100, 1000, 50), (2, 1001, 5000, 50), (2, 5001, 10000, 100), (2, 10001, 25000, 200), (2, 25001, 50000, 400),
(3, 100, 1000, 50), (3, 1001, 5000, 50), (3, 5001, 10000, 100), (3, 10001, 25000, 200), (3, 25001, 50000, 400);

-- Utilisateur de test
INSERT INTO user (numero) VALUES ('0000000000');