<?php

$modoEditar = $modoEditar ?? false;
$errores    = $errores ?? [];
$accionForm = $modoEditar
    ? url('index.php?action=actualizar&id=' . (int) $datos['id'])
    : url('index.php?action=guardar');

$estados = [
    'disponible' => 'Disponible',
    'agotado'    => 'Agotado',
    'reservado'  => 'Reservado',
];
$estadoActual = (string) ($datos['estado'] ?? 'disponible');
?>
<main>
<div class="page-wrap-sm">

  <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
    <div style="width:3.5rem;height:3.5rem;border-radius:1rem;flex-shrink:0;background:<?= $modoEditar ? 'linear-gradient(135deg,#f59e0b,#d97706)' : 'linear-gradient(135deg,#2563eb,#7c3aed)' ?>;color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(0,0,0,.18);">
      <?php if ($modoEditar): ?>
      <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
      <?php else: ?>
      <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/><path d="M12 13h4"/><path d="M12 7h4"/><path d="M12 10h1"/></svg>
      <?php endif; ?>
    </div>
    <div>
      <h2 style="font-size:1.5rem;font-weight:800;"><?= $modoEditar ? 'Editar libro' : 'Registrar nuevo libro' ?></h2>
      <p style="font-size:.875rem;color:#64748b;margin-top:.2rem;"><?= $modoEditar ? 'Modifica los datos y guarda los cambios.' : 'Completa los datos para agregar el libro al catálogo.' ?></p>
    </div>
  </div>

  <?php if ($modoEditar && !empty($datos['created_at'])): ?>
  <div style="display:inline-flex;align-items:center;gap:.5rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:99px;padding:.3rem .9rem;font-size:.75rem;color:#64748b;font-family:monospace;margin-bottom:1.5rem;">
    # ID: <?= (int) $datos['id'] ?> &nbsp;|&nbsp; Registrado: <?= e(date('d/m/Y', strtotime((string) $datos['created_at']))) ?>
  </div>
  <?php endif; ?>

  <?php if (!empty($errores['db'])): ?>
  <div class="form-alert" role="alert">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
    <?= e($errores['db']) ?>
  </div>
  <?php endif; ?>

  <form method="POST" action="<?= e($accionForm) ?>" novalidate>
    <input type="hidden" name="csrf_token" value="<?= e(App\Core\App::csrfToken()) ?>">
    <div class="card" style="padding:2rem;display:flex;flex-direction:column;gap:1.5rem;">

      <div class="form-grid-2">
        <div>
          <label class="form-label" for="titulo">Título <span style="color:#ef4444">*</span></label>
          <input id="titulo" type="text" name="titulo" maxlength="<?= App\Models\LibroInput::MAX_TITULO ?>"
                 value="<?= e($datos['titulo'] ?? '') ?>" placeholder="Ej: Los ríos profundos"
                 class="form-control<?= campo_invalido($errores, 'titulo') ?>">
          <?= mensaje_error($errores, 'titulo') ?>
        </div>
        <div>
          <label class="form-label" for="autor">Autor <span style="color:#ef4444">*</span></label>
          <input id="autor" type="text" name="autor" maxlength="<?= App\Models\LibroInput::MAX_AUTOR ?>"
                 value="<?= e($datos['autor'] ?? '') ?>" placeholder="Ej: José María Arguedas"
                 class="form-control<?= campo_invalido($errores, 'autor') ?>">
          <?= mensaje_error($errores, 'autor') ?>
        </div>
      </div>

      <div class="form-grid-2">
        <div>
          <label class="form-label" for="editorial">Editorial <span style="color:#ef4444">*</span></label>
          <input id="editorial" type="text" name="editorial" maxlength="<?= App\Models\LibroInput::MAX_EDITORIAL ?>"
                 value="<?= e($datos['editorial'] ?? '') ?>" placeholder="Ej: Losada, Planeta…"
                 class="form-control<?= campo_invalido($errores, 'editorial') ?>">
          <?= mensaje_error($errores, 'editorial') ?>
        </div>
        <div>
          <label class="form-label" for="categoria_id">Categoría <span style="color:#ef4444">*</span></label>
          <select id="categoria_id" name="categoria_id" class="form-control<?= campo_invalido($errores, 'categoria_id') ?>">
            <option value="">— Selecciona —</option>
            <?php foreach ($categorias as $categoria): ?>
            <option value="<?= (int) $categoria['id'] ?>" <?= (string) ($datos['categoria_id'] ?? '') === (string) $categoria['id'] ? 'selected' : '' ?>>
              <?= e($categoria['nombre']) ?>
            </option>
            <?php endforeach; ?>
          </select>
          <?= mensaje_error($errores, 'categoria_id') ?>
        </div>
      </div>

      <div class="form-grid-3">
        <div>
          <label class="form-label" for="precio">Precio (S/.) <span style="color:#ef4444">*</span></label>
          <div class="form-control-prefix">
            <span class="prefix">S/</span>
            <input id="precio" type="number" name="precio" step="0.01" min="0"
                   max="<?= App\Models\LibroInput::MAX_PRECIO ?>"
                             value="<?= e($datos['precio'] ?? '') ?>" placeholder="0.00"
                             class="form-control<?= campo_invalido($errores, 'precio') ?>">
          </div>
          <?= mensaje_error($errores, 'precio') ?>
        </div>
        <div>
          <label class="form-label" for="stock">Stock <span style="color:#ef4444">*</span></label>
          <input id="stock" type="number" name="stock" min="0" max="<?= App\Models\LibroInput::MAX_STOCK ?>"
                 value="<?= e($datos['stock'] ?? '') ?>" placeholder="0"
                 class="form-control<?= campo_invalido($errores, 'stock') ?>">
          <?= mensaje_error($errores, 'stock') ?>
        </div>
        <div>
          <label class="form-label" for="anio">Año publicación <span style="color:#ef4444">*</span></label>
          <input id="anio" type="number" name="anio"
                 min="<?= App\Models\LibroInput::ANIO_MINIMO ?>" max="<?= (int) date('Y') + 1 ?>"
                 value="<?= e($datos['anio'] ?? date('Y')) ?>"
                 class="form-control<?= campo_invalido($errores, 'anio') ?>">
          <?= mensaje_error($errores, 'anio') ?>
        </div>
      </div>

      <div>
        <span class="form-label" id="estado-label" style="margin-bottom:.5rem;">Estado</span>
        <div style="display:flex;flex-wrap:wrap;gap:.75rem;" role="radiogroup" aria-labelledby="estado-label">
          <?php foreach ($estados as $valor => $etiqueta): ?>
          <label style="cursor:pointer;">
            <input type="radio" name="estado" value="<?= e($valor) ?>" class="estado-radio"
                   <?= $estadoActual === $valor ? 'checked' : '' ?> style="display:none;">
            <span class="estado-pill <?= $estadoActual === $valor ? 'active-' . e($valor) : '' ?>" data-val="<?= e($valor) ?>">
              <?= e($etiqueta) ?>
            </span>
          </label>
          <?php endforeach; ?>
        </div>
        <?= mensaje_error($errores, 'estado') ?>
      </div>

      <div style="display:flex;gap:.75rem;padding-top:.75rem;border-top:1px solid #f1f5f9;flex-wrap:wrap;">
        <button type="submit" class="btn <?= $modoEditar ? 'btn-warning' : 'btn-primary' ?>" style="flex:1;min-width:160px;justify-content:center;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg>
          <?= $modoEditar ? 'Guardar cambios' : 'Registrar libro' ?>
        </button>
        <a href="<?= e(url('index.php')) ?>" class="btn btn-ghost" style="flex:1;min-width:120px;justify-content:center;">Cancelar</a>
      </div>

    </div>
  </form>
</div>
</main>

<script>
document.querySelectorAll('.estado-radio').forEach((radio) => {
  radio.addEventListener('change', () => {
    document.querySelectorAll('.estado-pill').forEach((pill) => {
      const control = document.querySelector(`.estado-radio[value="${pill.dataset.val}"]`);
      pill.className = 'estado-pill' + (control && control.checked ? ' active-' + pill.dataset.val : '');
    });
  });
});
</script>