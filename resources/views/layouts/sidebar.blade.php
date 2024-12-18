<aside class="main-sidebar sidebar-dark-primary elevation-4 dashbroad__sidebar__bg">
    <a href="{{ route('home') }}" class="brand-link">
        <img src="/uploads/peach.jfif"
             alt="Applepeach Logo"
             class="brand-image img-circle elevation-3">
        <div class="brand-text-container">
            <span class="brand-text-main">The Apple Peach House</span>
            <span class="brand-text-sub">Hotel Management System</span>
        </div>
    </a>

    <div class="sidebar custom-sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>
</aside>

<style>
.dashbroad__sidebar__bg {
    background: linear-gradient(180deg, #FF8B5E 0%, #FFA07A 100%);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
    overflow: hidden;
    box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
}

.dashbroad__sidebar__bg::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
    pointer-events: none;
}

.brand-link {
    padding: 0.8rem !important;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 8px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
    text-decoration: none !important;
}

.brand-image {
    height: 30px;
    width: 30px;
    min-width: 30px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.brand-text-container {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
    overflow: hidden;
    width: 100%;
}

.brand-text-main {
    color: #4A2511;
    font-weight: 600;
    font-size: 0.9rem;
    letter-spacing: 0.3px;
    text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.2);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.brand-text-sub {
    color: #663300;
    font-size: 0.7rem;
    font-weight: 400;
    letter-spacing: 0.2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.brand-link:hover {
    text-decoration: none !important;
    background: rgba(255, 255, 255, 0.15);
}

.brand-link:hover .brand-image {
    transform: scale(1.05) rotate(5deg);
    border-color: rgba(255, 255, 255, 0.5);
}

.custom-sidebar {
    padding: 0.8rem;
    height: calc(100% - 4.5rem);
}

.user-panel {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.user-panel .image img {
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.user-panel .image img:hover {
    transform: scale(1.1);
    border-color: rgba(255, 255, 255, 0.5);
}

.user-panel .info {
    padding-left: 1rem;
}

.user-panel .info a {
    color: #FFFFFF;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.user-panel .user-role {
    display: block;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    margin-top: 0.2rem;
}

.custom-sidebar .nav-link {
    color: #FFFFFF !important;
    padding: 0.8rem 1rem;
    margin: 0.3rem 0;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    overflow: hidden;
}

.custom-sidebar .nav-link i,
.custom-sidebar .nav-link span.material-symbols-outlined {
    font-size: 1.1rem;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    color: rgba(255, 255, 255, 0.9);
}

.custom-sidebar .nav-link:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: none;
}

.custom-sidebar .nav-link:hover i,
.custom-sidebar .nav-link:hover span.material-symbols-outlined {
    transform: scale(1.1);
}

.custom-sidebar .nav-link.active {
    background: #4CAF50;
    color: #FFFFFF !important;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
}

.custom-sidebar .nav-treeview {
    margin: 0.2rem 0 0.2rem 1.5rem;
    padding: 0.3rem;
}

.custom-sidebar .nav-treeview::before {
    content: '';
    position: absolute;
    left: -2px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0.2), transparent);
}

.custom-sidebar .nav-treeview .nav-link {
    background: #FFFFFF;
    border-radius: 6px;
    padding: 0.6rem 1rem;
    margin: 0.2rem 0;
    color: #333333 !important;
    font-weight: 400;
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.custom-sidebar .nav-treeview .nav-link:hover {
    background: #F5F5F5;
}

.custom-sidebar .nav-treeview .nav-link.active {
    background: #4CAF50 !important;
    color: #FFFFFF !important;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
    border: none;
}

.custom-sidebar .nav-treeview .nav-link.active p,
.custom-sidebar .nav-treeview .nav-link.active i {
    color: #FFFFFF !important;
}

.custom-sidebar .nav-treeview .nav-link p {
    color: inherit !important;
    opacity: 1;
    margin: 0;
}

.custom-sidebar .nav-treeview .nav-link i {
    color: inherit !important;
    opacity: 0.9;
}

/* Green left border indicator */
.custom-sidebar .nav-treeview .nav-link.active::before {
    content: '';
    position: absolute;
    left: -4px;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 70%;
    background-color: #45a049;
    border-radius: 0 4px 4px 0;
}

/* Ensure submenu is visible when parent is active */
.nav-item.menu-open > .nav-treeview {
    display: block;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Style for the parent menu item when expanded */
.nav-item.menu-open > .nav-link {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
}

.custom-sidebar .nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 0;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    transition: width 0.2s ease;
    z-index: -1;
}

.custom-sidebar .nav-link:hover::before {
    width: 100%;
}

.custom-sidebar .nav-link.active::after {
    content: '';
    position: absolute;
    left: -0.8rem;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 60%;
    background: #4CAF50;
    border-radius: 2px;
}

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-8px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.nav-sidebar > .nav-item {
    animation: fadeInLeft 0.2s ease-out forwards;
    animation-delay: calc(var(--item-index) * 0.05s);
}

.custom-sidebar .nav-link:active {
    transform: scale(0.98) translateX(3px);
}

/* Enhanced Scrollbar Styling */
.custom-sidebar::-webkit-scrollbar {
    width: 5px;
}

.custom-sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}

.custom-sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.custom-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Enhanced Animation for menu items */
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateX(-15px);
    }
    to { 
        opacity: 1; 
        transform: translateX(0);
    }
}

.nav-sidebar > .nav-item {
    animation: fadeIn 0.4s ease-out forwards;
    animation-delay: calc(var(--item-index) * 0.1s);
}

/* Add subtle pulse animation for active items with darker colors */
@keyframes subtlePulse {
    0% { box-shadow: 0 4px 15px rgba(76, 175, 80, 0.25); }
    50% { box-shadow: 0 4px 20px rgba(76, 175, 80, 0.35); }
    100% { box-shadow: 0 4px 15px rgba(76, 175, 80, 0.25); }
}

.custom-sidebar .nav-link.active {
    animation: subtlePulse 2s infinite;
}

/* Optional: Add animation on page load */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.brand-text-container {
    animation: fadeInDown 0.5s ease-out forwards;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .brand-text-main {
        font-size: 0.8rem;
    }
    .brand-text-sub {
        font-size: 0.65rem;
    }
}

/* Role-specific styling */
[data-role="Admin"] .nav-link {
    border-left: 3px solid #FFD700;
}

[data-role="Manager"] .nav-link {
    border-left: 3px solid #98FB98;
}

[data-role="Front Desk"] .nav-link {
    border-left: 3px solid #87CEEB;
}

/* Enhanced hover effects */
.custom-sidebar .nav-link::after {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
}

.custom-sidebar .nav-link:hover::after {
    transform: translateX(100%);
}

/* Improved active state */
.custom-sidebar .nav-link.active i,
.custom-sidebar .nav-link.active span.material-symbols-outlined {
    transform: scale(1.1);
    color: #FFFFFF;
}

/* Enhanced animation for menu items */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.nav-sidebar > .nav-item {
    opacity: 0;
    animation: slideIn 0.5s ease forwards;
    animation-delay: calc(var(--item-index) * 0.1s);
}

/* Improved scrollbar */
.custom-sidebar::-webkit-scrollbar {
    width: 6px;
}

.custom-sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}

.custom-sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.custom-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Responsive improvements */
@media (max-width: 768px) {
    .custom-sidebar .nav-link {
        padding: 0.7rem 0.8rem;
        font-size: 0.9rem;
    }
    
    .custom-sidebar .nav-treeview {
        padding-left: 1rem;
        margin-left: 0.6rem;
    }
    
    .brand-text-container {
        display: none;
    }
}

/* Parent menu item (Inventory) */
.nav-item.menu-open > .nav-link {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
}

/* Green left border for active menu items */
.custom-sidebar .nav-link.active {
    border-left: 4px solid #4CAF50;
}

/* Adjust the submenu position */
.nav-sidebar .nav-treeview {
    padding-left: 0.5rem;
}
</style>
