## VERSION 1

### Base 
- (ok) Configuration de la base sqlite (Miantra)
- (ok) Conception base de donnees (Miantra/Tsanta)
- (ok) Creation des tables (Tsanta)
- (ok) Insertion des donnees de test (Miantra)


### Cote operateur
- (ok) CRUD regex (Miantra) :
    - creer fonction :
        - save
        - delete
        - form(redirection page)
    - creer vue listPrefix,formPrefix
- (ok) CRUD : - type depot (Tsanta)
         -  type retrait (Tsanta)
         -  type transfert (Tsanta) :
             - Création de 2 model FraisModel et TypeModel avec leur champs
             - Création de controller FraisController pour faire les CRUD 
             - Ajout des routes en appellons les methodes dans les controllers 
             - Création de view admin/liste_frais pour afficher les listes de toutes les types c'est a dire depot, retrait et transfert avec les actions CRUD
- (ok) Verification des frais attribuer selon l'action (Miantra)
    - creer fonction recuperer frais pour le montant dand FraisModel
- (ok) Calcul des gains via les frais : (Miantra)
    - retrait (Miantra)
    - transfert (Miantra)
    - creer fonction pour inserer le frais des operation dans la table fraisObtenu
    - creer fonction pour sommer les frais selon le type
- (ok) Voir situation compte client (Tsanta)
    - creation d'une model OperationModel avec tout les champs de la table operation avec une fonction getHistoriqueParUser c'est une script avec 
jointure pour avoir l'historique de tout les clients
    - creation de clientController pour avoir tout les clients ainsi qu'une methode de situation pour voir ses historiques  et ses solde
    - creation de la routes clients
    - creation de view pour afficher les listes de clients ainsi que les situation de chaque clients




### Cote client
- (ok) Login automatique avec numero (Miantra) :
    - fonction redirect vers login
    - fonction verifier si admin -> rediriger vers page admin sinon verifier regex :
        - si valide creer user si pas encore present sinon recuperer l'user present

- (ok) Calcul du solde du client connecter (Tsanta)
    - appeler fonction calculer solde
- (ok) Faire depot automatique (Miantra) 
- (ok) Faire retrait automatique (Tsanta) 
- (ok) Faire transfert (Tsanta) 
    - creer fonction recuperer somme operation pour chaque type dans OperationModel
    - creer fonction calculer solde user connecter
    - creer fonction ajouter operation 
    - appeler fonction recuperer frais
    - appeler fonction inserer frais obtenu
- (ok) Voir historique (Tsanta)
   - ajout d'une fonction historiqueParClient() pour recuperer toute les historiques des clients connecter
    - Creation d'une route historique
    - Creation de view historique_client pour afficher l'historique