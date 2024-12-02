# La saisie dans PometWEB

*L'ensemble des informations présentes dans ces pages ont été extraites du guide "Aide à la saisie dans POMETWEB" réalisé par Mario Lepage*

## Généralités

La saisie dans POMETWEB a été simplifiée et plusieurs contrôles ajoutés de façon à limiter très significativement les erreurs commises à la saisie et leur propagation dans la base de données.

Ainsi, la saisie dans POMETWEB ne concerne que les traits valides. Tout trait ayant été invalidé pour des raisons de croches à répétition ou d’avarie sur le chalut ne compte pas et n'est pas à saisir dans la base de données. Les contrôles à la saisie feront en sorte de la bloquer si le protocole n’est pas respecté, comme la vitesse ou la durée du trait, ou encore des positions non conformes. Il faudra donc corriger d’abord quand cela sera possible avant de saisir une information.

Ce document vous guidera pour la saisie et vous donnera quelques précisions sur certaines subtilités de l’outil. La prise en main est facile et ne demande pas de formation particulière.

## Démarrage de la saisie

Les campagnes sont crées au préalable par l’administrateur (Mario Lepage). Vous devrez donc lui indiquer le nom des masses d’eau sur lesquelles vous intervenez et pour lesquelles vous souhaitez saisir une campagne.

Pour démarrer la saisie, cliquez sur Campagnes, sélectionnez le nom de la masse d’eau et l’année. Vous pouvez maintenant saisir les données sur les traits de chalut. Si la campagne pour laquelle vous souhaitez saisir des données n’existe pas, contactez Mario Lepage (mario.lepage@inrae.fr) pour qu'il crée crée la campagne.

## Saisie des informations sur le trait

Pour saisir un nouveau trait de chalut, sélectionnez d’abord la campagne et cliquez sur “envoyer”.

Cliquez ensuite sur “nouveau trait" pour saisir un nouveau trait ou sélectionnez un trait déjà existant pour compléter ou corriger la saisie.

### Donnée générales

Dans la fenêtre de modification de trait dans la partie concernant les données générales sur le trait, indiquez la date et l’heure du trait avec la précision à la seconde si vous l'avez, mais pour le calcul d’effort de pêche, on ne considèrera que les minutes.

Sélectionnez l’engin : **petit chalut à perche** ou **chalut à perche** selon l’engin que vous avez utilisé.

Indiquez un nom de station ou autre information qui vous sont utiles pour identifier le trait (ce champs vous est dédié, nous ne l’utilisons pas par la suite. Il peut parfois vous être utile de repérer les traits réalisés dans un secteur plus rapidement qu’avec les coordonnées géographiques).

Le champ *Campagne* s’affiche automatiquement, **vérifiez seulement qu’il s’agit de la bonne campagne**.

Indiquez ensuite les informations sur le trait (durée, coef, profondeur moyenne, distance réelle chalutée lorsque vous l’avez). 
Nous avons imposé une erreur maximale de 20% entre la longueur du trait mesuré à partir des coordonnées géographiques et la longueur du trait mesurée. Pour l’instant nous n’avons pas eu de trait ayant un écart normal supérieur à 20%. Ces écarts se produisent dans les méandres ou si on fait des zigzag (ce qui n’est pas souhaitable). Si, dans certains cas, il était nécessaire de mettre un écart supérieur à 20%, nous pourrions revoir cette règle. La vitesse du trait est calculée automatiquement à partir de la distance mesurée et de la durée du trait.

### Données physico-chimiques

Les paramètres physico-chimiques sont à mesurer **avant de mettre en pêche**. Renseignez ici la T°, l’oxygène dissous en **% de saturation**, la salinité en PSU et/ou la classe de salinité. Avec l’expérience et dans des conditions normales, on connaît assez bien les limites de classe de salinité dans les estuaires. En cas de panne de sonde, un échantillon d’eau doit être prélevé pour une analyse ultérieure de la salinité et de la conductivité. 

**Attention, pour l’oxygène dissous les valeurs sont à indiquer en % de saturation et non en mg/L**. Les mesures sont à effectuer au fond et non dans une bouteille à prélèvement car la remontée de la bouteille change très significativement la valeur de l’oxygène dissous.

**La conductivité est exprimée en micro Siemmens** et non en milliSiemmens ! (ex: 28000 **μS** pour une salinité de 23 PSU)

### Coordonnées GPS

Vous pouvez saisir les coordonnées GPS, en projection WGS84, soit  :

- en degré.minute.millième (première colonne) : elles seront converties automatiquement en valeur décimale
- directement en valeur décimale (seconde colonne)

Si vous saisissez en degré - minute - millième :

- La saisie est simplifiée. Vous pouvez entrer 45°10.299N ou 48 10 299N. Il est possible de ne pas indiquer N car nous travaillons principalement dans l’hémisphère nord mais pour la Guyane, il faudra saisir S pour les latitudes pour indiquer l’hémisphère sud. Pour les longitudes, le même principe de saisie est appliqué (1°02.508W ou 1 02 508W). Pensez à bien vérifier si vous êtes en degré **Est** ou **Ouest** (W ou O se traduit par une valeur négative en degrés décimaux). Après avoir saisie une coordonnée, la validation se fait en appuyant sur “Enter” et la transformation en degrés décimaux se fait automatiquement

Pour la Guyane, vous pouvez également saisir les coordonnées en RGFG95 dans les zones qui sont affichées uniquement dans ce cas de figure. Les coordonnée seront traduites en WGS84, en format numérique.

Après la validation de la dernière coordonnée, un calcul de la distance parcourue est lancé automatiquement et la case apparaît en rouge si la distance présente un écart supérieur à 20% de la valeur indiquée plus haut dans la partie “Données générales”

**Astuce: si vous ne mesurez pas vos traits de chalut directement sur votre GPS, indiquez dans la distance réelle mesurée une valeur proche de 700m. C’est la longueur moyenne des traits de chalut. Vous pourrez ensuite rectifier cette valeur en fonction de la valeur calculée pour respecter l’écart de 20%.**

Cliquez ensuite sur enregistrer si aucune case n’apparaît en rouge. **Visualisez le trait réalisé sur une carte dans l’écran suivant pour vous assurer qu’il n’y a pas un problème de coordonnées et que le trait est bien là où il devrait être**. En cas de problème, vous pouvez revenir sur le trait en cliquant sur le lien “modifier le trait”.

Vous pouvez maintenant passez à la saisie des captures en cliquant sur le lien “Poissons capturés” et cliquez sur “nouvelle espèce”.

Il se peut que vous n’ayez aucune capture dans votre chalut. **Dans ce cas, indiquez-le dans la case commentaire du trait**.

### Importation des traces GPS

Si vous avez enregistré le trait GPS à partir d'un appareil, vous pouvez importer les traces dans le logiciel. Deux formats sont disponibles :

- GPX : c'est le format le plus complet. Il permet de stocker plusieurs traces en même temps, et est fourni en général par les logiciels de type "Marine"
- CSV : les points relevés sont enregistrés dans un tableau, avec un fichier par trace.

L'importation s'effectue depuis le détail du trait, après qu'il ait été créé.

L'importation d'un fichier GPX s'effectue en deux étapes, celui-ci pouvant contenir plusieurs traces. Dans un premier temps, le logiciel recherche les traces existantes dans le fichier et indique les heures de début et de fin de chaque trace.
Indiquez le numéro de la trace que vous voulez importer, et le logiciel lancera alors l'importation.

Pour les fichiers CSV, ceux-ci doivent impérativement contenir les trois champs suivants :
- time : date-heure du point, sous la forme 2021-06-01T13:09:46.000Z ou équivalente (il faut respecter le format AAAA-MM-JJ HH:mm:ss au minimum)
- lon : la longitude, sous forme décimale
- lat : la latitude sous forme décimale

L'ordre des colonnes n'importe pas, le logiciel les retrouvera. Par contre, les champs doivent impérativement être nommés de la même façon.
D'autres champs peuvent exister dans le fichier, ils ne seront pas traités.

Une fois l'importation effectuée, la trace sera affichée en **bleu** sur la carte. **Vérifiez que les traces rouge et bleue se superposent approximativement**, au moins au niveau des points de départ et d'arrivée.

### Saisie des informations sur les captures

La saisie des informations sur les captures se fait en deux temps : 

- La saisie des informations concernant l’échantillon
- La saisie des information concernant les individus

Dans la première partie, vous indiquez le nom de l’espèce; la recherche du nom de l’espèce se fait sur le nom commun ou le nom latin. Plusieurs choix sont parfois possibles selon le mot clé utilisé (ex: Pomatoschistus vous proposera la liste de toutes les espèces de ce genre actuellement dans la base. Avec le mot clé minutus, vous pourriez avoir plusieurs espèces ayant le mot “minutus” dans son nom Trisopterus minutus, Pomatoschistus minutus, etc.) Utilisez les flèches d’ascenseur de la fenêtre qui apparait pour voir les différents choix de nom et sélectionnez le bon.

Une fois l’espèce sélectionnée, indiquer le nombre et le poids total de l’échantillon pour cette espèce. Une fois enregistré, vous pourrez passer à la saisie des tailles et poids individuels. Il suffit de saisir la première taille puis d’appuyer sur “entrer” sur votre clavier pour passer à l’individu suivant ou sur “tab” pour passer à la saisie du poids.

Pour saisir une nouvelle espèce cliquez sur le lien “nouvelle espèce” ou cliquez sur le lien retour à la liste des traits pour saisir un nouveau trait. 
Si vous cliquez sur “retour au détail du trait” vous pourrez contrôler que vous avez bien saisi toutes les espèces de votre trait.

## Exportation des données

A la fin de votre saisie, vous pouvez exporter à partir de la page concernant les traits de chalut, les informations sur les traits, les échantillons et les captures que vous pourrez importer dans le gestionnaire de base de données de votre choix ou simplement ouvrir les fichiers avec un tableur. 