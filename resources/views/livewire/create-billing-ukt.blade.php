<div>
    <form wire:submit="save" method="POST">
        <div class="form-group">
            <label>NPM</label>
            <input type="text" class="form-control" wire:model="npm">
            @error('npm')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" class="form-control" wire:model="nama">
            @error('nama')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label>Program Studi</label>
            <select class="form-control" id="prodi" wire:model="prodi" style="width: 100%;">
                <option value="">-- Pilih --</option>
                @foreach ($fakultas as $row)
                    <optgroup label="{{ $row->nama_fakultas }}">
                        @foreach ($row->prodi as $prodi)
                            <option value="{{ $prodi->kd_prodi }}" {{ $prodi == $prodi->kd_prodi ? 'selected' : '' }}>
                                {{ $prodi->kd_prodi . ' - ' . $prodi->nm_prodi }}
                                ({{ $prodi->jenjang }})
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('nama')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label>Angkatan</label>
            <select class="form-control" id="angkatan" wire:model="angkatan" style="width: 100%;">
                <option value="">-- Pilih --</option>
                @for ($tahun = date('Y'); $tahun >= 2015; $tahun--)
                    <option value="{{ $tahun }}" {{ $angkatan == $tahun ? 'selected' : '' }}>
                        {{ $tahun }}</option>
                @endfor
            </select>
            @error('nama')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label>Kategori UKT</label>
            <select class="form-control" wire:model="kategori_ukt" style="width: 100%;">
                <option value="">-- Pilih --</option>
                @for ($kat = 1; $kat <= 8; $kat++)
                    <option value="K{{ $kat }}" {{ $kategori_ukt == 'K' . $kat ? 'selected' : '' }}>
                        K{{ $kat }}
                    </option>
                @endfor
            </select>
            @error('nama')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label>Nominal</label>
            <input type="number" class="form-control" wire:model="nominal" placeholder="0">
            @error('nominal')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary float-right">Simpan</button>
    </form>
</div>
