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
