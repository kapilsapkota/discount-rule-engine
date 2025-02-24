## Discount Rule Engine

## Overview 
This Discount Rule Engine allows you to create and manage discount rules that are applied to a shopping cart,
either at the item level or cart level. Discounts can be based on product categories, minimum cart total,
or a fixed amount/percentage. This engine supports stackable discounts, ensuring multiple rules are applied in
formatted cart.

## Features
- Item-Level Discounts: Apply discounts to specific categories or individual items.
- Cart-Level Discounts: Apply discounts to the entire cart based on its cart subtotal.
- Stacking Discounts: Automatically combine applicable item and cart discounts.
- Flexible Rule Creation: Supports percentage-based or fixed amount discounts.


## Quick Setup

1. Clone the repository:
   ```sh
   git clone https://github.com/kapilsapkota/discount-rule-engine.git
   cd discount-rule-engine
   ```

2. Install dependencies:
   ```sh
   composer install
   npm install && npm run build
   ```

3. Set up environment variables:
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```

4. Run migrations:
   ```sh
   php artisan migrate
   ```

5. Seed database (if necessary):
   ```sh
   php artisan db:seed
   ```
   This will seed some demo categories.

6. Start the server:
   ```sh
   php artisan serve
   ```

## How to Use the Discount Rule Engine

### Create Discount Rules
We have CRUD for discounts. Please follow the navigation from welcome blade. Or Start with

   ```
   http://localhost:8000/discounts
   ```


### Apply Discounts API

Keep in mind only subtotal, item.price and item.category_id are validated.

**Endpoint:**
```
POST /api/discounts/apply
```

**Request Body:**
```json
{
  "subtotal": 100,
  "items": [
    {"id": 1, "price": 50, "category_id": 1},
    {"id": 2, "price": 50, "category_id": 2}
  ]
}
```
