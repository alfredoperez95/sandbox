<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SALESFORCESUCKS · Consultoría</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0b0f1a;
            --bg-card: #111827;
            --bg-hover: #1a2332;
            --border: #1e293b;
            --text: #e2e8f0;
            --text-muted: #94a3b8;
            --accent: #00d9ff;
            --accent-dim: rgba(0, 217, 255, 0.15);
            --success: #10b981;
            --success-dim: rgba(16, 185, 129, 0.2);
            --warning: #f59e0b;
            --danger: #ef4444;
            --radius: 10px;
            --shadow: 0 4px 24px rgba(0,0,0,0.3);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            font-family: 'Outfit', sans-serif;
            background: var(--bg-dark);
            color: var(--text);
            line-height: 1.5;
        }
        .app {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1.5rem;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--accent), #00ff88);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .nav-tabs {
            display: flex;
            gap: 0.25rem;
        }
        .nav-tabs button {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-muted);
            border-radius: var(--radius);
            cursor: pointer;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .nav-tabs button:hover { background: var(--bg-hover); color: var(--text); }
        .nav-tabs button.active { background: var(--accent-dim); color: var(--accent); border-color: var(--accent); }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary { background: linear-gradient(135deg, #00d9ff, #00b8db); color: #0b0f1a; }
        .btn-secondary { background: var(--bg-hover); color: var(--text); border: 1px solid var(--border); }
        .btn-ghost { background: transparent; color: var(--accent); border: 1px solid var(--accent); }
        .btn-danger { background: rgba(239,68,68,0.2); color: #f87171; border: 1px solid rgba(239,68,68,0.4); }
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .dash-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem;
            box-shadow: var(--shadow);
        }
        .dash-card .label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 0.25rem; }
        .dash-card .value { font-size: 1.5rem; font-weight: 700; color: var(--text); }
        .dash-card .value.highlight { color: var(--accent); }
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            align-items: center;
        }
        .filters select {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            background: var(--bg-card);
            color: var(--text);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 0.9rem;
        }
        .filters select:focus { outline: none; border-color: var(--accent); }
        .section { display: none; }
        .section.active { display: block; }
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid var(--border); }
        th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 600; }
        tr:hover { background: var(--bg-hover); }
        tr:last-child td { border-bottom: none; }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .amount { font-weight: 600; color: var(--success); }
        .actions-cell { white-space: nowrap; }
        .actions-cell button {
            padding: 0.35rem 0.6rem;
            margin-right: 0.25rem;
            border: none;
            background: transparent;
            color: var(--text-muted);
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
        }
        .actions-cell button:hover { background: var(--bg-hover); color: var(--accent); }
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 1rem;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            max-width: 520px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        .modal header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            margin-bottom: 0;
        }
        .modal header h2 { font-size: 1.25rem; font-weight: 600; }
        .modal .body { padding: 1.5rem; }
        .modal .footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
        .form-group { margin-bottom: 1rem; }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid var(--border);
            background: var(--bg-dark);
            color: var(--text);
            border-radius: var(--radius);
            font-family: inherit;
            font-size: 0.95rem;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--accent);
        }
        .form-group textarea { min-height: 80px; resize: vertical; }
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--text-muted);
        }
        .empty-state p { margin-bottom: 1rem; }
        .detail-grid {
            display: grid;
            gap: 1.5rem;
            padding: 1.5rem;
        }
        .detail-row { display: grid; grid-template-columns: 140px 1fr; gap: 0.5rem; align-items: start; }
        .detail-row .label { color: var(--text-muted); font-size: 0.85rem; }
        .detail-row .value { font-weight: 500; }
        .loading { text-align: center; padding: 2rem; color: var(--text-muted); }
        .toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            padding: 0.75rem 1.25rem;
            background: var(--bg-card);
            border: 1px solid var(--success);
            border-radius: var(--radius);
            color: var(--success);
            font-size: 0.9rem;
            box-shadow: var(--shadow);
            z-index: 200;
            transform: translateY(100px);
            opacity: 0;
            transition: transform 0.3s, opacity 0.3s;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.error { border-color: var(--danger); color: #f87171; }
    </style>
</head>
<body>
    <div class="app">
        <header>
            <span class="logo">SALESFORCESUCKS</span>
            <nav class="nav-tabs">
                <button type="button" class="nav-tab active" data-tab="opportunities">Oportunidades</button>
                <button type="button" class="nav-tab" data-tab="companies">Empresas</button>
            </nav>
            <div>
                <button type="button" class="btn btn-primary" id="btnNewCompany">+ Empresa</button>
                <button type="button" class="btn btn-primary" id="btnNewOpportunity">+ Oportunidad</button>
            </div>
        </header>

        <section id="section-opportunities" class="section active">
            <div class="dashboard-cards" id="dashboardCards"></div>
            <div class="filters">
                <select id="filterStage">
                    <option value="">Todas las etapas</option>
                </select>
                <select id="filterCompany">
                    <option value="">Todas las empresas</option>
                </select>
                <select id="filterStatus">
                    <option value="">Todos</option>
                    <option value="open">Abiertas</option>
                    <option value="won">Ganadas</option>
                    <option value="lost">Perdidas</option>
                </select>
            </div>
            <div class="card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Oportunidad</th>
                                <th>Empresa</th>
                                <th>Etapa</th>
                                <th>Valor</th>
                                <th>Cierre previsto</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="opportunitiesTable"></tbody>
                    </table>
                </div>
                <div id="opportunitiesEmpty" class="empty-state" style="display:none;">
                    <p>No hay oportunidades. Crea una empresa y luego una oportunidad.</p>
                    <button type="button" class="btn btn-primary" id="btnNewOpportunity2">+ Nueva oportunidad</button>
                </div>
                <div id="opportunitiesLoading" class="loading" style="display:none;">Cargando…</div>
            </div>
        </section>

        <section id="section-companies" class="section">
            <div class="card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Empresa</th>
                                <th>Sector</th>
                                <th>Tamaño</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="companiesTable"></tbody>
                    </table>
                </div>
                <div id="companiesEmpty" class="empty-state" style="display:none;">
                    <p>No hay empresas. Añade la primera.</p>
                    <button type="button" class="btn btn-primary" id="btnNewCompany2">+ Nueva empresa</button>
                </div>
                <div id="companiesLoading" class="loading" style="display:none;">Cargando…</div>
            </div>
        </section>
    </div>

    <!-- Modal Oportunidad -->
    <div class="modal-overlay" id="modalOpportunity">
        <div class="modal">
            <header><h2 id="modalOpportunityTitle">Nueva oportunidad</h2></header>
            <div class="body">
                <form id="formOpportunity">
                    <input type="hidden" id="oppId" name="id">
                    <div class="form-group">
                        <label>Empresa *</label>
                        <select id="oppCompanyId" name="company_id" required></select>
                    </div>
                    <div class="form-group">
                        <label>Contacto</label>
                        <select id="oppContactId" name="contact_id"></select>
                    </div>
                    <div class="form-group">
                        <label>Título / Proyecto *</label>
                        <input type="text" id="oppTitle" name="title" required placeholder="Ej. Proyecto de transformación digital">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea id="oppDescription" name="description" placeholder="Resumen del alcance..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Valor estimado</label>
                        <input type="number" id="oppValue" name="estimated_value" step="0.01" min="0" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label>Moneda</label>
                        <select id="oppCurrency" name="currency">
                            <option value="EUR">EUR</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Etapa</label>
                        <select id="oppStageId" name="stage_id"></select>
                    </div>
                    <div class="form-group">
                        <label>Probabilidad (%)</label>
                        <input type="number" id="oppProbability" name="probability" min="0" max="100" value="10">
                    </div>
                    <div class="form-group">
                        <label>Fecha cierre prevista</label>
                        <input type="date" id="oppCloseDate" name="expected_close_date" min="">
                    </div>
                    <div class="form-group">
                        <label>Asignado a</label>
                        <select id="oppAssigned" name="assigned_to">
                            <option value="">— Seleccionar —</option>
                            <option value="Alfredo Pérez">Alfredo Pérez</option>
                            <option value="Rafa Calvo">Rafa Calvo</option>
                            <option value="Guillermo Truhan">Guillermo Truhan</option>
                            <option value="Gerard Prats">Gerard Prats</option>
                            <option value="Xavi Tor">Xavi Tor</option>
                            <option value="Alvaro Arbaiza">Alvaro Arbaiza</option>
                        </select>
                    </div>
                </form>
            </div>
            <footer class="footer">
                <button type="button" class="btn btn-secondary" data-close="modalOpportunity">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveOpportunity">Guardar</button>
            </footer>
        </div>
    </div>

    <!-- Modal Empresa -->
    <div class="modal-overlay" id="modalCompany">
        <div class="modal">
            <header><h2 id="modalCompanyTitle">Nueva empresa</h2></header>
            <div class="body">
                <form id="formCompany">
                    <input type="hidden" id="companyId" name="id">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" id="companyName" name="name" required placeholder="Nombre de la empresa">
                    </div>
                    <div class="form-group">
                        <label>Sector</label>
                        <select id="companyIndustry" name="industry">
                            <option value="">— Seleccionar —</option>
                            <option value="Consultoría">Consultoría</option>
                            <option value="Tecnología">Tecnología</option>
                            <option value="Banca y finanzas">Banca y finanzas</option>
                            <option value="Retail y distribución">Retail y distribución</option>
                            <option value="Industria y manufactura">Industria y manufactura</option>
                            <option value="Energía y utilities">Energía y utilities</option>
                            <option value="Salud">Salud</option>
                            <option value="Telecomunicaciones">Telecomunicaciones</option>
                            <option value="Sector público">Sector público</option>
                            <option value="Logística y transporte">Logística y transporte</option>
                            <option value="Seguros">Seguros</option>
                            <option value="Legal">Legal</option>
                            <option value="Marketing y medios">Marketing y medios</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tamaño</label>
                        <select id="companySize" name="size">
                            <option value="">—</option>
                            <option value="startup">Startup</option>
                            <option value="pyme">PYME</option>
                            <option value="mediana">Mediana</option>
                            <option value="gran_empresa">Gran empresa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Web</label>
                        <input type="url" id="companyWebsite" name="website" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label>Notas</label>
                        <textarea id="companyNotes" name="notes" placeholder="Notas internas..."></textarea>
                    </div>
                </form>
            </div>
            <footer class="footer">
                <button type="button" class="btn btn-secondary" data-close="modalCompany">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveCompany">Guardar</button>
            </footer>
        </div>
    </div>

    <!-- Modal Contacto -->
    <div class="modal-overlay" id="modalContact">
        <div class="modal">
            <header><h2 id="modalContactTitle">Nuevo contacto</h2></header>
            <div class="body">
                <form id="formContact">
                    <input type="hidden" id="contactId" name="id">
                    <div class="form-group">
                        <label>Empresa *</label>
                        <select id="contactCompanyId" name="company_id" required></select>
                    </div>
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" id="contactName" name="name" required placeholder="Nombre y apellidos">
                    </div>
                    <div class="form-group">
                        <label>Cargo</label>
                        <input type="text" id="contactRole" name="role" placeholder="Director de Operaciones">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="contactEmail" name="email" placeholder="email@empresa.com">
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="tel" id="contactPhone" name="phone" placeholder="+34 ...">
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" id="contactIsPrimary" name="is_primary"> Contacto principal</label>
                    </div>
                </form>
            </div>
            <footer class="footer">
                <button type="button" class="btn btn-secondary" data-close="modalContact">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveContact">Guardar</button>
            </footer>
        </div>
    </div>

    <!-- Modal Detalle Oportunidad -->
    <div class="modal-overlay" id="modalOpportunityDetail">
        <div class="modal" style="max-width: 560px;">
            <header>
                <h2 id="detailTitle">Detalle</h2>
                <div style="margin-top: 0.5rem;">
                    <span class="badge" id="detailStageBadge">—</span>
                </div>
            </header>
            <div class="body">
                <div class="detail-grid" id="detailContent"></div>
                <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                    <button type="button" class="btn btn-ghost" id="btnEditOpportunity">Editar</button>
                    <button type="button" class="btn btn-danger" id="btnDeleteOpportunity">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        const API = '/api';
        function apiUrl(path, query = {}) {
            const q = new URLSearchParams(query).toString();
            return API + '/' + path + (q ? '?' + q : '');
        }
        async function api(path, options = {}) {
            const url = path.startsWith('http') ? path : API + '/' + path;
            const res = await fetch(url, {
                ...options,
                headers: { 'Content-Type': 'application/json', ...options.headers },
                ...(options.body && typeof options.body === 'object' && !(options.body instanceof FormData)
                    ? { body: JSON.stringify(options.body) } : options.body ? { body: options.body } : {}),
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) throw new Error(data.error || res.statusText);
            return data;
        }
        function showToast(msg, isError = false) {
            const el = document.getElementById('toast');
            el.textContent = msg;
            el.classList.toggle('error', isError);
            el.classList.add('show');
            setTimeout(() => el.classList.remove('show'), 3000);
        }

        let stages = [];
        let companies = [];

        async function loadStages() {
            stages = await api('stages');
            const sel = document.getElementById('oppStageId');
            sel.innerHTML = stages.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
            const filterStage = document.getElementById('filterStage');
            filterStage.innerHTML = '<option value="">Todas las etapas</option>' + stages.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
            return stages;
        }
        async function loadCompanies() {
            companies = await api('companies');
            const oppSel = document.getElementById('oppCompanyId');
            const contactSel = document.getElementById('contactCompanyId');
            const options = companies.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
            oppSel.innerHTML = '<option value="">— Seleccionar —</option>' + options;
            contactSel.innerHTML = '<option value="">— Seleccionar —</option>' + options;
            const filterCompany = document.getElementById('filterCompany');
            filterCompany.innerHTML = '<option value="">Todas las empresas</option>' + options;
            return companies;
        }
        function loadContactsForCompany(companyId, selectId) {
            if (!companyId) {
                document.getElementById(selectId).innerHTML = '<option value="">— Ninguno —</option>';
                return;
            }
            api('contacts?company_id=' + companyId).then(contacts => {
                const sel = document.getElementById(selectId);
                const current = sel.value;
                sel.innerHTML = '<option value="">— Ninguno —</option>' + contacts.map(c => `<option value="${c.id}">${c.name}${c.role ? ' · ' + c.role : ''}</option>`).join('');
                if (current) sel.value = current;
            });
        }
        document.getElementById('oppCompanyId').addEventListener('change', function() {
            loadContactsForCompany(this.value, 'oppContactId');
        });

        // Descripción sugerida según título (solo si está vacía)
        const DESCRIPTION_TEMPLATES = [
            { keywords: ['transformación digital', 'transformacion digital'], text: 'Proyecto de consultoría en transformación digital. Alcance: diagnóstico, roadmap y acompañamiento en la implementación.' },
            { keywords: ['estratégic', 'estrategia'], text: 'Proyecto de consultoría estratégica. Definición de objetivos, análisis y plan de actuación.' },
            { keywords: ['operaciones', 'operativa'], text: 'Proyecto de consultoría en mejora de operaciones. Análisis de procesos y propuesta de optimización.' },
            { keywords: ['digital'], text: 'Proyecto de consultoría digital. Análisis de capacidades actuales y propuesta de evolución.' },
            { keywords: ['proceso', 'procesos'], text: 'Proyecto de consultoría en procesos. Mapeo, análisis y mejora de flujos de trabajo.' },
            { keywords: ['organizativ', 'cambio'], text: 'Proyecto de consultoría organizativa y gestión del cambio. Acompañamiento en la transformación.' },
            { keywords: ['tecnologí', 'tecnología', 'it ', ' sistemas'], text: 'Proyecto de consultoría tecnológica. Análisis de sistemas y propuesta de soluciones.' },
        ];
        const DEFAULT_DESCRIPTION = 'Proyecto de consultoría. Alcance por definir con el cliente.';
        function suggestDescriptionFromTitle() {
            const title = (document.getElementById('oppTitle').value || '').toLowerCase();
            const descEl = document.getElementById('oppDescription');
            if (!title || descEl.value.trim() !== '') return;
            for (const t of DESCRIPTION_TEMPLATES) {
                if (t.keywords.some(kw => title.includes(kw))) {
                    descEl.value = t.text;
                    return;
                }
            }
            descEl.value = DEFAULT_DESCRIPTION;
        }
        document.getElementById('oppTitle').addEventListener('input', suggestDescriptionFromTitle);
        document.getElementById('oppTitle').addEventListener('blur', suggestDescriptionFromTitle);

        // Fecha mínima = hoy para el calendario de cierre
        function setCloseDateMin() {
            document.getElementById('oppCloseDate').min = new Date().toISOString().split('T')[0];
        }

        async function loadDashboard() {
            const d = await api('dashboard');
            const wrap = document.getElementById('dashboardCards');
            wrap.innerHTML = d.by_stage.map(s => `
                <div class="dash-card">
                    <div class="label">${s.name}</div>
                    <div class="value">${s.count}</div>
                    <small style="color:var(--text-muted)">${Number(s.total_value).toLocaleString('es-ES', { style: 'currency', currency: 'EUR' })}</small>
                </div>
            `).join('') + `
                <div class="dash-card">
                    <div class="label">Pipeline total</div>
                    <div class="value highlight">${Number(d.pipeline_value).toLocaleString('es-ES', { style: 'currency', currency: 'EUR' })}</div>
                </div>
            `;
        }
        async function loadOpportunities() {
            const tbody = document.getElementById('opportunitiesTable');
            const empty = document.getElementById('opportunitiesEmpty');
            const loading = document.getElementById('opportunitiesLoading');
            loading.style.display = 'block';
            empty.style.display = 'none';
            tbody.innerHTML = '';
            const stageId = document.getElementById('filterStage').value;
            const companyId = document.getElementById('filterCompany').value;
            const status = document.getElementById('filterStatus').value;
            const query = {};
            if (stageId) query.stage_id = stageId;
            if (companyId) query.company_id = companyId;
            if (status) query.status = status;
            const path = 'opportunities' + (Object.keys(query).length ? '?' + new URLSearchParams(query) : '');
            const list = await api(path);
            loading.style.display = 'none';
            if (!list.length) {
                empty.style.display = 'block';
                return;
            }
            tbody.innerHTML = list.map(o => `
                <tr>
                    <td><a href="#" class="view-opp" data-id="${o.id}" style="color:var(--accent); text-decoration:none;">${escapeHtml(o.title)}</a></td>
                    <td>${escapeHtml(o.company_name || '—')}</td>
                    <td><span class="badge" style="background:${o.stage_color || '#333'}22; color:${o.stage_color || '#fff'}">${escapeHtml(o.stage_name || '—')}</span></td>
                    <td class="amount">${o.estimated_value != null ? Number(o.estimated_value).toLocaleString('es-ES', { style: 'currency', currency: o.currency || 'EUR' }) : '—'}</td>
                    <td>${o.expected_close_date || '—'}</td>
                    <td class="actions-cell">
                        <button type="button" class="edit-opp" data-id="${o.id}">Editar</button>
                    </td>
                </tr>
            `).join('');
            tbody.querySelectorAll('.view-opp').forEach(b => b.addEventListener('click', e => { e.preventDefault(); openDetail(parseInt(b.dataset.id)); }));
            tbody.querySelectorAll('.edit-opp').forEach(b => b.addEventListener('click', () => openOpportunityForm(parseInt(b.dataset.id))));
        }
        async function loadCompaniesTable() {
            const tbody = document.getElementById('companiesTable');
            const empty = document.getElementById('companiesEmpty');
            const loading = document.getElementById('companiesLoading');
            loading.style.display = 'block';
            empty.style.display = 'none';
            tbody.innerHTML = '';
            const list = await api('companies');
            loading.style.display = 'none';
            if (!list.length) {
                empty.style.display = 'block';
                return;
            }
            tbody.innerHTML = list.map(c => `
                <tr>
                    <td>${escapeHtml(c.name)}</td>
                    <td>${escapeHtml(c.industry || '—')}</td>
                    <td>${escapeHtml(c.size || '—')}</td>
                    <td class="actions-cell">
                        <button type="button" class="edit-company" data-id="${c.id}">Editar</button>
                        <button type="button" class="add-contact" data-id="${c.id}">+ Contacto</button>
                    </td>
                </tr>
            `).join('');
            tbody.querySelectorAll('.edit-company').forEach(b => b.addEventListener('click', () => openCompanyForm(parseInt(b.dataset.id))));
            tbody.querySelectorAll('.add-contact').forEach(b => b.addEventListener('click', () => openContactForm(parseInt(b.dataset.id))));
        }
        function escapeHtml(s) { if (s == null) return ''; const div = document.createElement('div'); div.textContent = s; return div.innerHTML; }

        document.querySelectorAll('.nav-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
                tab.classList.add('active');
                document.getElementById('section-' + tab.dataset.tab).classList.add('active');
                if (tab.dataset.tab === 'opportunities') { loadDashboard(); loadOpportunities(); }
                if (tab.dataset.tab === 'companies') loadCompaniesTable();
            });
        });
        document.querySelectorAll('[data-close]').forEach(btn => {
            btn.addEventListener('click', () => document.getElementById(btn.dataset.close).classList.remove('open'));
        });
        document.getElementById('modalOpportunity').addEventListener('click', e => { if (e.target === e.currentTarget) e.currentTarget.classList.remove('open'); });
        document.getElementById('modalCompany').addEventListener('click', e => { if (e.target === e.currentTarget) e.currentTarget.classList.remove('open'); });
        document.getElementById('modalContact').addEventListener('click', e => { if (e.target === e.currentTarget) e.currentTarget.classList.remove('open'); });
        document.getElementById('modalOpportunityDetail').addEventListener('click', e => { if (e.target === e.currentTarget) e.currentTarget.classList.remove('open'); });

        function openOpportunityForm(id) {
            document.getElementById('modalOpportunityTitle').textContent = id ? 'Editar oportunidad' : 'Nueva oportunidad';
            document.getElementById('oppId').value = id || '';
            document.getElementById('formOpportunity').reset();
            document.getElementById('oppId').value = id || '';
            if (id) {
                api('opportunities/' + id).then(o => {
                    document.getElementById('oppCompanyId').value = o.company_id;
                    loadContactsForCompany(o.company_id, 'oppContactId');
                    setTimeout(() => {
                        document.getElementById('oppContactId').value = o.contact_id || '';
                        document.getElementById('oppTitle').value = o.title;
                        document.getElementById('oppDescription').value = o.description || '';
                        document.getElementById('oppValue').value = o.estimated_value ?? '';
                        document.getElementById('oppCurrency').value = o.currency || 'EUR';
                        document.getElementById('oppStageId').value = o.stage_id || 1;
                        document.getElementById('oppProbability').value = o.probability ?? 10;
                        document.getElementById('oppCloseDate').value = o.expected_close_date || '';
                        document.getElementById('oppAssigned').value = o.assigned_to || '';
                    }, 100);
                });
            }
            setCloseDateMin();
            document.getElementById('modalOpportunity').classList.add('open');
        }
        function openCompanyForm(id) {
            document.getElementById('modalCompanyTitle').textContent = id ? 'Editar empresa' : 'Nueva empresa';
            document.getElementById('companyId').value = id || '';
            document.getElementById('formCompany').reset();
            document.getElementById('companyId').value = id || '';
            if (id) {
                api('companies/' + id).then(c => {
                    document.getElementById('companyName').value = c.name;
                    document.getElementById('companyIndustry').value = c.industry || '';
                    document.getElementById('companySize').value = c.size || '';
                    document.getElementById('companyWebsite').value = c.website || '';
                    document.getElementById('companyNotes').value = c.notes || '';
                });
            }
            document.getElementById('modalCompany').classList.add('open');
        }
        function openContactForm(companyId) {
            document.getElementById('modalContactTitle').textContent = 'Nuevo contacto';
            document.getElementById('contactId').value = '';
            document.getElementById('formContact').reset();
            document.getElementById('contactCompanyId').value = companyId || '';
            document.getElementById('modalContact').classList.add('open');
        }
        async function openDetail(id) {
            const o = await api('opportunities/' + id);
            document.getElementById('detailTitle').textContent = o.title;
            document.getElementById('detailStageBadge').textContent = o.stage_name;
            document.getElementById('detailStageBadge').style.background = (o.stage_color || '#333') + '22';
            document.getElementById('detailStageBadge').style.color = o.stage_color || '#fff';
            document.getElementById('detailContent').innerHTML = `
                <div class="detail-row"><span class="label">Empresa</span><span class="value">${escapeHtml(o.company_name)}</span></div>
                <div class="detail-row"><span class="label">Contacto</span><span class="value">${escapeHtml(o.contact_name || '—')} ${o.contact_email ? '(' + escapeHtml(o.contact_email) + ')' : ''}</span></div>
                <div class="detail-row"><span class="label">Descripción</span><span class="value">${escapeHtml(o.description || '—')}</span></div>
                <div class="detail-row"><span class="label">Valor estimado</span><span class="value">${o.estimated_value != null ? Number(o.estimated_value).toLocaleString('es-ES', { style: 'currency', currency: o.currency || 'EUR' }) : '—'}</span></div>
                <div class="detail-row"><span class="label">Probabilidad</span><span class="value">${o.probability ?? '—'}%</span></div>
                <div class="detail-row"><span class="label">Cierre previsto</span><span class="value">${o.expected_close_date || '—'}</span></div>
                <div class="detail-row"><span class="label">Asignado a</span><span class="value">${escapeHtml(o.assigned_to || '—')}</span></div>
            `;
            document.getElementById('btnEditOpportunity').onclick = () => { document.getElementById('modalOpportunityDetail').classList.remove('open'); openOpportunityForm(id); };
            document.getElementById('btnDeleteOpportunity').onclick = () => {
                if (confirm('¿Eliminar esta oportunidad?')) {
                    api('opportunities/' + id, { method: 'DELETE' }).then(() => {
                        document.getElementById('modalOpportunityDetail').classList.remove('open');
                        loadOpportunities(); loadDashboard();
                        showToast('Oportunidad eliminada');
                    }).catch(e => showToast(e.message, true));
                }
            };
            document.getElementById('modalOpportunityDetail').classList.add('open');
        }

        document.getElementById('btnNewOpportunity').addEventListener('click', () => openOpportunityForm());
        document.getElementById('btnNewOpportunity2').addEventListener('click', () => openOpportunityForm());
        document.getElementById('btnNewCompany').addEventListener('click', () => openCompanyForm());
        document.getElementById('btnNewCompany2').addEventListener('click', () => openCompanyForm());
        document.getElementById('saveOpportunity').addEventListener('click', async () => {
            const id = document.getElementById('oppId').value;
            const payload = {
                company_id: document.getElementById('oppCompanyId').value,
                contact_id: document.getElementById('oppContactId').value || null,
                title: document.getElementById('oppTitle').value,
                description: document.getElementById('oppDescription').value,
                estimated_value: document.getElementById('oppValue').value ? parseFloat(document.getElementById('oppValue').value) : null,
                currency: document.getElementById('oppCurrency').value,
                stage_id: document.getElementById('oppStageId').value,
                probability: parseInt(document.getElementById('oppProbability').value, 10),
                expected_close_date: document.getElementById('oppCloseDate').value || null,
                assigned_to: document.getElementById('oppAssigned').value || null,
            };
            try {
                if (id) await api('opportunities/' + id, { method: 'PUT', body: payload });
                else await api('opportunities', { method: 'POST', body: payload });
                document.getElementById('modalOpportunity').classList.remove('open');
                loadOpportunities(); loadDashboard(); loadCompanies();
                showToast(id ? 'Oportunidad actualizada' : 'Oportunidad creada');
            } catch (e) { showToast(e.message, true); }
        });
        document.getElementById('saveCompany').addEventListener('click', async () => {
            const id = document.getElementById('companyId').value;
            const payload = {
                name: document.getElementById('companyName').value,
                industry: document.getElementById('companyIndustry').value,
                size: document.getElementById('companySize').value,
                website: document.getElementById('companyWebsite').value,
                notes: document.getElementById('companyNotes').value,
            };
            try {
                if (id) await api('companies/' + id, { method: 'PUT', body: payload });
                else await api('companies', { method: 'POST', body: payload });
                document.getElementById('modalCompany').classList.remove('open');
                loadCompanies(); loadCompaniesTable(); loadOpportunities();
                showToast(id ? 'Empresa actualizada' : 'Empresa creada');
            } catch (e) { showToast(e.message, true); }
        });
        document.getElementById('saveContact').addEventListener('click', async () => {
            const id = document.getElementById('contactId').value;
            const payload = {
                company_id: document.getElementById('contactCompanyId').value,
                name: document.getElementById('contactName').value,
                role: document.getElementById('contactRole').value,
                email: document.getElementById('contactEmail').value,
                phone: document.getElementById('contactPhone').value,
                is_primary: document.getElementById('contactIsPrimary').checked,
            };
            try {
                if (id) await api('contacts/' + id, { method: 'PUT', body: payload });
                else await api('contacts', { method: 'POST', body: payload });
                document.getElementById('modalContact').classList.remove('open');
                loadCompaniesTable();
                showToast(id ? 'Contacto actualizado' : 'Contacto creado');
            } catch (e) { showToast(e.message, true); }
        });
        document.getElementById('filterStage').addEventListener('change', loadOpportunities);
        document.getElementById('filterCompany').addEventListener('change', loadOpportunities);
        document.getElementById('filterStatus').addEventListener('change', loadOpportunities);

        (async () => {
            await loadStages();
            await loadCompanies();
            await loadDashboard();
            await loadOpportunities();
        })();
    </script>
</body>
</html>
