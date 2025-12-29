@extends('admin.layout')

@section('page-title', 'Quản lý Banners')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2 style="color: white; margin: 0;">Banners</h2>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary" style="padding: 10px 20px;">
        <i class="fas fa-plus"></i> Thêm Banner
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Preview</th>
                <th>Tiêu đề</th>
                <th>Loại</th>
                <th>Thứ tự</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banners as $index => $banner)
                <tr>
                    <td>{{ ($banners->currentPage() - 1) * $banners->perPage() + $index + 1 }}</td>
                    <td>
                        @if($banner->media_type === 'video')
                            <video style="width: 120px; height: 70px; object-fit: cover; border-radius: 3px;" muted>
                                <source src="{{ asset('storage/' . $banner->video_path) }}" type="video/mp4">
                            </video>
                        @else
                            <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" 
                                 style="width: 120px; height: 70px; object-fit: cover; border-radius: 3px;">
                        @endif
                    </td>
                    <td>{{ $banner->title }}</td>
                    <td>
                        @if($banner->media_type === 'video')
                            <span class="badge" style="background: rgba(255, 87, 34, 0.2); color: #ff5722;">
                                <i class="fas fa-video"></i> Video
                            </span>
                        @else
                            <span class="badge" style="background: rgba(103, 193, 245, 0.2); color: #67c1f5;">
                                <i class="fas fa-image"></i> Hình ảnh
                            </span>
                        @endif
                    </td>
                    <td>{{ $banner->order }}</td>
                    <td>
                        <form action="{{ route('admin.banners.toggle', $banner->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @if($banner->is_active)
                                <button type="submit" class="badge badge-success" style="border: none; cursor: pointer;">
                                    <i class="fas fa-toggle-on"></i> Bật
                                </button>
                            @else
                                <button type="submit" class="badge badge-danger" style="border: none; cursor: pointer;">
                                    <i class="fas fa-toggle-off"></i> Tắt
                                </button>
                            @endif
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('admin.banners.delete', $banner->id) }}" method="POST" 
                              style="display: inline-block; margin-left: 5px;" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background: #ff6b6b; color: white; padding: 6px 12px; border: none; border-radius: 3px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div style="margin-top: 30px; color: var(--steam-text); text-align: center;">
    {{ $banners->links() }}
</div>
@endsection
