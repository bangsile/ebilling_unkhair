<div class="card card-primary">
    <div class="card-body">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="#" class="nav-link {{ $currentStep == 1 ? 'active bg-light' : 'disabled' }}">
                    <i class="fa fa-user"></i> Pengguna
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a href="#" class="nav-link {{ $currentStep == 2 ? 'active bg-light' : 'disabled' }}">
                    <i class="fa fa-user-secret"></i> Role Pengguna
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade {{ $currentStep == 1 ? 'show active' : '' }}" role="tabpanel"
                aria-labelledby="username-tab">
                <form wire:submit="check" method="post" class="mt-3">
                    <div class="form-group">
                        <label>Username Login</label>
                        <input type="text" wire:model="username" class="form-control" id="username"
                            placeholder="Username Login">
                        @error('username')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary float-right" wire:loading.attr="disabled"
                        wire:target="check">
                        <span wire:loading.remove wire.target="check">Selanjutnya <i
                                class="fa fa-arrow-circle-right"></i></span>
                        <span wire:loading wire.target="check">Please wait...</span>
                    </button>
                </form>
            </div>

            <div class="tab-pane fade {{ $currentStep == 2 ? 'show active' : '' }}" role="tabpanel"
                aria-labelledby="role-tab">
                <form wire:submit="save" method="post" class="mt-3">
                    <div class="form-group">
                        <label>Nama Pengguna</label>
                        <input type="text" wire:model="name" class="form-control" placeholder="Nama Pengguna">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" wire:model="role_pengguna">
                            <option value="">-- Pilih --</option>
                            @foreach ($roles as $role)
                                <option value='{{ $role->name }}'>{{ $role->name }}</option>
                            @endforeach
                        </select>

                        @error('role_pengguna')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary float-right" wire:loading.attr="disabled"
                        wire:target="save">
                        <span wire:loading.remove wire.target="save"><i class="fa fa-save"></i>
                            Simpan</span>
                        <span wire:loading wire.target="save">Please wait...</span>
                    </button>
                    <button type="button" wire:click="back('1')" class="btn btn-default"><i
                            class="fa fa-arrow-circle-left"></i>
                        Kembali</button>
                </form>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>
