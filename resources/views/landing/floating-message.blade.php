{{-- resources/views/landing/floating-message.blade.php --}}
<div id="floating-dev-btn" onclick="document.getElementById('dev-modal').classList.add('show')"
     style="
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 9999;
        background: #1e40af;
        color: #fff;
        padding: 10px 18px;
        border-radius: 999px;
        font-size: 13px;
        font-family: sans-serif;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(30,64,175,0.35);
        display: flex;
        align-items: center;
        gap: 8px;
        user-select: none;
     ">
    <span style="font-size:16px;">🚧</span>
    <span>Dalam Pengembangan</span>
</div>

<div id="dev-modal"
     onclick="if(event.target===this)this.classList.remove('show')"
     style="
        display: none;
        position: fixed;
        inset: 0;
        z-index: 10000;
        background: rgba(0,0,0,0.35);
        align-items: center;
        justify-content: center;
        font-family: sans-serif;
     ">
    <div style="
        background: #fff;
        border-radius: 16px;
        padding: 36px 32px;
        max-width: 380px;
        width: 90%;
        text-align: center;
        box-shadow: 0 8px 40px rgba(0,0,0,0.15);
        position: relative;
    ">
        <div style="font-size: 48px; margin-bottom: 12px;">🚧</div>
        <h2 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px;">
            Sedang Dalam Pengembangan
        </h2>
        <p style="font-size: 13px; color: #6b7280; line-height: 1.6; margin-bottom: 6px;">
            Halaman ini masih dalam proses pengembangan oleh
        </p>
        <p style="font-size: 14px; font-weight: 600; color: #1e40af; margin-bottom: 20px;">
            Surya Pratama
        </p>
        <p style="font-size: 12px; color: #9ca3af; margin-bottom: 24px;">
            Kami sedang bekerja keras untuk menghadirkan fitur terbaik. Terima kasih atas kesabarannya!
        </p>
        <button onclick="document.getElementById('dev-modal').classList.remove('show')"
                style="
                    background: #1e40af;
                    color: #fff;
                    border: none;
                    padding: 10px 28px;
                    border-radius: 999px;
                    font-size: 13px;
                    font-weight: 600;
                    cursor: pointer;
                ">
            Tutup
        </button>
    </div>
</div>

<style>
    #dev-modal.show { display: flex !important; }
    #floating-dev-btn:hover { background: #1d3fa5; transform: scale(1.03); transition: all .15s; }
</style>