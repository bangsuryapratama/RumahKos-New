{{-- ════════════════════════════════════
     FLOATING DEV BUTTON
════════════════════════════════════ --}}
<button id="floating-dev-btn"
        onclick="document.getElementById('dev-modal').classList.add('show')"
        aria-label="Buka info pengembangan">
    <i data-lucide="construction" id="fab-icon"></i>
    <span class="fab-label">Dalam Pengembangan</span>
    <span class="fab-dot"></span>
</button>

{{-- ════════════════════════════════════
     DEV MODAL
════════════════════════════════════ --}}
<div id="dev-modal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="dev-modal-title"
     onclick="if(event.target===this)this.classList.remove('show')">

    <div class="dev-modal-box">

        {{-- Close button --}}
        <button class="dev-modal-close"
                onclick="document.getElementById('dev-modal').classList.remove('show')"
                aria-label="Tutup">
            <i data-lucide="x"></i>
        </button>

        {{-- Icon ring --}}
        <div class="dev-modal-icon-ring">
            <i data-lucide="hard-hat"></i>
        </div>

        {{-- Status pill --}}
        <div class="dev-status-pill">
            <span class="dev-status-dot"></span>
            Sedang aktif dikerjakan
        </div>

        <h2 id="dev-modal-title" class="dev-modal-title">Dalam Pengembangan</h2>

        <p class="dev-modal-sub">
            Halaman ini sedang dibangun oleh
        </p>
        <p class="dev-modal-name">Surya Pratama</p>

        <div class="dev-modal-divider"></div>

        <p class="dev-modal-note">
            Kami bekerja keras menghadirkan fitur terbaik.<br>
            Terima kasih atas kesabarannya! 🙏
        </p>

        <button class="dev-modal-btn"
                onclick="document.getElementById('dev-modal').classList.remove('show')">
            <i data-lucide="check" style="width:14px;height:14px;"></i>
            Mengerti, Tutup
        </button>
    </div>
</div>

<style>
/* ══ FAB ══════════════════════════════════════════════════════════ */
#floating-dev-btn {
    position: fixed;
    bottom: 24px;
    right: 20px;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 8px;
    background: #111827;
    color: #fff;
    border: none;
    padding: 11px 18px;
    border-radius: 999px;
    font-size: 13px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 600;
    letter-spacing: 0.01em;
    cursor: pointer;
    box-shadow: 0 4px 24px rgba(0,0,0,.25), 0 1px 4px rgba(0,0,0,.15);
    transition: background .2s, transform .2s, box-shadow .2s;
    animation: fabFloatIn .5s .4s cubic-bezier(.22,.61,.36,1) both;
}
#floating-dev-btn:hover {
    background: #1f2937;
    transform: translateY(-3px);
    box-shadow: 0 8px 32px rgba(0,0,0,.3);
}
#floating-dev-btn:active { transform: scale(.96); }

#fab-icon {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
    transition: transform .3s ease;
}
#floating-dev-btn:hover #fab-icon { transform: rotate(-15deg); }

/* Label hidden on very small screens */
.fab-label { display: inline; }
@media (max-width: 360px) { .fab-label { display: none; } }

/* Pulsing green dot */
.fab-dot {
    width: 7px; height: 7px;
    background: #22c55e;
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 0 0 0 rgba(34,197,94,.5);
    animation: fabPulse 2.2s ease-in-out infinite;
}
@keyframes fabPulse {
    0%   { box-shadow: 0 0 0 0 rgba(34,197,94,.5); }
    60%  { box-shadow: 0 0 0 6px rgba(34,197,94,0); }
    100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
}

@keyframes fabFloatIn {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ══ MODAL BACKDROP ════════════════════════════════════════════════ */
#dev-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 10000;
    background: rgba(0,0,0,.5);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    align-items: center;
    justify-content: center;
    padding: 16px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
#dev-modal.show { display: flex; }

/* ══ MODAL BOX ═════════════════════════════════════════════════════ */
.dev-modal-box {
    background: #fff;
    border-radius: 24px;
    padding: 36px 32px 32px;
    max-width: 380px;
    width: 100%;
    text-align: center;
    position: relative;
    box-shadow: 0 24px 80px rgba(0,0,0,.2), 0 8px 24px rgba(0,0,0,.12);
    animation: modalIn .28s cubic-bezier(.22,.61,.36,1) both;
}
@media (max-width: 480px) {
    .dev-modal-box {
        padding: 32px 24px 28px;
        border-radius: 20px;
    }
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(.92) translateY(16px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

/* ── Close ── */
.dev-modal-close {
    position: absolute;
    top: 14px; right: 14px;
    width: 32px; height: 32px;
    background: #f1f5f9;
    border: none;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: #64748b;
    transition: background .15s, color .15s;
}
.dev-modal-close:hover { background: #e2e8f0; color: #111827; }
.dev-modal-close i { width: 14px; height: 14px; }

/* ── Icon ring ── */
.dev-modal-icon-ring {
    width: 64px; height: 64px;
    background: linear-gradient(135deg, #dbeafe, #eff6ff);
    border: 1.5px solid #bfdbfe;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
}
.dev-modal-icon-ring i {
    width: 28px; height: 28px;
    color: #1d4ed8;
}

/* ── Status pill ── */
.dev-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #15803d;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 999px;
    margin-bottom: 14px;
}
.dev-status-dot {
    width: 6px; height: 6px;
    background: #22c55e;
    border-radius: 50%;
    animation: fabPulse 2.2s ease-in-out infinite;
}

/* ── Text ── */
.dev-modal-title {
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 8px;
    line-height: 1.3;
}
.dev-modal-sub {
    font-size: 13px;
    color: #64748b;
    margin: 0 0 4px;
    line-height: 1.6;
}
.dev-modal-name {
    font-size: 15px;
    font-weight: 700;
    color: #1d4ed8;
    margin: 0 0 20px;
}
.dev-modal-divider {
    height: 1px;
    background: #f1f5f9;
    margin: 0 0 20px;
    border: none;
}
.dev-modal-note {
    font-size: 12px;
    color: #94a3b8;
    line-height: 1.75;
    margin: 0 0 24px;
}

/* ── CTA button ── */
.dev-modal-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    background: #111827;
    color: #fff;
    border: none;
    padding: 13px 24px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    letter-spacing: 0.01em;
    transition: background .15s, transform .1s;
}
.dev-modal-btn:hover { background: #1f2937; }
.dev-modal-btn:active { transform: scale(.98); }
</style>