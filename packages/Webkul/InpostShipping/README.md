# InPost Paczkomat — moduł wysyłki dla Bagisto

Kompletna integracja metody wysyłki InPost Paczkomat dla platformy Bagisto.
Zawiera carrier class, GeoWidget InPost, zapis wybranego paczkomatu w bazie danych
oraz widok w panelu admina.

---

## Struktura modułu

```
packages/Webkul/InpostShipping/
└── src/
    ├── Carriers/
    │   └── Inpost.php                          # Carrier class — logika stawek
    ├── Config/
    │   ├── carriers.php                         # Definicja metody wysyłki
    │   └── system.php                           # Pola konfiguracyjne admina
    ├── Database/
    │   └── Migrations/
    │       └── 2024_01_01_000000_add_inpost_point_to_orders_and_cart.php
    ├── Http/
    │   ├── Controllers/
    │   │   └── InpostController.php             # AJAX — zapis wybranego paczkomatu
    │   └── routes.php
    ├── Listeners/
    │   └── OrderSaved.php                       # Kopiuje paczkomat z sesji do zamówienia
    ├── Providers/
    │   └── InpostShippingServiceProvider.php    # Główny ServiceProvider
    └── Resources/
        ├── lang/
        │   ├── pl/app.php
        │   └── en/app.php
        └── views/
            ├── admin/orders/
            │   └── inpost-info.blade.php        # Informacja o paczkomacie w adminie
            └── shop/checkout/
                └── geowidget.blade.php          # GeoWidget w checkoucie
```

---

## Instalacja

### 1. Skopiuj pakiet

```
packages/
└── Webkul/
    └── InpostShipping/
```

### 2. Zarejestruj namespace w `composer.json` (główny katalog Bagisto)

```json
{
    "autoload": {
        "psr-4": {
            "Webkul\\InpostShipping\\": "packages/Webkul/InpostShipping/src"
        }
    }
}
```

### 3. Zaktualizuj autoloader

```bash
composer dump-autoload
```

### 4. Zarejestruj ServiceProvider w `bootstrap/providers.php`

```php
<?php

return [
    // ... inne providery ...
    Webkul\InpostShipping\Providers\InpostShippingServiceProvider::class,
];
```

### 5. Uruchom migracje

```bash
php artisan migrate
```

### 6. Wyczyść cache

```bash
php artisan optimize:clear
```

---

## Konfiguracja w panelu admina

1. Przejdź do **Admin → Configure → Shipping Methods → InPost Paczkomat**
2. Uzupełnij pola:
   - **Method Title** — np. `InPost Paczkomat`
   - **Description** — opis wyświetlany w checkoucie
   - **Base Rate** — cena wysyłki (np. `9.99`)
   - **GeoWidget Token** — token z panelu InPost (https://manager.inpost.pl)
   - **Environment** — `sandbox` (testy) lub `production` (produkcja)
   - **Enabled** — `Yes`
3. Zapisz konfigurację

---

## Jak to działa

### Przepływ checkout

```
Klient wybiera „InPost Paczkomat" jako metodę wysyłki
  ↓
View Render Event wstrzykuje GeoWidget poniżej listy metod
  ↓
Klient klika „Wybierz paczkomat" → otwiera się modal z mapą InPost
  ↓
Klient wybiera paczkomat → callback onInpostPointSelected()
  ↓
AJAX POST /inpost/save-point → sesja + CartShippingRate
  ↓
Klient składa zamówienie
  ↓
Event: checkout.order.save.after → OrderSaved::handle()
  ↓
inpost_point_id / inpost_point_name / inpost_point_address
zapisane w tabeli orders
```

### Dane w bazie

Kolumny dodane do tabel:

**`cart_shipping_rates`**
- `inpost_point_id` — ID paczkomatu (np. `WAW123M`)
- `inpost_point_name` — alias `inpost_point_id`
- `inpost_point_address` — adres paczkomatu

**`orders`**
- `inpost_point_id`
- `inpost_point_name`
- `inpost_point_address`

---

## Token GeoWidget

Token uzyskasz w panelu InPost Manager:
- Sandbox: https://sandbox-manager.inpost.pl
- Production: https://manager.inpost.pl

Przejdź do **Integracje → GeoWidget → Tokeny** i wygeneruj nowy token.

---

## Znalezienie właściwego View Render Event

Jeśli GeoWidget nie pojawia się w checkoucie, uruchom Blade Tracer Bagisto:

```bash
# Wyszukaj eventy w widokach checkout
grep -r "view_render_event" packages/Webkul/Shop/src/Resources/views/checkout/
```

Następnie zaktualizuj nazwy eventów w `InpostShippingServiceProvider::boot()`.
