<div class="mb-3">
    <label class="form-label" for="nombre">Nombre</label>
    <input @error('nombre') aria-invalid="true" @enderror class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $categoria?->nombre) }}" maxlength="255" required>
</div>
<div class="form-check mb-4">
    <input type="hidden" name="activo" value="0">
    <input @error('activo') aria-invalid="true" @enderror class="form-check-input" id="activo" name="activo" type="checkbox" value="1" @checked((bool) old('activo', $categoria?->activo ?? true))>
    <label class="form-check-label" for="activo">Mostrar en el catálogo</label>
</div>
<div class="form-actions">
    <button class="btn btn-primary" type="submit">Guardar categoría</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.categorias.index') }}">Cancelar</a>
</div>
