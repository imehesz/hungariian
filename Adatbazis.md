#A FOTOALBUM adatbazis felepitese

# A FOTOALBUM alkalmazas adatbazisanak felepitese #

```
++++++++++++++++++++++
+       images       +
+--------------------+
+ id (int) auto_inc  +
+ albumID (int)      +>---
+ name (varchar)     +   |
+ path (varchar)     +   |
+ created (varchar)  +   |
+ updated (varchar)  +   |
+ deleted (int) 0    +   |
++++++++++++++++++++++   |
                         |
++++++++++++++++++++++   |
+       albums       +   |
+--------------------+   |
+ id (int) auto_inc  +1---
+ name (varchar)     +
+ created (varchar)  +
+ updated (varchar)  +
+ deleted (int) 0    +
++++++++++++++++++++++
```

# SQLite #

CREATE TABLE images (albumID int, created varchar(20), deleted int(1) default 0, id INTEGER PRIMARY KEY, name varchar(100), path varchar(150), updated varchar(20));

CREATE TABLE albums ( created varchar(20), deleted int(1) default 0, id INTEGER PRIMARY KEY, name varchar(100), updated varchar(20));