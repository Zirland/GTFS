#Instalace
V MySQL schématu je nutno vytvořit novou databázi

	create database GTFS;

a nastavit k databázi příslušná oprávnění.

V naší databázi byl pro přístup k těmto datům vytvořen užívatel `gtfs` s heslem `gtfs`.

Pro vytvoření tabulek na data potřebná k naplnění formátu GTFS Static je vytvořen soubor `schema.sql`. 

**Skript nevytváří soubor `feed_info.txt`, proto není pro  tato data vytvořena tabulka.**

Vytvoření tabulek provedeme zadáním příkazu

	mysql -u gtfs -p GTFS < schema.sql

a potvrdíme zadáním hesla.
