{{-- ============================================================
     FLOATING MESSAGE BUTTON
     Include SEKALI di landing/layout LUAR loop, sebelum </body>
     JANGAN include di dalam <a> atau <form> apapun!
     ============================================================ --}}

<style>
    #floatMsgBtn {
        position: fixed;
        bottom: 24px;
        right: 20px;
        z-index: 9999;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(37,99,235,0.4);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    #floatMsgBtn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 28px rgba(37,99,235,0.5);
    }
    #floatMsgBtn:active { transform: scale(0.95); }

    #floatMsgBtn::before {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        border: 2px solid rgba(37,99,235,0.35);
        animation: floatPulse 2s ease-out infinite;
    }
    @keyframes floatPulse {
        0%   { transform: scale(1); opacity: 1; }
        100% { transform: scale(1.55); opacity: 0; }
    }

    #floatMsgBtn .icon-msg  { transition: opacity 0.15s, transform 0.15s; }
    #floatMsgBtn .icon-close {
        position: absolute;
        opacity: 0; transform: rotate(-90deg) scale(0.6);
        transition: opacity 0.15s, transform 0.15s;
    }
    #floatMsgBtn.open .icon-msg   { opacity: 0; transform: rotate(90deg) scale(0.6); }
    #floatMsgBtn.open .icon-close { opacity: 1; transform: rotate(0deg) scale(1); }

    #floatMsgTooltip {
        position: fixed;
        bottom: 32px;
        right: 82px;
        z-index: 9998;
        background: #1e293b;
        color: white;
        font-size: 12px;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 8px;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transform: translateX(6px);
        transition: opacity 0.2s, transform 0.2s;
    }
    #floatMsgTooltip::after {
        content: '';
        position: absolute;
        right: -5px; top: 50%; transform: translateY(-50%);
        border: 5px solid transparent;
        border-left-color: #1e293b;
        border-right: 0;
    }
    #floatMsgTooltip.peek { opacity: 1; transform: translateX(0); }

    #floatMsgPopup {
        position: fixed;
        bottom: 86px;
        right: 20px;
        z-index: 9998;
        width: min(320px, calc(100vw - 32px));
        background: white;
        border-radius: 18px;
        box-shadow: 0 12px 48px rgba(0,0,0,0.16), 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        transform-origin: bottom right;
        transform: scale(0.85) translateY(12px);
        opacity: 0;
        pointer-events: none;
        transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), opacity 0.2s ease;
    }
    #floatMsgPopup.open {
        transform: scale(1) translateY(0);
        opacity: 1;
        pointer-events: auto;
    }

    .fmp-header {
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        padding: 16px;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .fmp-avatar {
        width: 40px; height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .fmp-header-text .fmp-name { font-weight: 700; font-size: 14px; line-height: 1.2; }
    .fmp-header-text .fmp-role { font-size: 11px; opacity: 0.8; margin-top: 1px; }
    .fmp-status-dot {
        display: inline-block; width: 7px; height: 7px;
        background: #4ade80; border-radius: 50%;
        margin-right: 4px;
        animation: blink 2s ease-in-out infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

    .fmp-body { padding: 16px; }

    .fmp-bubble {
        background: #f1f5f9;
        border-radius: 4px 14px 14px 14px;
        padding: 12px 14px;
        font-size: 13px;
        color: #1e293b;
        line-height: 1.55;
        margin-bottom: 12px;
    }
    .fmp-bubble strong { color: #1d4ed8; }
    .fmp-bubble .fmp-sig {
        display: block;
        margin-top: 8px;
        font-size: 11px;
        color: #64748b;
        font-style: italic;
    }

    .fmp-typing {
        display: flex; gap: 4px; align-items: center;
        padding: 2px 0 10px;
    }
    .fmp-typing span {
        width: 6px; height: 6px;
        background: #94a3b8; border-radius: 50%;
        animation: typingDot 1.2s ease-in-out infinite;
    }
    .fmp-typing span:nth-child(2) { animation-delay: 0.2s; }
    .fmp-typing span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typingDot {
        0%,60%,100% { transform: translateY(0); opacity: 0.5; }
        30% { transform: translateY(-5px); opacity: 1; }
    }

    .fmp-dismiss {
        width: 100%;
        padding: 9px;
        background: #f8faff;
        border: 1.5px solid #e0e7ff;
        border-radius: 10px;
        color: #2563eb;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s;
        display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .fmp-dismiss:hover { background: #eff6ff; }

    /*
     * MOBILE FIX: geser ke kiri agar tidak tumpuk dengan #backToTop
     * #backToTop ada di bottom:24px right:20px
     * jadi floatMsgBtn pindah ke right:80px (beri gap 8px dari back-to-top)
     */
    @media (max-width: 1023px) {
        #floatMsgBtn     { bottom: 24px; right: 80px; }
        #floatMsgTooltip { bottom: 32px; right: 140px; }
        #floatMsgPopup   { bottom: 86px; right: 16px; }
    }
</style>

{{-- TOOLTIP --}}
<div id="floatMsgTooltip">Pesan dari developer</div>

{{-- POPUP --}}
<div id="floatMsgPopup" role="dialog" aria-modal="true" aria-label="Pesan Developer">
    <div class="fmp-header">
        <div class="fmp-avatar">👨‍💻</div>
        <div class="fmp-header-text">
            <div class="fmp-name">_suryapratama</div>
            <div class="fmp-role">
                <span class="fmp-status-dot"></span>Developer · RumahKos
            </div>
        </div>
    </div>
    <div class="fmp-body">
        <div class="fmp-typing" id="fmpTyping">
            <span></span><span></span><span></span>
        </div>
        <div class="fmp-bubble" id="fmpBubble" style="display:none">
            Halo! 👋 Website ini saat ini <strong>sedang dalam pengembangan</strong> oleh developer.<br><br>
            Jika kamu menemukan bug atau kendala, mohon <strong>bersabar</strong> ya — kami terus bekerja untuk pengalaman yang lebih baik! 🚀
            <span class="fmp-sig">— _suryapratama</span>
        </div>
        <button type="button" class="fmp-dismiss" onclick="closeFloatMsg()">
            <i class="fas fa-check text-xs"></i> Oke, mengerti!
        </button>
    </div>
</div>

{{-- FLOATING BUTTON --}}
{{-- type="button" WAJIB — tanpa ini browser anggap ini submit button --}}
<button type="button" id="floatMsgBtn" aria-label="Pesan dari developer" title="Pesan dari developer">
    <span class="icon-msg"><i class="fas fa-comment-dots text-lg"></i></span>
    <span class="icon-close"><i class="fas fa-times text-lg"></i></span>
</button>

<script>
(function () {
    var btn    = document.getElementById('floatMsgBtn');
    var popup  = document.getElementById('floatMsgPopup');
    var typing = document.getElementById('fmpTyping');
    var bubble = document.getElementById('fmpBubble');
    var tip    = document.getElementById('floatMsgTooltip');
    var isOpen = false;
    var shown  = false;

    // Peek tooltip 3 detik setelah load
    setTimeout(function () {
        if (!isOpen) {
            tip.classList.add('peek');
            setTimeout(function () { tip.classList.remove('peek'); }, 3000);
        }
    }, 3000);

    // Gunakan addEventListener — BUKAN onclick attr — agar bisa stopPropagation
    btn.addEventListener('click', function (e) {
        e.preventDefault();      // cegah default browser
        e.stopPropagation();     // cegah bubble ke <a> parent manapun
        isOpen ? doClose() : doOpen();
    });

    function doOpen() {
        isOpen = true;
        btn.classList.add('open');
        popup.classList.add('open');
        tip.classList.remove('peek');
        tip.style.opacity = '0';

        if (!shown) {
            typing.style.display = 'flex';
            bubble.style.display = 'none';
            setTimeout(function () {
                typing.style.display = 'none';
                bubble.style.display = 'block';
                shown = true;
            }, 1400);
        }
    }

    function doClose() {
        isOpen = false;
        btn.classList.remove('open');
        popup.classList.remove('open');
    }

    // Expose untuk tombol "Oke, mengerti!"
    window.closeFloatMsg = doClose;

    // Klik di luar popup → tutup
    document.addEventListener('click', function (e) {
        if (isOpen && !popup.contains(e.target) && !btn.contains(e.target)) {
            doClose();
        }
    });

    // ESC → tutup
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen) doClose();
    });
})();
</script>