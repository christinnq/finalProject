// Dashboard-specific logic: show Active Projects and allow quick create

(function() {
  const API = {
    list: 'data/projects_list.php',
    create: 'data/projects_create.php'
  };

  function qs(sel) { return document.querySelector(sel); }

  const countEl = qs('#active-projects-count');
  const recentList = qs('#recent-projects-list');
  const newProjectBtn = qs('#new-project-btn');
  const popup = qs('#projectFormPopup');
  const closePopup = qs('#closePopup');
  const cancelBtn = qs('#cancelBtn');
  const form = qs('#newProjectForm');

  function showPopup() { if (popup) popup.style.display = 'flex'; }
  function hidePopup() { if (popup) popup.style.display = 'none'; }

  async function refreshActiveCount() {
    if (!countEl) return;
    try {
      const res = await fetch(API.list);
      const data = await res.json();
      const projects = Array.isArray(data.projects) ? data.projects : [];
      const active = projects.filter(p => String(p.status) !== 'Done').length;
      countEl.textContent = String(active);
    } catch (e) {
      console.error('Failed to load active projects count', e);
    }
  }

  function statusPill(status) {
    const cls = status === 'Done' ? 'done' : (status === 'In Progress' ? 'in-progress' : 'not-started');
    return `<span class="pill ${cls}">${status}</span>`;
  }

  function progressPercentFor(status) {
    if (status === 'Done') return 100;
    if (status === 'In Progress') return 70;
    return 30;
  }

  function renderRecentItem(p) {
    const pct = progressPercentFor(String(p.status));
    return `
      <div class="recent-item">
        <button class="dots" aria-label="menu">⋮</button>
        <div class="recent-main">
          <div class="recent-title">${p.name}</div>
          <div class="recent-sub">${p.client_name}</div>
          <div class="recent-progress"><div class="bar"><div class="fill" style="width:${pct}%"></div></div><span class="pct">${pct}%</span></div>
        </div>
        <div class="recent-meta">
          ${statusPill(String(p.status))}
          <div class="due">Due: ${p.due_date}</div>
        </div>
      </div>`;
  }

  async function loadRecent() {
    if (!recentList) return;
    try {
      const res = await fetch(API.list);
      const data = await res.json();
      const projects = Array.isArray(data.projects) ? data.projects : [];
      const top = projects.slice(0, 4);
      recentList.innerHTML = top.map(renderRecentItem).join('');
    } catch (e) {
      console.error('Failed to load recent projects', e);
    }
  }

  async function createProject(payload) {
    const res = await fetch(API.create, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    return res.json();
  }

  document.addEventListener('DOMContentLoaded', () => {
    refreshActiveCount();
    loadRecent();

    newProjectBtn && (newProjectBtn.onclick = showPopup);
    closePopup && (closePopup.onclick = hidePopup);
    cancelBtn && (cancelBtn.onclick = hidePopup);
    window.addEventListener('click', (e) => { if (e.target === popup) hidePopup(); });

    if (form) {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = Object.fromEntries(new FormData(form).entries());
        try {
          const { project, error } = await createProject(formData);
          if (error) throw new Error(error);
          // Increment the counter only if the new project is considered active
          if (project && String(project.status) !== 'Done' && countEl) {
            const current = parseInt(countEl.textContent || '0', 10) || 0;
            countEl.textContent = String(current + 1);
          }
          // Prepend to recent list for immediate feedback
          if (project && recentList) {
            recentList.insertAdjacentHTML('afterbegin', renderRecentItem(project));
          }
          form.reset();
          hidePopup();
        } catch (err) {
          console.error(err);
          alert('Failed to create project. Ensure the backend is running and DB is configured.');
        }
      });
    }
  });
})();
