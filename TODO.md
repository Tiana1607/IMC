# Plan : Application IMC — Tâches Prioritaires pour 3 Personnes

**Livrable : lundi 11 mai 2026**

## TL;DR

Construire le MVP en 5 phases pour que trois contributeurs travaillent en parallèle sans conflits Git majeurs.  
Priorités : 1) Auth + Users + BD, 2) Régimes/Activités + CRUD + Admin, 3) Dashboard utilisateur + Recommandations + Portefeuille + PDF.

---

## Phases & Étapes

### **Phase 0 — Configuration du projet (HAUTE, jour 0–0.5)**

1. **BD et configuration**
   - Modifier `app/Config/Database.php` : entrer les identifiants MySQL/PostgreSQL
   - Créer une BD locale de développement
   
2. **Sécurité & filtres**
   - Activer CSRF et session dans `app/Config/Filters.php`
   
3. **Dépendances**
   - `composer require mpdf/mpdf` (pour PDF, alternative: `tecnickcom/tcpdf`)
   
4. **Layout partagé**
   - Créer `app/Views/layout/main.php` (barre nav, header, footer) — réutilisé par tous les autres gabarits

---

### **Phase 1 — Authentification & Modèle Utilisateur (CRITIQUE, jour 0.5–1)**

**Propriétaire recommandé : Personne A (Backend)**

1. **Migration & Modèle Utilisateur**
   - Créer migration `app/Database/Migrations/2026-05-XX-XXXXXX_CreateUsersTable.php`
   - Champs : `id`, `email` (UNIQUE), `password_hash`, `name`, `gender`, `height`, `weight`, `age`, `objective` (gain/perte/ideal), `imc_value`, `wallet_balance`, `is_gold`, `created_at`, `updated_at`
   - Modèle : `app/Models/User.php` avec methods `calculateIMC()`, `updateWallet()`

2. **Contrôleur Authentification**
   - `app/Controllers/Auth.php` avec actions :
     - `GET/POST register_step1()` — formulaire info personnelle (nom, email, genre, taille, age)
     - `GET/POST register_step2()` — formulaire santé (poids, objectif)
     - `GET/POST login()` — login/password
     - `GET logout()`
   - Validation côté serveur (email unique, password ≥ 8 caractères, taille/poids > 0)

3. **Filtre d'authentification**
   - Créer `app/Filters/AuthFilter.php` — vérifie session utilisateur, redirige vers login si absent
   - Enregistrer dans `app/Config/Filters.php`

4. **Vues**
   - `app/Views/auth/register_step1.php` — formulaire perso
   - `app/Views/auth/register_step2.php` — formulaire santé
   - `app/Views/auth/login.php` — login
   - Tous héritent du layout principal

5. **Routes** (ajouter à `app/Config/Routes.php`)
   ```
   GET/POST  /auth/register/step1
   GET/POST  /auth/register/step2
   GET/POST  /auth/login
   GET       /auth/logout
   ```

**Merges requis pour le reste du projet :** Absolument.

---

### **Phase 2 — Régimes & Activités + Admin Basique (HAUTE, jour 1–2)**

**Propriétaire recommandé : Personne C (Admin/Intégration)**

1. **Migrations & Modèles**
   - Régimes : `app/Models/Regime.php` + migration
     - Champs : `id`, `name`, `description`, `calorie_target`, `price_per_week`, `duration_weeks`, `weight_change_percent`, `meat_percent`, `fish_percent`, `poultry_percent`, `created_at`, `updated_at`
   - Activités : `app/Models/Activity.php` + migration
     - Champs : `id`, `name`, `description`, `calories_per_hour`, `intensity` (low/medium/high), `equipment_needed`, `created_at`, `updated_at`
   - **Objectives Liaison (NEW)** :
     - `regime_objectives` : junction table (regime_id, objective) — lie chaque régime aux objectifs supportés (loss/gain/ideal)
     - `activity_objectives` : junction table (activity_id, objective) — lie chaque activité aux objectifs supportés
     - Models : `app/Models/RegimeObjective.php`, `app/Models/ActivityObjective.php` avec helpers : `getRegimesByObjective()`, `getActivitiesByObjective()`, `hasObjective()`
   - **Avantage** : Flexible (un régime peut supporter plusieurs objectifs) + filtrage facile en BD

2. **Contrôleurs Admin CRUD**
   - `app/Controllers/Admin/RegimeController.php` — list, create, edit, update, delete
   - `app/Controllers/Admin/ActivityController.php` — list, create, edit, update, delete
   - Valider tous les champs (prix > 0, pourcentages = 100%)

3. **Contrôleur Dashboard Admin**
   - `app/Controllers/Admin/DashboardController.php`
   - Afficher : nombre utilisateurs, régimes actifs, codes utilisés, revenus totaux

4. **Filtre Admin**
   - Créer `app/Filters/AdminFilter.php` — vérifier flag admin de l'utilisateur (ajouter `is_admin` à table users)
   - Enregistrer dans `app/Config/Filters.php`

5. **Vues Admin**
   - `app/Views/admin/regime/index.php` — liste régimes avec boutons edit/delete
   - `app/Views/admin/regime/form.php` — créer/modifier régime
   - `app/Views/admin/activity/index.php` — liste activités
   - `app/Views/admin/activity/form.php` — créer/modifier activité
   - `app/Views/admin/dashboard.php` — stats, graphiques (Chart.js ok)

6. **Routes Admin** (ajouter à `app/Config/Routes.php`)
   ```
   GET/POST  /admin
   GET/POST  /admin/regimes
   GET/POST  /admin/regimes/:id
   DELETE    /admin/regimes/:id
   GET/POST  /admin/activities
   GET/POST  /admin/activities/:id
   DELETE    /admin/activities/:id
   ```

**Merges requis :** Phase 1 (User/Auth).

---

### **Phase 3 — Portefeuille & Codes Promo (MOYENNE, jour 2–3)**

**Propriétaire recommandé : Personne A (Backend)**

1. **Migrations & Modèles**
   - Portefeuille : `app/Models/Wallet.php` + migration
     - Champs : `id`, `user_id` (FK), `balance`, `created_at`, `updated_at`
   - Codes promo : `app/Models/PromoCode.php` + migration
     - Champs : `id`, `code` (UNIQUE), `amount`, `is_used`, `used_by_user_id` (FK, nullable), `used_at` (nullable), `created_at`

2. **Contrôleur Portefeuille**
   - `app/Controllers/WalletController.php`
   - `POST /wallet/add-code` — valider code promo, créditer portefeuille utilisateur, marquer code comme utilisé
   - `GET /wallet` — afficher solde et historique

3. **Intégration Purchase**
   - Ajouter route `POST /regime/:id/purchase`
   - Vérifier solde utilisateur ≥ prix régime
   - Décrémenter portefeuille, créer record dans table `user_regimes` (FK user + FK regime + date achat)

4. **Modèle relation**
   - `app/Models/UserRegime.php` — liaison user/regime (many-to-many)
   - Champs : `id`, `user_id`, `regime_id`, `purchased_at`, `starts_at`, `ends_at`

**Merges requis :** Phase 1 (User).

---

### **Phase 4 — Logique métier : IMC, Recommandations, Gold (MOYENNE, jour 3–4)**

**Propriétaire recommandé : Personne A (Backend) pour logique, Personne B (Frontend) pour affichage**

1. **Calcul IMC**
   - Helper : `app/Helpers/ImmediateCalculationHelper.php`
   - Fonction `calculateIMC($weight, $height_cm)` → retourne IMC
   - Fonction `getIMCCategory($imc)` → "Sous-poids", "Normal", "Sur-poids", "Obésité"
   - Appeler lors de la sauvegarde du profil utilisateur

2. **Moteur de recommandation**
   - Service `app/Controllers/Dashboard.php` ou helper
   - Logique : basée sur objectif + IMC actuel
     - Si objectif = "Gains" ET IMC < 25 → régimes +calories, activités force
     - Si objectif = "Perte" ET IMC > 25 → régimes -calories, activités cardio
     - Si objectif = "Idéal" → régimes équilibrés
   - Retourner top 3 régimes + 2 activités suggérées
   - Afficher sur `app/Views/dashboard/recommendations.php`

3. **Adhésion Gold**
   - Ajouter champ `is_gold` à tabla users (déjà dans Phase 1)
   - Route `POST /upgrade-gold` — prix fixe (ex: 49€), débité du portefeuille
   - Appliquer 15% réduction sur tous les prix régimes si `is_gold = true`
   - Afficher badge "GOLD" sur le profil

4. **Dashboard utilisateur**
   - `app/Controllers/Dashboard.php`
   - Afficher : IMC, catégorie, objectif, régimes achetés, activités suggérées, solde portefeuille, badge Gold

**Merges requis :** Phase 1 (User), Phase 2 (Regime/Activity), Phase 3 (Wallet).

---

### **Phase 5 — Export PDF, Seeds, Tests, Polish (BASSE→MOYENNE, jour 4–5)**

**Propriétaire recommandé : Personne B (Frontend/PDF), Personne C (Seeds/Tests)**

1. **Export PDF**
   - Créer `app/Controllers/PdfController.php`
   - Route `GET /regime/:id/export-pdf`
   - Utiliser `mpdf/mpdf` : générer PDF avec plan régime (nom, description, calories, composition, durée, prix avec réduction Gold si applicable)
   - Télécharger automatiquement ou afficher en navigateur

2. **Seeds BD**
   - `app/Database/Seeds/UserSeeder.php` — 5 utilisateurs (variés en objectif/IMC)
   - `app/Database/Seeds/RegimeSeeder.php` — 5 régimes (variés: perte, gains, équilibre)
   - `app/Database/Seeds/ActivitySeeder.php` — 5 activités (cardio, force, flexibility)
   - `app/Database/Seeds/PromoCodeSeeder.php` — 15 codes (montants variés: 5€, 10€, 50€)
   - Exécuter `php spark db:seed` pour remplir données de test

3. **Tests**
   - Tests unitaires `tests/unit/` : modèles (IMC, User save/load)
   - Tests intégration `tests/` : workflow register→login→purchase→export-pdf
   - Exécuter `./vendor/bin/phpunit`

4. **UX Polish**
   - Messages flash (succès/erreur) avec CSS Bootstrap alert
   - Validation formulaires JS côté client (optionnel)
   - Redirection post-register vers étape 2 puis dashboard
   - Vérifications : solde portefeuille, prix régime, date expiration

**Merges requis :** Toutes phases précédentes.

---

## Répartition des Rôles & Travail Parallèle

### **Personne A — Backend / Cœur Système**
- **Responsabilités** : Authentification, User model, Wallet/Codes promo, Recommandations, API endpoints
- **Fichiers clés** :
  - `app/Models/User.php`, `Wallet.php`, `PromoCode.php`
  - `app/Controllers/Auth.php`, `WalletController.php`
  - `app/Database/Migrations/` (propriétaire unique — timestamper chaque fichier)
  - Helpers : IMC, recommendations
- **Timing** : Phase 1 → Phase 3 → Phase 4 (logique)

### **Personne B — Frontend / UX / PDF**
- **Responsabilités** : Layout principal, formulaires enregistrement, dashboard utilisateur, interfaces recommandations, export PDF, UX polish
- **Fichiers clés** :
  - `app/Views/layout/main.php`, `app/Views/auth/`, `app/Views/dashboard/`
  - `app/Controllers/PdfController.php`
  - Appels AJAX vers endpoints Personne A
- **Timing** : Phase 0 (layout) → Phase 1 (enregistrement/login UI) → Phase 4 (recommendations UI) → Phase 5 (PDF UI)

### **Personne C — Admin / Intégration / QA**
- **Responsabilités** : CRUD Régimes/Activités, Dashboard admin, Seeds, graphiques, tests, validation finale
- **Fichiers clés** :
  - `app/Controllers/Admin/RegimeController.php`, `ActivityController.php`, `DashboardController.php`
  - `app/Filters/AdminFilter.php`
  - `app/Views/admin/` (toutes vues admin)
  - `app/Database/Seeds/`
  - `tests/`
- **Timing** : Phase 0–1 (infra) → Phase 2 (admin CRUD) → Phase 5 (seeds, tests, polish)

---

## Règles de Collaboration (Réduire les Conflits)

1. **Branches feature courtes**
   - Branch par feature : `feature/auth`, `feature/wallet-codes`, `feature/regimes-crud`, etc.
   - Petites PRs (une tâche logique = une PR)
   - Merger vers `main` tous les jours après revue rapide

2. **Migrations — Personne A seule**
   - Seule Personne A crée/édite fichiers migration (`app/Database/Migrations/`)
   - Les deux autres dépendent de migrations stables mergées
   - Si quelqu'un d'autre doit ajouter une migration, **noter timestamp et notifier l'équipe**

3. **Vues — Propriété par composant**
   - Personne A : aucune vue, logs/helpers uniquement
   - Personne B : `app/Views/auth/`, `app/Views/dashboard/`, `app/Views/layout/`
   - Personne C : `app/Views/admin/`
   - ✅ Pas d'édition concurrente du même fichier

4. **Contrats API partagés**
   - Créer `API.md` documentant endpoints, paramètres, réponses JSON
   - Personne A définit contrat, B/C consomment
   - Évite synchronisation lente

5. **Commit & Push quotidien**
   - Au moins un commit/push par jour par personne
   - Messages clairs : `feat: add user login`, `fix: handle missing regime price`, etc.

---

## MVP Minimal (Deadline 11 mai)

### **Jour 0–1 (Must Complete)**
- ✅ Config BD, migration users, auth (register 2-step + login)
- ✅ Dashboard utilisateur avec IMC, objectif, solde portefeuille
- ✅ Layout principal avec barre nav

### **Jour 1–2**
- ✅ Modèle Régimes + admin CRUD basique
- ✅ Recommandations (mapping statique ok : objectif + IMC range → régimes)
- ✅ Portefeuille + achat régime (décrément solde, création record)
- ✅ Codes promo (validation + crédit)

### **Jour 3–4**
- ✅ Export PDF régime (titre, description, prix, durée)
- ✅ Dashboard admin (stats simples)
- ✅ Adhésion Gold (flag + 15% réduction)
- ✅ Activities (affichage suggérées sur dashboard)
- ✅ Seeds données : 5 users, 5 régimes, 5 activités, 15 codes

### **Jour 4–5**
- ✅ Polish UX (messages flash, validation, redirects)
- ✅ Tests manuels + reproduction checklist
- ✅ Préparation livrables :
  - SQL dump (`database_dump.sql`)
  - README avec instructions (clone, `composer install`, migrations, seeds, run local)
  - Liste membres + lien GitHub/GitLab
  - Form Google (lien livrables)

---

## Verification Checklist

1. **BD & Migrations**
   - [ ] Exécuter `php spark migrate` → tables `users`, `regimes`, `activities`, `wallets`, `promo_codes`, `user_regimes` créées
   - [ ] Exécuter `php spark db:seed` → 5 users, 5 régimes, 5 activités, 15 codes promo présents

2. **Workflow Utilisateur**
   - [ ] Inscription (step 1 + step 2) sans erreurs
   - [ ] IMC calculé et affiché après step 2
   - [ ] Login avec email/password valide
   - [ ] Dashboard affiche IMC, objectif, portefeuille
   - [ ] 3 régimes recommandés affichés basés sur objectif
   - [ ] Ajouter code promo → solde portefeuille augmente
   - [ ] Acheter régime → solde baisse, `user_regimes` record créé
   - [ ] Exporter régime en PDF → fichier téléchargé

3. **Workflow Admin**
   - [ ] Connexion admin, accès `/admin`
   - [ ] Dashboard affiche stats (users, régimes, revenus)
   - [ ] Créer régime → apparaît dans liste
   - [ ] Éditer régime → prix/composition modifiés
   - [ ] Supprimer régime
   - [ ] Créer activité, éditer, supprimer

4. **Gold Membership**
   - [ ] Acheter adhésion Gold → `is_gold = true`, solde débité
   - [ ] Prix régimes affichent -15%
   - [ ] Non-Gold voit prix plein

5. **Git & Code**
   - [ ] Tous commits + pushes quotidiens
   - [ ] Branches mergées vers `main` après revue
   - [ ] No conflicts dans `main`

---

## Fichiers Clés à Créer / Modifier

| Catégorie | Fichiers |
|-----------|----------|
| **Migrations** | `2026-05-XX-*_CreateUsersTable.php`, `..._CreateRegimesTable.php`, `..._CreateActivitiesTable.php`, `..._CreateWalletsTable.php`, `..._CreatePromoCodesTable.php`, `..._CreateUserRegimesTable.php`, `..._CreateRegimeObjectivesTable.php`, `..._CreateActivityObjectivesTable.php` |
| **Modèles** | `User.php`, `Regime.php`, `Activity.php`, `Wallet.php`, `PromoCode.php`, `UserRegime.php`, `RegimeObjective.php`, `ActivityObjective.php` |
| **Contrôleurs** | `Auth.php`, `Dashboard.php`, `WalletController.php`, `Admin/RegimeController.php`, `Admin/ActivityController.php`, `Admin/DashboardController.php`, `PdfController.php` |
| **Filtres** | `AuthFilter.php`, `AdminFilter.php` |
| **Vues** | `layout/main.php`, `auth/{register_*,login}.php`, `dashboard/index.php`, `admin/{regime,activity,dashboard}.php` |
| **Helpers** | `ImmediateCalculationHelper.php` (IMC), seeds, recommandation logic |
| **Config** | Routes.php, Filters.php, Database.php, Validation.php |

---

## Estimations Temps

| Phase | Durée | Critique | Qui |
|-------|-------|----------|-----|
| 0 | 0.5j | OUI | Tous ensemble |
| 1 | 1j | CRITIQUE | A (exec), B/C (wait/prep) |
| 2 | 1j | OUI | C (exec), A (support), B (prep) |
| 3 | 1j | OUI | A (exec), B (UI pour wallet) |
| 4 | 1j | OUI | A (logique) + B (UI) |
| 5 | 1j | NON | B (PDF) + C (seeds, tests) |
| **Total** | **5.5j** | | |

**Date limite : 11 mai (lundi) — 5 jours à partir de maintenant (6 mai)**

---

## Questions Rapides à Clarifier

1. **Email de confirmation requis ?** Recommandation : **NON** pour MVP.
2. **Propriétaire migrations ?** Recommandation : **Personne A**
3. **Gold → paiement externe ou portefeuille ?** Recommandation : **Portefeuille** (plus simple).
4. **Nombre admin vs utilisateurs normaux ?** Recommandation : **1 admin**, 4 utilisateurs normaux dans seeds.

---

## Livrables Finaux

En date du 11 mai :

1. **Dépôt Git** (GitHub/GitLab)
   - Toutes branches mergées vers `main`
   - README avec instructions setup (clone, `composer install`, `php spark migrate`, `php spark db:seed`, `php spark serve`)

2. **Script SQL** (`database_dump.sql`)
   - Export BD avec schema + données seeds

3. **Google Form**
   - Lien vers repo, noms membres, description features

4. **Documentation minimale**
   - `API.md` — endpoints résumé
   - `ARCHITECTURE.md` — vue d'ensemble modèles/controllers