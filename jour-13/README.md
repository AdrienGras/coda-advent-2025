# Site e-commerce des elfes - Rapport d'audit

## Introduction

Le site des elfes présente des performances médiocres, notamment sur mobile, avec un **First Contentful Paint (FCP) de 8,0 s** et un **Speed Index de 10,2 s**. Sur desktop, les résultats sont meilleurs (FCP de 1,5 s), mais restent perfectibles. L’analyse GreenIT-Analysis révèle **un EcoIndex de 4,44 (note G)**, avec **une empreinte carbone de 2,91 gCO2e** et **une consommation d’eau de 4,37 cl**. Les audits Lighthouse soulignent également des problèmes d’accessibilité (images sans attributs alt, structure des titres) et de bonnes pratiques (erreurs CORS, fichiers JavaScript non minifiés, images non optimisées).

**Classement : 🟥 (Rouge)**

Le site est lent, peu éco-responsable et peu accessible, nécessitant des améliorations urgentes pour réduire son impact environnemental et améliorer l’expérience utilisateur.

## 4 Actions Prioritaires

| Action | Détails | Impact | Effort |
|--------|---------|--------|--------|
| **Optimiser les images** | Compresser et redimensionner les images. Utiliser des formats modernes (WebP, AVIF). | **Élevé** (réduction du poids de la page) | **Faible** |
| **Minifier et différer le JavaScript** | Minifier les fichiers JS et différer le chargement des scripts non critiques. | **Élevé** (réduction du temps de blocage) | **Moyen** |
| **Améliorer la mise en cache** | Configurer des en-têtes de cache pour les ressources statiques. | **Moyen** (meilleure performance pour les visites répétées) | **Faible** |
| **Corriger les problèmes d’accessibilité** | Ajouter des attributs alt aux images et structurer correctement les titres (h1, h2, etc.). | **Moyen** (meilleure accessibilité) | **Faible** |
    
## Documents liés

- [Etude Lighthouse (Desktop)](./lighthouse_desktop.pdf)
- [Etude Lighthouse (Mobile)](./lighthouse_mobile.pdf)
- [Etude EcoIndex/GreenIT](./EcoIndex.png)

---

## Etude générale

### Performances

#### Problèmes identifiés :

- Temps de chargement lent (10 secondes sur mobile).
- Ressources bloquantes (polices Google, scripts de CDN).
- Images non optimisées.
- Temps de réponse du serveur lent.
- Trop de requêtes JavaScript.
- Taille du DOM trop grande.

#### Recommandations :

##### Optimisation des images

- Compresser les images en utilisant des outils comme TinyPNG ou ImageOptim.
- Convertir les images en formats modernes comme WebP ou AVIF.
- Implémenter des images responsives en utilisant l'attribut srcset.

##### Réduction des ressources bloquantes : 

- Déplacer les scripts non critiques vers le bas de la page ou les charger de manière asynchrone en utilisant l'attribut async ou defer.
- Utiliser des polices locales ou des polices système pour réduire les requêtes externes.

##### Optimisation du JavaScript :

- Minifier et compresser tous les fichiers JavaScript en utilisant des outils comme UglifyJS ou Terser.
- Éviter les scripts inutiles et les dépendances lourds.
- Implémenter le chargement paresseux pour les scripts non critiques en utilisant des bibliothèques comme lozad.js.

##### Optimisation du serveur :

- Améliorer le temps de réponse du serveur en optimisant les requêtes de base de données, en utilisant des caches et en améliorant l'infrastructure serveuse.
- Appliquer une compression pour réduire la taille des ressources en utilisant des outils comme Gzip ou Brotli.
- Utiliser des techniques de mise en cache pour réduire le nombre de requêtes en configurant correctement les en-têtes de cache HTTP.

### Accessibilité

#### Problèmes identifiés

- Images sans attributs alt.
- Éléments de heading non ordonnés.
- Éléments interactifs sans labels.
- Contraste des couleurs insuffisant.

#### Recommandations :

- **Ajouter des attributs alt :** Ajouter des attributs alt à toutes les images pour décrire leur contenu.
- **Organiser les éléments de heading :** Organiser les éléments de heading de manière séquentielle (h1, h2, h3, etc.) pour faciliter la navigation.
- **Ajouter des labels aux éléments interactifs :** Ajouter des labels aux éléments interactifs comme les boutons et les liens pour qu'ils soient accessibles via les lecteurs d'écran.
- **Vérifier le contraste des couleurs :** Vérifier le contraste des couleurs en utilisant des outils comme le Contrast Checker et ajuster les couleurs si nécessaire.

## Plan d'action détaillé

### Optimisation des images :

- Compresser toutes les images en utilisant des outils comme TinyPNG ou ImageOptim.
- Convertir les images en formats modernes comme WebP ou AVIF.
- Implémenter des images responsives en utilisant l'attribut srcset.

### Réduction des ressources bloquantes :

- Déplacer les scripts non critiques vers le bas de la page ou les charger de manière asynchrone en - utilisant l'attribut async ou defer.
- Utiliser des polices locales ou des polices système pour réduire les requêtes externes.

### Optimisation du JavaScript

- Minifier et compresser tous les fichiers JavaScript en utilisant des outils comme UglifyJS ou Terser.
- Éviter les scripts inutiles et les dépendances lourds en auditant les scripts utilisés et en supprimant ceux qui ne sont pas nécessaires.
- Implémenter le chargement paresseux pour les scripts non critiques en utilisant des bibliothèques comme lozad.js.

### Amélioration de l'accessibilité

- Ajouter des attributs alt à toutes les images pour décrire leur contenu.
- Organiser les éléments de heading de manière séquentielle (h1, h2, h3, etc.) pour faciliter la navigation.
- Ajouter des labels aux éléments interactifs comme les boutons et les liens pour qu'ils soient accessibles via les lecteurs d'écran.
- Vérifier le contraste des couleurs en utilisant des outils comme le Contrast Checker et ajuster les couleurs si nécessaire.

### Optimisation côté serveur

- Améliorer le temps de réponse du serveur en optimisant les requêtes de base de données, en utilisant des caches et en améliorant l'infrastructure serveuse.
- Appliquer une compression pour réduire la taille des ressources en utilisant des outils comme Gzip ou Brotli.
- Utiliser des techniques de mise en cache pour réduire le nombre de requêtes en configurant correctement les en-têtes de cache HTTP.
