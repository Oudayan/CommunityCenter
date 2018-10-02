Création de sites Webs 2
Groupe 15612
Oudayan Dutta
TP2 - Thème WordPress centre communautaire


Adresse du site :   http://e9134268.webdev.cmaisonneuve.qc.ca/wordpress/multisite/

C'est le même site que pour mes travaux et TP1, donc les mêmes usagers :

- ODutta (SuperAdmin)
- NTesla (Editor)
- MFaraday (Author)
- JCMaxwell (Contributor)
- TEdison (Subscriber)

Les mots de passes sont identiques aux usernames.



Les fichiers utilisés pour ce thème sont :
    
    - fonction.php
    - page_activity.php
    - single.php
    - header.php
    - 404.php
    - style.css



Voici le contenu du fichier OD1KN0B_readme.txt :


<readme>

    Bienvenue au thème OD1KN0B pour centres culturels et communautaires !


    L'installation du thème avec toutes ses fonctionnalités ne pourrait être plus simple : toutes les installation sont faites automatiquement pour vous. Une simple activation du thème suffit pour pouvoir utilisier toutes les fonctiontionnalités !


    Voici une liste des éléments créés automatiquement à l'ativation du thème OD1KN0B :


    1 - Un type de post personnalisé pour créer des activités pour votre centre communautaire, avec des champs personalisés (Animateur, date et lieu) et des catégories d'activités.

        Les usagers ayants accès au menu des activité dans le panneau d'administration sont:
            - Administrateurs
            - Éditeur
            - Contributeurs (Avec droits restreints, doivent faire approuver leurs posts d'activité par un Admin ou Éditeur / Ne peuvent pas créer de nouvelles catégories d'activités.)


    2 - Les pages nécessaires au fonctionnement du site :

        - Acceuil OD1KN0B : La page d'acceuil du site qui affiche les 3 activité les plus récentes. Les titres d'activités sont des hyperliens pour accéder à la page de chaque activité. 
        Shortcode : [activities_home] 

        - Toutes les activités - OD1KN0B : La page qui affiche la liste toutes les activités publiées.

        - Page d'une activité : La page où les détails d'une activité sont affichés. À partir de cette page, on peut s'inscrire à l'activité, voir la liste des participants (si créateur de l'Activité) et naviguer entre les différentes activités offertes dans le même lieu.

        - Inscription à une activité – OD1KN0B : Le formulaire pour s'inscrire à une activité.
        Shortcode : [subscribe_to_activity]
        
        - Liste des participants – OD1KN0B : La page où le créateur de l'activité peut voir qui est inscrit à une activité. Cette page est accessible via un bouton dans la page d'une activité.
        Shortcode : [participants]
        

    3 - La création de la table "activity_participations" dans la base de donnée wordpress pour garder les participations de chaque activité.


    4 - La création de la navigation : menus, boutons et hyperliens entre les pages.



    Lors de la désactivation du thème, les pages sont effacée si le titre n'a pas été changé. Par contre, les données du site (activités, catégories, participants) sont gardés et seront accessibles lors de la réactivation du thème.


    Bonne gestion des activités avec le thème OD1KN0B de WordPress !
    
</readme>