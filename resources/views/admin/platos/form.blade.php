<div class="mb-3">
    <label class="form-label" for="nombre">Nombre</label>
    <input @error('nombre') aria-invalid="true" @enderror class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $plato?->nombre) }}" maxlength="255" required>
</div>
<div class="mb-3">
    <label class="form-label" for="categoria_id">Categoría</label>
    <select @error('categoria_id') aria-invalid="true" @enderror class="form-select" id="categoria_id" name="categoria_id" required>
        <option value="">Elegir categoría</option>
        @foreach ($categorias as $categoria)
            <option value="{{ $categoria->id }}" @selected((string) old('categoria_id', $plato?->categoria_id) === (string) $categoria->id)>
                {{ $categoria->nombre }}{{ $categoria->activo ? '' : ' (inactiva)' }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label" for="descripcion">Descripción</label>
    <textarea @error('descripcion') aria-invalid="true" @enderror class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $plato?->descripcion) }}</textarea>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label" for="precio">Precio</label>
        <input @error('precio') aria-invalid="true" @enderror class="form-control" id="precio" name="precio" type="number" min="0.01" step="0.01" value="{{ old('precio', $plato?->precio) }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label" for="stock">Stock</label>
        <input @error('stock') aria-invalid="true" @enderror class="form-control" id="stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', $plato?->stock ?? 0) }}" required>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="imagen">Imagen (JPG, PNG o WebP; máximo 2 MB)</label>
    <input @error('imagen') aria-invalid="true" @enderror class="form-control" id="imagen" name="imagen" type="file" accept="image/jpeg,image/png,image/webp">
    @if ($plato?->imagen)
        <img class="mt-3 admin-image-preview" src="{{ asset($plato->imagen) }}" alt="Imagen actual de {{ $plato->nombre }}">
    @endif
</div>
<div class="form-check mb-4">
    <input type="hidden" name="activo" value="0">
    <input @error('activo') aria-invalid="true" @enderror class="form-check-input" id="activo" name="activo" type="checkbox" value="1" @checked((bool) old('activo', $plato?->activo ?? true))>
    <label class="form-check-label" for="activo">Disponible en el catálogo</label>
</div>
<div class="form-actions">
    <button class="btn btn-primary" type="submit">Guardar plato</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.platos.index') }}">Cancelar</a>
</div>
