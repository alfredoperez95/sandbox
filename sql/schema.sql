-- Oportunidades de venta B2B - Consultoría
-- Empresas (clientes potenciales o actuales)
CREATE TABLE IF NOT EXISTS companies (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    industry TEXT,
    size TEXT,
    website TEXT,
    notes TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Contactos en cada empresa
CREATE TABLE IF NOT EXISTS contacts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    company_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    role TEXT,
    email TEXT,
    phone TEXT,
    is_primary INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- Etapas del embudo de ventas
CREATE TABLE IF NOT EXISTS stages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    slug TEXT NOT NULL UNIQUE,
    sort_order INTEGER DEFAULT 0,
    color TEXT
);

INSERT OR IGNORE INTO stages (id, name, slug, sort_order, color) VALUES
(1, 'Prospección', 'prospeccion', 1, '#6366f1'),
(2, 'Calificación', 'calificacion', 2, '#8b5cf6'),
(3, 'Propuesta', 'propuesta', 3, '#0ea5e9'),
(4, 'Negociación', 'negociacion', 4, '#f59e0b'),
(5, 'Ganada', 'ganada', 5, '#10b981'),
(6, 'Perdida', 'perdida', 6, '#ef4444');

-- Oportunidades de venta
CREATE TABLE IF NOT EXISTS opportunities (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    company_id INTEGER NOT NULL,
    contact_id INTEGER,
    title TEXT NOT NULL,
    description TEXT,
    estimated_value REAL,
    currency TEXT DEFAULT 'EUR',
    stage_id INTEGER NOT NULL DEFAULT 1,
    probability INTEGER DEFAULT 10,
    expected_close_date DATE,
    assigned_to TEXT,
    status TEXT DEFAULT 'open',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE SET NULL,
    FOREIGN KEY (stage_id) REFERENCES stages(id)
);

CREATE INDEX IF NOT EXISTS idx_opportunities_company ON opportunities(company_id);
CREATE INDEX IF NOT EXISTS idx_opportunities_stage ON opportunities(stage_id);
CREATE INDEX IF NOT EXISTS idx_opportunities_close ON opportunities(expected_close_date);
CREATE INDEX IF NOT EXISTS idx_contacts_company ON contacts(company_id);
