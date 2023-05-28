CREATE TABLE "choroby" (
  "id" integer PRIMARY KEY,
  "id_wirus" integer,
  "jednostka_chorobowa" varchar,
  "czynnik_etiologiczny" varchar,
  "objawy_ogolne_i_miejscowe_poza_ju" varchar,
  "objawy_miejscowe_w_ju" varchar,
  "rozpoznanie" varchar,
  "roznicowanie" varchar
);

CREATE TABLE "wirusy" (
  "id" integer,
  "nazwa_wirusa_lub_rodziny_wirusow" varchar,
  "skrot" varchar,
  "genom" varchar,
  "okres_wylegania_dni" varchar,
  "szczepionka" varchar,
  "droga_zakazenia" varchar,
  "jednostka_chorobowa" varchar
);

ALTER TABLE "choroby" ADD FOREIGN KEY ("id_wirus") REFERENCES "wirusy" ("id");
