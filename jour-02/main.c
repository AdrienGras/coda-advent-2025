#include <stdio.h> 
#include <string.h>

// Définition de la structure Reindeer
typedef struct {
    char name[20];   // Nom du renne (max 19 caractères + '\0')
    char status[20]; // Statut du renne (ex: "présent", "vétérinaire", etc.)
} Reindeer;

/**
 * Fonction pour compter les rennes présents dans le tableau.
 * @param reindeers : Tableau de structures Reindeer
 * @param size : Taille du tableau
 * @return : Nombre de rennes présents
 */
int countPresentReindeers(Reindeer reindeers[], int size) {
    int count = 0;

    // Parcours du tableau de rennes
    for (int i = 0; i < size; i++) {
        // Comparaison du statut avec "présent"
        // On fait attention à ne pas compter les rennes avec des statuts ambigus
        // ... n'est ce pas Prancer ?
        if (strcmp(reindeers[i].status, "présent") == 0) {
            count++; // Incrémentation du compteur si le renne est présent
        }
    }

    return count; 
}

int main(void) {
    // Initialisation du tableau de rennes avec leurs noms et statuts
    Reindeer reindeers[8] = {
        {"Dasher",   "présent"},
        {"Dancer",   "vétérinaire"},
        {"Prancer",  "présent ? 😬"},
        {"Vixen",    "spa"},
        {"Comet",    "présent"},
        {"Cupid",    "parti"},
        {"Donner",   "présent"},
        {"Blitzen",  "présent"}
    };

    // Appel de la fonction pour compter les rennes présents
    int present = countPresentReindeers(reindeers, 8);

    // Affichage du résultat
    printf("🎅 Santa: %d out of %d reindeers are present in the stable tonight.\n", present, 8);

    return 0; 
}
