<form action="/restablecer" method="POST">
    @csrf
    <input type="hidden" name="userId" value="{{ $userId }}">
    <label for="password">Nueva Contraseña:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <label for="password_confirmation">Confirmar Contraseña:</label>
    <input type="password" id="password_confirmation" name="password_confirmation" required>
    <br>
    <button type="submit">Restablecer Contraseña</button>
</form>
