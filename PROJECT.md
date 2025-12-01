# # 📘 User Stories — Portail Interne ESN

*(Consultants • Commerciaux • Admin)*

---

## ## 👤 Consultant

1. **En tant que consultant, je peux créer et gérer mon profil** (bio, compétences, tags, CV).
2. **En tant que consultant, je peux voir la liste des missions disponibles.**
3. **En tant que consultant, je peux filtrer les missions par tag/compétence.**
4. **En tant que consultant, je peux consulter le détail d’une mission.**
5. **En tant que consultant, je peux postuler à une mission.**
6. **En tant que consultant, je peux voir l’historique de mes candidatures.**
7. **En tant que consultant, je peux retirer ma candidature.**
8. **En tant que consultant, je reçois une notification lorsqu’une nouvelle mission correspond à mes tags.**
9. **En tant que consultant, je reçois une notification lorsqu’un commercial répond à ma candidature.**
10. **En tant que consultant, je peux échanger des messages avec le commercial.** *(optionnel mais recommandé)*

---

## ## 🧑‍💼 Commercial

1. **En tant que commercial, je peux créer une mission.**
2. **En tant que commercial, je peux modifier ou archiver une mission.**
3. **En tant que commercial, je peux associer des tags à une mission.**
4. **En tant que commercial, je peux consulter les candidatures reçues.**
5. **En tant que commercial, je peux filtrer les candidats selon leurs compétences/tags.**
6. **En tant que commercial, je peux consulter le profil détaillé d’un consultant ayant postulé.**
7. **En tant que commercial, je peux contacter un consultant via messagerie.**
8. **En tant que commercial, je reçois une notification lorsqu’un consultant postule.**
9. **En tant que commercial, je peux changer le statut d’une candidature** (en cours, retenu, refusé).

---

## ## 🛠️ Administrateur

1. **En tant qu’admin, je peux gérer les utilisateurs** (consultants, commerciaux, admins).
2. **En tant qu’admin, je peux définir et modifier les rôles.**
3. **En tant qu’admin, je peux gérer la liste des tags** (dev, cloud, data, ia…).
4. **En tant qu’admin, je peux visualiser toutes les missions.**
5. **En tant qu’admin, je peux accéder à un tableau de bord (Filament) avec statistiques.**
6. **En tant qu’admin, je peux gérer les permissions globales de la plateforme.**

---

# # 🗄️ Schéma de Base de Données (Markdown)

---

## ## **1. users**

```sql
users
---------
id               BIGINT PK
name             VARCHAR
email            VARCHAR UNIQUE
password         VARCHAR
role             VARCHAR   -- stocké en string (consultant, commercial, admin)
created_at       TIMESTAMP
updated_at       TIMESTAMP
```

---

## ## **2. consultant_profiles**

(Seulement pour les utilisateurs ayant `role = consultant`)

```sql
consultant_profiles
--------------------
id                BIGINT PK
user_id           BIGINT FK → users.id
bio               TEXT
cv_url            VARCHAR
experience_years  INT
created_at        TIMESTAMP
updated_at        TIMESTAMP
```

---

## ## **3. tags**

```sql
tags
------
id          BIGINT PK
name        VARCHAR
created_at  TIMESTAMP
updated_at  TIMESTAMP
```

---

## ## **4. taggables** (relation polymorphique)

Permet d’associer un tag à :

* une mission
* un consultant

```sql
taggables
-----------
id             BIGINT PK
tag_id         BIGINT FK → tags.id
taggable_id    BIGINT
taggable_type  VARCHAR   -- "Mission" ou "ConsultantProfile"
```

---

## ## **5. missions**

```sql
missions
----------
id             BIGINT PK
commercial_id  BIGINT FK → users.id
title          VARCHAR
description    TEXT
daily_rate     INT
location       VARCHAR
status         VARCHAR  -- active, archived
created_at     TIMESTAMP
updated_at     TIMESTAMP
```

---

## ## **6. applications** (candidatures)

```sql
applications
---------------
id             BIGINT PK
mission_id     BIGINT FK → missions.id
consultant_id  BIGINT FK → users.id
status         VARCHAR   -- pending, viewed, accepted, rejected
created_at     TIMESTAMP
updated_at     TIMESTAMP
```

---

## ## **7. messages** (optionnel mais utile)

```sql
messages
-----------
id             BIGINT PK
sender_id      BIGINT FK → users.id
receiver_id    BIGINT FK → users.id
message        TEXT
created_at     TIMESTAMP
```

---

## ## **8. notifications**

Tu peux utiliser la table native Laravel :

```sql
notifications
--------------
id           UUID PK
type         VARCHAR
notifiable_id BIGINT
notifiable_type VARCHAR
data         JSON
read_at      TIMESTAMP NULL
created_at   TIMESTAMP
updated_at   TIMESTAMP
```

---

# # 📊 Schéma visuel (ASCII)

```
USERS (role: consultant/commercial/admin)
   |
   | 1---1
   |
CONSULTANT_PROFILES
   |            \
   | tags via     \ many-to-many polymorphic
   | taggables      \
   |                 \
TAGS -----< TAGGABLES >----- MISSIONS
                                    |
                                    | 1---N
                                    |
                               APPLICATIONS
```

---
