# README

Repo for the Hattrick French Scout team applications

## Travailler sur Github
### Cloner le repo et créer un fork
Pour ajouter une clef SSH, il suffit de suivre les étpes ici : https://help.github.com/en/articles/adding-a-new-ssh-key-to-your-github-account.

Après avoir ajouté votre clef SSH sur Gitlab vous pouvez cloner le repo en local (je vous conseille Git Bash sur Windows) :
```sh
git clone git@github.com:hattrick-french-dtn/dtn-interface.git hattrick # Crée une copie locale du projet dans le dossier hattrick
cd hattrick

```
Vous pouvez ensuite forker le projet, via l'option Github. Pour le faire également en local et vérifier, voici les commandes à saisir :
```sh
git clone NOM_UTILISATEUR git@github.com:NOM_UTILISATEUR/dtn-interface.git 
git remote add NOM_UTILISATEUR git@github.com:NOM_UTILISATEUR/dtn-interface.git
git remote -v
git fetch NOM_UTILISATEUR
```

Voilà, vous êtes prêt.

### Travailler avec les branches
Depuis la branche master, voici comment créer une branche et pousser des modifs :
```sh
git checkout -b Mabranche # Crée une nouvelle branche Mabranche depuis la branche actuelle (placez-vous sur master dans la plupart des cas)

* vous faites vos modifs *

git add fichiersmodifiés # Ajoute vos fichiers modifiés pour un commit
git commit
git push Nom_Utilisateur mabranche # Pousse mabranche modifiée sur votre fork en ligne
```

Si vous travaillez depuis le site Github, vous pouvez simplement modifier les fichiers depuis votre fork, les enregistrer sur une branche, et vous arrivez au même résultat.

Il ne vous reste plus qu'à créer une Pull Request de votre branche vers Master, en explicitant vos modifications. Un administrateur se chargera de tester puis valider vos modifications.

Il est possible de faire plusieurs commits par PR (un commit par modification mineure que vous pouvez expliquer en une ligne est une bonne pratique).

### Utiliser les Issues
Quelques règles de bon fonctionnement pour les Issues :
- Une issue par problème
- On référence chaque PR à l'Issue à laquelle elle est liée en commentant #idIssue sous la PR
- Lorsqu'une Issue est résolue, on la clôture.

## Faire fonctionner la base en local

TODO


## Version 2.2.6
### Enhancement
+ fiche resume, append the link to hattrick player page
+ fiche forum
    + append the link to hattrick player page 
	+ add some decoration to ease selection of the text

## Version 2.2.5
### Enhancement
+ Fiche consultation, allow to display htms
    + only choosen caracteristics
	+ choosen caracteristics and training weeks
+ Add default HTMS to recherche_result.php
+ Add default HTMS to ExportCsv.php

## Version 2.2.4
### Features
+ add HTMS point to TOP for forum and Fiche consultation

## Version 2.2.3
### Bugfix
+ redirection when session expire to a valid page

## Version 2.2.2
### Bugfix
+ iihelp send message using gmail account

## Version 2.2.1
### Features
+ add HTMS point

## Version 2.2
### Features
+ port to PHP5
+ move all mysql code from myqsl_ to PDO
+ second team scan
+ internationnal team scan

### Missing
+ iihelp send message
 
### Bugfix
+ support of the nickname and firstname
+ fix error for player team do not grant access to its skills
+ fix ficheDTN layout
+ fix jpgrah
