{{--
    VISTA: resources/views/notificaciones/index.blade.php
    Vista completa de notificaciones del usuario autenticado.
    - Vendedor: ve sus alertas de límite de fecha_fin
    - Gerente/Admin: ve alertas de vendedores
--}}
@extends('layouts.app')

@section('title', 'Notificaciones')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-bell-fill"></i> Notificaciones
        @if($totalNoLeidas > 0)
            <span class="badge bg-danger ms-2" style="font-size:.65rem;vertical-align:middle;">
                {{ $totalNoLeidas }} nueva{{ $totalNoLeidas > 1 ? 's' : '' }}
            </span>
        @endif
    </h2>
    <div class="d-flex gap-2">
        @if($totalNoLeidas > 0)
        <button class="btn btn-outline-primary btn-sm" id="btn-leer-todas">
            <i class="bi bi-check2-all"></i> Marcar todas leídas
        </button>
        @endif
        <button class="btn btn-outline-danger btn-sm" id="btn-limpiar">
            <i class="bi bi-trash3"></i> Limpiar leídas
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
/* ── Variables del sistema ─────────────────────────── */
:root {
    --notif-radius : 12px;
    --notif-shadow : 0 2px 8px rgba(0,0,0,.07);
}

/* ── Filtros ─────────────────────────────────────────── */
.filter-chips { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 1.25rem; }
.filter-chip {
    padding: 4px 14px;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 600;
    cursor: pointer;
    border: 1.5px solid #e2e8f0;
    background: white;
    color: #64748b;
    transition: all .15s;
}
.filter-chip.active, .filter-chip:hover {
    background: #6c5dd3;
    color: white;
    border-color: #6c5dd3;
}

/* ── Tarjeta de notificación ─────────────────────────── */
.notif-card {
    background: white;
    border-radius: var(--notif-radius);
    box-shadow: var(--notif-shadow);
    border: 1px solid #e2e8f0;
    margin-bottom: .75rem;
    overflow: hidden;
    transition: transform .15s, box-shadow .15s;
    position: relative;
}
.notif-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(0,0,0,.1);
}
.notif-card.no-leida {
    border-left: 4px solid #6c5dd3;
    background: #fafbff;
}
.notif-card.leida { opacity: .78; }

.notif-card-body {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 1.25rem;
}

/* Icono de la notificación */
.notif-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1.2rem;
}
.color-warning  { background: #fffbeb; color: #d97706; }
.color-danger   { background: #fff1f2; color: #ef4444; }
.color-primary  { background: #eff6ff; color: #3b82f6; }
.color-success  { background: #f0fdf4; color: #16a34a; }

/* Contenido */
.notif-content { flex: 1; min-width: 0; }
.notif-titulo {
    font-size: .88rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 3px;
}
.notif-mensaje {
    font-size: .8rem;
    color: #475569;
    line-height: 1.45;
    margin-bottom: 6px;
}
.notif-meta {
    font-size: .7rem;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-wrap: wrap;
}
.notif-meta .badge-tipo {
    font-size: .65rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: .04em;
}

/* Badge no leída */
.unread-dot {
    width: 9px;
    height: 9px;
    background: #6c5dd3;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 6px;
}

/* Acciones */
.notif-actions {
    display: flex;
    flex-direction: column;
    gap: .35rem;
    align-items: flex-end;
    flex-shrink: 0;
}
.notif-actions .btn { font-size: .72rem; border-radius: 7px; padding: 3px 9px; }

/* Detalle expandido para tipo gerente */
.notif-detail-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: .65rem 1rem;
    margin-top: .4rem;
    font-size: .78rem;
    color: #374151;
}
.notif-detail-box .detail-row { display: flex; gap: .5rem; margin-bottom: 2px; }
.notif-detail-box .detail-lbl { font-weight: 700; min-width: 80px; color: #6c5dd3; }

/* Empty state */
.empty-notif {
    text-align: center;
    padding: 4rem 2rem;
    color: #94a3b8;
}
.empty-notif i { font-size: 3.5rem; display: block; margin-bottom: 1rem; }
.empty-notif h5 { font-size: 1rem; font-weight: 700; }
.empty-notif p  { font-size: .85rem; }

/* Toast feedback */
#toast-feedback {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    min-width: 220px;
    border-radius: 10px;
    padding: 12px 18px;
    font-size: .82rem;
    font-weight: 600;
    display: none;
    animation: slideUp .3s ease;
    box-shadow: 0 4px 16px rgba(0,0,0,.15);
}
@keyframes slideUp { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }
</style>
@endpush

@section('content')
@php
    $CSRF = csrf_token();
@endphp

{{-- Chips de filtro --}}
<div class="filter-chips">
    <button class="filter-chip active" data-filter="todas">Todas</button>
    <button class="filter-chip" data-filter="no-leida">No leídas</button>
    <button class="filter-chip" data-filter="fecha_fin_limite">Mis alertas de fecha</button>
    <button class="filter-chip" data-filter="fecha_fin_limite_gerente">Alertas de vendedores</button>
</div>

{{-- Lista de notificaciones --}}
<div id="notif-container">
    @forelse($notificaciones as $notif)
    @php
        $data    = $notif->data;
        $leida   = !is_null($notif->read_at);
        $tipo    = $data['tipo']  ?? 'default';
        $color   = $data['color'] ?? 'primary';
        $icono   = $data['icono'] ?? 'bi-bell';
        $esGerente = $tipo === 'fecha_fin_limite_gerente';
    @endphp

    <div class="notif-card {{ $leida ? 'leida' : 'no-leida' }}"
         data-id="{{ $notif->id }}"
         data-tipo="{{ $tipo }}"
         data-leida="{{ $leida ? '1' : '0' }}">
        <div class="notif-card-body">
            {{-- Dot indicador --}}
            @if(!$leida)
                <div class="unread-dot mt-1"></div>
            @else
                <div style="width:9px;flex-shrink:0;"></div>
            @endif

            {{-- Ícono --}}
            <div class="notif-icon-wrap color-{{ $color }}">
                <i class="bi {{ $icono }}"></i>
            </div>

            {{-- Contenido --}}
            <div class="notif-content">
                <div class="notif-titulo">{{ $data['titulo'] ?? 'Notificación' }}</div>
                <div class="notif-mensaje">{{ $data['mensaje'] ?? '' }}</div>

                {{-- Detalle ampliado para notificaciones de Gerente --}}
                @if($esGerente)
                <div class="notif-detail-box">
                    <div class="detail-row">
                        <span class="detail-lbl">Vendedor:</span>
                        <span>{{ $data['vendedor'] ?? '—' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-lbl">Código:</span>
                        <span>{{ $data['vendedor_cod'] ?? '—' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-lbl">DNI:</span>
                        <span>{{ $data['vendedor_dni'] ?? '—' }}</span>
                    </div>
                    @if(!empty($data['vendedor_tel']))
                    <div class="detail-row">
                        <span class="detail-lbl">Teléfono:</span>
                        <span>{{ $data['vendedor_tel'] }}</span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span class="detail-lbl">Proforma:</span>
                        <a href="{{ route('proformas.show', $data['proforma_id']) }}"
                           style="color:#6c5dd3;font-weight:700;text-decoration:none;">
                            {{ $data['proforma_nro'] ?? '—' }} <i class="bi bi-box-arrow-up-right" style="font-size:.65rem;"></i>
                        </a>
                    </div>
                    <div class="detail-row">
                        <span class="detail-lbl">Modificaciones:</span>
                        <span class="text-danger fw-bold">{{ $data['count'] ?? '—' }} veces</span>
                    </div>
                </div>
                @elseif(!empty($data['proforma_id']))
                    {{-- Enlace simple para notificación de vendedor --}}
                    <div class="mt-1">
                        <a href="{{ route('proformas.show', $data['proforma_id']) }}"
                           style="font-size:.75rem;color:#6c5dd3;font-weight:600;text-decoration:none;">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            Ver proforma {{ $data['proforma_nro'] ?? '' }}
                        </a>
                    </div>
                @endif

                {{-- Meta --}}
                <div class="notif-meta mt-2">
                    <span>{{ $notif->created_at->diffForHumans() }}</span>
                    <span>{{ $notif->created_at->format('d/m/Y H:i') }}</span>
                    @if($leida)
                        <span class="badge-tipo" style="background:#f1f5f9;color:#64748b;">Leída</span>
                    @else
                        <span class="badge-tipo" style="background:#ede9fe;color:#6c5dd3;">Nueva</span>
                    @endif
                </div>
            </div>

            {{-- Acciones --}}
            <div class="notif-actions">
                @if(!$leida)
                    <button class="btn btn-outline-primary btn-sm btn-leer"
                            data-id="{{ $notif->id }}"
                            title="Marcar como leída">
                        <i class="bi bi-check2"></i>
                    </button>
                @endif
                <button class="btn btn-outline-danger btn-sm btn-eliminar"
                        data-id="{{ $notif->id }}"
                        title="Eliminar">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-notif">
        <i class="bi bi-bell-slash"></i>
        <h5>Sin notificaciones</h5>
        <p>No tienes notificaciones en este momento.</p>
    </div>
    @endforelse
</div>

{{-- Paginación --}}
@if($notificaciones->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $notificaciones->links() }}
</div>
@endif

{{-- Toast feedback --}}
<div id="toast-feedback"></div>

@endsection

@push('scripts')
<script>
const CSRF    = '{{ csrf_token() }}';
const baseUrl = '{{ url("/notificaciones") }}';

// ── Toast ──────────────────────────────────────────────────────
function toast(msg, ok = true) {
    const el = document.getElementById('toast-feedback');
    el.textContent = msg;
    el.style.background  = ok ? '#d1fae5' : '#fee2e2';
    el.style.color       = ok ? '#065f46' : '#991b1b';
    el.style.display     = 'block';
    setTimeout(() => el.style.display = 'none', 2500);
}

// ── Fetch helper ───────────────────────────────────────────────
function apiFetch(url, method = 'POST') {
    return fetch(url, {
        method,
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    }).then(r => r.json());
}

// ── Marcar una como leída ──────────────────────────────────────
document.addEventListener('click', function (e) {
    const btnLeer = e.target.closest('.btn-leer');
    if (!btnLeer) return;
    const id   = btnLeer.dataset.id;
    const card = document.querySelector(`.notif-card[data-id="${id}"]`);
    apiFetch(`${baseUrl}/${id}/leer`).then(() => {
        if (card) {
            card.classList.remove('no-leida');
            card.classList.add('leida');
            card.dataset.leida = '1';
            card.querySelector('.unread-dot')?.remove();
            btnLeer.remove();
        }
        toast('Notificación marcada como leída');
    });
});

// ── Eliminar una ───────────────────────────────────────────────
document.addEventListener('click', function (e) {
    const btnElim = e.target.closest('.btn-eliminar');
    if (!btnElim) return;
    const id   = btnElim.dataset.id;
    const card = document.querySelector(`.notif-card[data-id="${id}"]`);
    apiFetch(`${baseUrl}/${id}`, 'DELETE').then(() => {
        card?.remove();
        toast('Notificación eliminada');
        checkEmpty();
    });
});

// ── Marcar todas leídas ────────────────────────────────────────
document.getElementById('btn-leer-todas')?.addEventListener('click', function () {
    apiFetch(`${baseUrl}/leer-todas`).then(() => {
        document.querySelectorAll('.notif-card.no-leida').forEach(card => {
            card.classList.remove('no-leida');
            card.classList.add('leida');
            card.dataset.leida = '1';
            card.querySelector('.unread-dot')?.remove();
            card.querySelector('.btn-leer')?.remove();
        });
        toast('Todas las notificaciones marcadas como leídas');
        this.remove(); // ocultar botón
    });
});

// ── Limpiar leídas ─────────────────────────────────────────────
document.getElementById('btn-limpiar')?.addEventListener('click', function () {
    if (!confirm('¿Eliminar todas las notificaciones ya leídas?')) return;
    apiFetch(`${baseUrl}/limpiar`, 'DELETE').then(() => {
        document.querySelectorAll('.notif-card.leida').forEach(c => c.remove());
        toast('Notificaciones leídas eliminadas');
        checkEmpty();
    });
});

// ── Filtros ────────────────────────────────────────────────────
document.querySelectorAll('.filter-chip').forEach(chip => {
    chip.addEventListener('click', function () {
        document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
        this.classList.add('active');

        const f = this.dataset.filter;
        document.querySelectorAll('.notif-card').forEach(card => {
            const mostrar = (
                f === 'todas'     ? true :
                f === 'no-leida'  ? card.dataset.leida === '0' :
                card.dataset.tipo === f
            );
            card.style.display = mostrar ? '' : 'none';
        });
    });
});

// ── Estado vacío ───────────────────────────────────────────────
function checkEmpty() {
    const visible = document.querySelectorAll('.notif-card:not([style*="display: none"])').length;
    const container = document.getElementById('notif-container');
    if (visible === 0 && !container.querySelector('.empty-notif')) {
        container.innerHTML = `
            <div class="empty-notif">
                <i class="bi bi-bell-slash"></i>
                <h5>Sin notificaciones</h5>
                <p>No hay notificaciones que mostrar.</p>
            </div>`;
    }
}
</script>
@endpush
