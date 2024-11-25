@extends('layouts.admin')

@section('title', 'Global Settings')

@section('content')
<div class="main-content">
    <div class="row">
        <br>
        <br>
    </div>
    <div class="container">
   <div class="breadcrumb">

       <h1>Global Settings</h1>
   </div>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="site_name">Site Name</label>
                <input type="text" class="form-control" id="site_name" name="site_name" value="{{ $settings['site_name'] ?? '' }}" required>
            </div>

            <div class="form-group">
                <label for="site_description">Site Description</label>
                <textarea class="form-control" id="site_description" name="site_description">{{ $settings['site_description'] ?? '' }}</textarea>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="maintenance_mode" name="maintenance_mode" value="1" {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="maintenance_mode">Maintenance Mode</label>
            </div>

            <div class="form-group">
                <label for="default_user_role">Default User Role</label>
                <select class="form-control" id="default_user_role" name="default_user_role">
                    @if(isset($roles) && $roles->count() > 0)
                        @foreach($roles as $id => $name)
                            <option value="{{ $id }}" {{ ($settings['default_user_role'] ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    @else
                        <option value="">No roles available</option>
                    @endif
                </select>
            </div>

            <div class="form-group">
                <label for="items_per_page">Items Per Page</label>
                <input type="number" class="form-control" id="items_per_page" name="items_per_page" value="{{ $settings['items_per_page'] ?? 15 }}" min="5" max="100" required>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="allow_user_registration" name="allow_user_registration" value="1" {{ ($settings['allow_user_registration'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="allow_user_registration">Allow User Registration</label>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
        </form>
    </div>
</div>
@endsection