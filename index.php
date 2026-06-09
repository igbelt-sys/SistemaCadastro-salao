<?php
$pageTitle = 'Silvana | Inicio';
$basePath = '';
$activeSection = 'inicio';
$bodyClass = 'home-body';

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/sidebar.php';
?>
<section class="home-stage">
    <div class="home-stage__glow home-stage__glow--top"></div>
    <div class="home-stage__glow home-stage__glow--side"></div>
    <div class="home-stage__line home-stage__line--left" aria-hidden="true"></div>
    <div class="home-stage__line home-stage__line--right" aria-hidden="true"></div>

    <article class="home-hero">
        <h1 class="hero-title">Bem<span class="hero-title__dash">-</span>vinda, <span class="title-accent">Silvana!</span></h1>
        <div class="home-hero__ornament" aria-hidden="true">
            <span></span>
            <b></b>
            <span></span>
        </div>
        <p class="hero-copy">
            Gerencie seus clientes, produtos e servi&ccedil;os de forma simples e organizada.
        </p>
    </article>
</section>

<section class="home-card-grid">
    <article class="dashboard-card dashboard-card--home">
        <div class="dashboard-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24">
                <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"></path>
                <circle cx="9.5" cy="7" r="4"></circle>
                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
        <h2 class="dashboard-card__title">Clientes</h2>
        <div class="dashboard-card__rule"></div>
        <p class="dashboard-card__copy">
            Cadastre, consulte e acompanhe suas clientes com uma visualiza&ccedil;&atilde;o clara e sofisticada.
        </p>
        <a class="btn btn--primary" href="Clientes/index.php">Acessar</a>
    </article>

    <article class="dashboard-card dashboard-card--home">
        <div class="dashboard-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24">
                <path d="M7 7h10"></path>
                <path d="M9 7V4h6v3"></path>
                <rect x="6" y="7" width="12" height="14" rx="2"></rect>
            </svg>
        </div>
        <h2 class="dashboard-card__title">Produtos</h2>
        <div class="dashboard-card__rule"></div>
        <p class="dashboard-card__copy">
            Gerencie os produtos utilizados nos atendimentos com um padr&atilde;o visual elegante e funcional.
        </p>
        <a class="btn btn--primary" href="Produtos/index.php">Acessar</a>
    </article>

    <article class="dashboard-card dashboard-card--home">
        <div class="dashboard-card__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24">
                <circle cx="6.5" cy="17.5" r="3.5"></circle>
                <circle cx="17.5" cy="17.5" r="3.5"></circle>
                <path d="M8.8 14.8 15.2 4"></path>
                <path d="M15.2 14.8 8.8 4"></path>
            </svg>
        </div>
        <h2 class="dashboard-card__title">Servi&ccedil;os</h2>
        <div class="dashboard-card__rule"></div>
        <p class="dashboard-card__copy">
            Organize os servi&ccedil;os oferecidos com descri&ccedil;&otilde;es, valores e acessos r&aacute;pidos para edi&ccedil;&atilde;o.
        </p>
        <a class="btn btn--primary" href="Servicos/index.php">Acessar</a>
    </article>
</section>
<div class="home-waves" aria-hidden="true">
    <svg viewBox="0 0 1440 260" preserveAspectRatio="none">
        <path class="home-wave-fill home-wave-fill--olive" d="M0,260 L0,92 C62,144 112,186 170,204 C235,223 304,221 370,192 C439,162 503,130 581,132 C680,134 752,191 846,216 C938,241 1040,237 1135,221 C1256,201 1354,158 1440,86 L1440,260 Z"></path>
        <path class="home-wave-line home-wave-line--gold" d="M0,74 C62,126 114,166 173,184 C240,204 307,203 373,175 C441,147 505,116 583,118 C681,120 753,176 847,201 C939,226 1040,223 1133,207 C1252,188 1351,148 1440,78"></path>
        <path class="home-wave-line home-wave-line--cream" d="M0,86 C64,138 118,178 178,196 C243,215 308,214 375,186 C444,157 507,127 585,129 C683,131 754,187 848,211 C940,236 1040,233 1135,217 C1254,198 1352,157 1440,90"></path>
        <path class="home-wave-line home-wave-line--gold-soft" d="M18,92 C80,144 132,184 189,202 C251,221 315,220 381,193 C449,165 512,136 589,138 C685,141 756,194 848,218 C938,241 1034,239 1125,224 C1239,205 1334,167 1420,104"></path>
        <path class="home-wave-line home-wave-line--gold-soft" d="M34,100 C94,150 145,189 201,206 C262,224 324,223 389,196 C456,169 518,141 595,143 C689,146 758,198 848,221 C936,244 1030,242 1117,228 C1227,210 1320,176 1404,117"></path>
        <path class="home-wave-line home-wave-line--gold-soft" d="M52,108 C110,156 160,193 214,209 C273,226 334,225 397,199 C462,173 523,147 598,149 C690,152 759,202 848,224 C933,246 1024,245 1109,232 C1216,216 1307,186 1388,129"></path>
        <path class="home-wave-line home-wave-line--gold-soft" d="M70,116 C126,162 174,198 227,214 C284,230 343,229 405,204 C468,178 528,152 602,154 C692,158 761,205 849,227 C932,248 1019,247 1101,236 C1206,222 1294,195 1372,140"></path>
    </svg>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
