DROP TABLE IF EXISTS merchants;

CREATE TABLE merchants (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT
);

DROP TABLE IF EXISTS transactions;

CREATE TABLE transactions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  merchant_id INTEGER,
  date TEXT,
  amount TEXT,
  currency TEXT
);