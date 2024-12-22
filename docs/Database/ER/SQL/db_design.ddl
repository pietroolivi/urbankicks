-- *********************************************
-- * Standard SQL generation                   
-- *--------------------------------------------
-- * DB-MAIN version: 11.0.2              
-- * Generator date: Sep 14 2021              
-- * Generation date: Thu Dec 12 14:31:47 2024 
-- * LUN file: C:\Users\tomas\Desktop\UrbanKicks\docs\db\ER\DBDesign.lun 
-- * Schema: UrbanKicks/SQL 
-- ********************************************* 


-- Database Section
-- ________________ 

create database UrbanKicks;


-- DBSpace Section
-- _______________


-- Tables Section
-- _____________ 

create table aggiungere (
     ID_Prodotto char(36) not null,
     Colore varchar(50) not null,
     Taglia numeric(5,2) not null,
     Email varchar(100) not null,
     constraint ID_aggiungere_ID primary key (ID_Prodotto, Colore, Taglia, Email));

create table CARRELLO (
     ID_Carrello char(36) not null,
     Email varchar(100) not null,
     Data_Creazione date not null,
     Data_Modifica date not null,
     Valore_Totale float(10) not null,
     constraint ID_CARRELLO_ID primary key (ID_Carrello),
     constraint SID_CARRE_UTENT_ID unique (Email));

create table comprendere (
     ID_Carrello char(36) not null,
     ID_Prodotto char(36) not null,
     Colore varchar(50) not null,
     Taglia numeric(5,2) not null,
     Quantita numeric(10) not null,
     constraint ID_comprendere_ID primary key (ID_Prodotto, Colore, Taglia, ID_Carrello));

create table INDIRIZZO (
     Email varchar(100) not null,
     Via varchar(255) not null,
     NumeroCivico numeric(5) not null,
     CAP numeric(5) not null,
     Citta varchar(100) not null,
     Provincia varchar(50) not null,
     Nazione varchar(50) not null,
     Predefinito char not null,
     constraint ID_INDIRIZZO_ID primary key (Email, Via, NumeroCivico, CAP, Citta));

create table MESSAGGIO (
     Email varchar(100) not null,
     Oggetto varchar(100) not null,
     Corpo varchar(1000) not null,
     Timestamp_Invio date not null,
     constraint ID_MESSAGGIO_ID primary key (Email, Timestamp_Invio));

create table NOTIFICA (
     ID_Notifica char(36) not null,
     TipoNotifica varchar(50) not null,
     Messaggio varchar(255) not null,
     Timestamp_Invio date not null,
     Tipo varchar(20) not null,
     Email varchar(100) not null,
     constraint ID_NOTIFICA_ID primary key (ID_Notifica));

create table ORDINE (
     ID_Ordine char(36) not null,
     Data_Ordine date not null,
     Costo_Totale float(10) not null,
     Metodo_Pagamento varchar(50) not null,
     Tipo varchar(20) not null,
     Email varchar(100) not null,
     ID_Sconto char(36),
     IDPagamento char(36) not null,
     constraint ID_ORDINE_ID primary key (ID_Ordine));

create table PAGAMENTO (
     IDPagamento char(36) not null,
     TipoPagamento varchar(50) not null,
     NumeroCarta char(16),
     Circuito varchar(20),
     DataScadenza date,
     CVC numeric(3),
     Email varchar(100),
     Predefinito char not null,
     Int_Email varchar(100) not null,
     constraint ID_PAGAMENTO_ID primary key (IDPagamento));

create table PRODOTTO (
     ID_Prodotto char(36) not null,
     Nome varchar(100) not null,
     Descrizione varchar(1000) not null,
     Marca varchar(50) not null,
     Tipo varchar(20) not null,
     Prezzo float(10) not null,
     Data_Aggiunta date not null,
     Sta_Tipo varchar(20) not null,
     constraint ID_PRODOTTO_ID primary key (ID_Prodotto));

create table PRODOTTO_ORDINE (
     ID_Prodotto char(36) not null,
     Colore varchar(50) not null,
     Taglia numeric(5,2) not null,
     Quantita numeric(10) not null,
     ID_Ordine char(36) not null,
     Prezzo_Acquisto float(10) not null,
     constraint ID_PRODOTTO_ORDINE_ID primary key (ID_Prodotto, Colore, Taglia, Quantita),
     constraint SID_PRODOTTO_ORDINE_ID unique (ID_Ordine, Quantita));

create table PRODOTTO_STORICO (
     ID_Prodotto char(36) not null,
     Prezzo float(10) not null,
     Data_Modifica date not null,
     constraint ID_PRODOTTO_STORICO_ID primary key (ID_Prodotto, Data_Modifica));

create table RECENSIONE (
     ID_Prodotto char(36) not null,
     Email varchar(100) not null,
     Punteggio numeric(2,1) not null,
     Descrizione varchar(1000) not null,
     Data_Recensione date not null,
     constraint ID_RECENSIONE_ID primary key (ID_Prodotto, Email));

create table SCONTO (
     ID_Sconto char(36) not null,
     Descrizione varchar(255) not null,
     TipoSconto varchar(20) not null,
     Valore float(10) not null,
     Data_Inizio date not null,
     Data_Fine date not null,
     constraint ID_SCONTO_ID primary key (ID_Sconto));

create table STATO_NOTIFICA (
     Tipo varchar(20) not null,
     Descrizione varchar(255) not null,
     constraint ID_STATO_NOTIFICA_ID primary key (Tipo));

create table STATO_PRODOTTO (
     Tipo varchar(20) not null,
     Descrizione varchar(255) not null,
     constraint ID_STATO_PRODOTTO_ID primary key (Tipo));

create table STATO_SPEDIZIONE (
     Tipo varchar(20) not null,
     Descrizione varchar(255) not null,
     constraint ID_STATO_SPEDIZIONE_ID primary key (Tipo));

create table Tracking_Spedizione (
     ID_Ordine char(36) not null,
     Posizione varchar(255) not null,
     Timestamp_Aggiornamento date not null,
     constraint ID_Track_ORDIN_ID primary key (ID_Ordine));

create table UTENTE (
     Email varchar(100) not null,
     Nome varchar(50) not null,
     Cognome varchar(50) not null,
     Password varchar(255) not null,
     Telefono varchar(15),
     Data_Registrazione date not null,
     Preferenze_Newsletter char not null,
     Ruolo varchar(20) not null,
     constraint ID_UTENTE_ID primary key (Email));

create table utilizza (
     ID_Sconto char(36) not null,
     Email varchar(100) not null,
     constraint ID_utilizza_ID primary key (ID_Sconto, Email));

create table VARIANTE (
     ID_Prodotto char(36) not null,
     Colore varchar(50) not null,
     Taglia numeric(5,2) not null,
     Quantita numeric(10) not null,
     constraint ID_VARIANTE_ID primary key (ID_Prodotto, Colore, Taglia));

create table WISHLIST (
     Email varchar(100) not null,
     Data_Creazione date not null,
     constraint ID_WISHL_UTENT_ID primary key (Email));


-- Constraints Section
-- ___________________ 

alter table aggiungere add constraint REF_aggiu_WISHL_FK
     foreign key (Email)
     references WISHLIST;

alter table aggiungere add constraint REF_aggiu_VARIA
     foreign key (ID_Prodotto, Colore, Taglia)
     references VARIANTE;

alter table CARRELLO add constraint SID_CARRE_UTENT_FK
     foreign key (Email)
     references UTENTE;

alter table comprendere add constraint REF_compr_VARIA
     foreign key (ID_Prodotto, Colore, Taglia)
     references VARIANTE;

alter table comprendere add constraint REF_compr_CARRE_FK
     foreign key (ID_Carrello)
     references CARRELLO;

alter table INDIRIZZO add constraint REF_INDIR_UTENT
     foreign key (Email)
     references UTENTE;

alter table MESSAGGIO add constraint REF_MESSA_UTENT
     foreign key (Email)
     references UTENTE;

alter table NOTIFICA add constraint REF_NOTIF_STATO_FK
     foreign key (Tipo)
     references STATO_NOTIFICA;

alter table NOTIFICA add constraint REF_NOTIF_UTENT_FK
     foreign key (Email)
     references UTENTE;

alter table ORDINE add constraint ID_ORDINE_CHK
     check(exists(select * from Tracking_Spedizione
                  where Tracking_Spedizione.ID_Ordine = ID_Ordine)); 

alter table ORDINE add constraint REF_ORDIN_STATO_FK
     foreign key (Tipo)
     references STATO_SPEDIZIONE;

alter table ORDINE add constraint REF_ORDIN_UTENT_FK
     foreign key (Email)
     references UTENTE;

alter table ORDINE add constraint REF_ORDIN_SCONT_FK
     foreign key (ID_Sconto)
     references SCONTO;

alter table ORDINE add constraint REF_ORDIN_PAGAM_FK
     foreign key (IDPagamento)
     references PAGAMENTO;

alter table PAGAMENTO add constraint REF_PAGAM_UTENT_FK
     foreign key (Int_Email)
     references UTENTE;

alter table PRODOTTO add constraint ID_PRODOTTO_CHK
     check(exists(select * from VARIANTE
                  where VARIANTE.ID_Prodotto = ID_Prodotto)); 

alter table PRODOTTO add constraint REF_PRODO_STATO_FK
     foreign key (Sta_Tipo)
     references STATO_PRODOTTO;

alter table PRODOTTO_ORDINE add constraint REF_PRODO_VARIA
     foreign key (ID_Prodotto, Colore, Taglia)
     references VARIANTE;

alter table PRODOTTO_ORDINE add constraint REF_PRODO_ORDIN
     foreign key (ID_Ordine)
     references ORDINE;

alter table PRODOTTO_STORICO add constraint REF_PRODO_PRODO
     foreign key (ID_Prodotto)
     references PRODOTTO;

alter table RECENSIONE add constraint REF_RECEN_UTENT_FK
     foreign key (Email)
     references UTENTE;

alter table RECENSIONE add constraint REF_RECEN_PRODO
     foreign key (ID_Prodotto)
     references PRODOTTO;

alter table Tracking_Spedizione add constraint ID_Track_ORDIN_FK
     foreign key (ID_Ordine)
     references ORDINE;

alter table UTENTE add constraint ID_UTENTE_CHK
     check(exists(select * from CARRELLO
                  where CARRELLO.Email = Email)); 

alter table UTENTE add constraint ID_UTENTE_CHK
     check(exists(select * from WISHLIST
                  where WISHLIST.Email = Email)); 

alter table utilizza add constraint REF_utili_UTENT_FK
     foreign key (Email)
     references UTENTE;

alter table utilizza add constraint REF_utili_SCONT
     foreign key (ID_Sconto)
     references SCONTO;

alter table VARIANTE add constraint EQU_VARIA_PRODO
     foreign key (ID_Prodotto)
     references PRODOTTO;

alter table WISHLIST add constraint ID_WISHL_UTENT_FK
     foreign key (Email)
     references UTENTE;


-- Index Section
-- _____________ 

create unique index ID_aggiungere_IND
     on aggiungere (ID_Prodotto, Colore, Taglia, Email);

create index REF_aggiu_WISHL_IND
     on aggiungere (Email);

create unique index ID_CARRELLO_IND
     on CARRELLO (ID_Carrello);

create unique index SID_CARRE_UTENT_IND
     on CARRELLO (Email);

create unique index ID_comprendere_IND
     on comprendere (ID_Prodotto, Colore, Taglia, ID_Carrello);

create index REF_compr_CARRE_IND
     on comprendere (ID_Carrello);

create unique index ID_INDIRIZZO_IND
     on INDIRIZZO (Email, Via, NumeroCivico, CAP, Citta);

create unique index ID_MESSAGGIO_IND
     on MESSAGGIO (Email, Timestamp_Invio);

create unique index ID_NOTIFICA_IND
     on NOTIFICA (ID_Notifica);

create index REF_NOTIF_STATO_IND
     on NOTIFICA (Tipo);

create index REF_NOTIF_UTENT_IND
     on NOTIFICA (Email);

create unique index ID_ORDINE_IND
     on ORDINE (ID_Ordine);

create index REF_ORDIN_STATO_IND
     on ORDINE (Tipo);

create index REF_ORDIN_UTENT_IND
     on ORDINE (Email);

create index REF_ORDIN_SCONT_IND
     on ORDINE (ID_Sconto);

create index REF_ORDIN_PAGAM_IND
     on ORDINE (IDPagamento);

create unique index ID_PAGAMENTO_IND
     on PAGAMENTO (IDPagamento);

create index REF_PAGAM_UTENT_IND
     on PAGAMENTO (Int_Email);

create unique index ID_PRODOTTO_IND
     on PRODOTTO (ID_Prodotto);

create index REF_PRODO_STATO_IND
     on PRODOTTO (Sta_Tipo);

create unique index ID_PRODOTTO_ORDINE_IND
     on PRODOTTO_ORDINE (ID_Prodotto, Colore, Taglia, Quantita);

create unique index SID_PRODOTTO_ORDINE_IND
     on PRODOTTO_ORDINE (ID_Ordine, Quantita);

create unique index ID_PRODOTTO_STORICO_IND
     on PRODOTTO_STORICO (ID_Prodotto, Data_Modifica);

create unique index ID_RECENSIONE_IND
     on RECENSIONE (ID_Prodotto, Email);

create index REF_RECEN_UTENT_IND
     on RECENSIONE (Email);

create unique index ID_SCONTO_IND
     on SCONTO (ID_Sconto);

create unique index ID_STATO_NOTIFICA_IND
     on STATO_NOTIFICA (Tipo);

create unique index ID_STATO_PRODOTTO_IND
     on STATO_PRODOTTO (Tipo);

create unique index ID_STATO_SPEDIZIONE_IND
     on STATO_SPEDIZIONE (Tipo);

create unique index ID_Track_ORDIN_IND
     on Tracking_Spedizione (ID_Ordine);

create unique index ID_UTENTE_IND
     on UTENTE (Email);

create unique index ID_utilizza_IND
     on utilizza (ID_Sconto, Email);

create index REF_utili_UTENT_IND
     on utilizza (Email);

create unique index ID_VARIANTE_IND
     on VARIANTE (ID_Prodotto, Colore, Taglia);

create unique index ID_WISHL_UTENT_IND
     on WISHLIST (Email);

