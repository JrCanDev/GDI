-- Suppression des tables et vues existantes (ordre inverse de dépendance)
DROP VIEW IF EXISTS vw_h_dec, vw_h_dec_tdtp, vw_h_dec_cm, tps_par_enseignant_par_type,
  tp_par_enseignant_par_enseignement, tp_par_enseignant, td_par_enseignant_par_enseignement,
  td_par_enseignant, sae_par_enseignant_par_enseignement, sae_par_enseignant,
  rnt_par_enseignant_par_enseignement, rnt_par_enseignant, privileges_utilisateurs,
  maquette_ens, hq_par_enseignant_par_enseignement, hq_par_enseignant,
  evt_par_enseignant_par_enseignement, evt_par_enseignant,
  details, cm_par_enseignant_par_enseignement, cm_par_enseignant;

DROP TABLE IF EXISTS volume_pn, utilisateurs, statut, semaines, seances, possede,
  maquette, formation_groupe, formations, type_seance, enseignants, cours,
  competences, annee_scolaire;

-- TABLES DE BASE
CREATE TABLE annee_scolaire (
  id_as VARCHAR(10) PRIMARY KEY
);

CREATE TABLE competences (
  id_comp VARCHAR(100) PRIMARY KEY
);

CREATE TABLE cours (
  id_cours VARCHAR(25) PRIMARY KEY,
  intitule_cours VARCHAR(100) NOT NULL,
  commentaire_cours VARCHAR(4096)
);

CREATE TABLE enseignants (
  id_ens VARCHAR(4) PRIMARY KEY,
  titulaire_ens BOOLEAN DEFAULT FALSE NOT NULL,
  nom_ens VARCHAR(100) NOT NULL,
  prenom_ens VARCHAR(100) NOT NULL,
  tel_ens VARCHAR(14),
  mail_ens VARCHAR(100),
  ville_ens VARCHAR(100),
  commentaire_ens VARCHAR(4096)
);

CREATE TABLE type_seance (
  id_ts VARCHAR(100) PRIMARY KEY
);

CREATE TABLE formations (
  id_form VARCHAR(16) PRIMARY KEY
);

CREATE TABLE formation_groupe (
  id_form VARCHAR(16) NOT NULL,
  type_seance VARCHAR(100) NOT NULL,
  PRIMARY KEY (id_form, type_seance),
  FOREIGN KEY (id_form) REFERENCES formations(id_form) ON UPDATE CASCADE,
  FOREIGN KEY (type_seance) REFERENCES type_seance(id_ts) ON UPDATE CASCADE
);

CREATE TABLE semaines (
  num_sem VARCHAR(7) PRIMARY KEY
);

CREATE TABLE maquette (
  annee_scolaire VARCHAR(9) NOT NULL,
  num_sem VARCHAR(7) NOT NULL,
  id_cours VARCHAR(20) NOT NULL,
  type_seance VARCHAR(20) NOT NULL,
  id_ens VARCHAR(4) NOT NULL,
  duree_semaine DOUBLE PRECISION NOT NULL,
  PRIMARY KEY (annee_scolaire, num_sem, id_cours, type_seance, id_ens),
  FOREIGN KEY (annee_scolaire) REFERENCES annee_scolaire(id_as) ON UPDATE CASCADE,
  FOREIGN KEY (num_sem) REFERENCES semaines(num_sem) ON UPDATE CASCADE,
  FOREIGN KEY (id_cours) REFERENCES cours(id_cours) ON UPDATE CASCADE,
  FOREIGN KEY (type_seance) REFERENCES type_seance(id_ts) ON UPDATE CASCADE,
  FOREIGN KEY (id_ens) REFERENCES enseignants(id_ens) ON UPDATE CASCADE
);

CREATE TABLE possede (
  id_ens VARCHAR(4) NOT NULL,
  competence VARCHAR(100) NOT NULL,
  PRIMARY KEY (id_ens, competence),
  FOREIGN KEY (id_ens) REFERENCES enseignants(id_ens) ON UPDATE CASCADE,
  FOREIGN KEY (competence) REFERENCES competences(id_comp) ON UPDATE CASCADE
);

CREATE TABLE seances (
  annee_scolaire VARCHAR(10) NOT NULL,
  id_ens VARCHAR(4) NOT NULL,
  id_cours VARCHAR(225) NOT NULL,
  type_seance VARCHAR(25) NOT NULL,
  duree_seance DOUBLE PRECISION NOT NULL,
  mutualisation VARCHAR(25),
  commentaire_seance VARCHAR(4096),
  paiement VARCHAR(64) DEFAULT 'BDC' NOT NULL,
  PRIMARY KEY (annee_scolaire, id_ens, id_cours, type_seance, paiement),
  FOREIGN KEY (annee_scolaire) REFERENCES annee_scolaire(id_as) ON UPDATE CASCADE,
  FOREIGN KEY (id_ens) REFERENCES enseignants(id_ens) ON UPDATE CASCADE,
  FOREIGN KEY (id_cours) REFERENCES cours(id_cours) ON UPDATE CASCADE,
  FOREIGN KEY (type_seance) REFERENCES type_seance(id_ts) ON UPDATE CASCADE
);

CREATE TABLE statut (
  id_status VARCHAR(20) PRIMARY KEY,
  nb_heures_statut INTEGER NOT NULL,
  commentaire_statut VARCHAR(4096)
);

CREATE TABLE utilisateurs (
  nom_util VARCHAR(50) PRIMARY KEY,
  mdp VARCHAR(50) NOT NULL,
  id_ens VARCHAR(4) NOT NULL,
  admin BOOLEAN DEFAULT FALSE NOT NULL,
  vacataire BOOLEAN DEFAULT FALSE NOT NULL,
  budget BOOLEAN DEFAULT FALSE NOT NULL,
  FOREIGN KEY (id_ens) REFERENCES enseignants(id_ens) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE volume_pn (
  id_cours VARCHAR(25) PRIMARY KEY,
  volume REAL NOT NULL,
  FOREIGN KEY (id_cours) REFERENCES cours(id_cours) ON UPDATE CASCADE
);

DROP FUNCTION IF EXISTS utilisateur_check_vacataire();
CREATE OR REPLACE FUNCTION utilisateur_check_vacataire()
RETURNS TRIGGER AS $$
DECLARE
  isVacataire BOOLEAN;
  nom VARCHAR(100);
  prenom VARCHAR(100);
BEGIN
  SELECT nom_ens, prenom_ens INTO nom, prenom FROM enseignants WHERE id_ens = NEW.id_ens;
  IF nom IS NOT NULL AND prenom IS NOT NULL THEN
    SELECT NOT titulaire_ens INTO isVacataire FROM enseignants WHERE id_ens = NEW.id_ens;
    NEW.vacataire := isVacataire;
    RETURN NEW;
  ELSE
    RETURN OLD;
  END IF;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS utilisateur_check_vacataire_trigger on utilisateurs;
CREATE TRIGGER utilisateur_check_vacataire_trigger
BEFORE INSERT ON utilisateurs
FOR EACH ROW
EXECUTE FUNCTION utilisateur_check_vacataire();

-- VUES DE SYNTHESE
CREATE VIEW cm_par_enseignant AS
SELECT id_ens, SUM(duree_seance) AS tps_cm
FROM seances WHERE type_seance = 'CM'
GROUP BY id_ens;

CREATE VIEW cm_par_enseignant_par_enseignement AS
SELECT id_ens, id_cours, SUM(duree_seance) AS tps_cm
FROM seances WHERE type_seance = 'CM'
GROUP BY id_ens, id_cours;

CREATE VIEW details AS
SELECT e.id_ens, e.nom_ens, e.prenom_ens, c.id_cours, c.intitule_cours,
       c.commentaire_cours, a.annee_scolaire, a.type_seance, a.duree_seance,
       a.mutualisation, a.commentaire_seance, a.paiement
FROM enseignants e
JOIN seances a ON e.id_ens = a.id_ens
JOIN cours c ON a.id_cours = c.id_cours
ORDER BY e.id_ens, c.id_cours;

CREATE VIEW evt_par_enseignant AS
SELECT id_ens, SUM(duree_seance) AS tps_evt
FROM seances
WHERE type_seance = 'EVT'
GROUP BY id_ens;

CREATE VIEW evt_par_enseignant_par_enseignement AS
SELECT id_ens, id_cours, SUM(duree_seance) AS tps_evt
FROM seances
WHERE type_seance = 'EVT'
GROUP BY id_ens, id_cours;

CREATE VIEW hq_par_enseignant AS
SELECT id_ens, SUM(duree_seance) AS tps_hq
FROM seances
WHERE type_seance = 'Hors-quota'
GROUP BY id_ens;

CREATE VIEW hq_par_enseignant_par_enseignement AS
SELECT id_ens, id_cours, SUM(duree_seance) AS tps_hq
FROM seances
WHERE type_seance = 'Hors-quota'
GROUP BY id_ens, id_cours;

CREATE VIEW maquette_ens AS
SELECT m.annee_scolaire, m.num_sem, m.id_cours, m.type_seance, m.id_ens, m.duree_semaine, e.nom_ens, e.prenom_ens, c.intitule_cours, c.commentaire_cours
FROM maquette m
JOIN enseignants e ON m.id_ens = e.id_ens
JOIN cours c ON m.id_cours = c.id_cours;

CREATE VIEW privileges_utilisateurs AS
SELECT nom_util, vacataire, budget, admin
FROM utilisateurs;

CREATE VIEW rnt_par_enseignant AS
SELECT id_ens, SUM(duree_seance) AS tps_rnt
FROM seances
WHERE type_seance = 'RNT'
GROUP BY id_ens;

CREATE VIEW rnt_par_enseignant_par_enseignement AS
SELECT id_ens, id_cours, SUM(duree_seance) AS tps_rnt
FROM seances
WHERE type_seance = 'RNT'
GROUP BY id_ens, id_cours;

CREATE VIEW sae_par_enseignant AS
SELECT id_ens, SUM(duree_seance) AS tps_sae
FROM seances
WHERE type_seance LIKE 'SAE%'
GROUP BY id_ens;

CREATE VIEW sae_par_enseignant_par_enseignement AS
SELECT id_ens, id_cours, SUM(duree_seance) AS tps_sae
FROM seances
WHERE type_seance LIKE 'SAE%'
GROUP BY id_ens, id_cours;

CREATE VIEW td_par_enseignant AS
SELECT id_ens, SUM(duree_seance) AS tps_td
FROM seances
WHERE type_seance LIKE 'TD%'
GROUP BY id_ens;

CREATE VIEW td_par_enseignant_par_enseignement AS
SELECT id_ens, id_cours, SUM(duree_seance) AS tps_td
FROM seances
WHERE type_seance LIKE 'TD%'
GROUP BY id_ens, id_cours;

CREATE VIEW tp_par_enseignant AS
SELECT id_ens, SUM(duree_seance) AS tps_tp
FROM seances
WHERE type_seance LIKE 'TP%'
GROUP BY id_ens;

CREATE VIEW tp_par_enseignant_par_enseignement AS
SELECT id_ens, id_cours, SUM(duree_seance) AS tps_tp
FROM seances
WHERE type_seance LIKE 'TP%'
GROUP BY id_ens, id_cours;

CREATE VIEW tps_par_enseignant_par_type AS
SELECT id_ens, type_seance, annee_scolaire, SUM(duree_seance) AS duree_totale
FROM details
GROUP BY id_ens, annee_scolaire, type_seance;

CREATE VIEW vw_h_dec_cm AS
SELECT id_cours, SUM(duree_seance) AS cm
FROM seances WHERE type_seance LIKE 'CM%'
GROUP BY id_cours;

CREATE VIEW vw_h_dec_tdtp AS
SELECT id_cours, SUM(duree_seance) AS tdtp
FROM seances
WHERE type_seance LIKE 'TD%' OR type_seance LIKE 'TP%'
GROUP BY id_cours;

CREATE VIEW vw_h_dec AS
SELECT a.id_cours, a.cm, b.tdtp
FROM vw_h_dec_cm a
JOIN vw_h_dec_tdtp b ON a.id_cours = b.id_cours
ORDER BY a.id_cours;

INSERT INTO enseignants (id_ens, titulaire_ens, nom_ens, prenom_ens, tel_ens, mail_ens, ville_ens, commentaire_ens) VALUES
('0000',	't',	'utilisateur',	'super',	NULL,	NULL,	NULL,	NULL);

INSERT INTO utilisateurs (nom_util, mdp, id_ens, admin, vacataire, budget) VALUES
('admin',	'21232f297a57a5a743894a0e4a801fc3',	'0000',	't',	'f',	'f');
