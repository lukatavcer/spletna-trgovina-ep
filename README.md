#Seminarska naloga EP

**TO DO:**
- poskrbi za XSS
- napiši funkcije za vnos elementov v bazo


**TO DO OSNOVNO:**

Administrator:
- Dostop je dovoljen le odjemalcem, ki se overijo s pomočjo certifikatov X.509;

Prodajalec:
- Dostop je dovoljen le odjemalcem, ki se overijo s pomočjo certifikatov X.509;
- Pregled in obdelava naročil


Android aplikacija

**POSTOPEK ZA BAZO**

mysql -h 127.0.0.1 -u root -p\
CREATE DATABASE ep_db;\
exit

// se postavis v direktorij ep-projekt/api/db\
mysql -h localhost -u root -p ep_db < ep_db.sql\
mysql -h 127.0.0.1 -u root -p

**Baza:** 
- servername: localhost
- db name: ep_db
- username: root
- password root

**Administrator:**
- firstname: Admin
- lastname: Administratovic
- email: admin@admin.com
- password: admin1
  
**Prodajalec:**
- firstname: Prodajalec
- lastname: Ep
- email: pe@gmail.com
- password: eptest
    
**Stranka:**
- firstname: Ep
- lastname: Test
- email: ep@gmail.com
- password: eptest

BAZA
![Database](db.png)