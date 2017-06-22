# ES Manager

## Skyrim

### Create the database

    CREATE DATABASE esmanager;
    USE esmanager;

### Create the user

    CREATE USER 'esmanagerNginx'@'localhost' IDENTIFIED BY 'password';
    GRANT ALL PRIVILEGES ON esmanager.* TO 'esmanagerNginx'@'localhost';

### Creating tables

#### Characters

    CREATE TABLE characters(
    id int unsigned NOT NULL AUTO_INCREMENT,
    name varchar(144),
    race enum('Altmer','Argonian','Bosmer','Breton','Dunmer','Imperial','Khajiit','Nord','Orc','Redguard','Custom'),
    gender enum('M','F'),
    armorTypes set('Light','Heavy','Unarmored'),
    combatStyle set('Sword','Dagger','War Axe','Mace','Greatsword','Battleaxe','Warhammer','Bow','Crossbow','Dual wield','Bound weapons','Destruction','Summoning','Reanimation','Followers'),
    roleplay tinyint,
    morality enum('Good','Neutral','Evil'),
    PRIMARY KEY (id)
    );

#### Skills

    CREATE TABLE skills(
    characterId int unsigned NOT NULL,
    skill enum('Alteration','Conjuration','Destruction','Enchanting','Illusion','Restoration','Archery','Block','Heavy Armor','One-handed','Smithing','Two-handed','Alchemy','Light Armor','Lockpicking','Pickpocket','Sneak','Speech') NOT NULL,
    type enum('Primaray','Major','Minor'),
    PRIMARY KEY (characterId, skill),
    FOREIGN KEY (characterId) REFERENCES characters (id)
    );

#### Authors

    CREATE TABLE authors(
    id int unsigned NOT NULL AUTO_INCREMENT,
    name varchar(30),
    nexusId int unsigned,
    link text,
    categories set('Houses','Armor','Weapons','Followers','Quests','Gameplay','Armor Textures','Character Presets','Character Creation','ENB Presets','General','Visuals'),
    PRIMARY KEY (id)
    );

#### Mods - Nexus

    CREATE TABLE modsNexus(
    nexusId int unsigned NOT NULL,
    name varchar(500),
    authorId int unsigned NOT NULL,
    categories set('Houses','Armor','Weapons','Followers','Quests','Gameplay','Armor Textures','Character Presets','Character Creation','ENB Presets','General','Visuals'),
    PRIMARY KEY (nexusId),
    FOREIGN KEY (authorId) REFERENCES authors (id)
    );

#### Modules

    CREATE TABLE modules(
    id int unsigned NOT NULL AUTO_INCREMENT,
    modId int unsigned NOT NULL,
    type enum('House','Follower','Quest','Armor','Weapon'),
    PRIMARY KEY (id),
    FOREIGN KEY (modId) REFERENCES modsNexus (nexusId)
    );

#### Modules - Houses

    CREATE TABLE modulesHouses(
    moduleId int unsigned NOT NULL,
    location varchar(144),
    features text,
    acquire text,
    notes text,
    PRIMARY KEY (moduleId),
    FOREIGN KEY (moduleId) REFERENCES modules (id)
    );

#### Modules - Equipment

    CREATE TABLE modulesEquipment(
    moduleId int unsigned NOT NULL,
    level tinyint unsigned,
    value smallint unsigned,
    type enum('Sword','Dagger','War Axe','Mace','Greatsword','Battleaxe','Warhammer','Bow','Crossbow','Staff','Light','Heavy','Unarmored'),
    acquire text,
    notes text,
    PRIMARY KEY (moduleId),
    FOREIGN KEY (moduleId) REFERENCES modules (id)
    );

#### Crafting Materials

    CREATE TABLE craftingMaterials(
    id int unsigned NOT NULL AUTO_INCREMENT,
    moduleId int unsigned NOT NULL,
    ingredent varchar(144),
    quantity smallint,
    PRIMARY KEY (id),
    FOREIGN KEY (moduleId) REFERENCES modules (id)
    );

#### Modules - Followers

    CREATE TABLE modulesFollowers(
    moduleId int unsigned NOT NULL,
    location varchar(144),
    class set('Defender','Striker','Controller','Healer','Light','Heavy','Unarmored','1H','2H','Bow','Crossbow','Destruction','Conjuration'),
    gender enum('M','F'),
    notes text,
    PRIMARY KEY (moduleId),
    FOREIGN KEY (moduleId) REFERENCES modules (id)
    );

#### Modules - Quests

    CREATE TABLE modulesQuests(
    moduleId int unsigned NOT NULL,
    location varchar(144),
    requirements text,
    notes text,
    PRIMARY KEY (moduleId),
    FOREIGN KEY (moduleId) REFERENCES modules (id)
    );

#### Images - Characters

    CREATE TABLE imagesCharacters(
    id int unsigned NOT NULL AUTO_INCREMENT,
    url text,
    characterId int unsigned NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (characterId) REFERENCES characters (id)
    );

#### Images - Mods - Nexus

    CREATE TABLE imagesModsNexus(
    nexusUrlExtension varchar(144),
    modId int unsigned NOT NULL,
    PRIMARY KEY (nexusUrlExtension),
    FOREIGN KEY (modId) REFERENCES modsNexus (nexusId)
    );

#### Images - Modules - Nexus

    CREATE TABLE imagesModulesNexus(
    nexusUrlExtension varchar(144),
    moduleId int unsigned NOT NULL,
    PRIMARY KEY (nexusUrlExtension),
    FOREIGN KEY (moduleId) REFERENCES modules (id)
    );

#### Images - Authors - Nexus

    CREATE TABLE imagesAuthorsNexus(
    nexusUrlExtension varchar(144),
    authorId int unsigned NOT NULL,
    PRIMARY KEY (nexusUrlExtension),
    FOREIGN KEY (authorId) REFERENCES authors (id)
    );

---

#### Images - Mods - other

    CREATE TABLE imagesModsOther(
    url text,
    modId int unsigned NOT NULL,
    PRIMARY KEY (url),
    FOREIGN KEY (modId) REFERENCES mods (id)
    );

#### Images - Modules - Other

    CREATE TABLE imagesModulesOther(
    url text,
    moduleId int unsigned NOT NULL,
    PRIMARY KEY (url),
    FOREIGN KEY (moduleId) REFERENCES modules (id)
    );

#### Images - Authors - Other

    CREATE TABLE imagesAuthorsOther(
    url text,
    authorId int unsigned NOT NULL,
    PRIMARY KEY (url),
    FOREIGN KEY (authorId) REFERENCES authors (id)
    );