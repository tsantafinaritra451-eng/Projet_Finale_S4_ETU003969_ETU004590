## VERSION 1

### Base 
- Configuration de la base sqlite (Miantra)
- Conception base de donnees (Miantra/Tsanta)
- Creation des tables (Tsanta)
- Insertion des donnees de test (Miantra)


### Cote operateur
- CRUD regex (Miantra)
- Creation regex pour verifier numero valable de l'operateur (CRUD) (Miantra)
- CRUD type depot (Tsanta)
    - Création de 2 model FraisModel et TypeModel avec leur champs
    - Création de controller FraisController pour faire les CRUD 
    - Ajout des routes en appellons les methodes dans les controllers 
    - Création de view admin/liste_frais pour afficher les listes de toutes les types c'est a dire depot, retrait et transfert avec les actions CRUD
- CRUD type retrait (Tsanta)
- CRUD type transfert (Tsanta)
- Verification des frais attribuer selon l'action (Miantra)
- Calcul des gains via les frais : (Miantra)
    - retrait (Miantra)
    - transfert (Miantra)
- Voir situation compte client (Tsanta)




### Cote client
- Login automatique avec numero (Miantra)
- Calcul du solde du client connecter (Tsanta)
- Faire depot automatique (Miantra)
- Faire retrait automatique (Tsanta) :
    - verifier solde
- Faire transfert (Tsanta) :
    - verifier solde
- Voir historique (Tsanta)