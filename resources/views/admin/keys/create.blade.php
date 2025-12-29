@extends('admin.layout')

@section('title', 'Thêm Game Keys')

@section('content')
<div class="admin-header">
    <h1><i class="fas fa-plus"></i> Thêm Game Keys</h1>
    <a href="{{ route('admin.keys') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="form-container">
    <form action="{{ route('admin.keys.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="game_id">Chọn Game <span style="color: #ff4444;">*</span></label>
            <select name="game_id" id="game_id" class="form-control" required>
                <option value="">-- Chọn game --</option>
                @foreach($games as $game)
                    <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                        {{ $game->name }}
                    </option>
                @endforeach
            </select>
            @error('game_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="keys">Game Keys <span style="color: #ff4444;">*</span></label>
            <div style="color: var(--steam-text); font-size: 13px; margin-bottom: 8px;">
                <i class="fas fa-info-circle"></i> Nhập mỗi key một dòng. Hệ thống sẽ tự động bỏ qua các key trùng.
            </div>
            <textarea name="keys" id="keys" class="form-control" rows="15" required 
                      placeholder="MC-A1B2C3D4E5F6G7H8
MC-I9J8K7L6M5N4O3P2
MC-Q1R2S3T4U5V6W7X8
...">{{ old('keys') }}</textarea>
            @error('keys')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div style="background: #2a3f5f; padding: 15px; border-radius: 5px; border-left: 4px solid var(--steam-blue); margin-bottom: 20px;">
            <div style="color: white; font-weight: 600; margin-bottom: 8px;">
                <i class="fas fa-lightbulb"></i> Mẹo:
            </div>
            <ul style="color: var(--steam-text); font-size: 13px; margin: 0; padding-left: 20px;">
                <li>Bạn có thể copy/paste từ Excel hoặc file text</li>
                <li>Hệ thống tự động loại bỏ dòng trống</li>
                <li>Key code nên ngắn gọn và dễ nhớ (10-20 ký tự)</li>
                <li>Ví dụ format: GAME-XXXX-XXXX-XXXX</li>
            </ul>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu Keys
            </button>
            <a href="{{ route('admin.keys') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>

<script>
// Auto count keys
document.getElementById('keys').addEventListener('input', function() {
    const lines = this.value.split('\n').filter(line => line.trim() !== '');
    const count = lines.length;
    
    // Update helper text
    const helper = document.createElement('div');
    helper.style.color = 'var(--steam-blue)';
    helper.style.fontSize = '13px';
    helper.style.marginTop = '5px';
    helper.textContent = `${count} keys sẽ được thêm`;
    
    // Remove old helper
    const oldHelper = this.parentElement.querySelector('.key-counter');
    if (oldHelper) oldHelper.remove();
    
    // Add new helper
    helper.className = 'key-counter';
    this.parentElement.appendChild(helper);
});
</script>
@endsection
