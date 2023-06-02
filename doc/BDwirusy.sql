CREATE TABLE choroba (
  id SERIAL PRIMARY KEY,
  id_wirus INTEGER,
  choroba VARCHAR,
  objawy_ogolne VARCHAR,
  objawy_ju VARCHAR,
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
