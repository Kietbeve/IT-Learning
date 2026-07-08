    (function () {
        function showToast(message) {
            let toast = document.getElementById('devtool-toast');

            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'devtool-toast';
                toast.style.cssText = `
                    position: fixed;
                    bottom: 24px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: #1f2937;
                    color: #fff;
                    padding: 10px 18px;
                    border-radius: 8px;
                    font-size: 14px;
                    font-family: sans-serif;
                    z-index: 999999;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                `;
                document.body.appendChild(toast);
            }

            toast.textContent = message;
            toast.style.opacity = '1';

            clearTimeout(toast._timeout);
            toast._timeout = setTimeout(() => {
                toast.style.opacity = '0';
            }, 2000);
        }

        function block(e, message = 'Chức năng này đã bị tắt.') {
            e.preventDefault();
            showToast(message);
        }

        // Chặn chuột phải
        document.addEventListener('contextmenu', e => block(e));

        // Chặn phím tắt
        document.addEventListener('keydown', function (e) {
            const key = e.key.toUpperCase();

            const blocked =
                key === 'F12' ||
                (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(key)) ||
                (e.ctrlKey && ['U', 'C', 'V', 'X', 'A', 'S'].includes(key));

            if (blocked) {
                block(e);
            }
        });

        // Chặn copy / cut / paste
        document.addEventListener('copy', e => block(e));
        document.addEventListener('cut', e => block(e));
        document.addEventListener('paste', e => block(e));
    })();