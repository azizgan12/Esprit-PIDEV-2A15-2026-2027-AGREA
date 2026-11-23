/* AGREA — small UI enhancements (no dependencies) */
(function () {
    "use strict";

    var reduceMotion = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    /* Mobile sidebar toggle */
    var toggle = document.querySelector(".sidebar-toggle");
    var backdrop = document.querySelector(".sidebar-backdrop");
    function setSidebar(open) {
        document.body.classList.toggle("sidebar-open", open);
        if (toggle) toggle.setAttribute("aria-expanded", open ? "true" : "false");
    }
    if (toggle) toggle.addEventListener("click", function () {
        setSidebar(!document.body.classList.contains("sidebar-open"));
    });
    if (backdrop) backdrop.addEventListener("click", function () { setSidebar(false); });
    document.addEventListener("keydown", function (e) { if (e.key === "Escape") setSidebar(false); });

    /* Show / hide password buttons */
    var eye = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
    var eyeOff = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';

    document.querySelectorAll('.auth-container input[type="password"]').forEach(function (input) {
        var wrap = document.createElement("div");
        wrap.className = "password-wrap";
        input.parentNode.insertBefore(wrap, input);
        wrap.appendChild(input);

        var btn = document.createElement("button");
        btn.type = "button";
        btn.className = "password-toggle";
        btn.setAttribute("aria-label", "Show password");
        btn.innerHTML = eye;
        btn.addEventListener("click", function () {
            var show = input.type === "password";
            input.type = show ? "text" : "password";
            btn.innerHTML = show ? eyeOff : eye;
            btn.setAttribute("aria-label", show ? "Hide password" : "Show password");
        });
        wrap.appendChild(btn);
    });

    /* Count-up animation for stat numbers */
    document.querySelectorAll(".admin-stat-card h3").forEach(function (el) {
        var target = parseInt(el.textContent.trim(), 10);
        if (isNaN(target) || reduceMotion || target === 0) return;
        var duration = 900;
        var start = null;
        el.textContent = "0";
        function step(ts) {
            if (start === null) start = ts;
            var p = Math.min((ts - start) / duration, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(target * eased);
            if (p < 1) requestAnimationFrame(step);
        }
        setTimeout(function () { requestAnimationFrame(step); }, 250);
    });

    /* Disable the submit button after the first click to prevent double posts */
    document.querySelectorAll(".auth-container form").forEach(function (form) {
        form.addEventListener("submit", function () {
            var btn = form.querySelector('button[type="submit"]');
            if (btn && form.checkValidity()) {
                setTimeout(function () { btn.disabled = true; btn.style.opacity = ".75"; }, 0);
            }
        });
    });
})();
