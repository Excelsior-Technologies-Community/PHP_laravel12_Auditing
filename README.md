# PHP_laravel12_Auditing

<p align="center">
<a href="#"><img src="https://img.shields.io/badge/Laravel-12-red" alt="Laravel Version"></a>
<a href="#"><img src="https://img.shields.io/badge/PHP-8.x-blue" alt="PHP Version"></a>
<a href="#"><img src="https://img.shields.io/badge/Authentication-Laravel%20Breeze-green" alt="Auth System"></a>
<a href="#"><img src="https://img.shields.io/badge/Auditing-owen--it%2Flaravel--auditing-orange" alt="Auditing Package"></a>
<a href="#"><img src="https://img.shields.io/badge/Database-MySQL-lightgrey" alt="Database"></a>
</p>

---

## Overview

This project is a complete Laravel 12 Product Management system integrated with an advanced Auditing mechanism. It demonstrates how to track create, update, and delete operations automatically using the `owen-it/laravel-auditing` package. The system records user activity, stores old and new values in JSON format, and displays audit history in a professional timeline-based UI.

The purpose of this project is to showcase a clean implementation of CRUD operations with secure authentication and enterprise-style audit logging.

---

## Features

* Laravel 12 Installation and Setup
* Authentication using Laravel Breeze
* Product CRUD (Create, Read, Update, Delete)
* Automatic Audit Logging for Model Changes
* User-Based Change Tracking
* Before and After Value Comparison
* Timeline-Based Audit History UI
* PHP 8 Compatible Numeric Formatting
* Protected Routes with Authentication Middleware
* Clean and Professional UI Design

---

## Folder Structure

```
auditing-project/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── ProductController.php
│   └── Models/
│       └── Product.php
│
├── database/
│   └── migrations/
│       ├── create_products_table.php
│       └── create_audits_table.php
│
├── resources/
│   └── views/
│       └── products/
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── edit.blade.php
│           └── audits.blade.php
│
├── routes/
│   └── web.php
│
├── .env
└── README.md
```


## 1. Project Installation

### Step 1: Create Laravel 12 Project

```
composer create-project laravel/laravel auditing-project
```

---

## 2. Database Configuration

Open `.env` file and configure your database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=auditing
DB_USERNAME=root
DB_PASSWORD=
```

Create database in MySQL:

```
CREATE DATABASE auditing;
```

Run default migrations:

```
php artisan migrate
```

---

## 3. Install Laravel Auditing Package

We will use:

```
owen-it/laravel-auditing
```

Install package:

```
composer require owen-it/laravel-auditing
```

Publish configuration and migration:

```
php artisan vendor:publish --provider="OwenIt\Auditing\AuditingServiceProvider"
```

Run migration:

```
php artisan migrate
```

Now the `audits` table is created.

---

## 4. Install Authentication (User Tracking)

We use Laravel Breeze for authentication.

```
composer require laravel/breeze --dev
php artisan breeze:install
npm install
npm run dev
php artisan migrate
```

Now login/register system is ready.
Auditing will automatically track the logged-in user.

---

## 5. Create Product Module

### Step 1: Create Model and Migration

```
php artisan make:model Product -m
```

### Step 2: Update Migration

`database/migrations/xxxx_create_products_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

Run migration:

```
php artisan migrate
```

---

## 6. Enable Auditing in Product Model

`app/Models/Product.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Product extends Model implements Auditable
{
    use AuditableTrait;

    protected $fillable = [
        'name',
        'price',
        'status',
    ];

    protected $auditInclude = [
        'name',
        'price',
        'status',
    ];
}
```

---

## 7. Product Controller

`app/Http/Controllers/ProductController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Display all products
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // Show create product form
    public function create()
    {
        return view('products.create');
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|integer',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index');
    }

    // Show edit form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return redirect()->route('products.index');
    }

    // Delete product
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('products.index');
    }

    // Show audit history
    public function audits($id)
    {
        $product = Product::findOrFail($id);
        $audits = $product->audits()->latest()->get();
        return view('products.audits', compact('audits'));
    }
}
```

---

## 8. Routes

`routes/web.php`

```php
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::resource('products', ProductController::class);

    Route::get('products/{id}/audits',
        [ProductController::class, 'audits']
    )->name('products.audits');
});

require __DIR__.'/auth.php';
```

---

## 9. Blade Views

### Folder

```
resources/views/products
```

### index.blade.php
```
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h1 {
            margin-bottom: 20px;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            margin-right: 5px;
        }

        .btn-primary { background: #3490dc; color: white; }
        .btn-edit { background: #38c172; color: white; }
        .btn-delete { background: #e3342f; color: white; }

        .product {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        form {
            display: inline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Product List</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
    <hr>

    @foreach($products as $product)
        <div class="product">
            <strong>{{ $product->name }}</strong> - ₹{{ $product->price }}

            <div style="margin-top:10px;">
                <a href="{{ route('products.edit',$product->id) }}" class="btn btn-edit">Edit</a>

                <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-delete">Delete</button>
                </form>

                <a href="{{ route('products.audits',$product->id) }}" class="btn btn-primary">View Audits</a>
            </div>
        </div>
    @endforeach
</div>

</body>
</html>

```

### create.blade.php

```
<!DOCTYPE html>
<html>
<head>
    <title>Create Product</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }

        .card {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 14px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .back-btn:hover {
            background: #5a6268;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            padding: 10px 15px;
            background: #3490dc;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #2779bd;
        }
    </style>
</head>
<body>

<div class="card">

    <a href="{{ route('products.index') }}" class="back-btn">
        ⬅ Back to Products
    </a>

    <h2>Create Product</h2>

    <form method="POST" action="{{ route('products.store') }}">
        @csrf

        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" name="price" placeholder="Price" required>

        <button type="submit">Save</button>
    </form>

</div>

</body>
</html>

```

### edit.blade.php

```
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }

        .card {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 14px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .back-btn:hover {
            background: #5a6268;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button {
            padding: 10px 15px;
            background: #38c172;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #2d995b;
        }
    </style>
</head>
<body>

<div class="card">

    <a href="{{ route('products.index') }}" class="back-btn">
        ⬅ Back to Products
    </a>

    <h2>Edit Product</h2>

    <form method="POST" action="{{ route('products.update',$product->id) }}">
        @csrf
        @method('PUT')

        <input type="text" name="name" value="{{ $product->name }}" required>
        <input type="number" name="price" value="{{ $product->price }}" required>

        <button type="submit">Update</button>
    </form>

</div>

</body>
</html>

```

### audits.blade.php

```
<!DOCTYPE html>
<html>
<head>
    <title>Audit History</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 25px;
            padding: 8px 14px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .back-btn:hover {
            background: #5a6268;
        }

        h2 {
            margin-bottom: 30px;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 6px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #3490dc;
        }

        .audit {
            position: relative;
            background: #f9fafb;
            padding: 20px 25px;
            margin-bottom: 30px;
            border-radius: 10px;
        }

        .audit::before {
            content: "";
            position: absolute;
            left: -24px;
            top: 25px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #3490dc;
        }

        .audit.created::before { background: #38c172; }
        .audit.updated::before { background: #3490dc; }
        .audit.deleted::before { background: #e3342f; }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            display: inline-block;
            margin-bottom: 10px;
        }

        .created-badge { background: #38c172; }
        .updated-badge { background: #3490dc; }
        .deleted-badge { background: #e3342f; }

        .meta {
            font-size: 14px;
            margin-bottom: 15px;
            color: #555;
        }

        .changes {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .change-row {
            margin-bottom: 8px;
            font-size: 14px;
        }

        .field {
            font-weight: 600;
        }

        .old {
            color: #e3342f;
            font-weight: bold;
        }

        .new {
            color: #38c172;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">

    <a href="{{ route('products.index') }}" class="back-btn">
        ⬅ Back to Products
    </a>

    <h2>Audit History</h2>

    <div class="timeline">

        @forelse($audits as $audit)

            <div class="audit {{ $audit->event }}">

                {{-- Event Badge --}}
                <span class="badge 
                    {{ $audit->event == 'created' ? 'created-badge' : '' }}
                    {{ $audit->event == 'updated' ? 'updated-badge' : '' }}
                    {{ $audit->event == 'deleted' ? 'deleted-badge' : '' }}">
                    {{ ucfirst($audit->event) }}
                </span>

                {{-- Meta Info --}}
                <div class="meta">
                    <strong>User:</strong>
                    {{ $audit->user ? $audit->user->name : 'System' }}
                    |
                    <strong>Date:</strong>
                    {{ $audit->created_at->format('d M Y, h:i A') }}
                </div>

                {{-- Changes --}}
                <div class="changes">
                    <strong>Changes:</strong>

                    @foreach($audit->new_values as $field => $value)

                        <div class="change-row">
                            <span class="field">
                                {{ ucfirst($field) }}
                            </span> :

                            {{-- OLD VALUE --}}
                            <span class="old">
                                @if(isset($audit->old_values[$field]))
                                    @if(is_numeric($audit->old_values[$field]))
                                        ₹{{ number_format((float)$audit->old_values[$field]) }}
                                    @else
                                        {{ $audit->old_values[$field] }}
                                    @endif
                                @else
                                    N/A
                                @endif
                            </span>

                            →

                            {{-- NEW VALUE --}}
                            <span class="new">
                                @if(is_numeric($value))
                                    ₹{{ number_format((float)$value) }}
                                @else
                                    {{ $value }}
                                @endif
                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

        @empty
            <p>No audit history found.</p>
        @endforelse

    </div>

</div>

</body>
</html>

```

## How Auditing Works

When a product is:

* Created → event = created
* Updated → event = updated
* Deleted → event = deleted

Stored in `audits` table:

* user_id
* event
* old_values (JSON)
* new_values (JSON)
* created_at

---

## How to Run the Project

```
php artisan serve
```

Visit:

```
http://127.0.0.1:8000/register
```

Steps:

1. Register

   <img width="444" height="393" alt="Screenshot 2026-02-10 121138" src="https://github.com/user-attachments/assets/3d60101d-73cd-42fc-9cad-b2af81818ec7" />


2. Login

   <img width="449" height="276" alt="Screenshot 2026-02-10 121202" src="https://github.com/user-attachments/assets/dca6416d-0309-4b8b-a5ab-f9eaba028615" />


3. Product List (Index)

   <img width="860" height="286" alt="Screenshot 2026-02-10 111729" src="https://github.com/user-attachments/assets/28883c32-ae42-4993-8f2a-7909d4e830fb" />

4. Create Product

   <img width="561" height="329" alt="Screenshot 2026-02-10 110839" src="https://github.com/user-attachments/assets/b2ad5fd8-696b-40b7-8412-3a8c00ac90ea" />

5. Update Product

    <img width="564" height="327" alt="Screenshot 2026-02-10 110904" src="https://github.com/user-attachments/assets/a1c1a0b5-75b7-4693-85cc-39b55dc9161d" />

6. View Audit History
    
---

## Example Audit Output

If price changes:

```
Price: ₹50,000 → ₹80,000
```

If name changes:

```
Name: MOBILE → LAPTOP
```
<img width="971" height="701" alt="Screenshot 2026-02-10 111614" src="https://github.com/user-attachments/assets/a937493d-aa78-44cb-97b2-32957a70acf0" />

<img width="876" height="858" alt="Screenshot 2026-02-10 111640" src="https://github.com/user-attachments/assets/69d0a5e4-9ef0-41d9-9911-3a82159d6a01" />

---

