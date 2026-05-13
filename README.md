# ⚽ MatchDay — Sistema de Gestión de Torneos de Fútbol

Sistema web desarrollado con **Laravel 13** para la gestión integral de torneos de fútbol. Permite crear torneos con fase de grupos y eliminatorias, registrar equipos y jugadores, generar fixtures automáticos y visualizar estadísticas en tiempo real.

> Proyecto Final — Ingeniería en Sistemas Inteligentes · Aplicaciones Web Interactivas · UASLP 2025-2026/II

---

## 🚀 Tecnologías

| Capa | Tecnología |
|---|---|
| Backend | Laravel 13 · PHP 8.3+ |
| Base de datos | MySQL 8.0 |
| Autenticación | Laravel Breeze + Sanctum |
| Roles | Spatie Laravel Permission |
| PDF | barryvdh/laravel-dompdf |
| Correos | Laravel Mailable + Mailtrap |
| Frontend | Blade + Tailwind CSS + Vite |
| Gráficos | Chart.js |
| IA | Gemini API (Google) |
| Control de versiones | Git + GitHub |

---

## ✅ Funcionalidades

- **3 CRUDs completos**: Torneos, Equipos/Jugadores, Partidos
- **2 Roles**: Admin (gestión total) y Capitán (vista de su equipo)
- **Fixture automático** con algoritmo Round-Robin y sorteo aleatorio
- **Tabla de posiciones** en tiempo real (puntos, DG, GF)
- **Fases eliminatorias** generadas automáticamente (Cuartos → Semis → Final)
- **Penales** en partidos eliminatorios con empate
- **Bracket visual** con campeón destacado
- **Reportes PDF**: Fixture, Standings y Bracket
- **API RESTful** con autenticación Sanctum (5 endpoints)
- **Correos automáticos** al inscribir equipos y registrar resultados
- **Dashboards** con gráficos dinámicos por rol
- **Análisis IA** del próximo partido con Gemini API

---

## 📋 Requisitos

- PHP 8.3+
- Composer 2.x
- Node.js 18+ y npm
- MySQL 8.0
- Git

---

## ⚙️ Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/JoseGonzalez0428/matchday.git
cd matchday
```

### 2. Instalar dependencias

```bash
composer install
npm install
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=matchday
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
MAIL_FROM_ADDRESS=noreply@matchday.test
MAIL_FROM_NAME="MatchDay"

GEMINI_API_KEY=tu_api_key_gemini
AI_ANALYSIS_ENABLED=false
```

### 5. Crear la base de datos

Crea una base de datos llamada `matchday` en MySQL.

### 6. Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

### 7. Crear enlace de storage

```bash
php artisan storage:link
```

### 8. Compilar assets y levantar servidor

```bash
# Terminal 1
npm run dev

# Terminal 2
php artisan serve
```

Abre `http://127.0.0.1:8000` en el navegador.

---

## 👤 Credenciales de prueba

| Rol | Email | Contraseña |
|---|---|---|
| Admin | admin@matchday.test | password |
| Capitán | jardine@matchday.test | password |
| Capitán | cocca@matchday.test | password |

---

## 🌐 Endpoints de la API

| Método | Ruta | Descripción |
|---|---|---|
| POST | `/api/auth/token` | Obtener token de acceso |
| POST | `/api/auth/logout` | Revocar token |
| GET | `/api/tournaments` | Listar torneos activos |
| GET | `/api/tournaments/{id}` | Detalle de un torneo |
| GET | `/api/tournaments/{id}/standings` | Tabla de posiciones |
| GET | `/api/tournaments/{id}/matches` | Fixture completo |
| POST | `/api/matches/{id}/result` | Registrar resultado |

La colección de Postman está en `/docs/MatchDay.postman_collection.json`.

---

## 🗄️ Estructura de la base de datos

users
tournaments
groups
group_team (pivot)
teams
players
tournament_matches
goals
roles / permissions (Spatie)
personal_access_tokens (Sanctum)

---

## 📁 Arquitectura
app/
├── Http/Controllers/
│   ├── Admin/          # Controladores del panel admin
│   ├── Captain/        # Controladores del panel capitán
│   └── Api/            # Controladores de la API REST
├── Services/
│   ├── StandingsService.php    # Cálculo de tabla de posiciones
│   ├── FixtureService.php      # Generación de fixtures
│   └── MatchAnalysisService.php # Análisis IA con Gemini
├── Models/             # Eloquent models
├── Mail/               # Mailables
└── Helpers/
└── StatusHelper.php # Traducciones de status

---

## 📄 Licencia

Proyecto académico — UASLP 2025-2026