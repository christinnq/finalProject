
  const API = {
    list: 'data/projects_list.php',
    create: 'data/projects_create.php',
    update: 'data/projects_update.php',
    del: 'data/projects_delete.php'
  };

  const newProjectBtn = document.getElementById('new-project-btn');
  const popup = document.getElementById('projectFormPopup');
  const closePopup = document.getElementById('closePopup');
  const cancelBtn = document.getElementById('cancelBtn');
  const form = document.getElementById('newProjectForm');
  const dashboard = document.getElementById('dashboard-content');
  const searchInput = document.querySelector('.search-bar input');

  newProjectBtn.onclick = () => popup.style.display = 'flex';
  closePopup.onclick = cancelBtn.onclick = () => popup.style.display = 'none';
  window.onclick = (e) => { if (e.target === popup) popup.style.display = 'none'; };

  function statusClass(status) {
    const map = { 'Not Started': 'not-started', 'In Progress': 'in-progress', 'Done': 'done' };
    return map[status] || 'not-started';
  }

  function progressForStatus(status) {
    if (status === 'Done') return 100;
    if (status === 'In Progress') return 50;
    return 0;
  }

  function fmtMoney(value) {
    const n = Number(value || 0);
    return n.toLocaleString(undefined, { style: 'currency', currency: 'USD', maximumFractionDigits: 0 });
  }

  function fmtDate(iso) {
    try { return new Date(iso).toLocaleDateString(undefined, { month: 'short', day: '2-digit', year: 'numeric' }); }
    catch { return iso; }
  }

  function attachCardInteractions(card, project) {
    const statusBtn = card.querySelector('.status-btn');
    const statusDropdown = card.querySelector('.status-dropdown');
    const menuBtn = card.querySelector('.menu-btn');
    const menuDropdown = card.querySelector('.menu-dropdown');
    const progressBar = card.querySelector('.progress');
    const progressText = card.querySelector('.progress-value');

    statusBtn.addEventListener('click', () => {
      statusDropdown.style.display = statusDropdown.style.display === 'flex' ? 'none' : 'flex';
    });

    menuBtn.addEventListener('click', () => {
      menuDropdown.style.display = menuDropdown.style.display === 'flex' ? 'none' : 'flex';
    });

    card.querySelectorAll('.status-option').forEach(opt => {
      opt.addEventListener('click', async () => {
        const newStatus = opt.textContent;
        statusBtn.textContent = newStatus;
        statusBtn.className = 'status-btn ' + opt.classList[1];
        statusDropdown.style.display = 'none';

        const pct = progressForStatus(newStatus);
        progressBar.style.width = pct + '%';
        progressText.textContent = pct + '%';

        try {
          await fetch(API.update, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: project.id, status: newStatus })
          });
        } catch (e) { console.error('Update failed', e); }
      });
    });

    card.querySelector('.menu-option.delete').addEventListener('click', async () => {
      try {
        await fetch(API.del, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: project.id }) });
      } catch (e) { console.error('Delete failed', e); }
      card.style.transition = 'opacity 0.3s ease';
      card.style.opacity = '0';
      setTimeout(() => card.remove(), 300);
    });
  }

  function renderProjectCard(project) {
    const pct = progressForStatus(project.status);
    const card = document.createElement('div');
    card.className = 'project-card';
    card.dataset.projectId = project.id;
    card.innerHTML = `
      <div class="status-area">
        <button class="status-btn ${statusClass(project.status)}">${project.status}</button>
        <button class="menu-btn"><img src="./icons-menu/3Dots.png" alt="menu icon"></button>
        <div class="status-dropdown">
          <div class="status-option not-started">Not Started</div>
          <div class="status-option in-progress">In Progress</div>
          <div class="status-option done">Done</div>
        </div>
        <div class="menu-dropdown">
          <div class="menu-option delete">Delete</div>
        </div>
      </div>
      <h2>${project.name}</h2>
      <p>${project.description ? project.description : 'Project description that fits what we need to do'}</p>
      <div class="project-info">
        <div class="info-item"><img src="./icons-menu/Organization-color.svg" class="info-icon" /> ${project.client_name}</div>
        <div class="info-item"><img src="./icons-menu/calendar-color.svg" class="info-icon" /> ${fmtDate(project.due_date)}</div>
        <div class="info-item"><img src="./icons-menu/dolar-color.svg" class="info-icon" /> ${fmtMoney(project.budget)}</div>
        <div class="info-item"><img src="./icons-menu/clock-color.svg" class="info-icon" /> ${project.hours_spent || 0}h</div>
      </div>
      <div class="progress-header">
        <span>Progress</span><span class="progress-value">${pct}%</span>
      </div>
      <div class="progress-container">
        <div class="progress-bar"><div class="progress" style="width:${pct}%"></div></div>
      </div>
      <div class="card-buttons">
        <button class="btn-details">View Details</button>
        <button class="btn-log">Log Time</button>
      </div>`;
    attachCardInteractions(card, project);
    return card;
  }

  async function loadProjects(query = '') {
    dashboard.querySelectorAll('.project-card').forEach(n => n.remove());
    const url = query ? `${API.list}?q=${encodeURIComponent(query)}` : API.list;
    try {
      const res = await fetch(url);
      const data = await res.json();
      (data.projects || []).forEach(p => dashboard.appendChild(renderProjectCard(p)));
    } catch (e) {
      console.error('Failed to load projects', e);
    }
  }

  searchInput?.addEventListener('input', (e) => loadProjects(e.target.value.trim()));

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = Object.fromEntries(new FormData(form).entries());
    // Backend expects project_name and client_name etc.
    try {
      const res = await fetch(API.create, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
      });
      const { project, error } = await res.json();
      if (error) throw new Error(error);
      if (project) dashboard.prepend(renderProjectCard({
        id: project.id,
        name: project.name,
        description: project.description,
        client_name: project.client_name,
        due_date: project.due_date,
        budget: project.budget,
        status: project.status,
        hours_spent: project.hours_spent
      }));
      form.reset();
      popup.style.display = 'none';
    } catch (err) {
      console.error(err);
      alert('Failed to create project. Ensure the backend is running and DB is configured.');
    }
  });

  // Initial load
  document.addEventListener('DOMContentLoaded', () => loadProjects());
