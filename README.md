# SALESFORCESUCKS · Gestión de ventas para consultoría

Aplicación para la **creación y gestión de oportunidades de venta** en entorno B2B para una empresa de consultoría.

## Funcionalidades

- **Empresas**: alta y edición de empresas (clientes potenciales o actuales), sector, tamaño, web y notas.
- **Contactos**: contactos por empresa (nombre, cargo, email, teléfono, contacto principal).
- **Oportunidades**: proyectos/oportunidades vinculados a empresa y contacto, con:
  - Título, descripción, valor estimado y moneda (EUR/USD)
  - Etapa del embudo: Prospección → Calificación → Propuesta → Negociación → Ganada / Perdida
  - Probabilidad, fecha de cierre prevista y responsable asignado
- **Dashboard**: resumen por etapa y valor total del pipeline.
- **Filtros**: por etapa, empresa y estado (abiertas/ganadas/perdidas).

## Requisitos

- PHP 8.2+ con extensiones: `pdo`, `pdo_sqlite`, `json`
- Apache con `mod_rewrite` (opcional, para URLs amigables de la API)

## Instalación

1. Clonar o copiar el proyecto.
2. Asegurar que la carpeta `data/` exista y sea escribible (ahí se crea la base SQLite):
   ```bash
   mkdir -p data && chmod 755 data
   ```
3. En la primera petición se crea la base de datos y las tablas automáticamente.

### Con Docker

```bash
docker build -t oportunidades-b2b .
docker run -p 8080:80 -v $(pwd)/data:/var/www/html/data oportunidades-b2b
```

Abrir `http://localhost:8080`.

## Uso

- **Inicio**: en la pestaña *Oportunidades* se ve el dashboard y el listado. Filtros arriba de la tabla.
- **Nueva empresa**: botón «+ Empresa», rellenar y guardar.
- **Nueva oportunidad**: «+ Oportunidad», elegir empresa (y opcionalmente contacto), título y resto de campos.
- **Contactos**: en la pestaña *Empresas*, en cada fila usar «+ Contacto» para añadir contactos a esa empresa.
- **Editar / Ver detalle**: en el listado de oportunidades, clic en el título (detalle) o en «Editar».

## Estructura del proyecto

```
├── api/
│   └── index.php      # API REST (opportunities, companies, contacts, stages, dashboard)
├── config/
│   └── database.php   # Conexión PDO SQLite y creación del esquema
├── data/              # Base SQLite (generada automáticamente)
├── sql/
│   └── schema.sql     # Definición de tablas
├── .htaccess          # Rewrite para /api/*
├── index.php          # Interfaz web
├── Dockerfile
└── README.md
```

## API

Base: `/api/` (o `/api/index.php?path=...` si no usas rewrite).

| Método | Recurso | Descripción |
|--------|---------|-------------|
| GET | `opportunities` | Lista (query: `stage_id`, `company_id`, `status`) |
| GET | `opportunities/{id}` | Detalle de una oportunidad |
| POST | `opportunities` | Crear oportunidad |
| PUT | `opportunities/{id}` | Actualizar oportunidad |
| DELETE | `opportunities/{id}` | Eliminar oportunidad |
| GET | `companies` | Lista de empresas |
| GET | `companies/{id}` | Empresa y sus contactos |
| POST | `companies` | Crear empresa |
| PUT | `companies/{id}` | Actualizar empresa |
| DELETE | `companies/{id}` | Eliminar empresa |
| GET | `contacts?company_id={id}` | Contactos de una empresa |
| GET | `contacts/{id}` | Detalle contacto |
| POST | `contacts` | Crear contacto |
| PUT | `contacts/{id}` | Actualizar contacto |
| DELETE | `contacts/{id}` | Eliminar contacto |
| GET | `stages` | Etapas del embudo |
| GET | `dashboard` | Resumen pipeline por etapa y valor total |

Todos los cuerpos en JSON y respuestas en JSON.
