@extends('layouts.app')

@section('title', 'Profile Pengguna')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <div class="profile-photo-container mb-3">
                            @if(Auth::user()->photo)
                                <img class="profile-user-img img-fluid img-circle" 
                                     src="{{ asset('storage/profiles/' . Auth::user()->photo) }}" 
                                     alt="User profile picture" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="profile-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                     style="width: 150px; height: 150px;">
                                    <span class="text-white display-4">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>
                        <p class="text-muted text-center">
                            <span class="badge badge-{{ Auth::user()->isAdmin() ? 'danger' : 'info' }}">
                                {{ Auth::user()->isAdmin() ? 'Administrator' : 'Karyawan' }}
                            </span>
                        </p>

                        <!-- Upload Photo Form -->
                        <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                            @csrf
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/*">
                                    <label class="custom-file-label" for="photo">Pilih foto</label>
                                </div>
                                @error('photo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm btn-block">
                                <i class="fas fa-upload"></i> Upload Foto
                            </button>
                        </form>
                    </div>

                    <ul class="list-group list-group-unbordered mt-4">
                        <li class="list-group-item">
                            <b>Email</b> 
                            <span class="float-right">{{ Auth::user()->email }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Telepon</b> 
                            <span class="float-right">{{ Auth::user()->telepon ?? '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Bergabung</b> 
                            <span class="float-right">{{ Auth::user()->created_at->format('d M Y') }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> 
                            <span class="float-right">
                                @if(Auth::user()->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik Singkat</h3>
                </div>
                <div class="card-body">
                    @if(Auth::user()->isAdmin())
                    <small class="text-muted">Sebagai Administrator</small>
                    <p class="mt-2">Anda memiliki akses penuh untuk mengelola seluruh sistem rental mobil.</p>
                    @else
                    <small class="text-muted">Sebagai Karyawan</small>
                    <p class="mt-2">Anda dapat mengelola data mobil, pelanggan, dan penyewaan.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Update Profile Form -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Profile</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telepon">Nomor Telepon</label>
                                    <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                                           id="telepon" name="telepon" value="{{ old('telepon', $user->telepon) }}">
                                    @error('telepon')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <input type="text" class="form-control bg-light" value="{{ $user->isAdmin() ? 'Administrator' : 'Karyawan' }}" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ubah Password</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="current_password">Password Saat Ini *</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                           id="current_password" name="current_password" required>
                                    @error('current_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
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
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Activity Log (Optional) -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aktivitas Terakhir</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @php
                            $activities = [
                                ['time' => 'Hari ini', 'activity' => 'Login ke sistem', 'icon' => 'fas fa-sign-in-alt bg-success'],
                                ['time' => 'Kemarin', 'activity' => 'Mengupdate data pelanggan', 'icon' => 'fas fa-user-edit bg-info'],
                                ['time' => '2 hari lalu', 'activity' => 'Memproses penyewaan mobil', 'icon' => 'fas fa-car bg-primary'],
                                ['time' => '1 minggu lalu', 'activity' => 'Mengubah password', 'icon' => 'fas fa-key bg-warning'],
                            ];
                        @endphp
                        
                        @foreach($activities as $activity)
                        <div class="time-label">
                            <span class="bg-secondary">{{ $activity['time'] }}</span>
                        </div>
                        <div>
                            <i class="{{ $activity['icon'] }}"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> 12:05</span>
                                <h3 class="timeline-header no-border">{{ $activity['activity'] }}</h3>
                            </div>
                        </div>
                        @endforeach
                        
                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.profile-photo-container {
    position: relative;
    display: inline-block;
}

.profile-placeholder {
    font-weight: bold;
}

.profile-user-img {
    border: 3px solid #dee2e6;
    transition: all 0.3s ease;
}

.profile-user-img:hover {
    border-color: #007bff;
    transform: scale(1.05);
}

.timeline {
    position: relative;
    margin: 0 0 45px;
    padding: 0;
    list-style: none;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 40px;
    width: 4px;
    background: #ddd;
    margin-left: -2px;
}

.timeline > div {
    position: relative;
    margin-bottom: 20px;
}

.timeline > div::before,
.timeline > div::after {
    content: " ";
    display: table;
}

.timeline > div::after {
    clear: both;
}

.time-label span {
    font-size: 12px;
    padding: 5px 10px;
    color: #fff;
    border-radius: 15px;
}

.timeline-item {
    margin-left: 60px;
    margin-right: 15px;
    margin-top: 0;
    padding: 10px 15px;
    background: #f8f9fa;
    border-radius: 5px;
    border: 1px solid #dee2e6;
}

.timeline-header {
    margin: 0;
    color: #495057;
    border-bottom: none;
    font-size: 14px;
}

.timeline-body {
    padding: 10px;
}

.timeline > div > i {
    width: 40px;
    height: 40px;
    font-size: 16px;
    line-height: 40px;
    position: absolute;
    color: #fff;
    background: #6c757d;
    border-radius: 50%;
    text-align: center;
    left: 0;
    top: 0;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Format input telepon
    $('#telepon').on('input', function() {
        this.value = this.value.replace(/[^0-9+]/g, '');
    });

    // Preview photo before upload
    $('#photo').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
        
        // Show preview
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.profile-user-img').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Password strength indicator
    $('#password').on('keyup', function() {
        var password = $(this).val();
        var strength = 0;
        
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/\d/)) strength++;
        if (password.match(/[^a-zA-Z\d]/)) strength++;
        
        var strengthText = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];
        var strengthClass = ['danger', 'warning', 'info', 'primary', 'success'];
        
        $('#password-strength').remove();
        $(this).after('<div id="password-strength" class="mt-1"><small>Kekuatan password: <span class="text-' + strengthClass[strength] + '">' + strengthText[strength] + '</span></small></div>');
    });
});
</script>
@endpush