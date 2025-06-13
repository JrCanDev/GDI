-- Adminer 4.8.1 PostgreSQL 16.0 (Debian 16.0-1.pgdg120+1) dump

DROP TABLE IF EXISTS "annee_scolaire";
CREATE TABLE "public"."annee_scolaire" (
    "id_as" character varying(10) NOT NULL,
    CONSTRAINT "annee_scolaire_pkey" PRIMARY KEY ("id_as")
) WITH (oids = false);


DROP VIEW IF EXISTS "cm_par_enseignant";
CREATE TABLE "cm_par_enseignant" ("id_ens" character varying(4), "tps_cm" double precision);


DROP VIEW IF EXISTS "cm_par_enseignant_par_enseignement";
CREATE TABLE "cm_par_enseignant_par_enseignement" ("id_ens" character varying(4), "id_cours" character varying(225), "tps_cm" double precision);


DROP TABLE IF EXISTS "competences";
CREATE TABLE "public"."competences" (
    "id_comp" character varying(100) NOT NULL,
    CONSTRAINT "competences_pkey" PRIMARY KEY ("id_comp")
) WITH (oids = false);


DROP TABLE IF EXISTS "cours";
CREATE TABLE "public"."cours" (
    "id_cours" character varying(25) NOT NULL,
    "intitule_cours" character varying(100) NOT NULL,
    "commentaire_cours" character varying(4096),
    CONSTRAINT "cours_pkey" PRIMARY KEY ("id_cours")
) WITH (oids = false);


DROP VIEW IF EXISTS "details";
CREATE TABLE "details" ("id_ens" character varying(4), "nom_ens" character varying(100), "prenom_ens" character varying(100), "id_cours" character varying(25), "intitule_cours" character varying(100), "commentaire_cours" character varying(4096), "annee_scolaire" character varying(10), "type_seance" character varying(25), "duree_seance" double precision, "mutualisation" character varying(25), "commentaire_seance" character varying(4096), "paiement" character varying(64));


DROP TABLE IF EXISTS "enseignants";
CREATE TABLE "public"."enseignants" (
    "id_ens" character varying(4) NOT NULL,
    "titulaire_ens" boolean DEFAULT false NOT NULL,
    "nom_ens" character varying(100) NOT NULL,
    "prenom_ens" character varying(100) NOT NULL,
    "tel_ens" character varying(14),
    "mail_ens" character varying(100),
    "ville_ens" character varying(100),
    "commentaire_ens" character varying(4096),
    CONSTRAINT "enseignants_pkey" PRIMARY KEY ("id_ens")
) WITH (oids = false);


DROP VIEW IF EXISTS "evt_par_enseignant";
CREATE TABLE "evt_par_enseignant" ("id_ens" character varying(4), "tps_evt" double precision);


DROP VIEW IF EXISTS "evt_par_enseignantf_par_enseignement";
CREATE TABLE "evt_par_enseignantf_par_enseignement" ("id_ens" character varying(4), "id_cours" character varying(225), "tps_evt" double precision);


DROP TABLE IF EXISTS "formation_groupe";
CREATE TABLE "public"."formation_groupe" (
    "id_form" character varying(16) NOT NULL,
    "type_seance" character varying(100) NOT NULL,
    CONSTRAINT "formation_groupe_pkey" PRIMARY KEY ("id_form", "type_seance")
) WITH (oids = false);

CREATE INDEX "formation_groupe_id_form" ON "public"."formation_groupe" USING btree ("id_form");

CREATE INDEX "formation_groupe_type_seance" ON "public"."formation_groupe" USING btree ("type_seance");


DROP TABLE IF EXISTS "formations";
CREATE TABLE "public"."formations" (
    "id_form" character varying(16) NOT NULL,
    CONSTRAINT "formations_pkey" PRIMARY KEY ("id_form")
) WITH (oids = false);


DROP VIEW IF EXISTS "hq_par_enseignant";
CREATE TABLE "hq_par_enseignant" ("id_ens" character varying(4), "tps_hq" double precision);


DROP VIEW IF EXISTS "hq_par_enseignant_par_enseignement";
CREATE TABLE "hq_par_enseignant_par_enseignement" ("id_ens" character varying(4), "id_cours" character varying(225), "tps_hq" double precision);


DROP TABLE IF EXISTS "maquette";
CREATE TABLE "public"."maquette" (
    "annee_scolaire" character varying(9) NOT NULL,
    "num_sem" character varying(7) NOT NULL,
    "id_cours" character varying(20) NOT NULL,
    "type_seance" character varying(20) NOT NULL,
    "id_ens" character varying(3) NOT NULL,
    "duree_semaine" double precision NOT NULL,
    CONSTRAINT "maquette_annee_scolaire_num_sem_id_cours_type_seance_id_ens" PRIMARY KEY ("annee_scolaire", "num_sem", "id_cours", "type_seance", "id_ens")
) WITH (oids = false);

CREATE INDEX "maquette_annee_scolaire" ON "public"."maquette" USING btree ("annee_scolaire");

CREATE INDEX "maquette_annee_scolaire_num_sem_id_cours" ON "public"."maquette" USING btree ("annee_scolaire", "num_sem", "id_cours");

CREATE INDEX "maquette_annee_scolaire_num_sem_id_cours_type_seance" ON "public"."maquette" USING btree ("annee_scolaire", "num_sem", "id_cours", "type_seance");

CREATE INDEX "maquette_id_cours" ON "public"."maquette" USING btree ("id_cours");

CREATE INDEX "maquette_id_ens" ON "public"."maquette" USING btree ("id_ens");

CREATE INDEX "maquette_num_sem" ON "public"."maquette" USING btree ("num_sem");

CREATE INDEX "maquette_type_seance" ON "public"."maquette" USING btree ("type_seance");


DROP VIEW IF EXISTS "maquette_ens";
CREATE TABLE "maquette_ens" ("annee_scolaire" character varying(9), "num_sem" character varying(7), "id_cours" character varying(20), "type_seance" character varying(20), "id_ens" character varying(3), "duree_semaine" double precision, "nom_ens" character varying(100), "prenom_ens" character varying(100), "intitule_cours" character varying(100), "commentaire_cours" character varying(4096));


DROP TABLE IF EXISTS "possede";
CREATE TABLE "public"."possede" (
    "id_ens" character varying(4) NOT NULL,
    "competence" character varying(100) NOT NULL,
    CONSTRAINT "possede_pkey" PRIMARY KEY ("id_ens", "competence")
) WITH (oids = false);

CREATE INDEX "possede_competence" ON "public"."possede" USING btree ("competence");

CREATE INDEX "possede_id_ens" ON "public"."possede" USING btree ("id_ens");


DROP VIEW IF EXISTS "privileges_utilisateurs";
CREATE TABLE "privileges_utilisateurs" ("nom_util" character varying(50), "vacataire" boolean, "budget" boolean, "admin" boolean);


DROP VIEW IF EXISTS "rnt_par_enseignant";
CREATE TABLE "rnt_par_enseignant" ("id_ens" character varying(4), "tps_rnt" double precision);


DROP VIEW IF EXISTS "rnt_par_enseignant_par_enseignement";
CREATE TABLE "rnt_par_enseignant_par_enseignement" ("id_ens" character varying(4), "id_cours" character varying(225), "tps_rnt" double precision);


DROP VIEW IF EXISTS "sae_par_enseignant";
CREATE TABLE "sae_par_enseignant" ("id_ens" character varying(4), "tps_sae" double precision);


DROP VIEW IF EXISTS "sae_par_enseignant_par_enseignement";
CREATE TABLE "sae_par_enseignant_par_enseignement" ("id_ens" character varying(4), "id_cours" character varying(225), "tps_sae" double precision);


DROP TABLE IF EXISTS "seances";
CREATE TABLE "public"."seances" (
    "annee_scolaire" character varying(10) NOT NULL,
    "id_ens" character varying(4) NOT NULL,
    "id_cours" character varying(225) NOT NULL,
    "type_seance" character varying(25) NOT NULL,
    "duree_seance" double precision NOT NULL,
    "mutualisation" character varying(25),
    "commentaire_seance" character varying(4096),
    "paiement" character varying(64) DEFAULT 'BDC' NOT NULL,
    CONSTRAINT "seances_pkey" PRIMARY KEY ("annee_scolaire", "id_ens", "id_cours", "type_seance", "paiement")
) WITH (oids = false);

CREATE INDEX "seances_annee_scolaire" ON "public"."seances" USING btree ("annee_scolaire");

CREATE INDEX "seances_annee_scolaire_id_cours_type_seance" ON "public"."seances" USING btree ("annee_scolaire", "id_cours", "type_seance");

CREATE INDEX "seances_id_cours" ON "public"."seances" USING btree ("id_cours");

CREATE INDEX "seances_id_ens" ON "public"."seances" USING btree ("id_ens");

CREATE INDEX "seances_paiement" ON "public"."seances" USING btree ("paiement");

CREATE INDEX "seances_type_seance" ON "public"."seances" USING btree ("type_seance");


DROP TABLE IF EXISTS "semaines";
CREATE TABLE "public"."semaines" (
    "num_sem" character varying(7) NOT NULL,
    CONSTRAINT "semaines_pkey" PRIMARY KEY ("num_sem")
) WITH (oids = false);


DROP TABLE IF EXISTS "statut";
CREATE TABLE "public"."statut" (
    "id_status" character varying(20) NOT NULL,
    "nb_heures_statut" integer NOT NULL,
    "commentaire_statut" character varying(4096),
    CONSTRAINT "statut_pkey" PRIMARY KEY ("id_status")
) WITH (oids = false);


DROP VIEW IF EXISTS "td_par_enseignant";
CREATE TABLE "td_par_enseignant" ("id_ens" character varying(4), "tps_td" double precision);


DROP VIEW IF EXISTS "td_par_enseignant_par_enseignement";
CREATE TABLE "td_par_enseignant_par_enseignement" ("id_ens" character varying(4), "id_cours" character varying(225), "tps_td" double precision);


DROP VIEW IF EXISTS "tp_par_enseignant";
CREATE TABLE "tp_par_enseignant" ("id_ens" character varying(4), "tps_tp" double precision);


DROP VIEW IF EXISTS "tp_par_enseignant_par_enseignement";
CREATE TABLE "tp_par_enseignant_par_enseignement" ("id_ens" character varying(4), "id_cours" character varying(225), "tps_tp" double precision);


DROP VIEW IF EXISTS "tps_par_enseignant_par_type";
CREATE TABLE "tps_par_enseignant_par_type" ("id_ens" character varying(4), "type_seance" character varying(25), "annee_scolaire" character varying(10), "duree_totale" double precision);


DROP TABLE IF EXISTS "type_seance";
CREATE TABLE "public"."type_seance" (
    "id_ts" character varying(100) NOT NULL,
    CONSTRAINT "type_seance_pkey" PRIMARY KEY ("id_ts")
) WITH (oids = false);


DROP TABLE IF EXISTS "utilisateurs";
CREATE TABLE "public"."utilisateurs" (
    "nom_util" character varying(50) NOT NULL,
    "mdp" character varying(50) NOT NULL,
    "id_ens" character varying(4) NOT NULL,
    "admin" boolean DEFAULT false NOT NULL,
    "vacataire" boolean DEFAULT false NOT NULL,
    "budget" boolean DEFAULT false NOT NULL,
    CONSTRAINT "utilisateurs_nom_util" PRIMARY KEY ("nom_util")
) WITH (oids = false);

CREATE INDEX "utilisateurs_id_ens" ON "public"."utilisateurs" USING btree ("id_ens");


DELIMITER ;;

CREATE TRIGGER "utilisateur_check_vacataire_trigger" BEFORE INSERT ON "public"."utilisateurs" FOR EACH ROW EXECUTE FUNCTION utilisateur_check_vacataire();;

DELIMITER ;

DROP TABLE IF EXISTS "volume_pn";
CREATE TABLE "public"."volume_pn" (
    "id_cours" character varying(25) NOT NULL,
    "volume" real NOT NULL,
    CONSTRAINT "volume_PN_pkey" PRIMARY KEY ("id_cours")
) WITH (oids = false);


DROP VIEW IF EXISTS "vw_h_dec";
CREATE TABLE "vw_h_dec" ("id_cours" character varying(225), "cm" double precision, "tdtp" double precision);


DROP VIEW IF EXISTS "vw_h_dec_cm";
CREATE TABLE "vw_h_dec_cm" ("id_cours" character varying(225), "cm" double precision);


DROP VIEW IF EXISTS "vw_h_dec_tdtp";
CREATE TABLE "vw_h_dec_tdtp" ("id_cours" character varying(225), "tdtp" double precision);


ALTER TABLE ONLY "public"."formation_groupe" ADD CONSTRAINT "formation_groupe_id_form_fkey" FOREIGN KEY (id_form) REFERENCES formations(id_form) ON UPDATE CASCADE NOT DEFERRABLE;
ALTER TABLE ONLY "public"."formation_groupe" ADD CONSTRAINT "formation_groupe_type_seance_fkey" FOREIGN KEY (type_seance) REFERENCES type_seance(id_ts) ON UPDATE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "public"."maquette" ADD CONSTRAINT "maquette_annee_scolaire_fkey" FOREIGN KEY (annee_scolaire) REFERENCES annee_scolaire(id_as) ON UPDATE CASCADE NOT DEFERRABLE;
ALTER TABLE ONLY "public"."maquette" ADD CONSTRAINT "maquette_id_cours_fkey" FOREIGN KEY (id_cours) REFERENCES cours(id_cours) ON UPDATE CASCADE NOT DEFERRABLE;
ALTER TABLE ONLY "public"."maquette" ADD CONSTRAINT "maquette_id_ens_fkey" FOREIGN KEY (id_ens) REFERENCES enseignants(id_ens) ON UPDATE CASCADE NOT DEFERRABLE;
ALTER TABLE ONLY "public"."maquette" ADD CONSTRAINT "maquette_num_sem_fkey" FOREIGN KEY (num_sem) REFERENCES semaines(num_sem) ON UPDATE CASCADE NOT DEFERRABLE;
ALTER TABLE ONLY "public"."maquette" ADD CONSTRAINT "maquette_type_seance_fkey" FOREIGN KEY (type_seance) REFERENCES type_seance(id_ts) ON UPDATE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "public"."possede" ADD CONSTRAINT "possede_competence_fkey" FOREIGN KEY (competence) REFERENCES competences(id_comp) ON UPDATE CASCADE NOT DEFERRABLE;
ALTER TABLE ONLY "public"."possede" ADD CONSTRAINT "possede_id_ens_fkey" FOREIGN KEY (id_ens) REFERENCES enseignants(id_ens) ON UPDATE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "public"."seances" ADD CONSTRAINT "seances_annee_scolaire_fkey" FOREIGN KEY (annee_scolaire) REFERENCES annee_scolaire(id_as) ON UPDATE CASCADE NOT DEFERRABLE;
ALTER TABLE ONLY "public"."seances" ADD CONSTRAINT "seances_id_cours_fkey" FOREIGN KEY (id_cours) REFERENCES cours(id_cours) ON UPDATE CASCADE NOT DEFERRABLE;
ALTER TABLE ONLY "public"."seances" ADD CONSTRAINT "seances_id_ens_fkey" FOREIGN KEY (id_ens) REFERENCES enseignants(id_ens) ON UPDATE CASCADE NOT DEFERRABLE;
ALTER TABLE ONLY "public"."seances" ADD CONSTRAINT "seances_type_seance_fkey" FOREIGN KEY (type_seance) REFERENCES type_seance(id_ts) ON UPDATE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "public"."utilisateurs" ADD CONSTRAINT "utilisateurs_id_ens_fkey" FOREIGN KEY (id_ens) REFERENCES enseignants(id_ens) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "public"."volume_pn" ADD CONSTRAINT "volume_pn_id_cours_fkey" FOREIGN KEY (id_cours) REFERENCES cours(id_cours) ON UPDATE CASCADE NOT DEFERRABLE;

DROP TABLE IF EXISTS "cm_par_enseignant";
CREATE VIEW "cm_par_enseignant" AS SELECT id_ens,
    sum(duree_seance) AS tps_cm
   FROM seances
  WHERE ((type_seance)::text = 'CM'::text)
  GROUP BY id_ens;

DROP TABLE IF EXISTS "cm_par_enseignant_par_enseignement";
CREATE VIEW "cm_par_enseignant_par_enseignement" AS SELECT id_ens,
    id_cours,
    sum(duree_seance) AS tps_cm
   FROM seances
  WHERE ((type_seance)::text = 'CM'::text)
  GROUP BY id_ens, id_cours;

DROP TABLE IF EXISTS "details";
CREATE VIEW "details" AS SELECT e.id_ens,
    e.nom_ens,
    e.prenom_ens,
    c.id_cours,
    c.intitule_cours,
    c.commentaire_cours,
    a.annee_scolaire,
    a.type_seance,
    a.duree_seance,
    a.mutualisation,
    a.commentaire_seance,
    a.paiement
   FROM ((enseignants e
     JOIN seances a ON (((e.id_ens)::text = (a.id_ens)::text)))
     JOIN cours c ON (((a.id_cours)::text = (c.id_cours)::text)))
  ORDER BY e.id_ens, c.id_cours;

DROP TABLE IF EXISTS "evt_par_enseignant";
CREATE VIEW "evt_par_enseignant" AS SELECT id_ens,
    sum(duree_seance) AS tps_evt
   FROM seances
  WHERE ((type_seance)::text = 'EVT'::text)
  GROUP BY id_ens;

DROP TABLE IF EXISTS "evt_par_enseignantf_par_enseignement";
CREATE VIEW "evt_par_enseignantf_par_enseignement" AS SELECT id_ens,
    id_cours,
    sum(duree_seance) AS tps_evt
   FROM seances
  WHERE ((type_seance)::text = 'EVT'::text)
  GROUP BY id_ens, id_cours;

DROP TABLE IF EXISTS "hq_par_enseignant";
CREATE VIEW "hq_par_enseignant" AS SELECT id_ens,
    sum(duree_seance) AS tps_hq
   FROM seances
  WHERE ((type_seance)::text = 'Hors-quota'::text)
  GROUP BY id_ens;

DROP TABLE IF EXISTS "hq_par_enseignant_par_enseignement";
CREATE VIEW "hq_par_enseignant_par_enseignement" AS SELECT id_ens,
    id_cours,
    sum(duree_seance) AS tps_hq
   FROM seances
  WHERE ((type_seance)::text = 'Hors-quota'::text)
  GROUP BY id_ens, id_cours;

DROP TABLE IF EXISTS "maquette_ens";
CREATE VIEW "maquette_ens" AS SELECT maquette.annee_scolaire,
    maquette.num_sem,
    maquette.id_cours,
    maquette.type_seance,
    maquette.id_ens,
    maquette.duree_semaine,
    enseignants.nom_ens,
    enseignants.prenom_ens,
    cours.intitule_cours,
    cours.commentaire_cours
   FROM ((maquette
     JOIN enseignants ON (((maquette.id_ens)::text = (enseignants.id_ens)::text)))
     JOIN cours ON (((maquette.id_cours)::text = (cours.id_cours)::text)));

DROP TABLE IF EXISTS "privileges_utilisateurs";
CREATE VIEW "privileges_utilisateurs" AS SELECT nom_util,
    vacataire,
    budget,
    admin
   FROM utilisateurs;

DROP TABLE IF EXISTS "rnt_par_enseignant";
CREATE VIEW "rnt_par_enseignant" AS SELECT id_ens,
    sum(duree_seance) AS tps_rnt
   FROM seances
  WHERE ((type_seance)::text = 'RNT'::text)
  GROUP BY id_ens;

DROP TABLE IF EXISTS "rnt_par_enseignant_par_enseignement";
CREATE VIEW "rnt_par_enseignant_par_enseignement" AS SELECT id_ens,
    id_cours,
    sum(duree_seance) AS tps_rnt
   FROM seances
  WHERE ((type_seance)::text = 'RNT'::text)
  GROUP BY id_ens, id_cours;

DROP TABLE IF EXISTS "sae_par_enseignant";
CREATE VIEW "sae_par_enseignant" AS SELECT id_ens,
    sum(duree_seance) AS tps_sae
   FROM seances
  WHERE ((type_seance)::text ~~ 'SAE%'::text)
  GROUP BY id_ens;

DROP TABLE IF EXISTS "sae_par_enseignant_par_enseignement";
CREATE VIEW "sae_par_enseignant_par_enseignement" AS SELECT id_ens,
    id_cours,
    sum(duree_seance) AS tps_sae
   FROM seances
  WHERE ((type_seance)::text ~~ 'SAE%'::text)
  GROUP BY id_ens, id_cours;

DROP TABLE IF EXISTS "td_par_enseignant";
CREATE VIEW "td_par_enseignant" AS SELECT id_ens,
    sum(duree_seance) AS tps_td
   FROM seances
  WHERE ((type_seance)::text ~~ 'TD%'::text)
  GROUP BY id_ens;

DROP TABLE IF EXISTS "td_par_enseignant_par_enseignement";
CREATE VIEW "td_par_enseignant_par_enseignement" AS SELECT id_ens,
    id_cours,
    sum(duree_seance) AS tps_td
   FROM seances
  WHERE ((type_seance)::text ~~ 'TD%'::text)
  GROUP BY id_ens, id_cours;

DROP TABLE IF EXISTS "tp_par_enseignant";
CREATE VIEW "tp_par_enseignant" AS SELECT id_ens,
    sum(duree_seance) AS tps_tp
   FROM seances
  WHERE ((type_seance)::text ~~ 'TP%'::text)
  GROUP BY id_ens;

DROP TABLE IF EXISTS "tp_par_enseignant_par_enseignement";
CREATE VIEW "tp_par_enseignant_par_enseignement" AS SELECT id_ens,
    id_cours,
    sum(duree_seance) AS tps_tp
   FROM seances
  WHERE ((type_seance)::text ~~ 'TP%'::text)
  GROUP BY id_ens, id_cours;

DROP TABLE IF EXISTS "tps_par_enseignant_par_type";
CREATE VIEW "tps_par_enseignant_par_type" AS SELECT id_ens,
    type_seance,
    annee_scolaire,
    sum(duree_seance) AS duree_totale
   FROM details
  GROUP BY id_ens, annee_scolaire, type_seance;

DROP TABLE IF EXISTS "vw_h_dec";
CREATE VIEW "vw_h_dec" AS SELECT a.id_cours,
    a.cm,
    b.tdtp
   FROM (vw_h_dec_cm a
     JOIN vw_h_dec_tdtp b ON (((a.id_cours)::text = (b.id_cours)::text)))
  ORDER BY a.id_cours;

DROP TABLE IF EXISTS "vw_h_dec_cm";
CREATE VIEW "vw_h_dec_cm" AS SELECT id_cours,
    sum(duree_seance) AS cm
   FROM seances
  WHERE ((type_seance)::text ~~* 'CM%'::text)
  GROUP BY id_cours
  ORDER BY id_cours;

DROP TABLE IF EXISTS "vw_h_dec_tdtp";
CREATE VIEW "vw_h_dec_tdtp" AS SELECT id_cours,
    sum(duree_seance) AS tdtp
   FROM seances
  WHERE (((type_seance)::text ~~* 'TD%'::text) OR ((type_seance)::text ~~* 'TP%'::text))
  GROUP BY id_cours
  ORDER BY id_cours;

-- 2025-06-13 13:36:24.177774+00
