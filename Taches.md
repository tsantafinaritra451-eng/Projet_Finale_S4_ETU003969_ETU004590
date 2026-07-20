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
- CRUD : - type depot (Tsanta)
         -  type retrait (Tsanta)
         -  type transfert (Tsanta) :
             - Création de 2 model FraisModel et TypeModel avec leur champs
             - Création de controller FraisController pour faire les CRUD 
             - Ajout des routes en appellons les methodes dans les controllers 
             - Création de view admin/liste_frais pour afficher les listes de toutes les types c'est a dire depot, retrait et transfert avec les actions CRUD
- Verification des frais attribuer selon l'action (Miantra)
    - creer fonction recuperer frais pour le montant dand FraisModel
- Calcul des gains via les frais : (Miantra)
    - retrait (Miantra)
    - transfert (Miantra)
- Voir situation compte client (Tsanta)
    - creation de d'une model OperationModel avec tout les champs de la table operation avec une fonction getHistoriqueParUser c'est une script avec 
jointure pour avoir l'historique de tout les clients
    - creation de clientController pour avoir tout les clients ainsi qu'une methode de situation pour voir ses historiques  et ses solde
    - creation de la routes clients
    - creation de view pour afficher les listes de clients ainsi que les situation de chaque clients




### Cote client
- Login automatique avec numero (Miantra) :
    - fonction redirect vers login
    - fonction verifier si admin -> rediriger vers page admin sinon verifier regex :
        - si valide creer user si pas encore present sinon recuperer l'user present

- Calcul du solde du client connecter (Tsanta)
    - appeler fonction calculer solde
- Faire depot automatique (Miantra) 
- Faire retrait automatique (Tsanta) 
- Faire transfert (Tsanta) 
    - creer fonction recuperer somme operation pour chaque type dans OperationModel
    - creer fonction calculer solde user connecter
    - creer fonction ajouter operation 
    - appeler fonction recuperer frais
- Voir historique (Tsanta)