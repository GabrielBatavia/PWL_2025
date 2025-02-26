@extends('layouts.master')

@section('content')
    <h1>POS Transaction</h1>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('sales.process') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="product_name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" required>
                </div>

                <div class="mb-3">
                    <label for="total" class="form-label">Total</label>
                    <input type="text" class="form-control" id="total" name="total" readonly>
                </div>

                <button type="submit" class="btn btn-primary">Submit Transaction</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('quantity').addEventListener('input', calculateTotal);
        document.getElementById('price').addEventListener('input', calculateTotal);

        function calculateTotal() {
            let quantity = document.getElementById('quantity').value;
            let price = document.getElementById('price').value;
            let total = quantity * price;
            document.getElementById('total').value = total ? total : 0;
        }
    </script>
@endsection
