<div>
    <form wire:submit="save" method="POST">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control" wire:model="nama" disabled>
            @error('nama')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label>Nominal</label>
            <input type="number" class="form-control" wire:model="nominal" min="1" placeholder="0">
            @error('nominal')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label>Deskripsi Kegiatan</label>
            <input type="text" class="form-control" wire:model="deskripsi">
            @error('deskripsi')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary float-right">Simpan</button>
    </form>
</div>
