CREATE TABLE "choroba" (
  "id" INTEGER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
  "id_wirus" integer,
  "choroba" varchar,
  "etiologia" varchar,
  "objawy_ogolne" varchar,
  "objawy_ju" varchar,
  "rozpoznanie" varchar,
  "roznicowanie" varchar
);

CREATE TABLE "wirus" (
  "id" INTEGER GENERATED BY DEFAULT AS IDENTITY PRIMARY KEY,
  "nazwa" varchar,
  "skrot" varchar,
  "genom" varchar,
  "wyleganie" varchar,
  "szczepionka" varchar,
  "droga_zak" varchar
);

ALTER TABLE "choroba" ADD FOREIGN KEY ("id_wirus") REFERENCES "wirus" ("id");
