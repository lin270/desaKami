<div class="form-grid form-grid-2">
    <x-admin.input label="Nama" name="name" :value="$user->name" :required="true" maxlength="100" />
    <x-admin.input label="Email" name="email" type="email" :value="$user->email" :required="true" maxlength="150" autocomplete="off" />

    <x-admin.input
        label="Password"
        name="password"
        type="password"
        :required="! $user->exists"
        autocomplete="new-password"
        :help="$user->exists ? 'Kosongkan jika tidak ingin mengubah password. Minimal 8 karakter.' : 'Minimal 8 karakter.'"
    />
    <x-admin.input label="Konfirmasi password" name="password_confirmation" type="password" :required="! $user->exists" autocomplete="new-password" />

    <x-admin.select label="Role" name="role" :options="['admin' => 'Admin (akses penuh)', 'editor' => 'Editor (kelola konten sendiri)']" :value="$user->role" :required="true" />
    <x-admin.check label="Akun aktif" name="is_active" :checked="$user->is_active" help="Akun nonaktif tidak dapat login." />
</div>

<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-ghost" href="{{ route('admin.users.index') }}">Batal</a>
</div>
