const API = '/api';
document.getElementById('welcomeEnter').addEventListener('click', function() {
    document.getElementById('welcomeScreen').classList.add('hidden');
});
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
document.getElementById('oppStageId').addEventListener('change', function() {
    const v = parseInt(this.value, 10);
    const probEl = document.getElementById('oppProbability');
    if (v === 5) probEl.value = 100;
    else if (v === 6) probEl.value = 0;
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

function renderCharts(d) {
    const byStage = d.by_stage || [];
    const totalCount = byStage.reduce((sum, s) => sum + Number(s.count), 0);
    const maxValue = Math.max(1, ...byStage.map(s => Number(s.total_value)));
    const chartCount = document.getElementById('chartByCount');
    const chartValue = document.getElementById('chartByValue');
    if (totalCount > 0 && byStage.length > 0) {
        let acc = 0;
        const conicParts = byStage.map(s => {
            const n = Number(s.count);
            const pct = (n / totalCount) * 100;
            const start = acc;
            acc += pct;
            return `${s.color || 'var(--accent)'} ${start}% ${acc}%`;
        }).join(', ');
        chartCount.innerHTML = `
            <div class="chart-donut-wrap">
                <div class="chart-donut" style="background: conic-gradient(${conicParts}); position: relative;">
                    <div style="position: absolute; inset: 25%; border-radius: 50%; background: var(--bg-card); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.5rem;">${totalCount}</div>
                </div>
                <div class="chart-legend">
                    ${byStage.map(s => `<div class="chart-legend-item"><span class="chart-legend-dot" style="background:${s.color || 'var(--accent)'};"></span><span>${escapeHtml(s.name)} (${s.count})</span></div>`).join('')}
                </div>
            </div>`;
    } else {
        chartCount.innerHTML = '<p style="color:var(--text-muted); font-size:0.9rem;">Sin datos</p>';
    }
    chartValue.innerHTML = byStage.map(s => {
        const val = Number(s.total_value);
        const pct = (val / maxValue) * 100;
        return `<div class="chart-row">
            <span class="chart-label">${escapeHtml(s.name)}</span>
            <div class="chart-bar-wrap"><div class="chart-bar" style="width:${pct}%; background:${s.color || 'var(--accent)'};"></div></div>
            <span class="chart-value">${val.toLocaleString('es-ES', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 })}</span>
        </div>`;
    }).join('');
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
            <div class="label" style="margin-top: 0.75rem;">Pipeline pesado</div>
            <div class="value highlight" style="font-size: 0.9rem;">${Number(d.pipeline_weighted || 0).toLocaleString('es-ES', { style: 'currency', currency: 'EUR' })}</div>
        </div>
    `;
    renderCharts(d);
}

document.getElementById('btnToggleCharts').addEventListener('click', function() {
    const block = document.getElementById('chartsBlock');
    const isVisible = block.classList.toggle('visible');
    this.textContent = isVisible ? 'Ocultar gráficos' : 'Ver gráficos por fase';
});
let lastOpportunitiesList = [];
let filteredOpportunitiesList = [];

function applyOpportunitiesFiltersAndRender() {
    const assigned = (document.getElementById('filterAssigned').value || '').trim();
    const search = (document.getElementById('filterSearch').value || '').trim().toLowerCase();
    filteredOpportunitiesList = lastOpportunitiesList.filter(o => {
        if (assigned && (o.assigned_to || '') !== assigned) return false;
        if (search) {
            const title = (o.title || '').toLowerCase();
            const company = (o.company_name || '').toLowerCase();
            if (!title.includes(search) && !company.includes(search)) return false;
        }
        return true;
    });
    renderOpportunitiesTable(filteredOpportunitiesList);
}

function renderOpportunitiesTable(list) {
    const tbody = document.getElementById('opportunitiesTable');
    const empty = document.getElementById('opportunitiesEmpty');
    empty.style.display = 'none';
    if (!list.length) {
        tbody.innerHTML = '';
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
            <td>${escapeHtml(o.assigned_to || '—')}</td>
            <td class="actions-cell">
                <button type="button" class="edit-opp" data-id="${o.id}">Editar</button>
            </td>
        </tr>
    `).join('');
    tbody.querySelectorAll('.view-opp').forEach(b => b.addEventListener('click', e => { e.preventDefault(); openDetail(parseInt(b.dataset.id)); }));
    tbody.querySelectorAll('.edit-opp').forEach(b => b.addEventListener('click', () => openOpportunityForm(parseInt(b.dataset.id))));
}

async function loadOpportunities() {
    const empty = document.getElementById('opportunitiesEmpty');
    const loading = document.getElementById('opportunitiesLoading');
    loading.style.display = 'block';
    empty.style.display = 'none';
    document.getElementById('opportunitiesTable').innerHTML = '';
    const stageId = document.getElementById('filterStage').value;
    const companyId = document.getElementById('filterCompany').value;
    const status = document.getElementById('filterStatus').value;
    const query = {};
    if (stageId) query.stage_id = stageId;
    if (companyId) query.company_id = companyId;
    if (status) query.status = status;
    const path = 'opportunities' + (Object.keys(query).length ? '?' + new URLSearchParams(query) : '');
    lastOpportunitiesList = await api(path);
    loading.style.display = 'none';
    applyOpportunitiesFiltersAndRender();
}
let lastCompaniesList = [];
let filteredCompaniesList = [];

function applyCompaniesFiltersAndRender() {
    const sector = (document.getElementById('filterCompanySector').value || '').trim();
    const size = (document.getElementById('filterCompanySize').value || '').trim();
    const search = (document.getElementById('filterCompanySearch').value || '').trim().toLowerCase();
    filteredCompaniesList = lastCompaniesList.filter(c => {
        if (sector && (c.industry || '') !== sector) return false;
        if (size && (c.size || '') !== size) return false;
        if (search && !(c.name || '').toLowerCase().includes(search)) return false;
        return true;
    });
    renderCompaniesTable(filteredCompaniesList);
}

function renderCompaniesTable(list) {
    const tbody = document.getElementById('companiesTable');
    const empty = document.getElementById('companiesEmpty');
    empty.style.display = 'none';
    if (!list.length) {
        tbody.innerHTML = '';
        empty.style.display = 'block';
        return;
    }
    const sizeLabels = { startup: 'Startup', pyme: 'PYME', mediana: 'Mediana', gran_empresa: 'Gran empresa' };
    tbody.innerHTML = list.map(c => `
        <tr>
            <td><a href="#" class="view-company" data-id="${c.id}" style="color:var(--accent); text-decoration:none; font-weight:500;">${escapeHtml(c.name)}</a></td>
            <td>${escapeHtml(c.industry || '—')}</td>
            <td>${escapeHtml(sizeLabels[c.size] || c.size || '—')}</td>
            <td class="actions-cell">
                <button type="button" class="view-company-btn" data-id="${c.id}">Ver</button>
                <button type="button" class="edit-company" data-id="${c.id}">Editar</button>
                <button type="button" class="add-contact" data-id="${c.id}">+ Contacto</button>
            </td>
        </tr>
    `).join('');
    tbody.querySelectorAll('.view-company, .view-company-btn').forEach(b => b.addEventListener('click', e => { e.preventDefault(); openCompanyDetail(parseInt(b.dataset.id)); }));
    tbody.querySelectorAll('.edit-company').forEach(b => b.addEventListener('click', () => openCompanyForm(parseInt(b.dataset.id))));
    tbody.querySelectorAll('.add-contact').forEach(b => b.addEventListener('click', () => openContactForm(parseInt(b.dataset.id))));
}

async function loadCompaniesTable() {
    const empty = document.getElementById('companiesEmpty');
    const loading = document.getElementById('companiesLoading');
    loading.style.display = 'block';
    empty.style.display = 'none';
    document.getElementById('companiesTable').innerHTML = '';
    lastCompaniesList = await api('companies');
    loading.style.display = 'none';
    applyCompaniesFiltersAndRender();
}

document.getElementById('filterCompanySector').addEventListener('change', applyCompaniesFiltersAndRender);
document.getElementById('filterCompanySize').addEventListener('change', applyCompaniesFiltersAndRender);
document.getElementById('filterCompanySearch').addEventListener('input', applyCompaniesFiltersAndRender);
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
document.getElementById('modalCompanyDetail').addEventListener('click', e => { if (e.target === e.currentTarget) e.currentTarget.classList.remove('open'); });

async function openCompanyDetail(id) {
    const [company, opps] = await Promise.all([
        api('companies/' + id),
        api('opportunities?company_id=' + id)
    ]);
    document.getElementById('companyDetailTitle').textContent = company.name;
    const sizeLabels = { startup: 'Startup', pyme: 'PYME', mediana: 'Mediana', gran_empresa: 'Gran empresa' };
    document.getElementById('companyDetailInfo').innerHTML = `
        <h4>Datos de la empresa</h4>
        <div class="company-detail-grid">
            <span class="k">Sector</span><span>${escapeHtml(company.industry || '—')}</span>
            <span class="k">Tamaño</span><span>${escapeHtml(sizeLabels[company.size] || company.size || '—')}</span>
            <span class="k">Web</span><span>${company.website ? '<a href="' + escapeHtml(company.website) + '" target="_blank" rel="noopener" style="color:var(--accent);">' + escapeHtml(company.website) + '</a>' : '—'}</span>
            <span class="k">Notas</span><span>${escapeHtml(company.notes || '—')}</span>
        </div>
    `;
    const contacts = company.contacts || [];
    document.getElementById('companyDetailContactsList').innerHTML = contacts.length
        ? contacts.map(ct => `
            <div class="company-detail-contact">
                <strong>${escapeHtml(ct.name)}</strong>
                ${ct.role ? escapeHtml(ct.role) + ' · ' : ''}
                ${ct.email ? '<a href="mailto:' + escapeHtml(ct.email) + '" style="color:var(--accent);">' + escapeHtml(ct.email) + '</a>' : ''}
                ${ct.phone ? ' · ' + escapeHtml(ct.phone) : ''}
            </div>
        `).join('')
        : '<p style="color:var(--text-muted); font-size:0.9rem;">Sin contactos</p>';
    const oppsList = Array.isArray(opps) ? opps : [];
    document.getElementById('companyDetailOppsTable').innerHTML = oppsList.length
        ? `<table><thead><tr><th>Oportunidad</th><th>Etapa</th><th>Valor</th><th>Cierre</th><th></th></tr></thead><tbody>
        ${oppsList.map(o => `
            <tr>
                <td><a href="#" class="view-opp-in-detail" data-id="${o.id}" style="color:var(--accent);">${escapeHtml(o.title)}</a></td>
                <td><span class="badge" style="background:${(o.stage_color || '#333')}22; color:${o.stage_color || '#fff'}">${escapeHtml(o.stage_name || '—')}</span></td>
                <td>${o.estimated_value != null ? Number(o.estimated_value).toLocaleString('es-ES', { style: 'currency', currency: o.currency || 'EUR' }) : '—'}</td>
                <td>${o.expected_close_date || '—'}</td>
                <td><button type="button" class="btn-edit-opp-detail" data-id="${o.id}">Editar</button></td>
            </tr>
        `).join('')}
        </tbody></table>`
        : '<p style="color:var(--text-muted); font-size:0.9rem;">Sin oportunidades</p>';
    document.getElementById('modalCompanyDetail').classList.add('open');
    document.getElementById('companyDetailBody').querySelectorAll('.view-opp-in-detail').forEach(b => b.addEventListener('click', e => { e.preventDefault(); document.getElementById('modalCompanyDetail').classList.remove('open'); openDetail(parseInt(b.dataset.id)); }));
    document.getElementById('companyDetailBody').querySelectorAll('.btn-edit-opp-detail').forEach(b => { b.addEventListener('click', () => { document.getElementById('modalCompanyDetail').classList.remove('open'); openOpportunityForm(parseInt(b.dataset.id)); }); });
    document.getElementById('btnCompanyDetailEdit').onclick = () => { document.getElementById('modalCompanyDetail').classList.remove('open'); openCompanyForm(id); };
    document.getElementById('btnCompanyDetailContact').onclick = () => { document.getElementById('modalCompanyDetail').classList.remove('open'); openContactForm(id); };
}

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
function formatDateYMDToLocal(ymd) {
    if (!ymd) return '—';
    const [y, m, d] = ymd.split('-');
    if (!y || !m || !d) return ymd;
    return `${d}/${m}/${y}`;
}
async function openDetail(id) {
    const o = await api('opportunities/' + id);
    document.getElementById('detailTitle').textContent = o.title;
    const badge = document.getElementById('detailStageBadge');
    badge.textContent = o.stage_name;
    badge.style.background = (o.stage_color || '#333') + '22';
    badge.style.color = o.stage_color || '#fff';
    const contactLine = o.contact_name ? (o.contact_email ? escapeHtml(o.contact_name) + ' · <a href="mailto:' + escapeHtml(o.contact_email) + '" style="color:var(--accent);">' + escapeHtml(o.contact_email) + '</a>' : escapeHtml(o.contact_name)) : '—';
    const valueStr = o.estimated_value != null ? Number(o.estimated_value).toLocaleString('es-ES', { style: 'currency', currency: o.currency || 'EUR' }) : '—';
    document.getElementById('detailContent').innerHTML = `
        <div class="opp-detail-section">
            <div class="opp-detail-section-title">Cliente</div>
            <div class="opp-detail-grid">
                <span class="opp-detail-k">Empresa</span><span class="opp-detail-v">${escapeHtml(o.company_name)}</span>
                <span class="opp-detail-k">Contacto</span><span class="opp-detail-v">${contactLine}</span>
            </div>
        </div>
        <div class="opp-detail-section">
            <div class="opp-detail-section-title">Alcance</div>
            <div class="opp-detail-desc">${escapeHtml(o.description || 'Sin descripción.')}</div>
        </div>
        <div class="opp-detail-section">
            <div class="opp-detail-section-title">Económico</div>
            <div class="opp-detail-grid">
                <span class="opp-detail-k">Valor estimado</span><span class="opp-detail-v opp-detail-value">${valueStr}</span>
                <span class="opp-detail-k">Probabilidad de cierre</span>
                <div class="opp-detail-prob-wrap">
                    <div class="opp-detail-prob-bar-wrap">
                        <div class="opp-detail-prob-bar-fill" style="width: ${Math.min(100, Math.max(0, Number(o.probability) ?? 0))}%;"></div>
                    </div>
                    <span class="opp-detail-prob-pct">${o.probability != null ? o.probability + '%' : '—'}</span>
                </div>
            </div>
        </div>
        <div class="opp-detail-section">
            <div class="opp-detail-section-title">Planificación</div>
            <div class="opp-detail-grid">
                <span class="opp-detail-k">Cierre previsto</span><span class="opp-detail-v">${formatDateYMDToLocal(o.expected_close_date)}</span>
                <span class="opp-detail-k">Asignado a</span><span class="opp-detail-v">${escapeHtml(o.assigned_to || '—')}</span>
            </div>
        </div>
    `;
    document.getElementById('btnEditOpportunity').onclick = () => { document.getElementById('modalOpportunityDetail').classList.remove('open'); openOpportunityForm(id); };
    document.getElementById('btnDuplicateOpportunity').onclick = () => {
        document.getElementById('modalOpportunityDetail').classList.remove('open');
        document.getElementById('modalOpportunityTitle').textContent = 'Nueva oportunidad (copia)';
        document.getElementById('oppId').value = '';
        document.getElementById('formOpportunity').reset();
        document.getElementById('oppId').value = '';
        document.getElementById('oppCompanyId').value = o.company_id;
        loadContactsForCompany(o.company_id, 'oppContactId');
        setTimeout(() => {
            document.getElementById('oppContactId').value = o.contact_id || '';
            document.getElementById('oppTitle').value = (o.title || '') + ' (copia)';
            document.getElementById('oppDescription').value = o.description || '';
            document.getElementById('oppValue').value = o.estimated_value ?? '';
            document.getElementById('oppCurrency').value = o.currency || 'EUR';
            document.getElementById('oppStageId').value = o.stage_id || 1;
            document.getElementById('oppProbability').value = o.probability ?? 10;
            document.getElementById('oppCloseDate').value = o.expected_close_date || '';
            document.getElementById('oppAssigned').value = o.assigned_to || '';
        }, 150);
        setCloseDateMin();
        document.getElementById('modalOpportunity').classList.add('open');
    };
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

const btnAddPlus = document.getElementById('btnAddPlus');
const headerAddMenu = document.getElementById('headerAddMenu');
if (btnAddPlus && headerAddMenu) {
    btnAddPlus.addEventListener('click', function(e) {
        e.stopPropagation();
        headerAddMenu.classList.toggle('open');
    });
    document.getElementById('mobileAddCompany').addEventListener('click', function() {
        headerAddMenu.classList.remove('open');
        openCompanyForm();
    });
    document.getElementById('mobileAddOpportunity').addEventListener('click', function() {
        headerAddMenu.classList.remove('open');
        openOpportunityForm();
    });
    document.addEventListener('click', function() { headerAddMenu.classList.remove('open'); });
    headerAddMenu.addEventListener('click', function(e) { e.stopPropagation(); });
}
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
document.getElementById('filterAssigned').addEventListener('change', applyOpportunitiesFiltersAndRender);
document.getElementById('filterSearch').addEventListener('input', applyOpportunitiesFiltersAndRender);

function exportOpportunitiesCsv() {
    const list = filteredOpportunitiesList;
    if (!list.length) { showToast('No hay datos para exportar', true); return; }
    const headers = ['Título', 'Empresa', 'Etapa', 'Valor', 'Moneda', 'Cierre previsto', 'Asignado a', 'Probabilidad'];
    const rows = list.map(o => [
        (o.title || '').replace(/"/g, '""'),
        (o.company_name || '').replace(/"/g, '""'),
        o.stage_name || '',
        o.estimated_value ?? '',
        o.currency || 'EUR',
        o.expected_close_date || '',
        (o.assigned_to || '').replace(/"/g, '""'),
        o.probability ?? ''
    ].map(c => `"${c}"`).join(','));
    const csv = [headers.map(h => `"${h}"`).join(','), ...rows].join('\r\n');
    const blob = new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'oportunidades_' + new Date().toISOString().slice(0, 10) + '.csv';
    a.click();
    URL.revokeObjectURL(a.href);
    showToast('CSV exportado');
}
document.getElementById('btnExportCsv').addEventListener('click', exportOpportunitiesCsv);

(async () => {
    await loadStages();
    await loadCompanies();
    await loadDashboard();
    await loadOpportunities();
})();
ript>
