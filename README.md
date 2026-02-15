# House Search

Laravel 12 + Vue 3 application for searching houses with filters.

## Setup

```bash
composer install
npm install
php artisan migrate --seed
```

## Run

```bash
npm run dev
php artisan serve
```

Open `http://localhost:8000` in your browser.

## Tech Stack

- **Backend:** PHP 8.4, Laravel 12, SQLite
- **Frontend:** Vue 3 (Composition API, TypeScript), Element Plus, Axios
- **Tools:** Vite, Tailwind CSS, VueUse

## Architecture Highlights

### Pipeline Pattern for Filtering

Instead of chaining `if` statements in the controller, each filter is a separate class using Laravel Pipeline:

```
app/Pipelines/
  Pipe.php              — Abstract base class with auto-resolved filter key
  House/
    NamePipe.php        — LIKE partial match
    BedroomsPipe.php    — Exact match
    BathroomsPipe.php   — Exact match
    StoreysPipe.php     — Exact match
    GaragesPipe.php     — Exact match
    PricePipe.php       — Range filter (min/max)
```

The base `Pipe` class automatically derives the query parameter key from the class name (`BedroomsPipe` → `bedrooms`), eliminating hardcoded strings. Adding a new filter requires only creating a new class and registering it in the controller's `through()` array.

### Clean Controller

The controller has a single `index` method with no conditional logic — all filtering is delegated to the pipeline:

```php
return app(Pipeline::class)
    ->send(House::query())
    ->through([NamePipe::class, BedroomsPipe::class, ...])
    ->thenReturn()
    ->paginate(10);
```

### Frontend

- **Debounced search** on name input (300ms) via `useDebounceFn` from VueUse — prevents excessive API calls
- **Element Plus** components for UI (table, form, pagination, loading, empty state)
- **Error handling** with user-friendly error display
- **All parameters optional** — any combination of filters works

### API

`GET /api/houses` — paginated search endpoint:

| Parameter | Type | Description |
|-----------|------|-------------|
| name | string | Partial match |
| bedrooms | int | Exact match |
| bathrooms | int | Exact match |
| storeys | int | Exact match |
| garages | int | Exact match |
| price_min | numeric | Minimum price |
| price_max | numeric | Maximum price |
| page | int | Page number |

### Database

- SQLite for zero-config portability
- Migration with indexed columns for search performance
- Seeder imports data from CSV (`database/data/property-data.csv`)
- Factory for generating additional test data
