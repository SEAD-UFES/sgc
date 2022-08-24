@csrf
<div class="mb-3">
    <label for="inputLogin1" class="form-label">Login Institucional</label>
    <input name="login" type="text" id="inputLogin1" class="form-control" placeholder="Login Institucional"
        value="{{ isset($institutionalDetail) ? $institutionalDetail->login : old('login') }}" />
    @error('login')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="inputEmail1" class="form-label">Email Institucional</label>
    <input name="email" type="text" id="inputEmail1" class="form-control" placeholder="Email Institucional"
        value="{{ isset($institutionalDetail) ? $institutionalDetail->email : old('email') }}" />
    @error('email')
        <div class="text-danger">> {{ $message }}</div>
    @enderror
</div>
