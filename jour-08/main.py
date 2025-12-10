import sqlite3
import folium
from pyproj import Transformer

# 1. Connexion à la base de données
conn = sqlite3.connect('kids.db')
cursor = conn.cursor()

# 2. Requête SQL pour extraire le Top 3
query = """
SELECT
    c.first_name,
    c.last_name,
    ct.name AS city,
    co.name AS country,
    ep.x_m,
    ep.y_m,
    b.nice_score
FROM
    children c
JOIN
    behavior b ON c.id = b.child_id
JOIN
    households h ON c.household_id = h.id
JOIN
    cities ct ON h.city_id = ct.id
JOIN
    countries co ON ct.country_code = co.code
JOIN
    elf_plan ep ON c.id = ep.child_id
WHERE
    b.year = 2025
ORDER BY
    b.nice_score DESC
LIMIT 3;
"""
cursor.execute(query)
top3 = cursor.fetchall()
conn.close()

# 3. Vérification des résultats
if not top3:
    raise ValueError("Aucun enfant trouvé dans la base de données.")

# 4. Affichage des résultats
for child in top3:
    print(f"{child[0]} {child[1]} from {child[2]}, {child[3]} - Score: {child[6]}")

# 5. Conversion des coordonnées (EPSG:3857 → EPSG:4326)
transformer = Transformer.from_crs("EPSG:3857", "EPSG:4326", always_xy=True)
children_with_coords = []
for child in top3:
    first_name, last_name, city, country, x_m, y_m, nice_score = child
    lon, lat = transformer.transform(x_m, y_m)
    children_with_coords.append((first_name, last_name, city, country, nice_score, lat, lon))

# 6. Création de la carte
if children_with_coords:
    m = folium.Map(location=[children_with_coords[0][5], children_with_coords[0][6]], zoom_start=2)
    for child in children_with_coords:
        folium.Marker(
            location=[child[5], child[6]],
            popup=f"{child[0]} {child[1]} ({child[2]}, {child[3]})<br>Score: {child[4]}"
        ).add_to(m)
    m.save('top3_sages_map.html')
    print("Carte générée : top3_sages_map.html")
else:
    print("Aucune donnée valide pour générer la carte.")
