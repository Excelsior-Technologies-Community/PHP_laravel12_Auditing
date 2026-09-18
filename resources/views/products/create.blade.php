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

        h2 {
            margin-bottom: 25px;
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

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .error {
            color: #e3342f;
            font-size: 13px;
            margin-top: 5px;
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

    <a
        href="{{ route('products.index') }}"
        class="back-btn"
    >
        ⬅ Back to Products
    </a>

    <h2>Create Product</h2>

    <form
        method="POST"
        action="{{ route('products.store') }}"
    >

        @csrf

        <div class="form-group">

            <label for="name">
                Product Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Product Name"
                required
            >

            @error('name')
                <div class="error">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="price">
                Price
            </label>

            <input
                type="number"
                id="price"
                name="price"
                value="{{ old('price') }}"
                placeholder="Price"
                min="0"
                required
            >

            @error('price')
                <div class="error">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group">

            <label for="status">
                Status
            </label>

            <select
                id="status"
                name="status"
                required
            >

                <option
                    value="active"
                    {{ old('status', 'active') === 'active' ? 'selected' : '' }}
                >
                    Active
                </option>

                <option
                    value="inactive"
                    {{ old('status') === 'inactive' ? 'selected' : '' }}
                >
                    Inactive
                </option>

            </select>

            @error('status')
                <div class="error">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <button type="submit">
            Save Product
        </button>

    </form>

</div>

</body>

</html>