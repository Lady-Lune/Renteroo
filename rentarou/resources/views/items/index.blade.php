@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Browse All Items</h2>

    <!-- Search & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Search items..." value="{{ $search }}">
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Items Grid -->
    <div class="row g-4">
        @forelse($items as $item)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5>{{ $item->name }}</h5>
                        <p class="text-muted">{{ $item->category->name }}</p>
                        <h4 class="text-primary">Rs {{ number_format($item->rental_rate, 0) }}/day</h4>
                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-primary w-100">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">No items found</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
@endsection