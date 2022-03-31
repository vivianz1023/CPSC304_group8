
CREATE TABLE SinNumOrEqual_Name
(
    SinNumOrEqual CHAR(20) NOT NULL PRIMARY KEY,
    Name          CHAR(20) NOT NULL
);

CREATE TABLE SponsoringCompany
(
    Name CHAR(20) NOT NULL PRIMARY KEY
);

CREATE TABLE OlympicTeamMember_Sponsor
(
    ID            CHAR(20) NOT NULL PRIMARY KEY,
    Country       CHAR(20),
    SinNumOrEqual CHAR(20),
    Age           INTEGER CHECK (Age BETWEEN 0 AND 100),
    Sex           CHAR(1) CHECK (SEX IN ('M', 'F')),
    Cost          REAL CHECK (Cost >= 0),
    Sponsor_Name  CHAR(20),
    FOREIGN KEY (Sponsor_Name) REFERENCES SponsoringCompany (Name)
        ON DELETE SET NULL      
);

CREATE TABLE Name_WorldRecord
(
    CompetitionEvent_Name CHAR(50) NOT NULL PRIMARY KEY,
    WorldRecord           CHAR(20)
);

CREATE TABLE CompetitionEvent
(
    ID                    CHAR(20) NOT NULL PRIMARY KEY,
    CompetitionEvent_Name CHAR(50) NOT NULL,
    ParticipantNumber     INTEGER CHECK (ParticipantNumber >= 0),
    StartDate             DATE,
    EndDate               DATE
);


CREATE TABLE Coach
(
    ID               CHAR(20) NOT NULL PRIMARY KEY,
    TrainingSubject  CHAR(40),
    TeachingDuration INTERVAL YEAR TO MONTH,
    FOREIGN KEY (ID) REFERENCES OlympicTeamMember_Sponsor (ID)
        ON DELETE CASCADE
);

CREATE TABLE Athlete
(
    ID           CHAR(20) NOT NULL PRIMARY KEY,
    BronzeNumber INTEGER CHECK (BronzeNumber >= 0),
    SilverNumber INTEGER CHECK (SilverNumber >= 0),
    GoldNumber   INTEGER CHECK (GoldNumber >= 0),
    FOREIGN KEY (ID) REFERENCES OlympicTeamMember_Sponsor (ID)
        ON DELETE CASCADE
);

CREATE TABLE Insurance_Provide
(
    Name        CHAR(40) NOT NULL,
    MoneyAmount REAL CHECK (MoneyAmount >= 0),
    Athlete_ID  CHAR(20) NOT NULL,
    PRIMARY KEY (Name, Athlete_ID),
    FOREIGN KEY (Athlete_ID) REFERENCES Athlete (ID)
    ON DELETE CASCADE
);

CREATE TABLE Venue
(
    ID       CHAR(20) NOT NULL PRIMARY KEY,
    Capacity INTEGER CHECK (Capacity >= 0),
    Location CHAR(60),
    Name     CHAR(40) NOT NULL
);

CREATE TABLE Exist
(
    OLY_ID   CHAR(20) NOT NULL,
    Venue_ID CHAR(20) NOT NULL,
    PRIMARY KEY (OLY_ID, Venue_ID),
    FOREIGN KEY (OLY_ID) REFERENCES OlympicTeamMember_Sponsor (ID)
        ON DELETE CASCADE,
    FOREIGN KEY (Venue_ID) REFERENCES Venue (ID)
        ON DELETE CASCADE
);

CREATE TABLE Volunteer
(
    ID      CHAR(20) NOT NULL PRIMARY KEY,
    Country CHAR(20),
    Name    CHAR(20) NOT NULL,
    Sex     CHAR(1) CHECK (SEX IN ('M', 'F'))
);

CREATE TABLE Has
(
    Venue_ID CHAR(20) NOT NULL,
    Volun_ID CHAR(20) NOT NULL,
    PRIMARY KEY (Venue_ID, Volun_ID),
    FOREIGN KEY (Venue_ID) REFERENCES Venue (ID)
        ON DELETE CASCADE,
    FOREIGN KEY (Volun_ID) REFERENCES Volunteer (ID)
        ON DELETE CASCADE
);

CREATE TABLE Serve
(
    CompetitionEvent_ID CHAR(20) NOT NULL,
    Volunteer_ID        CHAR(20) NOT NULL,
    PRIMARY KEY (CompetitionEvent_ID, Volunteer_ID),
    FOREIGN KEY (CompetitionEvent_ID) REFERENCES CompetitionEvent (ID)
        ON DELETE CASCADE,
    FOREIGN KEY (Volunteer_ID) REFERENCES Volunteer (ID)
        ON DELETE CASCADE
);

CREATE TABLE CompetitionEquipment
(
    ID     CHAR(20) NOT NULL PRIMARY KEY,
    Name   CHAR(40) NOT NULL,
    Weight REAL CHECK (Weight >= 0)
);

CREATE TABLE Support
(
    CompetitionEquipment_ID CHAR(20) NOT NULL,
    SponsoringCompany_Name  CHAR(20) NOT NULL,
    Cost                    REAL CHECK (Cost >= 0),
    PRIMARY KEY (CompetitionEquipment_ID, SponsoringCompany_Name),
    FOREIGN KEY (CompetitionEquipment_ID) REFERENCES CompetitionEquipment (ID)
        ON DELETE CASCADE,
    FOREIGN KEY (SponsoringCompany_Name) REFERENCES SponsoringCompany (Name)
        ON DELETE CASCADE
);

CREATE TABLE LivePlatform
(
    Name     CHAR(20) NOT NULL PRIMARY KEY,
    Language CHAR(40) NOT NULL
);

CREATE TABLE Streaming
(
    LivePlatform_Name   CHAR(20) NOT NULL,
    CompetitionEvent_ID CHAR(20) NOT NULL,
    PRIMARY KEY (LivePlatform_Name, CompetitionEvent_ID),
    FOREIGN KEY (LivePlatform_Name) REFERENCES LivePlatform (Name)
        ON DELETE CASCADE,
    FOREIGN KEY (CompetitionEvent_ID) REFERENCES CompetitionEvent (ID)
        ON DELETE CASCADE
);

CREATE TABLE Use
(
    Athlete_ID              CHAR(20) NOT NULL,
    CompetitionEquipment_ID CHAR(20) NOT NULL,
    PRIMARY KEY (Athlete_ID, CompetitionEquipment_ID),
    FOREIGN KEY (Athlete_ID) REFERENCES Athlete (ID)
        ON DELETE CASCADE,
    FOREIGN KEY (CompetitionEquipment_ID) REFERENCES CompetitionEquipment (ID)
        ON DELETE CASCADE
);

CREATE TABLE Referee
(
    ID   CHAR(20) NOT NULL PRIMARY KEY,
    Sex  CHAR(1) CHECK (SEX IN ('M', 'F')),
    Name CHAR(20) NOT NULL,
    Country CHAR (20)
);

CREATE TABLE Participate
(
    Athlete_ID          CHAR(20) NOT NULL,
    CompetitionEvent_ID CHAR(20) NOT NULL,
    PRIMARY KEY (Athlete_ID, CompetitionEvent_ID),
    FOREIGN KEY (Athlete_ID) REFERENCES Athlete (ID)
        ON DELETE CASCADE,
    FOREIGN KEY (CompetitionEvent_ID) REFERENCES CompetitionEvent (ID)
        ON DELETE CASCADE
);

CREATE TABLE Monitor
(
    Athlete_ID          CHAR(20) NOT NULL,
    CompetitionEvent_ID CHAR(20) NOT NULL,
    Referee_ID          CHAR(20) NOT NULL,
    PRIMARY KEY (Athlete_ID, CompetitionEvent_ID, Referee_ID),
    FOREIGN KEY (Athlete_ID) REFERENCES Athlete (ID)
        ON DELETE CASCADE,
    FOREIGN KEY (CompetitionEvent_ID) REFERENCES CompetitionEvent (ID)
        ON DELETE CASCADE,
    FOREIGN KEY (Referee_ID) REFERENCES Referee (ID)
        ON DELETE CASCADE
);


INSERT INTO SinNumOrEqual_Name VALUES ('000000000', 'Alice');  
INSERT INTO SinNumOrEqual_Name VALUES ('001001001', 'Bob');  
INSERT INTO SinNumOrEqual_Name VALUES ('00200200202', 'Cara');  
INSERT INTO SinNumOrEqual_Name VALUES ('003003003', 'Dylan');  
INSERT INTO SinNumOrEqual_Name VALUES ('004004004', 'Ellen');  
INSERT INTO SinNumOrEqual_Name VALUES ('005005005', 'Fliny');  
INSERT INTO SinNumOrEqual_Name VALUES ('006006006', 'Gina');  
INSERT INTO SinNumOrEqual_Name VALUES ('007007007', 'Helen');  
INSERT INTO SinNumOrEqual_Name VALUES ('008008008', 'Isabella');  
INSERT INTO SinNumOrEqual_Name VALUES ('009009009', 'Jack');  
INSERT INTO SinNumOrEqual_Name VALUES ('010010010', 'Kris');  

INSERT INTO SponsoringCompany VALUES ('RBC');  
INSERT INTO SponsoringCompany VALUES ('FDM');  
INSERT INTO SponsoringCompany VALUES ('Intel');  
INSERT INTO SponsoringCompany VALUES ('Toyota');  
INSERT INTO SponsoringCompany VALUES ('Dow');  

INSERT INTO OlympicTeamMember_Sponsor VALUES ('a0000', 'America', '000000000', 21, 'F', 1000000, 'RBC');  
INSERT INTO OlympicTeamMember_Sponsor VALUES ('a0001', 'Canada', '001001001', 18, 'M', 500000, 'FDM');  
INSERT INTO OlympicTeamMember_Sponsor VALUES ('a0002', 'China', '00200200202', 17, 'F', 1000000, 'Intel');  
INSERT INTO OlympicTeamMember_Sponsor VALUES ('a0003', 'China', '003003003', 22, 'M', 2000000, 'Toyota');  
INSERT INTO OlympicTeamMember_Sponsor VALUES ('a0004', 'America', '004004004', 25, 'M', 1000000, 'Dow'); 
INSERT INTO OlympicTeamMember_Sponsor VALUES ('co0000', 'Canada', '005005005', 26, 'F', NULL, NULL);  
INSERT INTO OlympicTeamMember_Sponsor VALUES ('co0001', 'Canada', '006006006', 27, 'F', NULL, NULL);  
INSERT INTO OlympicTeamMember_Sponsor VALUES ('co0002', 'Canada', '007007007', 31, 'F', NULL, NULL);  
INSERT INTO OlympicTeamMember_Sponsor VALUES ('co0003', 'Canada', '008008008', 43, 'M', NULL, NULL);  
INSERT INTO OlympicTeamMember_Sponsor VALUES ('co0004', 'Canada', '008008008', 35, 'F', NULL, NULL);  

INSERT INTO Name_WorldRecord VALUES ('Figure skating team event Pairs Short program', '82.83');  
INSERT INTO Name_WorldRecord VALUES ('Short track speed skating Women''s 500 metres', '42.379');  
INSERT INTO Name_WorldRecord VALUES ('Speed skating Men''s 500 metres', '34.32');  
INSERT INTO Name_WorldRecord VALUES ('Speed skating Women''s team pursuit', '2:53.44');  
INSERT INTO Name_WorldRecord VALUES ('Speed skating Men''s 1500 metres', '1:43.21');  

INSERT INTO CompetitionEvent VALUES ('ce0000', 'Figure skating team event Pairs Short program', 40, DATE '2022-02-07', DATE '2022-02-09');  
INSERT INTO CompetitionEvent VALUES ('ce0005', 'Short track speed skating Women''s 500 metres', 8, DATE '2022-02-12', DATE '2022-02-14');  
INSERT INTO CompetitionEvent VALUES ('ce0010', 'Speed skating Men''s 500 metres', 8, DATE '2022-02-16', DATE '2022-02-18');  
INSERT INTO CompetitionEvent VALUES ('ce0015', 'Speed skating Women''s team pursuit', 30, DATE '2022-02-18', DATE '2022-02-19');  
INSERT INTO CompetitionEvent VALUES ('ce0001', 'Figure skating team event Pairs Short program', 40, DATE '2022-02-09', DATE '2022-02-09');  

INSERT INTO Coach VALUES ('co0000', 'Figure skating', INTERVAL '7-0' YEAR TO MONTH);  
INSERT INTO Coach VALUES ('co0001', 'Short track speed skating', INTERVAL '6-0' YEAR TO MONTH);  
INSERT INTO Coach VALUES ('co0002', 'Speed skating', INTERVAL '3-4' YEAR TO MONTH);  
INSERT INTO Coach VALUES ('co0003', 'Figure skating', NULL);  
INSERT INTO Coach VALUES ('co0004', 'Short track speed skating', NULL);  

INSERT INTO Athlete VALUES ('a0000', 1, 0, 2);  
INSERT INTO Athlete VALUES ('a0001', 0, 2, 1);  
INSERT INTO Athlete VALUES ('a0002', 0, 0, 0);  
INSERT INTO Athlete VALUES ('a0003', 0, 1, 0);  
INSERT INTO Athlete VALUES ('a0004', 1, 0, 0);  

INSERT INTO Insurance_Provide VALUES ('Manulife Financial Corporation', 100000, 'a0000');  
INSERT INTO Insurance_Provide VALUES ('Canada Life Assurance Company', 100000, 'a0000');  
INSERT INTO Insurance_Provide VALUES ('Sun Life Assurance Company', 200000, 'a0001');  
INSERT INTO Insurance_Provide VALUES ('RBC Insurance Company', 100000, 'a0002');  
INSERT INTO Insurance_Provide VALUES ('Desjardins', 100000, 'a0003');  

INSERT INTO Venue VALUES ('v0000', 80000, '1 National Stadium South Road, Beijing, China', 'The National Stadium');  
INSERT INTO Venue VALUES ('v0001', 17345, '56 Zhongguancun South Street, Beijing, China', 'Capital Indoor Stadium');  
INSERT INTO Venue VALUES ('v0002', 12000, 'Olympic Green, Chaoyang District, Beijing, China', 'The National Speed Skating Oval');  
INSERT INTO Venue VALUES ('v0003', 4912, 'Shijingshan District, Beijing', 'Big Air Shougang');  
INSERT INTO Venue VALUES ('v0004', 18000, 'Olympic Green, Beijing, China', 'National Indoor Stadium');  

INSERT INTO Exist VALUES ('a0000', 'v0000');  
INSERT INTO Exist VALUES ('a0000', 'v0001');  
INSERT INTO Exist VALUES ('co0001', 'v0001');  
INSERT INTO Exist VALUES ('a0002', 'v0002');  
INSERT INTO Exist VALUES ('co0003', 'v0003');  

INSERT INTO Volunteer VALUES ('vt0000', 'Canada', 'Alex', 'M');  
INSERT INTO Volunteer VALUES ('vt0001', 'Canada', 'Bella', 'F');  
INSERT INTO Volunteer VALUES ('vt0002', 'USA', 'Coco', 'F');  
INSERT INTO Volunteer VALUES ('vt0003', 'China', 'Hua', 'F');  
INSERT INTO Volunteer VALUES ('vt0004', 'Japan', 'Kaku', 'F');  

INSERT INTO Has VALUES ('v0000', 'vt0000');  
INSERT INTO Has VALUES ('v0001', 'vt0000');  
INSERT INTO Has VALUES ('v0001', 'vt0001');  
INSERT INTO Has VALUES ('v0002', 'vt0002');  
INSERT INTO Has VALUES ('v0002', 'vt0000');  

INSERT INTO Serve VALUES ('ce0000', 'vt0000');  
INSERT INTO Serve VALUES ('ce0005', 'vt0001');  
INSERT INTO Serve VALUES ('ce0010', 'vt0002');  
INSERT INTO Serve VALUES ('ce0015', 'vt0003');  
INSERT INTO Serve VALUES ('ce0001', 'vt0004');  

INSERT INTO CompetitionEquipment VALUES ('ceq0000', 'speed skating shoes', 2.10);  
INSERT INTO CompetitionEquipment VALUES ('ceq0001', 'speed skating shoes', 2.24);  
INSERT INTO CompetitionEquipment VALUES ('ceq0002', 'speed skating shoes', 2.33);  
INSERT INTO CompetitionEquipment VALUES ('ceq0003', 'figure skating shoes', 1.72);  
INSERT INTO CompetitionEquipment VALUES ('ceq0004', 'figure skating shoes', 1.64);  

INSERT INTO Support VALUES ('ceq0000', 'RBC', 100000);  
INSERT INTO Support VALUES ('ceq0001', 'FDM', 100000);  
INSERT INTO Support VALUES ('ceq0002', 'Intel', 200000);  
INSERT INTO Support VALUES ('ceq0003', 'Toyota', 100000);  
INSERT INTO Support VALUES ('ceq0004', 'Dow', 100000);  

INSERT INTO LivePlatform VALUES ('BBC', 'English');  
INSERT INTO LivePlatform VALUES ('CBC', 'English');  
INSERT INTO LivePlatform VALUES ('Fuji TV', 'Japanese');  
INSERT INTO LivePlatform VALUES ('KBS', 'Korean');  
INSERT INTO LivePlatform VALUES ('CCTV', 'Chinese');  

INSERT INTO Streaming VALUES ('BBC', 'ce0000');  
INSERT INTO Streaming VALUES ('CBC', 'ce0005');  
INSERT INTO Streaming VALUES ('Fuji TV', 'ce0010');  
INSERT INTO Streaming VALUES ('BBC', 'ce0015');  
INSERT INTO Streaming VALUES ('CCTV', 'ce0001');  
INSERT INTO Streaming VALUES ('Fuji TV', 'ce0000');    
INSERT INTO Streaming VALUES ('Fuji TV', 'ce0005');  
INSERT INTO Streaming VALUES ('BBC', 'ce0001');


INSERT INTO Use VALUES ('a0000', 'ceq0000');  
INSERT INTO Use VALUES ('a0001', 'ceq0001');  
INSERT INTO Use VALUES ('a0002', 'ceq0002');  
INSERT INTO Use VALUES ('a0003', 'ceq0003');  
INSERT INTO Use VALUES ('a0004', 'ceq0004');  

INSERT INTO Referee VALUES ('r0000', 'M', 'Alexander', 'Canada');  
INSERT INTO Referee VALUES ('r0001', 'M', 'Bennett', 'USA');  
INSERT INTO Referee VALUES ('r0002', 'F', 'Claire', 'Canada');  
INSERT INTO Referee VALUES ('r0003', 'M', 'Davis', 'Chile');  
INSERT INTO Referee VALUES ('r0004', 'F', 'Li', 'China');  

INSERT INTO Participate VALUES ('a0000', 'ce0005');  
INSERT INTO Participate VALUES ('a0001', 'ce0010');  
INSERT INTO Participate VALUES ('a0002', 'ce0015');  
INSERT INTO Participate VALUES ('a0003', 'ce0000');  
INSERT INTO Participate VALUES ('a0004', 'ce0001');  

INSERT INTO Monitor VALUES ('a0000', 'ce0005', 'r0000');  
INSERT INTO Monitor VALUES ('a0001', 'ce0010', 'r0001');  
INSERT INTO Monitor VALUES ('a0002', 'ce0015', 'r0002');  
INSERT INTO Monitor VALUES ('a0003', 'ce0000', 'r0003');  
INSERT INTO Monitor VALUES ('a0004', 'ce0001', 'r0004');  

Commit work;

