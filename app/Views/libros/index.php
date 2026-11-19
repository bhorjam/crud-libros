<?php

$boot = [
    'resumen'    => $resumen,
    'categorias' => $categorias,
    'apiUrl'     => url('api/libros.php'),
    'formUrl'    => url('index.php?action=crear'),
    'editarUrl'  => url('index.php?action=editar'),
    'eliminarUrl' => url('index.php?action=eliminar'),
    'flash'      => $flash,
    'csrfToken'  => App\Core\App::csrfToken(),
];

$bootJson = json_encode($boot, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
?>
<div id="app-root"></div>
<script>window.__BOOT__ = <?= $bootJson ?>;</script>
<script type="text/babel">
const { useState, useEffect, useCallback, useMemo, useRef } = React;

const IC = {
  book:       <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>,
  bookLg:     <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>,
  layers:     <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>,
  check:      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>,
  xCircle:    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>,
  checkSm:    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>,
  xSm:        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>,
  clock:      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>,
  search:     <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>,
  filter:     <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>,
  tag:        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/></svg>,
  xClose:     <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>,
  arrowUp:    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="m5 12 7-7 7 7"/><path d="M12 19V5"/></svg>,
  arrowDown:  <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>,
  updown:     <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg>,
  user:       <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>,
  alert:      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>,
  list:       <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>,
  plus:       <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>,
  pencil:     <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>,
  trash:      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>,
  trashLg:    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>,
  okToast:    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>,
  errToast:   <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>,
  x:          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>,
  emptyBook:  <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" style={{opacity:.3}}><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/><path d="M6 8h2"/><path d="M6 12h2"/></svg>,
};

const FILTROS_INICIALES = { q: '', estado: '', cat: '', orden: 'titulo', dir: 'asc' };

const fmt       = n => `S/ ${Number.parseFloat(n).toFixed(2)}`;
const capFirst  = s => s.charAt(0).toUpperCase() + s.slice(1);
const stockCls  = s => (s === 0 ? 'stock-empty' : s <= 3 ? 'stock-low' : 'stock-ok');
const filtrarActivo = f => Boolean(f.q || f.estado || f.cat);

const BADGE = {
  disponible: { cls: 'badge badge-disponible', ic: IC.checkSm },
  agotado:    { cls: 'badge badge-agotado',    ic: IC.xSm     },
  reservado:  { cls: 'badge badge-reservado',  ic: IC.clock   },
};

function Toast({ toasts, remove }) {
  return (
    <div className="toast-wrap">
      {toasts.map(t => (
        <div key={t.id} className={`toast toast-${t.tipo}`} role="status"
             onClick={() => remove(t.id)} style={{ cursor: 'pointer' }}>
          {t.tipo === 'success' ? IC.okToast : IC.errToast}
          <span>{t.msg}</span>
        </div>
      ))}
    </div>
  );
}

function ModalEliminar({ libro, onCancel, csrfToken, endpoint }) {
  useEffect(() => {
    const onKey = e => { if (e.key === 'Escape') onCancel(); };
    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, [onCancel]);

  return (
    <div className="modal-overlay" onClick={onCancel}>
      <div className="modal-box" role="dialog" aria-modal="true" aria-labelledby="modal-titulo"
           onClick={e => e.stopPropagation()}>
        <div className="modal-icon modal-icon-danger">{IC.trashLg}</div>
        <h3 className="modal-title" id="modal-titulo">¿Eliminar libro?</h3>
        <p className="modal-desc">
          Vas a eliminar <strong>"{libro.titulo}"</strong> de <em>{libro.autor}</em>.
          Esta acción no se puede deshacer.
        </p>
        <div className="modal-actions">
          <button type="button" className="btn btn-ghost" onClick={onCancel}>{IC.x} Cancelar</button>
          <form method="POST" action={`${endpoint}&id=${libro.id}`}>
            <input type="hidden" name="csrf_token" value={csrfToken} />
            <button type="submit" className="btn btn-danger">{IC.trash} Sí, eliminar</button>
          </form>
        </div>
      </div>
    </div>
  );
}

function StatsCards({ resumen }) {
  const cards = [
    { label: 'Total libros', value: resumen.total,       ic: IC.bookLg,  cls: 'stat-blue'   },
    { label: 'Stock total',  value: resumen.total_stock, ic: IC.layers,  cls: 'stat-indigo' },
    { label: 'Disponibles',  value: resumen.disponibles, ic: IC.check,   cls: 'stat-green'  },
    { label: 'Agotados',     value: resumen.agotados,    ic: IC.xCircle, cls: 'stat-red'    },
  ];

  return (
    <div className="grid-stats">
      {cards.map(c => (
        <div key={c.label} className={`stat-card ${c.cls}`}>
          <div className="stat-card-icon">{c.ic}</div>
          <div>
            <div className="stat-card-value">{c.value ?? 0}</div>
            <div className="stat-card-label">{c.label}</div>
          </div>
        </div>
      ))}
    </div>
  );
}

function FilterBar({ filtros, setFiltros, categorias }) {
  const [localQ, setLocalQ] = useState(filtros.q);
  const timer = useRef(null);

  useEffect(() => () => clearTimeout(timer.current), []);

  const handleQ = val => {
    setLocalQ(val);
    clearTimeout(timer.current);
    timer.current = setTimeout(() => setFiltros(f => ({ ...f, q: val })), 350);
  };

  const limpiar = () => {
    clearTimeout(timer.current);
    setLocalQ('');
    setFiltros(FILTROS_INICIALES);
  };

  const activo = filtrarActivo(filtros);
  const categoriaNombre = categorias.find(c => String(c.id) === String(filtros.cat))?.nombre;

  return (
    <div className="card" style={{ padding: '1rem 1.25rem' }}>
      <div style={{ display: 'flex', flexWrap: 'wrap', gap: '.75rem', alignItems: 'flex-end' }}>
        <div style={{ flex: 1, minWidth: '200px' }}>
          <label className="form-label" htmlFor="buscar" style={{ fontSize: '.75rem' }}>{IC.search} Buscar</label>
          <div style={{ position: 'relative' }}>
            <span style={{ position: 'absolute', left: '.8rem', top: '50%', transform: 'translateY(-50%)', color: '#94a3b8', display: 'flex', pointerEvents: 'none' }}>{IC.search}</span>
            <input id="buscar" type="text" className="form-control" style={{ paddingLeft: '2.4rem' }}
                   placeholder="Título, autor, editorial…" value={localQ}
                   onChange={e => handleQ(e.target.value)} />
          </div>
        </div>
        <div>
          <label className="form-label" htmlFor="estado" style={{ fontSize: '.75rem' }}>{IC.filter} Estado</label>
          <select id="estado" className="form-control" style={{ minWidth: '130px' }} value={filtros.estado}
                  onChange={e => setFiltros(f => ({ ...f, estado: e.target.value }))}>
            <option value="">Todos</option>
            <option value="disponible">Disponible</option>
            <option value="agotado">Agotado</option>
            <option value="reservado">Reservado</option>
          </select>
        </div>
        <div>
          <label className="form-label" htmlFor="cat" style={{ fontSize: '.75rem' }}>{IC.tag} Categoría</label>
          <select id="cat" className="form-control" style={{ minWidth: '160px' }} value={filtros.cat}
                  onChange={e => setFiltros(f => ({ ...f, cat: e.target.value }))}>
            <option value="">Todas</option>
            {categorias.map(c => <option key={c.id} value={c.id}>{c.nombre}</option>)}
          </select>
        </div>
        {activo && (
          <button type="button" className="btn btn-ghost btn-sm" onClick={limpiar}>
            {IC.xClose} Limpiar
          </button>
        )}
      </div>
      {activo && (
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: '.4rem', marginTop: '.75rem' }}>
          {filtros.q      && <span className="filter-chip">{IC.search} {filtros.q}</span>}
          {filtros.estado && <span className="filter-chip">{IC.filter} {filtros.estado}</span>}
          {filtros.cat    && <span className="filter-chip">{IC.tag} {categoriaNombre}</span>}
        </div>
      )}
    </div>
  );
}

function SortHeader({ col, label, activo, dir, onSort, align }) {
  const clase = `th-sort${activo ? ' active' : ''}`;
  const estilo = align ? { display: 'flex', justifyContent: align === 'right' ? 'flex-end' : 'center' } : undefined;

  return (
    <th className={align === 'right' ? 'th-right' : align === 'center' ? 'th-center' : ''}>
      <button type="button" className={clase} style={estilo} onClick={() => onSort(col)}>
        {label} <span className="sort-icon">{activo ? (dir === 'asc' ? IC.arrowUp : IC.arrowDown) : IC.updown}</span>
      </button>
    </th>
  );
}

function TablaLibros({ libros, loading, filtros, onOrdenar, onEliminar, editarUrl }) {
  if (loading) {
    return (
      <div style={{ textAlign: 'center', padding: '4rem', color: '#94a3b8' }}>
        <div className="spinner" style={{ margin: '0 auto .75rem' }}></div>
        <p style={{ fontSize: '.875rem' }}>Cargando catálogo…</p>
      </div>
    );
  }

  if (!libros.length) {
    return (
      <div className="empty-state">
        <div style={{ display: 'flex', justifyContent: 'center', marginBottom: '1rem' }}>{IC.emptyBook}</div>
        <p>No se encontraron libros con esos criterios.</p>
      </div>
    );
  }

  const columnas = [
    { col: 'titulo', label: 'Título' },
    { col: 'autor',  label: 'Autor' },
    { col: 'precio', label: 'Precio (S/.)', align: 'right' },
    { col: 'stock',  label: 'Stock', align: 'right' },
    { col: 'anio',   label: 'Año', align: 'center' },
  ];

  return (
    <div className="table-wrap">
      <table>
        <thead>
          <tr>
            {columnas.map(c => (
              <SortHeader key={c.col} {...c} activo={filtros.orden === c.col} dir={filtros.dir} onSort={onOrdenar} />
            ))}
            <th>Categoría</th>
            <th className="th-center">Estado</th>
            <th className="th-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          {libros.map(libro => {
            const badge = BADGE[libro.estado] ?? BADGE.disponible;
            const alerta = libro.stock === 0 || libro.stock <= 3;

            return (
              <tr key={libro.id}>
                <td>
                  <div style={{ display: 'flex', alignItems: 'center', gap: '.65rem' }}>
                    <div style={{ width: '2rem', height: '2rem', borderRadius: '.5rem', flexShrink: 0, background: 'linear-gradient(135deg,#eff6ff,#dbeafe)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#2563eb' }}>
                      {IC.book}
                    </div>
                    <span style={{ fontWeight: 600, color: '#1e293b' }}>{libro.titulo}</span>
                  </div>
                </td>
                <td style={{ color: '#475569' }}>
                  <span style={{ display: 'flex', alignItems: 'center', gap: '.35rem' }}>
                    <span style={{ color: '#cbd5e1' }}>{IC.user}</span>{libro.autor}
                  </span>
                </td>
                <td style={{ textAlign: 'right', fontWeight: 700, color: '#1e293b' }}>{fmt(libro.precio)}</td>
                <td style={{ textAlign: 'right' }}>
                  <span className={stockCls(Number(libro.stock))} style={{ display: 'inline-flex', alignItems: 'center', gap: '.3rem' }}>
                    {libro.stock}
                    {alerta && <span style={{ color: libro.stock === 0 ? '#f87171' : '#fbbf24' }}>{IC.alert}</span>}
                  </span>
                </td>
                <td style={{ textAlign: 'center', color: '#64748b' }}>{libro.anio}</td>
                <td>
                  <span style={{ background: '#eef2ff', color: '#4338ca', borderRadius: '99px', padding: '.15rem .65rem', fontSize: '.72rem', fontWeight: 700 }}>
                    {libro.categoria}
                  </span>
                </td>
                <td style={{ textAlign: 'center' }}>
                  <span className={badge.cls} style={{ display: 'inline-flex', alignItems: 'center', gap: '.3rem' }}>
                    {badge.ic} {capFirst(libro.estado)}
                  </span>
                </td>
                <td>
                  <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '.4rem' }}>
                    <a href={`${editarUrl}&id=${libro.id}`} className="btn-icon btn-icon-edit" title="Editar" aria-label={`Editar ${libro.titulo}`}>
                      {IC.pencil}
                    </a>
                    <button type="button" className="btn-icon btn-icon-delete" title="Eliminar"
                            aria-label={`Eliminar ${libro.titulo}`} onClick={() => onEliminar(libro)}>
                      {IC.trash}
                    </button>
                  </div>
                </td>
              </tr>
            );
          })}
        </tbody>
      </table>
    </div>
  );
}

function App() {
  const { resumen: resumenInicial, categorias, apiUrl, formUrl, editarUrl, eliminarUrl, flash, csrfToken } = window.__BOOT__;

  const [libros,  setLibros]  = useState([]);
  const [resumen, setResumen] = useState(resumenInicial);
  const [loading, setLoading] = useState(true);
  const [toasts,  setToasts]  = useState([]);
  const [modal,   setModal]   = useState(null);
  const [filtros, setFiltros] = useState(FILTROS_INICIALES);

  const removeToast = useCallback(id => setToasts(t => t.filter(x => x.id !== id)), []);

  const toast = useCallback((msg, tipo = 'success') => {
    const id = `${Date.now()}-${Math.random().toString(16).slice(2)}`;
    setToasts(t => [...t, { id, msg, tipo }]);
    setTimeout(() => removeToast(id), 3800);
  }, [removeToast]);

  useEffect(() => {
    if (flash?.msg) toast(flash.msg, flash.tipo);
  }, []);

  const query = useMemo(() => new URLSearchParams({
    q: filtros.q, estado: filtros.estado, cat: filtros.cat, orden: filtros.orden, dir: filtros.dir,
  }).toString(), [filtros]);

  useEffect(() => {
    const controller = new AbortController();

    const cargar = async () => {
      setLoading(true);
      try {
        const respuesta = await fetch(`${apiUrl}?${query}`, { signal: controller.signal });
        if (!respuesta.ok) throw new Error(`HTTP ${respuesta.status}`);
        const datos = await respuesta.json();
        setLibros(datos.libros ?? []);
        setResumen(datos.resumen ?? resumenInicial);
      } catch (error) {
        if (error.name !== 'AbortError') {
          toast('Error al conectar con el servidor.', 'error');
        }
      } finally {
        if (!controller.signal.aborted) setLoading(false);
      }
    };

    cargar();

    return () => controller.abort();
  }, [query]);

  const ordenar = useCallback(col => {
    setFiltros(f => ({
      ...f,
      orden: col,
      dir: f.orden === col && f.dir === 'asc' ? 'desc' : 'asc',
    }));
  }, []);

  return (
    <>
      <Toast toasts={toasts} remove={removeToast} />
      {modal && (
        <ModalEliminar libro={modal} onCancel={() => setModal(null)}
                       csrfToken={csrfToken} endpoint={eliminarUrl} />
      )}
      <div className="page-wrap" style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem' }}>
        <StatsCards resumen={resumen} />
        <FilterBar filtros={filtros} setFiltros={setFiltros} categorias={categorias} />
        <div className="card">
          <div className="card-header">
            <h2 style={{ fontSize: '1rem', fontWeight: 700, display: 'flex', alignItems: 'center', gap: '.5rem' }}>
              {IC.list} Catálogo de libros
              <span style={{ background: '#dbeafe', color: '#1d4ed8', borderRadius: '99px', padding: '.1rem .6rem', fontSize: '.72rem', fontWeight: 800 }}>
                {libros.length}
              </span>
            </h2>
            <a href={formUrl} className="btn btn-add">
              {IC.plus} Nuevo libro
            </a>
          </div>
          <TablaLibros libros={libros} loading={loading} filtros={filtros}
                       onOrdenar={ordenar} onEliminar={setModal} editarUrl={editarUrl} />
        </div>
      </div>
    </>
  );
}

ReactDOM.createRoot(document.getElementById('app-root')).render(<App />);
</script>