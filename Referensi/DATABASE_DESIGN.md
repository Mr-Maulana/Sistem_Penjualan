# Database Design (ERD Sederhana)

## Tabel Utama

### 1. users
- id (PK)
- name
- email
- password
- role
- created_at
- updated_at

### 2. customers
- id (PK)
- code
- name
- address
- city
- phone
- group
- created_at
- updated_at

### 3. products
- id (PK)
- code
- name
- category
- unit
- created_at
- updated_at

### 4. prices
- id (PK)
- product_id (FK)
- customer_group
- price_large
- price_small
- discount
- tax
- effective_date
- created_at
- updated_at

### 5. sales_invoices (header)
- id (PK)
- invoice_number
- date
- customer_id (FK)
- salesman
- payment_term
- down_payment
- discount
- tax
- total
- note
- status
- created_at
- updated_at

### 6. sales_invoice_details
- id (PK)
- sales_invoice_id (FK)
- product_id (FK)
- price
- quantity
- discount
- bonus
- subtotal
- created_at
- updated_at

### 7. audit_logs
- id (PK)
- user_id (FK)
- action
- table
- record_id
- description
- created_at

---

Relasi:
- 1 user dapat membuat banyak transaksi
- 1 customer memiliki banyak transaksi
- 1 produk memiliki banyak harga dan detail transaksi
- 1 sales_invoice memiliki banyak sales_invoice_details

Diagram visual dapat dibuat pada tahap selanjutnya.

---

Jika ada field tambahan dari sistem lama, akan dimasukkan pada tahap migrasi data.
