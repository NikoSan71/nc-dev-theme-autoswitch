*English*

**Wordpress Plugin**. Show a different theme if connected with a specific user.

Parameters
--------------
`$dtas_options` = array() with the options :

 - `user2switch`  : array(int) = **IDs** of users who see the development theme.
 - `devTheme` : string = development theme **name**.
 - `show_message` : boolean : true / false = show a little message (with "BETA") on top left.

Todo
------

- Add a plugin panel with options.


---

*Français*

**Plugin Wordpress**. Affiche un thème different en fonction de l'utilisateur connecté. Le but est de pouvoir bosser sur un thème en ligne, sur le site de production, sans avoir à changer de thème manuellement ou employer d'autres ruses :)

Parametres
--------------
`$dtas_options` = array() avec les options :

 - `user2switch`  : array(int) = **IDs** des utilisateur autorisés à voir le thème de developpement.
 - `devTheme` : string = **nom** du thème de developpement.
 - `show_message` : boolean : true / false = affiche (ou non) un message ("beta") en haut a gauche du site. Sert de repère pour être sur que l'on regarde le bon thème.

A faire
------

- Ajouter un panel d'administration pour les options du plugin.
