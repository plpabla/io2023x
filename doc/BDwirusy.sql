CREATE TABLE choroba (
  id SERIAL PRIMARY KEY,
  id_wirus INTEGER,
  jednostka_chorobowa VARCHAR,
  objawy_ogolne_miejscowe VARCHAR,
  objawy_miejscowe_ju VARCHAR,
  rozpoznanie VARCHAR,
  roznicowanie VARCHAR,
  FOREIGN KEY (id_wirus) REFERENCES wirus (id)
);


CREATE TABLE wirus (
  id SERIAL PRIMARY KEY,
  nazwa VARCHAR,
  skrot VARCHAR,
  genom VARCHAR,
  wyleganie VARCHAR,
  szczepionka VARCHAR,
  droga_zak VARCHAR
);
