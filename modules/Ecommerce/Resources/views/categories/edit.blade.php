<x-layouts.admin>
    <x-slot name="title">
        Edit Category: {{ $category->name }}
    </x-slot>

    <x-slot name="content">
        <form action="{{ route('ecommerce.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Category Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Category Name *</label>
                                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $category->name) }}">
                            </div>
                            
                            <div class="form-group mt-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
                            </div>
                            
                            <div class="form-group mt-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active & Visible</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top">
                            <button type="submit" class="btn btn-primary">Update Category</button>
                            <a href="{{ route('ecommerce.categories.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-slot>
</x-layouts.admin>
