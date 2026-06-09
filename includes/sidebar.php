<?php
$basePath = $basePath ?? '';
$activeSection = $activeSection ?? '';

$renderSidebarIcon = static function (string $icon): string {
    $icons = [
        'inicio' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5"></path><path d="M5 9.5V21h14V9.5"></path><path d="M9 21v-6h6v6"></path></svg>',
        'clientes' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"></path><circle cx="9.5" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
        'produtos' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 7h10"></path><path d="M9 7V4h6v3"></path><rect x="6" y="7" width="12" height="14" rx="2"></rect></svg>',
        'servicos' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="6.5" cy="17.5" r="3.5"></circle><circle cx="17.5" cy="17.5" r="3.5"></circle><path d="M8.8 14.8 15.2 4"></path><path d="M15.2 14.8 8.8 4"></path></svg>',
        'perfil' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 21a8 8 0 0 0-16 0"></path><circle cx="12" cy="8" r="4"></circle></svg>',
    ];

    return $icons[$icon] ?? $icons['inicio'];
};
?>
<div class="app-layout">
    <aside class="sidebar">
        <div class="brand-lockup">
            <img
                class="brand-logo"
                src="<?= htmlspecialchars($basePath . 'assets/imagens/logosalao-cortada.png', ENT_QUOTES, 'UTF-8') ?>"
                alt="Silvana Terapia e Estetica Capilar"
            >
        </div>

        <div class="sidebar-divider"></div>

        <nav class="sidebar-nav" aria-label="Menu principal">
            <ul>
                <li>
                    <a class="<?= $activeSection === 'inicio' ? 'is-active' : '' ?>" href="<?= htmlspecialchars($basePath . 'index.php', ENT_QUOTES, 'UTF-8') ?>">
                        <span class="nav-icon"><?= $renderSidebarIcon('inicio') ?></span>
                        <span>In&iacute;cio</span>
                    </a>
                </li>
                <li>
                    <a class="<?= $activeSection === 'clientes' ? 'is-active' : '' ?>" href="<?= htmlspecialchars($basePath . 'Clientes/index.php', ENT_QUOTES, 'UTF-8') ?>">
                        <span class="nav-icon"><?= $renderSidebarIcon('clientes') ?></span>
                        <span>Clientes</span>
                    </a>
                </li>
                <li>
                    <a class="<?= $activeSection === 'produtos' ? 'is-active' : '' ?>" href="<?= htmlspecialchars($basePath . 'Produtos/index.php', ENT_QUOTES, 'UTF-8') ?>">
                        <span class="nav-icon"><?= $renderSidebarIcon('produtos') ?></span>
                        <span>Produtos</span>
                    </a>
                </li>
                <li>
                    <a class="<?= $activeSection === 'servicos' ? 'is-active' : '' ?>" href="<?= htmlspecialchars($basePath . 'Servicos/index.php', ENT_QUOTES, 'UTF-8') ?>">
                        <span class="nav-icon"><?= $renderSidebarIcon('servicos') ?></span>
                        <span>Servi&ccedil;os</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-profile">
            <div class="sidebar-profile__summary">
                <div class="sidebar-profile__avatar" aria-hidden="true">
                    <?= $renderSidebarIcon('perfil') ?>
                </div>
                <div>
                    <p class="sidebar-profile__name">Silvana</p>
                    <p class="sidebar-profile__role">Administradora</p>
                </div>
            </div>

            <a class="sidebar-exit" href="#">
                <span class="nav-icon"><?= $renderSidebarIcon('inicio') ?></span>
                <span>Sair</span>
            </a>
        </div>
    </aside>

    <div class="app-main">
        <main class="page-content">
