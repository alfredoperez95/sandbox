<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SALESFORCESUCKS · Consultoría</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
</head>
<body>
    <div class="welcome-screen" id="welcomeScreen">
        <img src="https://portal.avvale.com/static/media/Avvale-logo-hor-white.a7b4a25a.png" alt="Avvale" class="welcome-logo">
        <p class="welcome-tagline">Gestión de oportunidades B2B</p>
        <button type="button" class="btn btn-primary welcome-btn" id="welcomeEnter">Entrar</button>
    </div>
    <div class="app">
        <header>
            <div class="logo-wrap">
                <img src="https://portal.avvale.com/static/media/Avvale-logo-hor-white.a7b4a25a.png" alt="Avvale" class="logo-img">
                <span class="logo-tagline">SALESFORCESUCKS</span>
            </div>
            <div class="header-nav-wrap">
                <nav class="nav-tabs">
                    <button type="button" class="nav-tab active" data-tab="opportunities">Oportunidades</button>
                    <button type="button" class="nav-tab" data-tab="companies">Empresas</button>
                </nav>
                <div class="header-add-mobile" id="headerAddMobile">
                    <button type="button" class="btn-add-plus" id="btnAddPlus" aria-label="Añadir">+</button>
                    <div class="header-add-menu" id="headerAddMenu">
                        <button type="button" class="header-add-option" id="mobileAddCompany">Empresa</button>
                        <button type="button" class="header-add-option" id="mobileAddOpportunity">Oportunidad</button>
                    </div>
                </div>
            </div>
            <div class="header-btns-desktop">
                <button type="button" class="btn btn-primary" id="btnNewCompany">+ Empresa</button>
                <button type="button" class="btn btn-primary" id="btnNewOpportunity">+ Oportunidad</button>
            </div>
        </header>

        <section id="section-opportunities" class="section active">
            <div class="dashboard-cards" id="dashboardCards"></div>
            <div class="charts-toggle-wrap">
                <button type="button" class="btn btn-secondary" id="btnToggleCharts">Ver gráficos por fase</button>
            </div>
            <div class="charts-block" id="chartsBlock">
                <div class="charts-card">
                    <h3>Oportunidades por fase</h3>
                    <div id="chartByCount"></div>
                </div>
                <div class="charts-card" style="margin-top: 1rem;">
                    <h3>Valor estimado por fase (€)</h3>
                    <div id="chartByValue"></div>
                </div>
            </div>
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
                <select id="filterAssigned">
                    <option value="">Todos los responsables</option>
                    <option value="Alfredo Pérez">Alfredo Pérez</option>
                    <option value="Rafa Calvo">Rafa Calvo</option>
                    <option value="Guillermo Truhan">Guillermo Truhan</option>
                    <option value="Gerard Prats">Gerard Prats</option>
                    <option value="Xavi Tor">Xavi Tor</option>
                    <option value="Alvaro Arbaiza">Alvaro Arbaiza</option>
                </select>
                <input type="search" id="filterSearch" placeholder="Buscar por título o empresa..." class="filter-search">
                <button type="button" class="btn btn-secondary" id="btnExportCsv" title="Exportar a CSV">Exportar CSV</button>
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
                                <th>Asignado a</th>
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
            <div class="filters" id="companiesFilters">
                <select id="filterCompanySector">
                    <option value="">Todos los sectores</option>
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
                <select id="filterCompanySize">
                    <option value="">Todos los tamaños</option>
                    <option value="startup">Startup</option>
                    <option value="pyme">PYME</option>
                    <option value="mediana">Mediana</option>
                    <option value="gran_empresa">Gran empresa</option>
                </select>
                <input type="search" id="filterCompanySearch" placeholder="Buscar por nombre..." class="filter-search">
            </div>
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

    <footer class="app-footer">
        <div class="app-footer-inner">
            <img src="https://portal.avvale.com/static/media/Avvale-logo-hor-white.a7b4a25a.png" alt="Avvale" class="app-footer-logo">
            <nav class="app-footer-links">
                <a href="#">Aviso legal</a>
                <a href="#">Privacidad</a>
                <a href="#">Contacto</a>
                <a href="#">Cookies</a>
            </nav>
        </div>
    </footer>

    <!-- Modal Detalle Empresa -->
    <div class="modal-overlay" id="modalCompanyDetail">
        <div class="modal" style="max-width: 640px;">
            <header><h2 id="companyDetailTitle">Empresa</h2></header>
            <div class="body" id="companyDetailBody">
                <div class="company-detail-section" id="companyDetailInfo"></div>
                <div class="company-detail-section" id="companyDetailContacts">
                    <h4>Contactos</h4>
                    <div class="company-detail-contacts" id="companyDetailContactsList"></div>
                </div>
                <div class="company-detail-section company-detail-history" id="companyDetailHistory">
                    <h4>Histórico de oportunidades</h4>
                    <div id="companyDetailOppsTable"></div>
                </div>
                <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                    <button type="button" class="btn btn-ghost" id="btnCompanyDetailEdit">Editar empresa</button>
                    <button type="button" class="btn btn-secondary" id="btnCompanyDetailContact">+ Contacto</button>
                </div>
            </div>
        </div>
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
                        <label>Probabilidad de negocio (%)</label>
                        <input type="number" id="oppProbability" name="probability" min="0" max="100" value="10" title="Afecta al pipeline pesado. En Ganada/Perdida se ajusta automáticamente.">
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
    <div class="modal-overlay opp-detail-modal" id="modalOpportunityDetail">
        <div class="modal">
            <header>
                <h2 id="detailTitle">Detalle</h2>
                <span class="badge detail-stage-badge" id="detailStageBadge">—</span>
            </header>
            <div class="body opp-detail-body">
                <div id="detailContent"></div>
                <div class="opp-detail-actions">
                    <button type="button" class="btn btn-ghost" id="btnEditOpportunity">Editar</button>
                    <button type="button" class="btn btn-secondary" id="btnDuplicateOpportunity">Duplicar</button>
                    <button type="button" class="btn btn-danger" id="btnDeleteOpportunity">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script src="js/app.js"></script>
</body>
</html>
