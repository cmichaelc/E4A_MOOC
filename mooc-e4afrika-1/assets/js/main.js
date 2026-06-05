/**
 * E4Afrika MOOC Platform — main.js
 * Interactions UI : validation, progression, quiz, filtres
 */

'use strict';

/* ============================================================
   1. NAVBAR — shrink au scroll
   ============================================================ */
const mainNav = document.getElementById('mainNav');
if (mainNav) {
  window.addEventListener('scroll', () => {
    mainNav.classList.toggle('py-1', window.scrollY > 50);
  }, { passive: true });
}

/* ============================================================
   2. TOGGLE VISIBILITY MOT DE PASSE
   ============================================================ */
function initPasswordToggles() {
  const pairs = [
    ['togglePwd',  'password'],
    ['togglePwd2', 'reg_password'],
  ];
  pairs.forEach(([btnId, inputId]) => {
    const btn   = document.getElementById(btnId);
    const input = document.getElementById(inputId);
    if (!btn || !input) return;
    btn.addEventListener('click', () => {
      const isText = input.type === 'text';
      input.type   = isText ? 'password' : 'text';
      btn.querySelector('i').className = isText ? 'bi bi-eye text-muted' : 'bi bi-eye-slash text-muted';
    });
  });
}

/* ============================================================
   3. INDICATEUR FORCE MOT DE PASSE
   ============================================================ */
function initPasswordStrength() {
  const input = document.getElementById('reg_password');
  const bar   = document.getElementById('pwdStrength');
  const hint  = document.getElementById('pwdHint');
  if (!input || !bar) return;

  input.addEventListener('input', () => {
    const val = input.value;
    let score = 0;
    if (val.length >= 8)             score++;
    if (/[A-Z]/.test(val))           score++;
    if (/[0-9]/.test(val))           score++;
    if (/[^A-Za-z0-9]/.test(val))    score++;

    const levels = [
      { pct:  0,   color: 'danger',  label: 'Trop court' },
      { pct: 25,   color: 'danger',  label: 'Faible' },
      { pct: 50,   color: 'warning', label: 'Moyen' },
      { pct: 75,   color: 'info',    label: 'Bon' },
      { pct: 100,  color: 'success', label: 'Excellent' },
    ];
    const level = levels[score] || levels[0];
    bar.style.width = level.pct + '%';
    bar.className   = `progress-bar bg-${level.color}`;
    if (hint) hint.textContent = level.label;
  });
}

/* ============================================================
   4. VALIDATION FORMULAIRE INSCRIPTION
   ============================================================ */
function initRegisterValidation() {
  const form    = document.getElementById('registerForm');
  const pwd     = document.getElementById('reg_password');
  const confirm = document.getElementById('confirm');
  const errDiv  = document.getElementById('confirmError');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    let valid = true;

    // Vérif mots de passe
    if (pwd && confirm && pwd.value !== confirm.value) {
      confirm.classList.add('is-invalid');
      if (errDiv) errDiv.classList.remove('d-none');
      valid = false;
    } else {
      if (confirm) confirm.classList.remove('is-invalid');
      if (errDiv)  errDiv.classList.add('d-none');
    }

    // Vérif CGV
    const cgv = document.getElementById('cgv');
    if (cgv && !cgv.checked) {
      cgv.classList.add('is-invalid');
      valid = false;
    }

    if (!valid) e.preventDefault();
  });

  // Confirmation en temps réel
  if (confirm) {
    confirm.addEventListener('input', () => {
      if (pwd && confirm.value !== pwd.value) {
        confirm.classList.add('is-invalid');
        if (errDiv) errDiv.classList.remove('d-none');
      } else {
        confirm.classList.remove('is-invalid');
        if (errDiv) errDiv.classList.add('d-none');
      }
    });
  }
}

/* ============================================================
   5. BOUTON LOGIN — spinner
   ============================================================ */
function initLoginSpinner() {
  const form = document.getElementById('loginForm');
  const btn  = document.getElementById('loginBtn');
  if (!form || !btn) return;

  form.addEventListener('submit', () => {
    btn.querySelector('.btn-text')?.classList.add('d-none');
    btn.querySelector('.spinner-border')?.classList.remove('d-none');
    btn.disabled = true;
  });
}

/* ============================================================
   6. BARRE DE PROGRESSION — animation au chargement
   ============================================================ */
function initProgressBars() {
  const bars = document.querySelectorAll('.progress-animated');
  bars.forEach(bar => {
    const target = bar.style.width;
    bar.style.width = '0%';
    requestAnimationFrame(() => {
      setTimeout(() => { bar.style.width = target; }, 100);
    });
  });
}

/* ============================================================
   7. QUIZ — progression et validation
   ============================================================ */
function initQuiz() {
  const form     = document.getElementById('quizForm');
  const progress = document.getElementById('quizProgressBar');
  const current  = document.getElementById('qCurrent');
  const pctLabel = document.getElementById('qPct');
  if (!form) return;

  const questions = form.querySelectorAll('.quiz-question');
  const total     = questions.length;

  // Mise à jour progression quand on répond
  form.querySelectorAll('.quiz-radio').forEach(radio => {
    radio.addEventListener('change', updateQuizProgress);
  });

  function updateQuizProgress() {
    const answered = form.querySelectorAll('.quiz-radio:checked').length;
    const pct      = total > 0 ? Math.round(answered / total * 100) : 0;
    if (progress) progress.style.width = pct + '%';
    if (pctLabel) pctLabel.textContent = pct + '%';
    if (current)  current.textContent  = Math.min(answered + 1, total);
  }

  // Style radio sélectionné
  form.querySelectorAll('.quiz-option-label').forEach(label => {
    label.addEventListener('click', function () {
      // Reset les labels du groupe
      const name   = this.querySelector('input')?.name;
      if (!name) return;
      form.querySelectorAll(`[name="${name}"]`).forEach(r => {
        r.closest('label')?.classList.remove('border-primary', 'bg-primary-soft');
      });
      this.classList.add('border-primary', 'bg-primary-soft');
    });
  });

  // Validation avant soumission
  const submitBtn = document.getElementById('submitQuiz');
  if (submitBtn) {
    form.addEventListener('submit', (e) => {
      const unanswered = total - form.querySelectorAll('.quiz-radio:checked').length;
      if (unanswered > 0) {
        e.preventDefault();
        showToast(`Veuillez répondre à toutes les questions (${unanswered} restante(s)).`, 'warning');
      }
    });
  }
}

/* ============================================================
   8. FILTRE TABLE (admin)
   ============================================================ */
function filterTable(input, tableId) {
  const filter = input.value.toLowerCase();
  const table  = document.getElementById(tableId);
  if (!table) return;
  const rows   = table.querySelectorAll('tbody tr');
  rows.forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
  });
}

/* ============================================================
   9. TOAST NOTIFICATIONS
   ============================================================ */
function showToast(message, type = 'info') {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'position-fixed bottom-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
  }

  const icons = { success: 'check-circle-fill', danger: 'exclamation-triangle-fill', warning: 'exclamation-circle-fill', info: 'info-circle-fill' };
  const toast = document.createElement('div');
  toast.className = `toast align-items-center text-bg-${type} border-0 show`;
  toast.setAttribute('role', 'alert');
  toast.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">
        <i class="bi bi-${icons[type] || 'info-circle-fill'} me-2"></i>${message}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>`;

  container.appendChild(toast);
  setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, 4000);
  toast.querySelector('.btn-close').addEventListener('click', () => toast.remove());
}

/* ============================================================
   10. AUTO-DISMISS ALERTS Bootstrap
   ============================================================ */
function initAlertsDismiss() {
  document.querySelectorAll('.alert:not(.alert-permanent)').forEach(alert => {
    setTimeout(() => {
      const bsAlert = bootstrap.Alert?.getOrCreateInstance?.(alert);
      bsAlert?.close?.();
    }, 5000);
  });
}

/* ============================================================
   11. CONFIRMATION SUPPRESSION
   ============================================================ */
function initDeleteConfirm() {
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', (e) => {
      const msg = el.dataset.confirm || 'Confirmer cette action ?';
      if (!confirm(msg)) e.preventDefault();
    });
  });
}

/* ============================================================
   12. UPLOAD FICHIER — aperçu nom
   ============================================================ */
function initFileInputs() {
  document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function () {
      const file = this.files[0];
      if (!file) return;

      // Vérification taille côté client (5 Mo)
      const maxSize = 5 * 1024 * 1024;
      if (file.size > maxSize) {
        showToast('Fichier trop volumineux ! Taille maximale : 5 Mo.', 'danger');
        this.value = '';
        return;
      }
      showToast(`Fichier sélectionné : ${file.name}`, 'success');
    });
  });
}

/* ============================================================
   13. SMOOTH SCROLL ANCHOR
   ============================================================ */
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
}

/* ============================================================
   14. COMPTEUR ANIMÉ (KPI cards)
   ============================================================ */
function animateCounters() {
  const counters = document.querySelectorAll('.kpi-value');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el      = entry.target;
      const rawText = el.textContent.trim();
      const match   = rawText.match(/^(\d+)/);
      if (!match) return;
      const target  = parseInt(match[1]);
      const suffix  = rawText.replace(match[1], '');
      let current   = 0;
      const step    = Math.max(1, Math.floor(target / 40));
      const timer   = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = current + suffix;
        if (current >= target) clearInterval(timer);
      }, 30);
      observer.unobserve(el);
    });
  }, { threshold: 0.5 });

  counters.forEach(el => observer.observe(el));
}

/* ============================================================
   INIT — DOMContentLoaded
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
  initPasswordToggles();
  initPasswordStrength();
  initRegisterValidation();
  initLoginSpinner();
  initProgressBars();
  initQuiz();
  initAlertsDismiss();
  initDeleteConfirm();
  initFileInputs();
  initSmoothScroll();
  animateCounters();
});

/* Exposer filterTable globalement (appelé inline dans les vues admin) */
window.filterTable = filterTable;
window.showToast   = showToast;
