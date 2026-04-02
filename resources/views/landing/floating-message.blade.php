{{-- ============================================================
     FLOATING MESSAGE BUTTON
     Include di layout utama: @include('components.floating-message')
     ============================================================ --}}

<style>
    /* Floating Button */
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

    /* Pulse ring */
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

    /* Icon flip on open */
    #floatMsgBtn .icon-msg  { transition: opacity 0.15s, transform 0.15s; }
    #floatMsgBtn .icon-close {
        position: absolute;
        opacity: 0; transform: rotate(-90deg) scale(0.6);
        transition: opacity 0.15s, transform 0.15s;
    }
    #floatMsgBtn.open .icon-msg   { opacity: 0; transform: rotate(90deg) scale(0.6); }
    #floatMsgBtn.open .icon-close { opacity: 1; transform: rotate(0deg) scale(1); }

    /* Tooltip label */
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
    #floatMsgBtn:not(.open):hover ~ #floatMsgTooltip,
    body:not(.float-opened) #floatMsgTooltip.peek {
        opacity: 1; transform: translateX(0);
    }

    /* Popup card */
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

    /* Popup header */
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
    .fmp-header-text .fmp-name  { font-weight: 700; font-size: 14px; line-height: 1.2; }
    .fmp-header-text .fmp-role  { font-size: 11px; opacity: 0.8; margin-top: 1px; }
    .fmp-status-dot {
        display: inline-block; width: 7px; height: 7px;
        background: #4ade80; border-radius: 50%;
        margin-right: 4px;
        animation: blink 2s ease-in-out infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

    /* Popup body */
    .fmp-body { padding: 16px; }

    /* Chat bubble */
    .fmp-bubble {
        background: #f1f5f9;
        border-radius: 4px 14px 14px 14px;
        padding: 12px 14px;
        font-size: 13px;
        color: #1e293b;
        line-height: 1.55;
        margin-bottom: 12px;
        position: relative;
    }
    .fmp-bubble strong { color: #1d4ed8; }
    .fmp-bubble .fmp-sig {
        display: block;
        margin-top: 8px;
        font-size: 11px;
        color: #64748b;
        font-style: italic;
    }

    /* Typing dots (decorative) */
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

    /* Dismiss button */
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

    /* Mobile: adjust bottom when sticky bar is present */
    @media (max-width: 1023px) {
        #floatMsgBtn   { bottom: 88px; }
        #floatMsgPopup { bottom: 150px; }
        #floatMsgTooltip { bottom: 96px; }
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
        <button class="fmp-dismiss" onclick="closeFloatMsg()">
            <i class="fas fa-check text-xs"></i> Oke, mengerti!
        </button>
    </div>
</div>

{{-- FLOATING BUTTON --}}
<button id="floatMsgBtn" onclick="toggleFloatMsg()" aria-label="Pesan dari developer" title="Pesan dari developer">
    <span class="icon-msg"><i class="fas fa-comment-dots text-lg"></i></span>
    <span class="icon-close"><i class="fas fa-times text-lg"></i></span>
</button>

<script>
(function () {
    const btn     = document.getElementById('floatMsgBtn');
    const popup   = document.getElementById('floatMsgPopup');
    const typing  = document.getElementById('fmpTyping');
    const bubble  = document.getElementById('fmpBubble');
    const tooltip = document.getElementById('floatMsgTooltip');

    let isOpen   = false;
    let shown    = false; // typing already resolved once

    // Peek tooltip after 3s on first load
    setTimeout(() => {
        if (!isOpen) {
            tooltip.classList.add('peek');
            setTimeout(() => tooltip.classList.remove('peek'), 3000);
        }
    }, 3000);

    window.toggleFloatMsg = function () {
        isOpen ? closeFloatMsg() : openFloatMsg();
    };

    window.openFloatMsg = function () {
        isOpen = true;
        btn.classList.add('open');
        popup.classList.add('open');
        tooltip.style.opacity = '0';

        if (!shown) {
            typing.style.display = 'flex';
            bubble.style.display = 'none';
            setTimeout(() => {
                typing.style.display = 'none';
                bubble.style.display = 'block';
                shown = true;
            }, 1400);
        }
    };

    window.closeFloatMsg = function () {
        isOpen = false;
        btn.classList.remove('open');
        popup.classList.remove('open');
    };

    // Close on outside click
    document.addEventListener('click', function (e) {
        if (isOpen && !popup.contains(e.target) && e.target !== btn && !btn.contains(e.target)) {
            closeFloatMsg();
        }
    });

    // ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen) closeFloatMsg();
    });
})();
</script>