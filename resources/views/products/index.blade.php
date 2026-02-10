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
