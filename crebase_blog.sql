#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: categories
#------------------------------------------------------------

CREATE TABLE categories(
        Id  Int  Auto_increment  NOT NULL ,
        Nom Varchar (255) NOT NULL
	,CONSTRAINT categories_PK PRIMARY KEY (Id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: statuts
#------------------------------------------------------------

CREATE TABLE statuts(
        Id  Int  Auto_increment  NOT NULL ,
        Nom Varchar (50) NOT NULL
	,CONSTRAINT statuts_PK PRIMARY KEY (Id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: articles
#------------------------------------------------------------

CREATE TABLE articles(
        Id              Int  Auto_increment  NOT NULL ,
        Titre           Varchar (50) NOT NULL ,
        Description     Text ,
        DateAjout       Date NOT NULL ,
        Auteur          Varchar (50) NOT NULL ,
        ImageRepository Varchar (50) ,
        ImageFileName   Varchar (255) ,
        Id_statuts      Int NOT NULL
	,CONSTRAINT articles_PK PRIMARY KEY (Id)

	,CONSTRAINT articles_statuts_FK FOREIGN KEY (Id_statuts) REFERENCES statuts(Id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: roles
#------------------------------------------------------------

CREATE TABLE roles(
        Id  Int  Auto_increment  NOT NULL ,
        Nom Varchar (50) NOT NULL
	,CONSTRAINT roles_PK PRIMARY KEY (Id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: utilisateurs
#------------------------------------------------------------

CREATE TABLE utilisateurs(
        Id       Int  Auto_increment  NOT NULL ,
        Pseudo   Varchar (50) NOT NULL ,
        Email    Varchar (255) NOT NULL ,
        Mdp      Varchar (50) NOT NULL ,
        Valide   Bool NOT NULL ,
        Id_roles Int NOT NULL
	,CONSTRAINT utilisateurs_PK PRIMARY KEY (Id)

	,CONSTRAINT utilisateurs_roles_FK FOREIGN KEY (Id_roles) REFERENCES roles(Id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: droits
#------------------------------------------------------------

CREATE TABLE droits(
        Id  Int  Auto_increment  NOT NULL ,
        Nom Varchar (50) NOT NULL
	,CONSTRAINT droits_PK PRIMARY KEY (Id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: categoriser
#------------------------------------------------------------

CREATE TABLE categoriser(
        Id          Int NOT NULL ,
        Id_articles Int NOT NULL
	,CONSTRAINT categoriser_PK PRIMARY KEY (Id,Id_articles)

	,CONSTRAINT categoriser_categories_FK FOREIGN KEY (Id) REFERENCES categories(Id)
	,CONSTRAINT categoriser_articles0_FK FOREIGN KEY (Id_articles) REFERENCES articles(Id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: comprendre
#------------------------------------------------------------

CREATE TABLE comprendre(
        Id       Int NOT NULL ,
        Id_roles Int NOT NULL
	,CONSTRAINT comprendre_PK PRIMARY KEY (Id,Id_roles)

	,CONSTRAINT comprendre_droits_FK FOREIGN KEY (Id) REFERENCES droits(Id)
	,CONSTRAINT comprendre_roles0_FK FOREIGN KEY (Id_roles) REFERENCES roles(Id)
)ENGINE=InnoDB;

