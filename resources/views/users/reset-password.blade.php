@extends('layouts.app')

@section('title', 'Reset Password - ' . $user->name)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Reset Password untuk {{ $user->name }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('users.reset-password', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Reset password untuk karyawan: <strong>{{ $user->name }}</strong> ({{ $user->email }})
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password Baru *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru *</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Reset Password
                </button>
                <a href="{{ route('users.show', $user->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection