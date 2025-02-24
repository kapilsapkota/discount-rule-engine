<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($discount) ? 'Edit' : 'Create' }} Discount</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="font-sans antialiased dark:bg-zinc-900 dark:text-white/50">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-6">
            <h1 class="text-2xl font-semibold text-black dark:text-white">
                {{ isset($discount) ? 'Edit Discount' : 'Create New Discount' }}
            </h1>
            <a href="{{ route('discounts.index') }}" class="btn btn-outline-secondary">
                Back to Discounts
            </a>
        </div>

        <div class="card shadow-sm dark:bg-zinc-800">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST"
                      action="{{ isset($discount) ? route('discounts.update', $discount) : route('discounts.store') }}">
                    @csrf
                    @if(isset($discount))
                        @method('PUT')
                    @endif

                    <div class="row g-4">
                        <!-- Discount Type -->
                        <div class="col-md-6">
                            <label class="form-label">Discount Type</label>
                            <select name="type" class="form-select" required>
                                <option value="percentage" {{ old('type', isset($discount) ? $discount->type : '') == 'percentage' ? 'selected' : '' }}>
                                    Percentage
                                </option>
                                <option value="fixed" {{ old('type', isset($discount) ? $discount->type : '') == 'fixed' ? 'selected' : '' }}>
                                    Fixed Amount
                                </option>
                            </select>
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6">
                            <label class="form-label">Amount</label>
                            <input type="number" name="amount"
                                   value="{{ old('amount', isset($discount) ? $discount->amount : '') }}"
                                   class="form-control" step="0.01" required>
                            @error('amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Min Cart Total -->
                        <div class="col-md-6">
                            <label class="form-label">Minimum Cart Total (Optional)</label>
                            <input type="number" name="min_cart_total"
                                   value="{{ old('min_cart_total', isset($discount) ? $discount->min_cart_total : '') }}"
                                   class="form-control" step="0.01">
                        </div>

                        <!-- Active Dates -->
                        <div class="col-md-6">
                            <label class="form-label">Active From (Optional)</label>
                            <input type="datetime-local" name="active_from"
                                   value="{{ old('active_from', isset($discount) && $discount->active_from ? $discount->active_from->format('Y-m-d\TH:i') : '') }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Active To (Optional)</label>
                            <input type="datetime-local" name="active_to"
                                   value="{{ old('active_to', isset($discount) && $discount->active_to ? $discount->active_to->format('Y-m-d\TH:i') : '') }}"
                                   class="form-control">
                        </div>

                        <!-- Categories -->
                        <div class="col-12">
                            <label class="form-label">Applicable Categories (Optional)</label>
                            <select name="categories[]" multiple class="form-select" style="height: 150px;">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ in_array($category->id, old('categories', isset($discount) ? $discount->categories->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                {{ isset($discount) ? 'Update Discount' : 'Create Discount' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
