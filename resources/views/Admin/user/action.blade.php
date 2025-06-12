<div class="text-center">
    <a href="{{ route('user.edit', $row->id) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i>
    </a>
    <button class="btn btn-danger btn-sm delete-btn" data-url="{{ route('user.destroy', $row->id) }}">
        <i class="fas fa-trash-alt"></i>
    </button>
</div>
