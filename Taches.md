## VERSION 1

### Base 
- Configuration de la base sqlite (Miantra)
- Conception base de donnees (Miantra/Tsanta)
- Creation des tables (Tsanta)
- Insertion des donnees de test (Miantra)


### Cote operateur
- CRUD regex (Miantra) :
    - creer fonction :
        - save
        - delete
        - form(redirection page)
    - creer vue listPrefix,formPrefix
- CRUD type depot (Tsanta)
- CRUD type retrait (Tsanta)
- CRUD type transfert (Tsanta)
- Verification des frais attribuer selon l'action (Miantra)
- Calcul des gains via les frais : (Miantra)
    - retrait (Miantra)
    - transfert (Miantra)
- Voir situation compte client (Tsanta)




### Cote client
- Login automatique avec numero (Miantra) :
    - fonction redirect vers login
    - fonction verifier si admin -> rediriger vers page admin sinon verifier regex :
        - si valide creer user si pas encore present sinon recuperer l'user present

- Calcul du solde du client connecter (Tsanta)
- Faire depot automatique (Miantra)
- Faire retrait automatique (Tsanta) :
    - verifier solde
- Faire transfert (Tsanta) :
    - verifier solde
- Voir historique (Tsanta)