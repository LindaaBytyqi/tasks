<style>
.sidebar-card{
    min-height:500px;
    margin-left:100px;
}
.sidebar-item{
    padding:25px 20px !important;
    font-size:18px;
    font-weight:600;
}
.sidebar-subitem{
    padding:22px 35px !important;
    font-size:16px;
}
.orders-item{
    margin-top:25px;
}
.sidebar-card .list-group-item:hover{
    background:#f8fafc;
}
@media (max-width: 991px) {
    .sidebar-card {
        margin-left: 30px;
        margin-right: 30px;
    }
}

@media (max-width: 767px) {
    .sidebar-card {
        width: 100%;
        max-width: 400px;
        min-height: auto;
        margin: 0 auto 40px auto;
    }
    .sidebar-item {
        padding: 18px 20px !important;
        font-size: 17px;
    }
    .sidebar-subitem {
        padding: 15px 30px !important;
        font-size: 15px;
    }
    .orders-item {
        margin-top: 15px;
    }
}

@media (max-width: 1024px) {
    .container-fluid {
        width: 100%;
        padding-left: 20px;
        padding-right: 20px;
        margin-top: 30px !important;
        box-sizing: border-box;
    }
    .row {
        display: flex;
        flex-direction: column;
        gap: 25px;
        margin-left: 0;
        margin-right: 0;
    }
    .col-md-3,
    .col-md-9 {
        width: 100%;
        max-width: 100%;
        flex: 0 0 100%;
        padding-left: 0;
        padding-right: 0;
    }

    .account-sidebar,
    .account-content {
        width: 100%;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
        box-sizing: border-box;
    }
    .sidebar-card {
        width: 100%;
        min-height: auto;
        margin: 0;
        box-sizing: border-box;
    }
    .welcome-card {
        width: 100%;
        max-width: 900px;
        margin: 0;
        box-sizing: border-box;
    }
}
</style>

<div class="card shadow-sm sidebar-card">
    <div class="card-header bg-dark text-white py-4">
        <h5 class="mb-0 text-center">
            My Account
        </h5>
    </div>
    <div class="list-group list-group-flush">
        <a href="#"
           class="list-group-item list-group-item-action sidebar-item">
            👤 Personal Information
        </a>

        <a href="dashboard.php?page=profile"
           class="list-group-item list-group-item-action sidebar-subitem">
            • My Profile
        </a>

        <a href="dashboard.php?page=changepassword"
           class="list-group-item list-group-item-action sidebar-subitem">
            • Change Password
        </a>

        <a href=""
           class="list-group-item list-group-item-action sidebar-item orders-item">
            📦 Orders
        </a>

        <a href="dashboard.php?page=orderdetail"
           class="list-group-item list-group-item-action sidebar-subitem">
            • My Orders
        </a>
    </div>
</div>