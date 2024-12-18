<!-- need to remove -->

<div data-role="{{ Auth::user()->Role }}">
    <li class="nav-item">
        <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <p>Dashboard</p>
        </a>
    </li>

    @if(Auth::user()->Role == 'Admin')
    <li class="nav-item">
        <a href="/user" class="nav-link {{ Request::is('user') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i>
            <p>Settings</p>
        </a>
    </li>
    @endif

    @if(Auth::user()->Role == 'Front Desk')
    <li class="nav-header">Guest Management</li>
    <li class="nav-item">
        <a href="/guest" class="nav-link {{ Request::is('guest') ? 'active' : '' }}">
            <span class="material-symbols-outlined">person</span>
            <p>Guest Registry</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/booking" class="nav-link {{ Request::is('booking') ? 'active' : '' }}">
            <i class="fa-solid fa-check-circle"></i>
            <p>Check in</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/invoice" class="nav-link {{ Request::is('invoice') ? 'active' : '' }}">
            <i class="fa-solid fa-file-invoice-dollar"></i>
            <p>Invoice</p>
        </a>
    </li>
    @endif

    @if(Auth::user()->Role == 'Manager')
    <li class="nav-header">Property Management</li>
    <li class="nav-item">
        <a href="/room" class="nav-link {{ Request::is('room') ? 'active' : '' }}">
            <span class="material-symbols-outlined">meeting_room</span>
            <p>Room Management</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link {{ Request::is('inventory*') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i>
            <p>
                Inventory
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="/income/category" class="nav-link {{ Request::is('income/category') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <p>Asset Tracking</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/income" class="nav-link {{ Request::is('income') ? 'active' : '' }}">
                    <i class="fa-solid fa-plus-circle"></i>
                    <p>Add Items</p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-header">Settings</li>
    <li class="nav-item">
        <a href="/taxSetting" class="nav-link {{ Request::is('taxSetting') ? 'active' : '' }}">
            <i class="fa-solid fa-percent"></i>
            <p>Tax Settings</p>
        </a>
    </li>
    <li class="nav-item">
        <a href="/paymentSetting" class="nav-link {{ Request::is('paymentSetting') ? 'active' : '' }}">
            <i class="fa-solid fa-credit-card"></i>
            <p>Payment Settings</p>
        </a>
    </li>
    @endif

    <li class="nav-header">Communication</li>
    <li class="nav-item">
        <a href="/sms" class="nav-link {{ Request::is('sms') ? 'active' : '' }}">
            <i class="fa-solid fa-message"></i>
            <p>SMS</p>
        </a>
    </li>
</div>

<style>
.nav-header {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 1rem 1rem 0.5rem;
    margin-top: 0.5rem;
    font-weight: 600;
}

.nav-item {
    margin: 4px 0;
    position: relative;
}

.nav-link {
    border-radius: 8px;
    margin: 0 8px;
    transition: all 0.3s ease;
    padding: 0.8rem 1rem;
    position: relative;
    z-index: 1;
    color: #ffffff;
}

.nav-link:hover {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.05));
    transform: translateX(4px);
}

.nav-link.active {
    background: linear-gradient(145deg, rgba(76, 175, 80, 0.6), rgba(76, 175, 80, 0.3));
    box-shadow: 0 4px 8px rgba(76, 175, 80, 0.2);
}

.nav-link.active::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 70%;
    background-color: #4CAF50;
    border-radius: 0 4px 4px 0;
}

.nav-link i, .nav-link span {
    margin-right: 8px;
    width: 20px;
    text-align: center;
    transition: transform 0.3s ease;
}

.nav-link:hover i, 
.nav-link:hover span {
    transform: scale(1.1);
}

.nav-link p {
    font-weight: 500;
    margin-bottom: 0;
}

.nav-item .right {
    transition: transform 0.3s ease;
}

.nav-item.menu-open .right {
    transform: rotate(-90deg);
}

.nav-treeview {
    transition: all 0.3s ease;
    margin-left: 1rem;
}

.nav-treeview .nav-link {
    font-size: 0.95rem;
    padding: 0.6rem 1rem;
}

.nav-treeview .nav-link:hover {
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.03));
}

.nav-treeview .nav-link.active {
    background: linear-gradient(145deg, rgba(76, 175, 80, 0.5), rgba(76, 175, 80, 0.2));
    box-shadow: 0 4px 8px rgba(76, 175, 80, 0.15);
}
</style>
