<!DOCTYPE html>
<html>
<head>
    <title>All Discounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Discount Management</h1>
        <a href="{{ route('discounts.create') }}" class="btn btn-success">
            Create New Discount
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th scope="col">Type</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Min Cart Total</th>
                    <th scope="col">Active From</th>
                    <th scope="col">Active To</th>
                    <th scope="col">Category</th>
                    <th scope="col" class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($discounts as $discount)
                    <tr>
                        <td>{{ $discount->type }}</td>
                        <td>{{ $discount->amount }}</td>
                        <td>{{ $discount->min_cart_total ?? '-' }}</td>
                        <td>{{ $discount->active_from ? $discount->active_from->format('d M, Y') : '' }}</td>
                        <td>{{ $discount->active_to ? $discount->active_to?->format('d M, Y') : '' }}</td>
                        <td>
                            @if($discount->categories->isNotEmpty())
                                {{ $discount->categories->pluck('name')->join(', ') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('discounts.edit', $discount) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('discounts.destroy', $discount) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this discount?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No discounts found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
